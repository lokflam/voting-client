<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function vote_index(Request $request) {
        $current = $request->query('current', '');

        $url = env('VOTING_URL').'/vote?limit=20&start='.$current;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        return view('user.vote.index', ['votes' => $json['data'], 'next' => $json['next_position']]);
    }

    public function show_vote($vote_id) {
        $url = env('VOTING_URL').'/vote/'.$vote_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        $total = DB::table('results')->where([['vote_id', $vote_id], ['item', 'total']])->orderBy('created_at', 'desc')->first();
        $casted = DB::table('results')->where([['vote_id', $vote_id], ['item', 'casted']])->orderBy('created_at', 'desc')->first();

        $results = DB::table('results')->where([['vote_id', $vote_id], ['item', '<>', 'total'], ['item', '<>', 'casted']])->orderBy('created_at', 'asc')->get();
        $coordinates = [];
        foreach($results as $result) {
            if(!isset($coordinates[$result->item])) {
                $coordinates[$result->item] = [
                    'x' => [],
                    'y' => [],
                ];
            }
            $coordinates[$result->item]['x'] []= $result->created_at;
            $coordinates[$result->item]['y'] []= $result->count;
        }
        
        return view('user.vote.show', ['vote' => $json['data'], 'total' => $total, 'casted' => $casted, 'coordinates' => $coordinates]);
    }

    public function get_result_update(Request $request) {
        $vote_id = $request->input('vote_id');
        if(!$vote_id) {
            return response()->json([
                'success' => false,
                'message' => 'Missing vote id'
            ]);
        }
        $start = $request->input('start', '');
        $results = DB::table('results')->where([['vote_id', $vote_id], ['item', '<>', 'total'], ['item', '<>', 'casted'], ['created_at', '>', $start]])->orderBy('created_at', 'asc')->get();
        
        $coordinates = [];
        foreach($results as $result) {
            if(!isset($coordinates[$result->item])) {
                $coordinates[$result->item] = [
                    'x' => [],
                    'y' => [],
                ];
            }
            $coordinates[$result->item]['x'] []= $result->created_at;
            $coordinates[$result->item]['y'] []= $result->count;
        }

        $total = DB::table('results')->where([['vote_id', $vote_id], ['item', 'total']])->orderBy('created_at', 'desc')->first();
        $casted = DB::table('results')->where([['vote_id', $vote_id], ['item', 'casted']])->orderBy('created_at', 'desc')->first();

        return response()->json([
            'success' => true,
            'data' => [
                'coordinates' => $coordinates,
                'total' => isset($total->count)? $total->count: 0,
                'casted' => isset($casted->count)? $casted->count: 0,
            ],
        ]);
    }

    public function cast_ballot(Request $request) {
        $validator = Validator::make($request->all(), [
            'vote_id' => 'required',
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('ballot')->withErrors($validator)->withInput();
        }

        $vote_id = $request->input('vote_id');
        $code = $request->input('code');

        $data = [
            'vote_id' => $vote_id,
            'code' => $code,
        ];
        $payload = json_encode($data);

        $url = env('VOTING_URL').'/ballot';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        $ballot_data = $json['data'];
        if(!$ballot_data) {
            return redirect('ballot?not_exists=1')->withErrors($validator)->withInput();
        }

        $url = env('VOTING_URL').'/vote/'.$vote_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        $vote = $json['data'];

        if(!$request->input('cast')) {
            return view('user.ballot.cast', [
                'vote' => $vote,
                'ballot' => $ballot_data['ballot'],
                'casted_log' => $ballot_data['casted_log'],
                'created_log' => $ballot_data['created_log'],
                'code' => $code,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'vote_id' => 'required',
            'code' => 'required',
            'choice' => 'required',
        ]);

        if ($validator->fails()) {
            $request->session()->flash('error', 'Missing argument');
            return redirect('ballot');
        }

        $data = [
            'vote_id' => $request->input('vote_id'),
            'code' => $request->input('code'),
            'choice' => $request->input('choice'),
        ];
        $payload = json_encode($data);

        $url = env('VOTING_URL').'/ballot/cast';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        if(!isset($json['batch_ids'])) {
            $request->session()->flash('error', 'Failed to submit request');
            return view('user.ballot.info', ['vote' => $vote, 'ballot' => $ballot, 'code' => $code]);
        }

        DB::table('batches')->insert([
            'id' => $json['batch_ids'],
            'action' => $json['action'],
            'data' => json_encode($json['data']),
            'submitted_at' => date('Y-m-d H:i:s', $json['submitted_at']),
        ]);
        $request->session()->flash('status', 'Successfully submitted request');
        return redirect('batch/'.$json['batch_ids']);
    }

    public function ballot(Request $request) {
        return view('user.ballot.search');
    }

    public function batch_index(Request $request) {
        $batches = DB::table('batches')->orderBy('submitted_at', 'desc');
        if($request->query('id')) {
            $batches = $batches->where('id', 'like', $request->query('id').'%');
        }
        $batches = $batches->simplePaginate(15);
        
        return view('user.batch.index', ['batches' => $batches]);
    }

    public function batch(Request $request, $batch_id) {
        $url = env('BLOCKCHAIN_URL').'/batch_statuses?id='.$batch_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        $record = DB::table('batches')->where('id', $batch_id)->first();

        return view('user.batch.show', ['batch' => (isset($json['data'][0])? $json['data'][0]: null), 'record' => $record]);
    }
}

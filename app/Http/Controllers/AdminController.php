<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
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

        return view('admin.vote.index', ['votes' => $json['data'], 'next' => $json['next_position']]);
    }

    public function create_vote(Request $request) {
        if(!$request->isMethod('post')) {
            return view('admin.vote.create');
        }

        $validator = Validator::make($request->all(), [
            'private_key' => 'required',
            'name' => 'required',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'candidate_code.*' => 'min:1|distinct',
            'candidate_name.*' => 'required_with:candidate_code.*',
            'cron_intervel' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($request->url())->withErrors($validator)->withInput();
        }

        $vote = new \stdClass();
        $vote->id = $request->input('id', '');
        $vote->name = $request->input('name');
        $vote->description = $request->input('description', '');
        $vote->start_at = strtotime($request->input('start_at'));
        $vote->end_at = strtotime($request->input('end_at'));
        $vote->candidates = [];
        $i = 0;
        while($request->input('candidate_code.'.$i)) {
            $candidate = new \stdClass();
            $candidate->code = $request->input('candidate_code.'.$i);
            $candidate->name = $request->input('candidate_name.'.$i);
            $candidate->description = $request->input('candidate_description.'.$i, '');
            $candidate->status = ($request->input('candidate_status.'.$i)? 0: 1);
            $vote->candidates []= $candidate;
            $i += 1;
        }

        $data = new \stdClass();
        $data->private_key = $request->input('private_key');
        $data->vote = $vote;
        $payload = json_encode($data);

        $url = env('VOTING_URL').'/vote/create';
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
            return view('admin.vote.create');
        }

        DB::table('batches')->insert([
            'id' => $json['batch_ids'],
            'action' => $json['action'],
            'data' => json_encode($json['data']),
            'submitted_at' => date('Y-m-d H:i:s', $json['submitted_at']),
        ]);
        
        if($request->input('cron_intervel') != 'n') {
            DB::table('cron')->insert([
                'job' => 'count_ballot',
                'data' => $json['data']['vote_id'],
                'intervel' => $request->input('cron_intervel'),
                'created_at' => date('Y-m-d H:i:s', $json['submitted_at']),
            ]);
        }

        DB::table('cron')->insert([
            'job' => 'update_result',
            'data' => $json['data']['vote_id'],
            'intervel' => '* * * * *',
            'created_at' => date('Y-m-d H:i:s', $json['submitted_at']),
        ]);

        $request->session()->flash('status', 'Successfully submitted request');
        return redirect('batch/'.$json['batch_ids']);
    }

    public function update_vote(Request $request, $vote_id) {
        $url = env('VOTING_URL').'/vote/'.$vote_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);
        $old_vote = $json['data'];

        $cron = DB::table('cron')->where([
            ['job', 'count_ballot'],
            ['data', $vote_id],
        ])->first();

        $cron_intervel = 'n';
        if(isset($cron->intervel)) {
            $cron_intervel = $cron->intervel;
        }

        if(!$request->isMethod('post')) {
            return view('admin.vote.update', ['vote' => $old_vote, 'cron_intervel' => $cron_intervel]);
        }

        $validator = Validator::make($request->all(), [
            'private_key' => 'required',
            'name' => 'required',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'candidate_code.*' => 'min:1|distinct',
            'candidate_name.*' => 'required_with:candidate_code.*',
            'cron_intervel' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($request->url())->withErrors($validator)->withInput();
        }

        $vote = new \stdClass();
        $vote->id = $old_vote['id'];
        $vote->name = $request->input('name');
        $vote->description = $request->input('description', '');
        $vote->start_at = strtotime($request->input('start_at'));
        $vote->end_at = strtotime($request->input('end_at'));
        $vote->candidates = [];
        $i = 0;
        while($request->input('candidate_code.'.$i)) {
            $candidate = new \stdClass();
            $candidate->code = $request->input('candidate_code.'.$i);
            $candidate->name = $request->input('candidate_name.'.$i);
            $candidate->description = $request->input('candidate_description.'.$i, '');
            $candidate->status = ($request->input('candidate_status.'.$i)? 0: 1);
            $vote->candidates []= $candidate;
            $i += 1;
        }

        $data = new \stdClass();
        $data->private_key = $request->input('private_key');
        $data->vote = $vote;
        $payload = json_encode($data);

        $url = env('VOTING_URL').'/vote/update';
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
            return view('admin.vote.udpate', ['vote' => $old_vote]);
        }

        DB::table('batches')->insert([
            'id' => $json['batch_ids'],
            'action' => $json['action'],
            'data' => json_encode($json['data']),
            'submitted_at' => date('Y-m-d H:i:s', $json['submitted_at']),
        ]);

        if($request->input('cron_intervel') != 'n') {
            DB::table('cron')->updateOrInsert([
                'job' => 'count_ballot',
                'data' => $vote_id,
            ], [
                'intervel' => $request->input('cron_intervel'),
            ]);
        } else {
            DB::table('cron')->where([
                ['job', 'count_ballot'],
                ['data', $vote_id],
            ])->delete();
        }

        $request->session()->flash('status', 'Successfully submitted request');
        return redirect('batch/'.$json['batch_ids']);
    }

    public function delete_vote(Request $request, $vote_id) {
        $url = env('VOTING_URL').'/vote/'.$vote_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);
        $vote = $json['data'];

        if(!$request->isMethod('post')) {
            return view('admin.vote.delete', ['vote' => $vote]);
        }

        $validator = Validator::make($request->all(), [
            'private_key' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect($request->url())->withErrors($validator)->withInput();
        }

        $data = [
            'private_key' => $request->input('private_key'),
            'vote_id' => $vote_id,
        ];
        $payload = json_encode($data);

        $url = env('VOTING_URL').'/vote';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        if(!isset($json['batch_ids'])) {
            $request->session()->flash('error', 'Failed to submit request');
            return view('admin.vote.delete', ['vote' => $vote]);
        }

        DB::table('batches')->insert([
            'id' => $json['batch_ids'],
            'action' => $json['action'],
            'data' => json_encode($json['data']),
            'submitted_at' => date('Y-m-d H:i:s', $json['submitted_at']),
        ]);

        DB::table('cron')->where('data', $vote->id)->delete();

        $request->session()->flash('status', 'Successfully submitted request');
        return redirect('batch/'.$json['batch_ids']);
    }

    public function add_ballot(Request $request, $vote_id) {
        $url = env('VOTING_URL').'/vote/'.$vote_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);
        $vote = $json['data'];

        if(!$request->isMethod('post')) {
            return view('admin.ballot.add', ['vote' => $vote]);
        }

        $validator = Validator::make($request->all(), [
            'private_key' => 'required',
            'id' => 'required',
            'code_mode' => 'required',
            'quantity' => 'required_if:code_mode,generate|max:1000',
            'codes' => 'required_if:code_mode,custom',
        ]);

        if ($validator->fails()) {
            return redirect($request->url())->withErrors($validator)->withInput();
        }

        $data = [
            'private_key' => $request->input('private_key'),
            'vote_id' => $request->input('id'),
        ];
        if($request->input('code_mode') == 'generate') {
            $data['quantity'] = (int)$request->input('quantity');
        } else {
            $data['codes'] = preg_split('/\r\n|[\r\n]/', $request->input('codes'));
        }
        $payload = json_encode($data);

        $url = env('VOTING_URL').'/ballot/add';
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
            return view('admin.ballot.add', ['vote' => $vote]);
        }

        $codes = $json['codes'];
        DB::table('batches')->insert([
            'id' => $json['batch_ids'],
            'action' => $json['action'],
            'data' => json_encode($json['data']),
            'submitted_at' => date('Y-m-d H:i:s', $json['submitted_at']),
        ]);
        $request->session()->flash('status', 'Successfully submitted request');
        return view('admin.ballot.codes', ['vote' => $vote, 'codes' => $codes, 'batch_id' => $json['batch_ids']]);
    }

    public function count_ballot(Request $request) {
        $validator = Validator::make($request->all(), [
            'private_key' => 'required',
            'vote_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid argument',
            ]);
        }

        $data = [
            'private_key' => $request->input('private_key'),
            'vote_id' => $request->input('vote_id'),
        ];
        $payload = json_encode($data);

        $url = env('VOTING_URL').'/ballot/count';
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
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit request',
            ]);
        }

        DB::table('batches')->insert([
            'id' => $json['batch_ids'],
            'action' => $json['action'],
            'data' => json_encode($json['data']),
            'submitted_at' => date('Y-m-d H:i:s', $json['submitted_at']),
        ]);

        return response()->json([
            'success' => true,
            'id' => $json['batch_ids'],
        ]);
    }

    public function update_result(Request $request) {
        $vote_id = $request->input('vote_id');

        if(!$vote_id) {
            return;
        }

        $url = env('VOTING_URL').'/vote/'.$vote_id.'/result';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        if(!(isset($json['data']) && $json['data'])) {
            return;
        }

        $results = [];
        foreach($json['data'] as $result) {
            $created_at = date('Y-m-d H:i:s', $result['created_at']);
            if(DB::table('results')->where('created_at', $created_at)->exists()) {
                continue;
            }

            if(!(isset($result['total']) && $result['total'] > 0)) {
                continue;
            }

            $results []= [
                'vote_id' => $result['vote_id'],
                'item' => 'total',
                'count' => $result['total'],
                'created_at' => $created_at,
            ];
            $results []= [
                'vote_id' => $result['vote_id'],
                'item' => 'casted',
                'count' => isset($result['casted'])? $result['casted']: 0,
                'created_at' => $created_at,
            ];
            foreach($result['counts'] as $count) {
                $results []= [
                    'vote_id' => $result['vote_id'],
                    'item' => $count['candidate'],
                    'count' => isset($count['count'])? $count['count']: 0,
                    'created_at' => $created_at,
                ];
            }
        }

        DB::table('results')->insert($results);
    }

    public function batch_index(Request $request) {
        $current = $request->query('current', '');

        $url = env('BLOCKCHAIN_URL').'/batches?limit=20&start='.$current;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        return view('admin.blockchain.batch.index', ['batches' => $json['data'], 'next' => (isset($json['paging']['next_position'])? $json['paging']['next_position']: '')]);
    }

    public function batch(Request $request, $batch_id) {
        $url = env('BLOCKCHAIN_URL').'/batches/'.$batch_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        return view('admin.blockchain.batch.show', ['batch' => (isset($json['data'])? $json['data']: null)]);
    }

    public function block_index(Request $request) {
        $current = $request->query('current', '');

        $url = env('BLOCKCHAIN_URL').'/blocks?limit=20&start='.$current;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        return view('admin.blockchain.block.index', ['blocks' => $json['data'], 'next' => (isset($json['paging']['next_position'])? $json['paging']['next_position']: '')]);
    }

    public function block(Request $request, $block_id) {
        $url = env('BLOCKCHAIN_URL').'/blocks/'.$block_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        return view('admin.blockchain.block.show', ['block' => (isset($json['data'])? $json['data']: null)]);
    }

    public function transaction_index(Request $request) {
        $current = $request->query('current', '');

        $url = env('BLOCKCHAIN_URL').'/transactions?limit=20&start='.$current;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        return view('admin.blockchain.transaction.index', ['transactions' => $json['data'], 'next' => (isset($json['paging']['next_position'])? $json['paging']['next_position']: '')]);
    }

    public function transaction(Request $request, $transaction_id) {
        $url = env('BLOCKCHAIN_URL').'/transactions/'.$transaction_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        return view('admin.blockchain.transaction.show', ['transaction' => (isset($json['data'])? $json['data']: null)]);
    }

    public function state_index(Request $request) {
        $current = $request->query('current', '');
        $address = $request->query('address', '');

        $url = env('BLOCKCHAIN_URL').'/state?limit=20&start='.$current.'&address='.$address;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        return view('admin.blockchain.state.index', ['states' => $json['data'], 'next' => (isset($json['paging']['next_position'])? $json['paging']['next_position']: '')]);
    }

    public function state(Request $request, $address) {
        $url = env('BLOCKCHAIN_URL').'/state/'.$address;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        return view('admin.blockchain.state.show', ['state' => (isset($json['data'])? $json['data']: null), 'address' => $address]);
    }
}

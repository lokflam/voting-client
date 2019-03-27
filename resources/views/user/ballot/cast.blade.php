@extends('layout')

@section('title', 'Ballot')

@section('content')
    <h1>Ballot</h1>
    <?php if(!$vote): ?>
        <p>Vote not exists</p>
    <?php else: ?>
        <p><a href="{{ url('vote/'.$vote['id']) }}">View vote</a></p>

        <form method="POST" action="">
            @csrf
            <div class="form-group row">
                <label for="vote_id" class="col-md-2 col-form-label">Vote ID</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="vote_id" value="{{ $ballot['vote_id'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="hashed_code" class="col-md-2 col-form-label">Hashed code</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="hashed_code" value="{{ $ballot['hashed_code'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="casted_at" class="col-md-2 col-form-label">Casted at</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="casted_at" value="{{ isset($ballot['casted_at'])? date('Y-m-d H:i:s', $ballot['casted_at']): '' }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="total_at" class="col-md-2 col-form-label">Counted in total at</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="total_at" value="{{ ($created_log && isset($created_log['processed_at']))? $created_log['processed_at']: '' }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="total_at" class="col-md-2 col-form-label">Counted in casted at</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="total_at" value="{{ ($casted_log && isset($casted_log['processed_at']))? $casted_log['processed_at']: '' }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="choice" class="col-md-2 col-form-label">Choice</label>
                <div class="col-md-10">
                    <?php foreach($vote['candidates'] as $candidate): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="choice" value="{{ $candidate['code'] }}" {{ (isset($ballot['choice']) && $ballot['choice']) || (isset($candidate['status']) && $candidate['status'] == 1)? 'disabled': '' }} {{ (isset($ballot['choice']) && $ballot['choice'] == $candidate['code'])? 'checked': '' }}>
                            <label class="form-check-label" for="code-custom">{{ $candidate['code'].' - '.$candidate['name'] }}</label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <input type="hidden" name="code" value={{ $code }}>
            <input type="hidden" name="cast" value="1">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    <?php endif; ?>
@endsection
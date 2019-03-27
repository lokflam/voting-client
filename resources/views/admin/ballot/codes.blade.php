@extends('admin.layout')

@section('title', 'Add ballot')

@section('content')
    <h1>Add ballot</h1>
    <?php if(!$vote): ?>
        <p>Vote not exists</p>
    <?php else: ?>
        <form method="" action="">
            <div class="form-group row">
                <label for="id" class="col-md-2 col-form-label">ID</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="id" value="{{ $vote['id'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="codes" class="col-md-2 col-form-label">Codes</label>
                <div class="col-md-10">
                    <textarea class="form-control-plaintext" name="codes" rows="5"><?php foreach($codes as $code) echo $code."\n"; ?></textarea>
                </div>
            </div>
        </form>
        <p><a href="{{ url('batch/'.$batch_id) }}">Batch info</a></p>
    <?php endif; ?>
@endsection

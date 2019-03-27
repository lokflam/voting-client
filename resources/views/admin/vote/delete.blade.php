@extends('admin.layout')

@section('title', 'Delete vote')

@section('content')
    <h1>Delete vote</h1>
    <?php if(!$vote): ?>
        <p>Vote not exists</p>
    <?php else: ?>
        <form method="POST" action="">
            @csrf
            <div class="form-group row">
                <label for="private_key" class="col-md-2 col-form-label">Private key</label>
                <div class="col-md-10">
                    <input type="text" class="form-control{{ $errors->has('private_key')? ' is-invalid': '' }}" name="private_key" value="{{ old('private_key') }}">
                    <div class="invalid-feedback">{{ $errors->first('private_key') }}</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="id" class="col-md-2 col-form-label">ID</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="id" value="{{ $vote['id'] }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    <?php endif; ?>
@endsection
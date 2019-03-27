@extends('admin.layout')

@section('title', 'Add ballot')

@section('content')
    <h1>Add ballot</h1>
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
            <div class="form-check">
                <input class="form-check-input" type="radio" name="code_mode" value="generate">
                <label class="form-check-label" for="code-generate">Genereate codes</label>
            </div>
            <div class="form-group row d-none" id="quantity-field">
                <label for="quantity" class="col-md-2 col-form-label">Quantity</label>
                <div class="col-md-10">
                    <input type="number" min="1" max="1000" class="form-control{{ $errors->has('quantity')? ' is-invalid': '' }}" name="quantity" value="{{ old('quantity') }}">
                    <div class="invalid-feedback">{{ $errors->first('quantity') }}</div>
                </div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="code_mode" value="custom">
                <label class="form-check-label" for="code-custom">Use custom codes</label>
            </div>
            <div class="form-group row d-none" id="codes-field">
                <label for="codes" class="col-md-2 col-form-label">Codes (seperate with new line)</label>
                <div class="col-md-10">
                    <textarea class="form-control{{ $errors->has('codes')? ' is-invalid': '' }}" name="codes" rows="5">{{ old('codes') }}</textarea>
                    <div class="invalid-feedback">{{ $errors->first('codes') }}</div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    <?php endif; ?>
@endsection

@section('body-foot')
    <script src="{{ url('js/vote.js') }}"></script>
@endsection
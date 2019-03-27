@extends('layout')

@section('title', 'Search ballot')

@section('content')
   <h1>Search ballot</h1>
   <?php if(app('request')->query('not_exists')): ?>
      <p>Ballot not exists</p>
   <?php else: ?>
      <form method="POST" action="ballot/cast">
         @csrf
         <div class="form-group row">
            <label for="vote_id" class="col-md-2 col-form-label">Vote ID</label>
            <div class="col-md-10">
                  <input type="text" class="form-control{{ $errors->has('vote_id')? ' is-invalid': '' }}" name="vote_id" value="{{ old('vote_id') }}">
                  <div class="invalid-feedback">{{ $errors->first('vote_id') }}</div>
            </div>
         </div>
         <div class="form-group row">
            <label for="code" class="col-md-2 col-form-label">Code</label>
            <div class="col-md-10">
                  <input type="text" class="form-control{{ $errors->has('code')? ' is-invalid': '' }}" name="code" value="{{ old('code') }}">
                  <div class="invalid-feedback">{{ $errors->first('code') }}</div>
            </div>
         </div>
         <button type="submit" class="btn btn-primary">Submit</button>
      </form>
   <?php endif; ?>
@endsection
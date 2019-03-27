@extends('admin.layout')

@section('title', 'State')

@section('content')
   <h1>State</h1>
   <?php if(!$state): ?>
      <p>State not exists</p>
   <?php else: ?>
      <form method="" action="">
         <div class="form-group row">
            <label for="address" class="col-md-2 col-form-label">address</label>
            <div class="col-md-10">
               <input type="text" class="form-control-plaintext" name="address" value="{{ $address }}">
            </div>
         </div>
         <div class="form-group row">
            <label for="data" class="col-md-2 col-form-label">Data</label>
            <div class="col-md-10">
                  <textarea class="form-control-plaintext" name="data" rows="20">{{ $state }}</textarea>
            </div>
        </div>
      </form>
   <?php endif; ?>
@endsection
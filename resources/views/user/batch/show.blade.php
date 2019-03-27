@extends('layout')

@section('title', 'Batch status')

@section('content')
   <h1>Batch status</h1>
   <?php if(!$batch): ?>
      <p>Batch not exists</p>
   <?php else: ?>
      <form method="" action="">
         <div class="form-group row">
            <label for="id" class="col-md-2 col-form-label">ID</label>
            <div class="col-md-10">
               <input type="text" class="form-control-plaintext" name="id" value="{{ $batch['id'] }}">
            </div>
         </div>
         <div class="form-group row">
            <label for="status" class="col-md-2 col-form-label">Status</label>
            <div class="col-md-10">
               <input type="text" class="form-control-plaintext" name="status" value="{{ $batch['status'] }}">
            </div>
         </div>
         <div class="form-group row">
            <label for="message" class="col-md-2 col-form-label">Message</label>
            <div class="col-md-10">
               <input type="text" class="form-control-plaintext" name="message" value="{{ isset($batch['invalid_transactions'][0]['message'])? $batch['invalid_transactions'][0]['message']: '' }}">
            </div>
         </div>
         <?php if($record): ?>
            <div class="form-group row">
               <label for="action" class="col-md-2 col-form-label">Action</label>
               <div class="col-md-10">
                  <input type="text" class="form-control-plaintext" name="action" value="{{ $record->action }}">
               </div>
            </div>
            <div class="form-group row">
               <label for="data" class="col-md-2 col-form-label">Data</label>
               <div class="col-md-10">
                  <input type="text" class="form-control-plaintext" name="data" value="{{ $record->data }}">
               </div>
            </div>
            <div class="form-group row">
               <label for="submitted_at" class="col-md-2 col-form-label">Submitted at</label>
               <div class="col-md-10">
                  <input type="text" class="form-control-plaintext" name="submitted_at" value="{{ $record->submitted_at }}">
               </div>
            </div>
         <?php endif; ?>
      </form>
   <?php endif; ?>
@endsection
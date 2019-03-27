@extends('admin.layout')

@section('title', 'Batches')

@section('content')
   <h1>Batch</h1>
   <form method="GET" action="" class="form-inline">
      <input type="text" class="my-1 mr-2 form-control" name="query" value="" placeholder="Header signature">
      <button type="submit" class="btn btn-primary" id="btn-redirect" data-url="{{ url('admin/blockchain/batch') }}">Go</button>
   </form>
   <div class="table-responsive">
      <table class="table table-hover">
         <thead>
            <th>Header signature</th>
            <th></th>
         </thead>
         <tbody>
            <?php if($batches) foreach ($batches as $batch): ?>
               <tr>
                  <td>{{ $batch['header_signature'] }}</td>
                  <td><a href="{{ url('admin/blockchain/batch/'.$batch['header_signature']) }}"><button type="button" class="btn btn-info">View</button></a></td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
   <?php if($next != ""): ?>
      <a href="{{ url('admin/blockchain/batch?current='.$next) }}"><button type="button" class="btn btn-light">Next</button></a>
   <?php endif; ?>
@endsection

@section('body-foot')
    <script src="{{ url('js/vote.js') }}"></script>
@endsection
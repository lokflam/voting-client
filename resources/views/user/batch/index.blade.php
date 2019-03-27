@extends('layout')

@section('title', 'Batch status')

@section('content')
   <h1>Batch status</h1>
   <form method="GET" action="" class="form-inline">
      <input type="text" class="my-1 mr-2 form-control" name="id" value="{{ app('request')->input('id') }}" placeholder="ID">
      <button type="submit" class="btn btn-primary">Search</button>
   </form>
   <div class="table-responsive">
      <table class="table table-hover">
         <thead>
            <th>ID</th>
            <th>Action</th>
            <th>Submitted at</th>
            <th></th>
         </thead>
         <tbody>
            <?php foreach ($batches as $batch): ?>
               <tr>
                  <td>{{ $batch->id }}</td>
                  <td>{{ $batch->action }}</td>
                  <td>{{ $batch->submitted_at }}</td>
                  <td><a href="{{ url('batch/'.$batch->id) }}"><button type="button" class="btn btn-info">View</button></a></td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
   <p>
      {{ $batches->links() }}
   </p>
@endsection

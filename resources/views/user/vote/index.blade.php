@extends('layout')

@section('title', 'Votes')

@section('content')
   <h1>Vote</h1>
   <form method="GET" action="" class="form-inline">
      <input type="text" class="my-1 mr-2 form-control" name="query" value="" placeholder="ID">
      <button type="submit" class="btn btn-primary" id="btn-redirect" data-url="{{ url('vote') }}">Go</button>
   </form>
   <div class="table-responsive">
      <table class="table table-hover">
         <thead>
            <th>ID</th>
            <th>Name</th>
            <th>Start at</th>
            <th>End at</th>
            <th>Created at</th>
            <th></th>
         </thead>
         <tbody>
            <?php if($votes) foreach ($votes as $vote): ?>
               <tr>
                  <td>{{ $vote['id'] }}</td>
                  <td>{{ $vote['name'] }}</td>
                  <td>{{ date('Y-m-d H:i:s', $vote['start_at']) }}</td>
                  <td>{{ date('Y-m-d H:i:s', $vote['end_at']) }}</td>
                  <td>{{ date('Y-m-d H:i:s', $vote['created_at']) }}</td>
                  <td><a href="{{ url('vote/'.$vote['id']) }}"><button type="button" class="btn btn-info">View</button></a></td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
   <?php if($next != ""): ?>
      <a href="{{ url('vote?current='.$next) }}"><button type="button" class="btn btn-light">Next</button></a>
   <?php endif; ?>
@endsection

@section('body-foot')
   <script src="{{ url('js/vote.js') }}"></script>
@endsection
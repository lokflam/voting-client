@extends('admin.layout')

@section('title', 'Votes')

@section('content')
   <h1>Vote</h1>
   <form method="GET" action="" class="form-inline">
      <input type="text" class="my-1 mr-2 form-control" name="id" value="" placeholder="ID">
      <button type="submit" class="btn btn-primary" id="search-vote-admin">Go</button>
   </form>

   <p><a href="{{ url('admin/vote/create') }}"><button type="button" class="btn btn-success">Create</button></a></p>

   <div class="table-responsive">
      <table class="table table-hover">
         <thead>
            <th>ID</th>
            <th>Name</th>
            <th>Start at</th>
            <th>End at</th>
            <th>Created at</th>
            <th></th>
            <th></th>
            <th></th>
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
                  <td><a href="{{ url('admin/vote/'.$vote['id'].'/update') }}"><button type="button" class="btn btn-info">Update</button></a></td>
                  <td><a href="{{ url('admin/vote/'.$vote['id'].'/delete') }}"><button type="button" class="btn btn-danger">Delete</button></a></td>
                  <td><a href="{{ url('admin/vote/'.$vote['id'].'/ballot/add') }}"><button type="button" class="btn btn-outline-primary">Add ballot</button></a></td>
                  <td><button type="button" class="count-ballot btn btn-outline-secondary" data-id="{{ $vote['id'] }}">Count ballot</button></td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
   <?php if($next != ""): ?>
      <a href="{{ url('?current='.$next) }}"><button type="button" class="btn btn-light">Next</button></a>
   <?php endif; ?>
@endsection

@section('body-foot')
   <script src="{{ url('js/vote.js') }}"></script>
@endsection
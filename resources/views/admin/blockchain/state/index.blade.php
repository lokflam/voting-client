@extends('admin.layout')

@section('title', 'States')

@section('content')
   <h1>State</h1>
   <form method="GET" action="" class="form-inline">
      <label for="address" class="my-1 mr-2 col-form-label">Address prefix (must be in even number)</label>
      <input type="text" class="my-1 mr-2 form-control" name="address" value="{{ app('request')->input('address') }}">
      <button type="submit" class="btn btn-primary">Search</button>
   </form>
   <div class="table-responsive">
      <table class="table table-hover">
         <thead>
            <th>Address</th>
            <th></th>
         </thead>
         <tbody>
            <?php if($states) foreach ($states as $state): ?>
               <tr>
                  <td>{{ $state['address'] }}</td>
                  <td><a href="{{ url('admin/blockchain/state/'.$state['address']) }}"><button type="button" class="btn btn-info">View</button></a></td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
   <?php if($next != ""): ?>
      <a href="{{ url('admin/blockchain/state?current='.$next.'&address='.app('request')->input('address')) }}"><button type="button" class="btn btn-light">Next</button></a>
   <?php endif; ?>
@endsection

@section('body-foot')
    <script src="{{ url('js/vote.js') }}"></script>
@endsection
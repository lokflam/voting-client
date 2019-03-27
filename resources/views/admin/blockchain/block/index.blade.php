@extends('admin.layout')

@section('title', 'Blocks')

@section('content')
   <h1>Block</h1>
   <form method="GET" action="" class="form-inline">
      <input type="text" class="my-1 mr-2 form-control" name="query" value="" placeholder="Header signature">
      <button type="submit" class="btn btn-primary" id="btn-redirect" data-url="{{ url('admin/blockchain/block') }}">Go</button>
   </form>
   <div class="table-responsive">
      <table class="table table-hover">
         <thead>
            <th>Header signature</th>
            <th></th>
         </thead>
         <tbody>
            <?php if($blocks) foreach ($blocks as $block): ?>
               <tr>
                  <td>{{ $block['header_signature'] }}</td>
                  <td><a href="{{ url('admin/blockchain/block/'.$block['header_signature']) }}"><button type="button" class="btn btn-info">View</button></a></td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
   <?php if($next != ""): ?>
      <a href="{{ url('admin/blockchain/block?current='.$next) }}"><button type="button" class="btn btn-light">Next</button></a>
   <?php endif; ?>
@endsection

@section('body-foot')
    <script src="{{ url('js/vote.js') }}"></script>
@endsection
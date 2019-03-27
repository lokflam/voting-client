@extends('admin.layout')

@section('title', 'Transactions')

@section('content')
   <h1>Transaction</h1>
   <form method="GET" action="" class="form-inline">
      <input type="text" class="my-1 mr-2 form-control" name="query" value="" placeholder="Header signature">
      <button type="submit" class="btn btn-primary" id="btn-redirect" data-url="{{ url('admin/blockchain/transaction') }}">Go</button>
   </form>
   <div class="table-responsive">
      <table class="table table-hover">
         <thead>
            <th>Header signature</th>
            <th></th>
         </thead>
         <tbody>
            <?php if($transactions) foreach ($transactions as $transaction): ?>
               <tr>
                  <td>{{ $transaction['header_signature'] }}</td>
                  <td><a href="{{ url('admin/blockchain/transaction/'.$transaction['header_signature']) }}"><button type="button" class="btn btn-info">View</button></a></td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
   <?php if($next != ""): ?>
      <a href="{{ url('admin/blockchain/transaction?current='.$next) }}"><button type="button" class="btn btn-light">Next</button></a>
   <?php endif; ?>
@endsection

@section('body-foot')
    <script src="{{ url('js/vote.js') }}"></script>
@endsection
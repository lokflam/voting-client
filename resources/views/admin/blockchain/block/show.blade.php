@extends('admin.layout')

@section('title', 'Block')

@section('content')
   <h1>Block</h1>
   <?php if(!$block): ?>
      <p>Block not exists</p>
   <?php else: ?>
      <form method="" action="">
         <div class="form-group row">
            <label for="sign" class="col-md-2 col-form-label">Header signature</label>
            <div class="col-md-10">
               <input type="text" class="form-control-plaintext" name="sign" value="{{ $block['header_signature'] }}">
            </div>
         </div>
         <div class="form-group row">
            <label for="data" class="col-md-2 col-form-label">Data</label>
            <div class="col-md-10">
                <pre><code>{{ json_encode($block, JSON_PRETTY_PRINT) }}</code></pre>
            </div>
        </div>
      </form>
   <?php endif; ?>
@endsection
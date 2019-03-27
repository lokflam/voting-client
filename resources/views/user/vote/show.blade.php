@extends('layout')

@section('title', 'Vote')

@section('content')
    <h1>Vote</h1>
    <?php if(!$vote): ?>
      <p>Vote not exists</p>
    <?php else: ?>
        <h2>Information</h2>    
        <form method="" action="">
            <div class="form-group row">
                <label for="id" class="col-md-2 col-form-label">ID</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="id" value="{{ $vote['id'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label">Name</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="name" value="{{ $vote['name'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="description" class="col-md-2 col-form-label">Description</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="description" value="{{ isset($vote['description'])? $vote['description']: '' }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="start_at" class="col-md-2 col-form-label">Start at</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="start_at" value="{{ date('Y-m-d H:i:s', $vote['start_at']) }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="end_at" class="col-md-2 col-form-label">End at</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="end_at" value="{{ date('Y-m-d H:i:s', $vote['end_at']) }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="candidates" class="col-md-2 col-form-label">Candidates</label>
                <div class="col-md-10" id="candidates">
                    <?php foreach($vote['candidates'] as $candidate): ?>
                        <div class="card mb-3 candidate-field">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="candidate-code" class="col-md-2 col-form-label">Candidate code</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control-plaintext" name="candidate_code[]" value="{{ $candidate['code'] }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="candidate-name" class="col-md-2 col-form-label">Candidate name</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control-plaintext" name="candidate_name[]" value="{{ $candidate['name'] }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="candidate-description" class="col-md-2 col-form-label">Candidate description</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control-plaintext" name="candidate_description[]" value="{{ isset($candidate['description'])? $candidate['description']: '' }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="candidate-status" class="col-md-2 col-form-label">Candidate status</label>
                                    <div class="col-md-10">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="candidate_status[]" {{ (isset($candidate['status']) && $candidate['status'])? '': 'checked' }} disabled>
                                            <label class="form-check-label" for="candidate-qualified">Qualified</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </form>
        <hr/>
        <h2>Recent result</h2>
        <?php if(isset($total->count) && $total->count > 0): ?>
            <div id="chart" class="col-md-12"></div>
            <div id="pie" class="col-md-12"></div>
        <?php else: ?>
            <p>No result yet</p>
        <?php endif; ?>
    <?php endif; ?>
@endsection

@section('body-foot')
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script>
        var coordinates = @json($coordinates);
        var vote_id = "{{ $vote? $vote['id']: '' }}";
        var total = "{{ isset($total->count)? $total->count: 0 }}";
        var casted = "{{ isset($casted->count)? $casted->count: 0 }}";
    </script>
    <script src="{{ url('js/chart.js') }}"></script>
@endsection
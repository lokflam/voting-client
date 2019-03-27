@extends('admin.layout')

@section('title', 'Update vote')

@section('content')
    <h1>Update vote</h1>
    <?php if(!$vote): ?>
        <p>Vote not exists</p>
    <?php else: ?>
        <p>
            <a href="{{ url('admin/vote/'.$vote['id'].'/ballot/add') }}"><button type="button" class="btn btn-outline-primary">Add ballot</button></a>
            <button type="button" class="count-ballot btn btn-outline-secondary" data-id="{{ $vote['id'] }}">Count ballot</button>
        </p>

        <form method="POST" action="">
            @csrf
            <div class="form-group row">
                <label for="private_key" class="col-md-2 col-form-label">Private key</label>
                <div class="col-md-10">
                    <input type="text" class="form-control{{ $errors->has('private_key')? ' is-invalid': '' }}" name="private_key" value="{{ old('private_key') }}">
                    <div class="invalid-feedback">{{ $errors->first('private_key') }}</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="id" class="col-md-2 col-form-label">ID</label>
                <div class="col-md-10">
                    <input type="text" class="form-control-plaintext" name="id" value="{{ $vote['id'] }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label">Name</label>
                <div class="col-md-10">
                    <input type="text" class="form-control{{ $errors->has('name')? ' is-invalid': '' }}" name="name" value="{{ old('name')? old('name'): $vote['name'] }}">
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="description" class="col-md-2 col-form-label">Description</label>
                <div class="col-md-10">
                    <textarea class="form-control{{ $errors->has('description')? ' is-invalid': '' }}" name="description" rows="2">{{ old('description')? old('description'): (isset($vote['description'])? $vote['description']: '') }}</textarea>
                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="start_at" class="col-md-2 col-form-label">Start at</label>
                <div class="col-md-10">
                    <input type="datetime-local" class="form-control{{ $errors->has('start_at')? ' is-invalid': '' }}" name="start_at" value="{{ old('start_at')? old('start_at'): date('Y-m-d\TH:i:s', $vote['start_at']) }}">
                    <div class="invalid-feedback">{{ $errors->first('start_at') }}</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="end_at" class="col-md-2 col-form-label">End at</label>
                <div class="col-md-10">
                    <input type="datetime-local" class="form-control{{ $errors->has('end_at')? ' is-invalid': '' }}" name="end_at" value="{{ old('end_at')? old('end_at'): date('Y-m-d\TH:i:s', $vote['end_at']) }}">
                    <div class="invalid-feedback">{{ $errors->first('end_at') }}</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="candidates" class="col-md-2 col-form-label">Candidates</label>
                <div class="col-md-10" id="candidates">
                    <?php
                        $i = 0;
                        $has_candidate = true;
                        $use_vote_data = old('candidate_code.0')? false: true;
                    ?>
                    <?php while($has_candidate): ?>
                        <?php $has_candidate = $use_vote_data? isset($vote['candidates'][$i+1]): (old('candidate_code.'.($i+1))? true: false); ?>
                        <div class="card mb-3 candidate-field">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="candidate-code" class="col-md-2 col-form-label">Candidate code</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control{{ $errors->has('candidate_code.'.$i)? ' is-invalid': '' }}" name="candidate_code[]" value="{{ $use_vote_data? $vote['candidates'][$i]['code']: old('candidate_code.'.$i) }}">
                                        <div class="invalid-feedback">{{ $errors->first('candidate_code.'.$i) }}</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="candidate-name" class="col-md-2 col-form-label">Candidate name</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control{{ $errors->has('candidate_name.'.$i)? ' is-invalid': '' }}" name="candidate_name[]" value="{{ $use_vote_data? $vote['candidates'][$i]['name']: old('candidate_name.'.$i) }}">
                                        <div class="invalid-feedback">{{ $errors->first('candidate_name.'.$i) }}</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="candidate-description" class="col-md-2 col-form-label">Candidate description</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control{{ $errors->has('candidate_description.'.$i)? ' is-invalid': '' }}" name="candidate_description[]" rows="2">{{ $use_vote_data? (isset($vote['candidates'][$i]['description'])? $vote['candidates'][$i]['description']: ''): old('candidate_description.'.$i) }}</textarea>
                                        <div class="invalid-feedback">{{ $errors->first('candidate_description.'.$i) }}</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="candidate-status" class="col-md-2 col-form-label">Candidate status</label>
                                    <div class="col-md-10">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input{{ $errors->has('candidate_status.'.$i)? ' is-invalid': '' }}" name="candidate_status[]" {{ $use_vote_data? (isset($vote['candidates'][$i]['status']) && $vote['candidates'][$i]['status']? '': 'checked'): (old('candidate_code.'.$i) && !old('candidate_status.'.$i)? '': 'checked') }}>
                                            <label class="form-check-label" for="candidate-qualified">Qualified</label>
                                        </div>
                                        <div class="invalid-feedback">{{ $errors->first('candidate_status.'.$i) }}</div>
                                    </div>
                                </div>
                                <?php if($has_candidate): ?>
                                    <button class="btn btn-danger remove-candidate">Remove</button>
                                <?php else: ?>
                                    <button class="btn btn-info add-candidate">Add</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php $i += 1; ?>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="cron_intervel" class="col-md-2 col-form-label">Count every</label>
                <div class="col-md-10">
                    <select class="form-control{{ $errors->has('cron_intervel')? ' is-invalid': '' }}" name="cron_intervel">
                        <option value="* * * * *" {{ $cron_intervel == '* * * * *'? 'selected': '' }}>minute</option>
                        <option value="*/5 * * * *" {{ $cron_intervel == '*/5 * * * *'? 'selected': '' }}>5 minute</option>
                        <option value="*/10 * * * *" {{ $cron_intervel == '*/10 * * * *'? 'selected': '' }}>10 minute</option>
                        <option value="*/15 * * * *" {{ $cron_intervel == '*/15 * * * *'? 'selected': '' }}>15 minute</option>
                        <option value="*/30 * * * *" {{ $cron_intervel == '*/30 * * * *'? 'selected': '' }}>30 minute</option>
                        <option value="0 * * * *" {{ $cron_intervel == '0 * * * *'? 'selected': '' }}>hour</option>
                        <option value="0 0 * * *" {{ $cron_intervel == '0 0 * * *'? 'selected': '' }}>day</option>
                        <option value="n" {{ $cron_intervel == 'n'? 'selected': '' }}>never</option>
                    </select>
                    <div class="invalid-feedback">{{ $errors->first('cron_intervel') }}</div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    <?php endif; ?>
@endsection

@section('body-foot')
    <script src="{{ url('js/vote.js') }}"></script>
@endsection
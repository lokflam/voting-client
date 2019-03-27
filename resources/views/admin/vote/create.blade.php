@extends('admin.layout')

@section('title', 'Create vote')

@section('content')
    <h1>Create vote</h1>
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
                <input type="text" class="form-control{{ $errors->has('id')? ' is-invalid': '' }}" name="id" value="{{ old('id') }}">
                <div class="invalid-feedback">{{ $errors->first('id') }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label for="name" class="col-md-2 col-form-label">Name</label>
            <div class="col-md-10">
                <input type="text" class="form-control{{ $errors->has('name')? ' is-invalid': '' }}" name="name" value="{{ old('name') }}">
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label for="description" class="col-md-2 col-form-label">Description</label>
            <div class="col-md-10">
                <textarea class="form-control{{ $errors->has('description')? ' is-invalid': '' }}" name="description" rows="2">{{ old('description') }}</textarea>
                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label for="start_at" class="col-md-2 col-form-label">Start at</label>
            <div class="col-md-10">
                <input type="datetime-local" class="form-control{{ $errors->has('start_at')? ' is-invalid': '' }}" name="start_at" value="{{ old('start_at') }}">
                <div class="invalid-feedback">{{ $errors->first('start_at') }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label for="end_at" class="col-md-2 col-form-label">End at</label>
            <div class="col-md-10">
                <input type="datetime-local" class="form-control{{ $errors->has('end_at')? ' is-invalid': '' }}" name="end_at" value="{{ old('end_at') }}">
                <div class="invalid-feedback">{{ $errors->first('end_at') }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label for="candidates" class="col-md-2 col-form-label">Candidates</label>
            <div class="col-md-10" id="candidates">
                <?php
                    $i = 0;
                    $has_candidate = true;
                ?>
                <?php while($has_candidate): ?>
                    <?php $has_candidate = old('candidate_code.'.($i+1))? true: false; ?>
                    <div class="card mb-3 candidate-field">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="candidate-code" class="col-md-2 col-form-label">Candidate code</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control{{ $errors->has('candidate_code.'.$i)? ' is-invalid': '' }}" name="candidate_code[]" value="{{ old('candidate_code.'.$i) }}">
                                    <div class="invalid-feedback">{{ $errors->first('candidate_code.'.$i) }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="candidate-name" class="col-md-2 col-form-label">Candidate name</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control{{ $errors->has('candidate_name.'.$i)? ' is-invalid': '' }}" name="candidate_name[]" value="{{ old('candidate_name.'.$i) }}">
                                    <div class="invalid-feedback">{{ $errors->first('candidate_name.'.$i) }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="candidate-description" class="col-md-2 col-form-label">Candidate description</label>
                                <div class="col-md-10">
                                    <textarea class="form-control{{ $errors->has('candidate_description.'.$i)? ' is-invalid': '' }}" name="candidate_description[]" rows="2">{{ old('candidate_description.'.$i) }}</textarea>
                                    <div class="invalid-feedback">{{ $errors->first('candidate_description.'.$i) }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="candidate-status" class="col-md-2 col-form-label">Candidate status</label>
                                <div class="col-md-10">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input{{ $errors->has('candidate_status.'.$i)? ' is-invalid': '' }}" name="candidate_status[]" {{ old('candidate_code.'.$i) && !old('candidate_status.'.$i)? '': 'checked' }}>
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
                    <option value="* * * * *" {{ old('cron_intervel') == '* * * * *'? 'selected': '' }}>minute</option>
                    <option value="*/5 * * * *" {{ old('cron_intervel') == '*/5 * * * *'? 'selected': '' }}>5 minute</option>
                    <option value="*/10 * * * *" {{ old('cron_intervel') == '*/10 * * * *'? 'selected': '' }}>10 minute</option>
                    <option value="*/15 * * * *" {{ old('cron_intervel') == '*/15 * * * *'? 'selected': '' }}>15 minute</option>
                    <option value="*/30 * * * *" {{ old('cron_intervel') == '*/30 * * * *'? 'selected': '' }}>30 minute</option>
                    <option value="0 * * * *" {{ old('cron_intervel') == '0 * * * *'? 'selected': '' }}>hour</option>
                    <option value="0 0 * * *" {{ old('cron_intervel') == '0 0 * * *'? 'selected': '' }}>day</option>
                    <option value="n" {{ old('cron_intervel') == 'n'? 'selected': '' }}>never</option>
                </select>
                <div class="invalid-feedback">{{ $errors->first('cron_intervel') }}</div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection

@section('body-foot')
    <script src="{{ url('js/vote.js') }}"></script>
@endsection
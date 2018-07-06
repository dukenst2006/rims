@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <h3>Job Summary</h3>

                <h4>{{ $job->title }}</h4>

                <p>
                    {{ $job->overview_short }}
                </p>

                <p>
                    <strong>Salary: {{ $job->currency }}</strong>
                    @if($job->salary_min == $job->salary_max)
                        {{ $job->salary_min }}
                    @elseif($job->salary_min == 0 && $job->salary_max == 0)
                        Confidential
                    @else
                        {{ $job->salary_min }} - {{ $job->salary_max }}
                    @endif
                </p>

                <p>
                    <strong>Location:</strong>
                    @if($job->area->ancestors->count())
                        @foreach($job->area->ancestors as $ancestor)
                            <span>{{ $ancestor->name }},</span>
                        @endforeach
                    @endif

                    {{ $job->area->name }}
                </p>

                <p>
                    <strong>Work
                        site:</strong> {{ $job->on_location == false ? 'Remote / Off site' : 'On site' }}
                </p>

                <p>
                    <strong>Type:</strong> {{ $job->type == 'full-time' ? 'Full time' : 'Part time' }}
                </p>

                <p>
                    Posted
                    <time>{{ $job->published_at->diffForHumans() }}</time>
                    by <strong>{{ $job->company->name }}</strong>
                </p>

                <!-- Deadline -->
                <p>
                    <strong>Deadline</strong>
                    {{ $job->closed_at->diffForHumans() }}
                </p>
            </div><!-- /.col-sm-4 -->
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Job Application: Step 1</h4>
                        <div class="card-subtitle">
                            Fill the fields below to start the application process
                        </div>
                        <form method="POST" action="{{ route('jobs.applications.store', [$job, $jobApplication]) }}">
                            {{ csrf_field() }}

                            <div class="form-group row{{ $errors->has('cv') ? ' has-error' : '' }}">
                                <label for="cv" class="col-md-4 control-label">Upload CV</label>

                                <div class="col-md-6">
                                    <cv-upload send-as="file"
                                               endpoint="{{ route('jobs.applications.cv.store', [$job, $jobApplication]) }}"
                                               current-cv="{{ $jobApplication->cv }}"
                                               preview-link="{{ route('jobs.applications.cv.show', [$job, $jobApplication]) }}"></cv-upload>

                                    @if ($errors->has('cv'))
                                        <div class="invalid-feedback{{ $errors->has('cv') ? ' d-block' : '' }}">
                                            <strong>{{ $errors->first('cv') }}</strong>
                                        </div>
                                    @endif

                                    <small class="form-text text-muted">
                                        The CV must be in <strong>pdf</strong> format.
                                    </small>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="form-group row{{ $errors->has('details') ? ' has-error' : '' }}">
                                <label for="details" class="col-md-4 control-label">Details</label>

                                <div class="col-md-6">
                                    <textarea id="details"
                                              class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}"
                                              name="details" rows="6"
                                              autofocus>{{ old('details') }}</textarea>

                                    @if ($errors->has('details'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('details') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.card -->
                </div><!-- /.card -->
            </div><!-- /.col-sm-8 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
@endsection
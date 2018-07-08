@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="job-header">
            <div class="row">
                <div class="col-sm-9">
                    <h3>{{ $job->title }}</h3>

                    @if($job->isPremium && !auth()->check())
                        <p>You need to be signed in to view this job's details.
                            <a href="{{ route('login') }}">Login</a>
                        </p>
                    @else
                        <p>
                            {{ $job->overview_short }}
                        </p>

                        <p>
                            <strong>Salary: {{ !$job->salaryIsConfidential ? $job->currency : null }}</strong>

                            @if($job->salaryIsConfidential)
                                Confidential
                            @elseif($job->salary_min == $job->salary_max)
                                {{ $job->salary_min }}
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
                    @endif
                </div><!-- /.col-sm-9 -->

                <div class="col-sm-3">
                    <!-- Deadline -->
                    <p>
                        <strong>Deadline</strong>
                        {{ $job->closed_at->diffForHumans() }}
                    </p>

                    @if(!$job->isPastDeadline)
                        <p><!-- Application -->
                            <a href="{{ route('jobs.applications.index', $job) }}" class="btn btn-primary btn-block">
                                Apply now
                            </a>
                        </p>
                    @endif
                </div><!-- /.col-sm-3 -->
            </div><!-- /.row -->
        </section><!-- /.job-header -->

        <section class="job-content my-3">
            <div class="row">
                <div class="col-sm-9">
                    @if($job->isPremium && !auth()->check())
                        <p>You need to be signed in to view this job's requirements and full details.
                            <a href="{{ route('login') }}">Login</a>
                        </p>
                    @else
                    <!-- Details -->
                        <div class="mb-3">
                            <h4>Details</h4>
                            <article>
                                {{ $job->overview }}
                            </article>
                        </div>

                        <!-- Education -->
                        <div class="mb-3">
                            <h4>Education</h4>
                            <article>
                                @foreach($job->education as $qualification)
                                    <div class="mb-3">
                                        <p>
                                            <strong>{{ $qualification->education->name }}</strong>
                                        </p>
                                        <article>
                                            {{ $qualification->details }}
                                        </article>
                                    </div>
                                @endforeach
                            </article>
                        </div>

                        <!-- Skills -->
                        <div class="mb-3">
                            <h4>Skills</h4>
                            <article>
                                @foreach($job->skills as $skillset)
                                    <div class="mb-3">
                                        <p>
                                            <strong>{{ $skillset->skill->name }}</strong>
                                        </p>
                                        <article>
                                            {{ $skillset->details }}
                                        </article>
                                    </div>
                                @endforeach
                            </article>
                        </div>

                        <!-- Other requirements -->
                        <div class="mb-3">
                            <h4>Other requirements</h4>
                            <article>
                                @foreach($job->requirements as $requirement)
                                    <div class="mb-3">
                                        <p>
                                            <strong>{{ $requirement->title }}</strong>
                                        </p>
                                        <article>
                                            {{ $requirement->details }}
                                        </article>
                                    </div>
                                @endforeach
                            </article>
                        </div>
                    @endif
                </div><!-- /.col-sm-9 -->
            </div><!-- /.row -->
        </section><!-- /.job-content -->
    </div><!-- /.container -->
@endsection
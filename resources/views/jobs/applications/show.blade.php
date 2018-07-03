@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="job-portfolio-header py-4">
            <div class="row">
                <div class="col-sm-12">
                    <div class="align-content-center">
                        <div class="my-2">
                            <img src="{{ $jobApplication->user->avatar ?: asset('img/avatars/default_avatar.png') }}"
                                 alt="{{ $jobApplication->user->name }} avatar"
                                 class="rounded-circle mx-auto d-block">
                        </div>
                        <h3 class="text-center">
                            {{ $jobApplication->user->name }}
                        </h3>
                        <p class="text-center">
                            @foreach($jobApplication->user->skills as $skillset)
                                <span class="badge badge-dark py-2 px-2 h4 border-radius-0 mx-1">
                                    {{ $skillset->skill->name }}
                                </span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="job-portfolio-content py-4">
            <div class="row">
                <div class="col-sm-8">
                    <section class="my-2">
                        <h3>Portfolio</h3>

                        <div class="my-2 py-2">
                            <p class="lead">
                                <a href="#" class="btn btn-primary">Go here</a>
                                to view <strong>{{ $jobApplication->user->name }}'s</strong> full portfolio.
                            </p>
                        </div>
                    </section>

                    <section class="my-2 py-2">
                        <h3>User's bid</h3>

                        {{ $jobApplication->details }}
                    </section>

                    <section class="job-portfolio-footer py-4">
                        <!-- todo: Show review & rating + accept or reject status -->

                        @if($jobApplication->accepted_at)
                            <p class="lead">Congratulations! Your application has been accepted.</p>
                        @elseif($jobApplication->rejected_at)
                            <p class="lead">Sorry, your application has been rejected.</p>
                        @else
                            <p class="lead">Thank you for applying. We will notify you when your application has been
                                reviewed.</p>
                        @endif
                    </section>
                </div>
                <div class="col-sm-4">
                    @if($jobApplication->submitted_at)
                        <p><strong>Submitted</strong> {{ $jobApplication->submitted_at->diffForHumans() }}</p>
                    @else
                        <p>Application not yet submitted.</p>
                    @endif

                    <div class="list-group mb-3">
                        <div class="list-group-item">
                            <p class="h4 my-0">CV / Resume</p>
                        </div>
                        <a href="{{ route('jobs.applications.cv.show', [$job, $jobApplication]) }}"
                           class="list-group-item list-group-item-action text-primary">
                            View user's CV
                        </a>
                    </div>

                    <div class="list-group mb-3">
                        <div class="list-group-item">
                            <p class="h4 my-0">Education</p>
                        </div>
                        @foreach($jobApplication->user->schools as $school)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-content-center">
                                    <div>
                                        <p class="h5">{{ $school->name }}</p>
                                        <p>{{ $school->course }}</p>
                                        <p>{{ $school->education->name }}</p>
                                    </div>
                                    <aside>
                                        <time>{{ $school->enrollmentYear }}</time>
                                        <span> &ndash; </span>
                                        <time>{{ $school->graduationYear }}</time>
                                    </aside>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="list-group mb-3">
                        <div class="list-group-item">
                            <p class="h4 my-0">Other attachments</p>
                        </div>
                        <div class="list-group-item">
                            No extra attachements found.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
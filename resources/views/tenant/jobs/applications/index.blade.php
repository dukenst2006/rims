@extends('tenant.layouts.default')

@section('tenant.content')
    <header>
        <div class="d-flex justify-content-between align-content-center">
            <div>
                <h3>{{ $job->title }}</h3>
            </div>

            <aside>
                <div class="nav justify-content-end">
                    <!-- View job link -->
                    <a class="nav-item nav-link" href="{{ route('jobs.show', $job) }}">
                        View Job
                    </a>
                </div>
            </aside>
        </div>

        <div>
            <section class="collapse" id="collapseJobSummary">
                <p>{{ $job->overview }}</p>

                <p>
                    <strong>Work site:</strong>
                    {{ $job->on_location == false ? 'Remote / Off site' : 'On site' }}
                </p>

                <p>
                    <strong>Type:</strong> {{ $job->type == 'full-time' ? 'Full time' : 'Part time' }}
                </p>

                <p>
                    <strong>Posted</strong> {{ $job->published_at->diffForHumans() }}
                </p>
            </section>
            <section>
                <p>
                    <strong>Deadline</strong> {{ $job->closed_at->diffForHumans() }}
                </p>

                <a id="toggleJobSummary" data-toggle="collapse" href="#collapseJobSummary" role="button"
                   aria-expanded="false" aria-controls="collapseJobSummary">
                    Show job summary...
                </a>
            </section>
        </div>
    </header>
    <hr>

    <section class="my-3">
        @forelse($applications as $jobApplication)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="card-title">
                        <div class="d-flex justify-content-between align-content-center">
                            <p class="h5">
                                <img src="{{ $jobApplication->user->avatar ?: asset('img/avatars/default_avatar.png') }}"
                                     alt=""
                                     class="rounded-circle mr-1">
                                {{ $jobApplication->user->name }}
                            </p>

                            <aside>
                                @if(!$jobApplication->cancelled_at)
                                    <a href="{{ route('tenant.jobs.applications.show', [$job, $jobApplication]) }}">
                                        View
                                    </a>
                                @endif
                            </aside>
                        </div>
                    </div><!-- /.card-title -->

                    <!-- Submission date -->
                    <p>
                        <strong>Submitted</strong>
                        {{ $jobApplication->submitted_at->diffForHumans() }}
                    </p>

                    <!-- Cancel date -->
                    <ul class="list-inline">
                        @if($jobApplication->accepted_at)
                            @if($jobApplication->declined_at)
                                <li class="list-inline-item">
                                    <strong>Declined by user</strong>
                                    {{ $jobApplication->declined_at->diffForHumans() }}
                                </li>
                            @else
                                <li class="list-inline-item">
                                    <strong>Applicant accepted</strong>
                                    {{ $jobApplication->accepted_at->diffForHumans() }}
                                </li>
                            @endif
                        @elseif($jobApplication->cancelled_at)
                            <li class="list-inline-item">
                                <strong>Applicant cancelled</strong>
                                {{ $jobApplication->cancelled_at->diffForHumans() }}
                            </li>
                        @else
                            <li class="list-inline-item">
                                Awaiting review
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        @empty
            <p>No applications submitted yet.</p>
        @endforelse
    </section>
    <section class="job-applications-footer">
        {{ $applications->appends(request()->query())->links() }}
    </section>
@endsection

@section('scripts')
    <script>
        $('#collapseJobSummary').on('hidden.bs.collapse', function () {
            $('#toggleJobSummary').text('Show job summary...')
        })

        $('#collapseJobSummary').on('shown.bs.collapse', function () {
            $('#toggleJobSummary').text('Hide job summary')
        })
    </script>
@endsection
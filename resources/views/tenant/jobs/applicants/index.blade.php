@extends('tenant.layouts.default')

@section('tenant.content')
    <header>
        <h3>Jobs Applicants</h3>
        <p>List of applicants for different jobs.</p>
    </header>
    <hr>

    <section class="my-3">
        @forelse($applications as $jobApplication)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="card-title">
                        <div class="d-flex justify-content-between align-content-center">
                            <section>
                                <p class="h5">
                                    <img src="{{ $jobApplication->user->avatar ?: asset('img/avatars/default_avatar.png') }}"
                                         alt=""
                                         class="rounded-circle mr-1">
                                    {{ $jobApplication->user->name }}
                                </p>

                                <!-- Job -->
                                <div>
                                    Applied for <strong>{{ $jobApplication->job->title }}</strong>
                                </div>
                            </section>

                            <aside>
                                @if(!$jobApplication->cancelled_at)
                                    <a class="mr-1"
                                       href="{{ route('tenant.jobs.applications.show', [$jobApplication->job, $jobApplication]) }}">
                                        View
                                    </a>
                                @endif

                                <a class="mr-1" href="{{ route('jobs.show', $jobApplication->job) }}">
                                    View job
                                </a><!-- View job link -->

                                <a href="{{ route('tenant.jobs.applications.index', $jobApplication->job) }}">
                                    Other applicants
                                </a><!-- View job applications link -->
                            </aside>
                        </div>
                    </div><!-- /.card-title -->

                    <!-- Job summary -->
                    <article class="mb-1" id="job-collapse-{{ $jobApplication->id }}">
                        <!-- collapse -->
                        <section class="collapse collapseJobSummary"
                                 id="collapseJobSummary-{{ $jobApplication->id }}">
                            <p>{{ $jobApplication->job->overview }}</p>

                            <p>
                                <strong>Work site:</strong>
                                {{ $jobApplication->job->on_location == false ? 'Remote / Off site' : 'On site' }}
                            </p>

                            <p>
                                <strong>Type:</strong> {{ $jobApplication->job->type == 'full-time' ? 'Full time' : 'Part time' }}
                            </p>

                            <p>
                                <strong>Posted</strong> {{ $jobApplication->job->published_at->diffForHumans() }}
                            </p>
                            <p>
                                <strong>Deadline</strong> {{ $jobApplication->job->closed_at->diffForHumans() }}
                            </p>
                        </section>

                        <p>
                            <a class="toggleJobSummary" data-toggle="collapse"
                               href="#collapseJobSummary-{{ $jobApplication->id }}" role="button"
                               aria-expanded="false"
                               aria-controls="collapseJobSummary-{{ $jobApplication->id }}">
                                Show job summary...
                            </a>
                        </p>
                    </article>

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
        $('.collapseJobSummary').on('hidden.bs.collapse', function () {
            $('.toggleJobSummary').text('Show job summary...')
        })

        $('.collapseJobSummary').on('shown.bs.collapse', function () {
            var parent = $('.collapseJobSummary.collapse.show').parent().attr('id')
            $('#' + parent + ' .toggleJobSummary').text('Hide job summary')
        })
    </script>
@endsection
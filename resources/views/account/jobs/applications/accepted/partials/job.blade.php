@component('account.jobs.partials._base_job', compact('jobApplication'))

    @slot('links')
        <div class="nav">
            <a href="{{ route('jobs.applications.show', [$jobApplication->job, $jobApplication]) }}"
               class="nav-item nav-link">
                View
            </a>

            @if(!$jobApplication->declined_at)
                <a href="{{ route('jobs.applications.decline', [$jobApplication->job, $jobApplication]) }}"
                   class="nav-item nav-link" title="Decline job opportunity"
                   onclick="event.preventDefault(); document.getElementById('decline-application-{{ $jobApplication->id }}').submit()">
                    Decline
                </a>

                <a href="#" class="nav-item nav-link d-none" title="Message listing owner">
                    Message
                </a>
            @endif
        </div>

        <form method="POST" action="{{ route('jobs.applications.decline', [$jobApplication->job, $jobApplication]) }}"
              id="decline-application-{{ $jobApplication->id }}" style="display: none">
            @csrf
        </form>
    @endslot

    @slot('content')
        @if($jobApplication->declined_at)
            <p class="lead">You have declined this job opportunity.</p>
        @endif
    @endslot

    @slot('timestamps')
        <li class="list-inline-item" title="{{ $jobApplication->accepted_at->toDayDateTimeString() }}">
            <strong>Accepted</strong> {{ $jobApplication->accepted_at->diffForHumans() }}
        </li>

        @if($jobApplication->declined_at)
            <li class="list-inline-item">
                <strong>Declined</strong> {{ $jobApplication->declined_at->diffForHumans() }}
            </li>
        @endif
    @endslot

@endcomponent
@component('account.jobs.partials._base_job', compact('jobApplication'))

    @slot('links')
        <div class="nav">
            <a href="{{ route('jobs.applications.show', [$jobApplication->job, $jobApplication]) }}"
               class="nav-item nav-link">
                View
            </a>

            @if(!$jobApplication->cancelled_at)
                <a href="{{ route('jobs.applications.cancel', [$jobApplication->job, $jobApplication]) }}"
                   class="nav-item nav-link"
                   onclick="event.preventDefault(); document.getElementById('cancel-application-{{ $jobApplication->id }}').submit()">
                    Cancel
                </a>
            @endif
        </div>

        <form method="POST" action="{{ route('jobs.applications.cancel', [$jobApplication->job, $jobApplication]) }}"
              id="cancel-application-{{ $jobApplication->id }}" style="display: none">
            @csrf
        </form>
    @endslot

    @slot('content')
        @if($jobApplication->cancelled_at)
            <p class="lead">
                You have cancelled your job application. You will no longer be listed among the applicants.
            </p>
        @endif
    @endslot

    @slot('timestamps')
        <li class="list-inline-item">
            <strong>Submitted</strong> {{ $jobApplication->submitted_at->diffForHumans() }}
        </li>

        @if($jobApplication->cancelled_at)
            <li class="list-inline-item">
                <strong>Cancelled</strong> {{ $jobApplication->cancelled_at->diffForHumans() }}
            </li>
        @endif
    @endslot

@endcomponent
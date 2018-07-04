@component('account.jobs.partials._base_job', compact('jobApplication'))

    @slot('links')
        <div class="nav">
            <a href="{{ route('jobs.applications.show', [$jobApplication->job, $jobApplication]) }}"
               class="nav-item nav-link">
                View
            </a>

            <a href="#" class="nav-item nav-link" title="Decline job opportunity">
                Decline
            </a>

            <a href="#" class="nav-item nav-link" title="Message listing owner">
                Message
            </a>
        </div>
    @endslot

    @slot('timestamps')
        <li class="list-inline-item" title="{{ $jobApplication->accepted_at->toDayDateTimeString() }}">
            Accepted {{ $jobApplication->accepted_at->diffForHumans() }}
        </li>
    @endslot

@endcomponent
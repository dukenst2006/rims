@component('account.jobs.partials._base_job', compact('jobApplication'))

    @slot('links')
        <div class="nav">
            <a href="{{ route('jobs.applications.show', [$jobApplication->job, $jobApplication]) }}"
               class="nav-item nav-link">
                View
            </a>
        </div>
    @endslot

    @slot('timestamps')
        <li class="list-inline-item" title="{{ $jobApplication->rejected_at->toDayDateTimeString() }}">
            <strong>Rejected</strong> {{ $jobApplication->rejected_at->diffForHumans() }}
        </li>
    @endslot

@endcomponent
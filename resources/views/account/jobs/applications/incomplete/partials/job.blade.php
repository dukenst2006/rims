@component('account.jobs.partials._base_job', compact('jobApplication'))

    @slot('links')
        <div class="nav">
            @if(!$jobApplication->job->isPastDeadline)
                <a href="{{ route('jobs.applications.create', [$jobApplication->job, $jobApplication]) }}"
                   class="nav-item nav-link" title="Continue application process">
                    Continue
                </a>
            @endif

            <a href="{{ route('jobs.applications.destroy', [$jobApplication->job, $jobApplication]) }}"
               class="nav-item nav-link"
               onclick="event.preventDefault(); document.getElementById('delete-application-{{ $jobApplication->id }}').submit()">
                Delete
            </a>
        </div>

        <form method="POST" action="{{ route('jobs.applications.destroy', [$jobApplication->job, $jobApplication]) }}"
              id="delete-application-{{ $jobApplication->id }}" style="display: none">
            @csrf
            @method('DELETE')
        </form>
    @endslot

@endcomponent
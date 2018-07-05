@component('account.jobs.partials._base_job', compact('jobApplication'))

    @slot('links')
        <div class="nav">
            <a href="{{ route('jobs.applications.show', [$jobApplication->job, $jobApplication]) }}"
               class="nav-item nav-link">
                View
            </a>

            <a href="{{ route('jobs.applications.edit', [$jobApplication->job, $jobApplication]) }}"
               class="nav-item nav-link">
                Edit
            </a>

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
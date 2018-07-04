@component('account.jobs.partials._base_job', compact('jobApplication'))

    @slot('links')
        <div class="nav">
            <a href="{{ route('jobs.applications.show', [$jobApplication->job, $jobApplication]) }}"
               class="nav-item nav-link">
                View
            </a>

            <a href="#" class="nav-item nav-link">Cancel</a>

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

    @slot('timestamps')
        <li class="list-inline-item">
            <strong>Submitted</strong> {{ $jobApplication->submitted_at->diffForHumans() }}
        </li>
    @endslot

@endcomponent
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-content-center">
            <h4>{{ $jobApplication->job->title }}</h4>

            <aside>
                {{ $links or '' }}
            </aside>
        </div>

        <!-- Content -->
        <div>
            {{ $content or '' }}
        </div>

        @if($jobApplication->job->closed_at)
            <p><strong>Deadline</strong> {{ $jobApplication->job->closed_at->diffForHumans() }}</p>
        @endif

        <ul class="list-inline my-1"><!-- Time -->
            <li class="list-inline-item">
                <strong>Created</strong> {{ $jobApplication->created_at->diffForHumans() }}
            </li>
            {{ $timestamps or '' }}
        </ul>
    </div>
</div>
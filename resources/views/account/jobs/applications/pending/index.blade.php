@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <header>
        <h4>Pending Job Applications</h4>
        <p>Applications awaiting reply.</p>
    </header>
    <hr>

    <section class="my-3">
        @forelse($applications as $jobApplication)
            @include('account.jobs.applications.pending.partials.job', compact('jobApplication'))
        @empty
            <p>No pending applications found.</p>
        @endforelse

        <div class="mb-2">
            {{ $applications->links() }}
        </div>
    </section>
@endsection
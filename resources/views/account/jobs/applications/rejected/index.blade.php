@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <header>
        <h4>Rejected Job Applications</h4>
    </header>
    <hr>

    <section class="my-3">
        @forelse($applications as $jobApplication)
            @include('account.jobs.applications.rejected.partials.job', compact('jobApplication'))
        @empty
            <p>No rejected applications found.</p>
        @endforelse

        <div class="mb-2">
            {{ $applications->links() }}
        </div>
    </section>
@endsection
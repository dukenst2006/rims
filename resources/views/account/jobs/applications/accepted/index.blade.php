@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <header>
        <h4>Accepted Job Applications</h4>
        <p>A list of job applications that you have applied for and accepted.</p>
    </header>
    <hr>

    <section class="my-3">
        @forelse($applications as $jobApplication)
            @include('account.jobs.applications.accepted.partials.job', compact('jobApplication'))
        @empty
            <p>No accepted applications found.</p>
        @endforelse

        <div class="mb-2">
            {{ $applications->links() }}
        </div>
    </section>
@endsection
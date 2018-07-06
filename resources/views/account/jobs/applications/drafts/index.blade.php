@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <header>
        <h4>Draft Job Applications</h4>
        <p>Drafts applications of jobs you are interested in.</p>
    </header>
    <hr>

    <section class="my-3">
        @forelse($applications as $jobApplication)
            @include('account.jobs.applications.drafts.partials.job', compact('jobApplication'))
        @empty
            <p>No draft applications found.</p>
        @endforelse

        <div class="mb-2">
            {{ $applications->links() }}
        </div>
    </section>
@endsection
@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <header>
        <h4>Incomplete Job Applications</h4>
        <p>Initial job applications drafts.</p>
    </header>
    <hr>

    <section class="my-3">
        @forelse($applications as $jobApplication)
            @include('account.jobs.applications.incomplete.partials.job', compact('jobApplication'))
        @empty
            <p>No incomplete applications found.</p>
        @endforelse

        <div class="mb-2">
            {{ $applications->links() }}
        </div>
    </section>
@endsection
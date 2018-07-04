@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Incomplete Job Applications</h4>
        </div>
    </div>

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
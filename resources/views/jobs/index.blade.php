@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Job listings -->
        <section class="my-3">
            <jobs-index endpoint="{{ route('jobs.index', $area) }}"></jobs-index>
        </section>
    </div>
@endsection
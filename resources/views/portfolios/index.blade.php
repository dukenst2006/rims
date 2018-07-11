@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="job-portfolio-header py-4">
            <div class="row">
                <div class="col-sm-12">
                    <div class="align-content-center">
                        <div class="my-2">
                            <img src="{{ $user->avatar ?: asset('img/avatars/default_avatar.png') }}"
                                 alt="{{ $user->name }} avatar"
                                 class="rounded-circle mx-auto d-block">
                        </div>
                        <h3 class="text-center">
                            {{ $user->name }}
                        </h3>
                        <p class="text-center">
                            @foreach($user->skills as $skillset)
                                <span class="badge badge-dark py-2 px-2 h4 border-radius-0 mx-1">
                                    {{ $skillset->skill->name }}
                                </span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="portfolio-content">
            <div class="portfolios card-columns mb-3">
                @forelse($portfolios as $portfolio)
                    <div class="card bg-transparent border-radius-0">
                        <img src="{{ $portfolio->image }}"
                             alt="image"
                             class="card-img-top">

                        <div class="card-body">
                            <div class="card-title">
                                <a href="#">
                                    <h5>{{ $portfolio->title }}</h5>
                                </a>
                            </div><!-- /.card-title -->

                            <!-- Short overview -->
                            <p>
                                {{ $portfolio->overview_short }}
                            </p>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                @empty
                    <p class="text-center">
                        This portfolio is empty.
                    </p>
                @endforelse
            </div><!-- /.card-deck -->
        </section>
    </div>
@endsection
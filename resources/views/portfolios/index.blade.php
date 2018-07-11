@extends('layouts.app')

@section('content')
    <div class="container">
        <section class="portfolio-header py-4">
            <div class="row">
                <div class="col-sm-12">
                    <div class="align-content-between">
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
                                <span class="badge badge-dark py-2 px-2 h4 rounded-0 mx-1">
                                    {{ $skillset->skill->name }}
                                </span>
                            @endforeach
                        </p>
                    </div><!-- /.align-content-center -->
                </div><!-- /.col-sm-12 -->
            </div><!-- /.row -->
        </section><!-- /.portfolio-header -->
        <section class="portfolio-content py-4">
            @forelse($portfolios->chunk(3) as $portfoliosChunked)
                <div class="row mb-3">
                    @foreach($portfoliosChunked as $portfolio)
                        <div class="col-sm-4">
                            <div class="card bg-transparent rounded-0">
                                <!-- Image -->
                                <img src="{{ $portfolio->image }}"
                                     alt="image"
                                     class="card-img-top">

                                <div class="card-body">
                                    <!-- Title -->
                                    <div class="card-title">
                                        <a href="{{ route('portfolio.show', [$user->username, $portfolio]) }}">
                                            <h5>{{ $portfolio->title }}</h5>
                                        </a>
                                    </div><!-- /.card-title -->

                                    <!-- Short overview -->
                                    <p>
                                        {{ $portfolio->overview_short }}
                                    </p>

                                    <!-- Last updated -->
                                    <p>
                                        Last updated
                                        <span class="text-muted">{{ $portfolio->updated_at->diffForHumans() }}</span>
                                    </p>
                                </div><!-- /.card-body -->
                            </div><!-- /.card -->
                        </div><!-- /.col-sm-4 -->
                    @endforeach
                </div><!-- /.row -->
            @empty
                <p class="text-center">
                    This portfolio is empty.
                </p>
            @endforelse

            {{ $portfolios->links() }}
        </section><!-- /.portfolio-content -->
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <!-- Uploads Carousel -->
                <div class="align-content-center mb-3">
                    <div id="uploadsCarousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#uploadsCarousel" data-slide-to="0" class="active"></li>
                            @foreach($uploads as $upload)
                                <li data-target="#uploadsCarousel" data-slide-to="{{ $upload->id }}"></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="{{ $portfolio->image }}" alt="portfolio image">
                            </div>
                            @foreach($uploads as $upload)
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="{{ $upload->fullPath }}"
                                         alt="{{ $upload->id }} screenshot">
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#uploadsCarousel" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#uploadsCarousel" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>

                <!-- Summary -->
                <div class="py-1 mt-2 mb-1">
                    <!-- Title -->
                    <h3>
                        {{ $portfolio->title }}
                    </h3>

                    <!-- Short overview -->
                    <p>
                        {{ $portfolio->overview_short }}
                    </p>

                    <!-- Last updated -->
                    <p>
                        Last updated
                        <span class="text-muted">{{ $portfolio->updated_at->diffForHumans() }}</span>
                    </p>
                </div>

                <!-- Details -->
                <div class="py-4 mb-3">
                    <h4>Overview</h4>
                    <hr>
                    {{ $portfolio->overview }}
                </div>

                @if($previousPortfolio != null || $nextPortfolio != null)
                    <footer class="my-1">
                        <div class="d-flex justify-content-between align-content-center">
                            {{-- Previous Portfolio Link --}}
                            <div class="py-1">
                                @if ($previousPortfolio != null)
                                    <a class="btn btn-link"
                                       href="{{ route('portfolio.show', [$user->username, $previousPortfolio]) }}"
                                       rel="prev">
                                        &laquo; {{ $previousPortfolio->title }}
                                    </a>
                                @endif
                            </div>

                            {{-- Next Portfolio Link --}}
                            <div class="py-1">
                                @if ($nextPortfolio != null)
                                    <a class="btn btn-link"
                                       href="{{ route('portfolio.show', [$user->username, $nextPortfolio]) }}"
                                       rel="next">
                                        {{ $nextPortfolio->title }} &raquo;
                                    </a>
                                @endif
                            </div>
                        </div>
                    </footer>
                @endif
            </div><!-- /.col-sm-8 -->
            <div class="col-sm-4">
                <div class="align-content-between">
                    <div class="my-2">
                        <img src="{{ $user->avatar ?: asset('img/avatars/default_avatar.png') }}"
                             alt="{{ $user->name }} avatar"
                             class="rounded-circle mx-auto d-block">
                    </div>
                    <h3>
                        {{ $user->name }}
                    </h3>
                    <p>
                        @foreach($user->skills as $skillset)
                            <span class="badge badge-dark py-2 px-2 h4 rounded-0 mx-1">
                                    {{ $skillset->skill->name }}
                                </span>
                        @endforeach
                    </p>
                </div><!-- /.align-content-between -->
            </div><!-- /.col-sm-4 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
@endsection
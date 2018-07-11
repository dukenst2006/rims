@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <!-- Image -->
                <img src="{{ $portfolio->image }}"
                     alt="image"
                     class="card-img-top">

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
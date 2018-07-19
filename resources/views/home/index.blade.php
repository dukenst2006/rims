@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Locations -->
        <section class="py-3 my-2">
            <h1 class="text-center">Choose a location</h1>
            <div class="my-3">
                @foreach($areas->chunk(3) as $areasChunked)
                    <div class="row">
                        @foreach($areasChunked as $area)
                            <div class="col-sm-4">
                                <a href="{{ route('areas.change', $area) }}">
                                    <h3>
                                        {{ $area->name }}
                                    </h3>
                                </a>
                                <hr>
                                <div class="nav flex-column">
                                    @foreach($area->children as $state)
                                        <a href="{{ route('areas.change', $state) }}" class="nav-item nav-link">
                                            {{ $state->name }}
                                        </a>
                                    @endforeach
                                </div><!-- /.nav -->
                            </div><!-- /.col-sm-4 -->
                        @endforeach
                    </div><!-- /.row -->
                @endforeach
            </div>
        </section>
    </div><!-- /.container -->
@endsection

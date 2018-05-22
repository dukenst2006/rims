@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Popular Categories -->
        <section class="mt-3 mb-5">
            <h2 class="text-center">Popular Categories</h2>
            <div class="row">
                <div class="col-sm-12">
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a href="#" class="nav-link">Web Developer</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Android App Developer</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">iOS App Developer</a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Locations -->
        <section class="my-5">
            <h2 class="text-center">Locations</h2>
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
        </section>
    </div><!-- /.container -->
@endsection

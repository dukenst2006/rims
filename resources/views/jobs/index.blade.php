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

        <!-- Content -->
        <section class="my-3">
            <div class="row">
                <div class="col-sm-3">
                    <h4>Filters</h4> <!-- Move to vue component -->

                    @include('jobs.partials._filters')
                </div>
                <div class="col-sm-9"> <!-- Move to vue component -->
                    <div class="card mb-2">
                        <div class="card-body">
                            <a href="#" class="card-title">
                                <h4>Job title</h4>
                            </a>

                            <p class="lead">Company</p>
                            <p class="card-text">Job category</p>
                            <p class="card-text">Job Location</p>
                            <p class="card-text">Primary Skills</p>
                        </div><!-- /.cart-title -->
                    </div><!-- /.card -->
                </div><!-- /.col-sm-9 -->
            </div><!-- /.row -->
        </section>
@endsection
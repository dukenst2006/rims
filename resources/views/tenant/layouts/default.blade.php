@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                @include('tenant.layouts.partials._navigation')
            </div>
            <div class="col-sm-9">
                @yield('tenant.content')
            </div>
        </div>
    </div>
@endsection
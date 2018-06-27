<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name') }}</title>

<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

@yield('styles')

<!-- Scripts -->
<script>
    window.Laravel = {!! json_encode([
        'user' => [
            'authenticated' => auth()->check(),
            'id' => auth()->check() ? auth()->user()->id : null,
            'name' => auth()->check() ? auth()->user()->name : null,
            'username' => auth()->check() ? auth()->user()->username : null,
        ]
    ]) !!}
</script>
@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <user-languages-index endpoint="{{ route('account.languages.index') }}"></user-languages-index>
@endsection

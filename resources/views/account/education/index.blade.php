@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <user-education-index endpoint="{{ route('account.education.index') }}"></user-education-index>
@endsection
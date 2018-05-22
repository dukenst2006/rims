@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <user-skills-index endpoint="{{ route('account.skills.index') }}"></user-skills-index>
@endsection

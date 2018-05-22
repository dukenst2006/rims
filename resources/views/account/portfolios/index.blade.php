@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <user-portfolios-index endpoint="{{ route('account.portfolios.index') }}"></user-portfolios-index>
@endsection

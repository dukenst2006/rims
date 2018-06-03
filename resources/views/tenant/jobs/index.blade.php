@extends('tenant.layouts.default')

@section('tenant.content')
    <tenant-job-index endpoint="{{ route('tenant.jobs.index') }}"></tenant-job-index>
@endsection
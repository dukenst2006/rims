@extends('account.layouts.default')

@section('account.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Avatar</h4>

            <form action="#">
                <avatar-upload
                        send-as="image"
                        endpoint="{{ route('account.avatar.upload.store') }}"
                        current-avatar="{{ auth()->user()->avatar ?: asset('img/avatars/default_avatar.png') }}"></avatar-upload>
            </form>
        </div><!-- /.card-body -->
    </div><!-- /.card -->
@endsection
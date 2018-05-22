@extends('account.layouts.default')

@section('account.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Avatar</h4>

            <form action="#">
                <avatar-upload
                        send-as="image"
                        endpoint="{{ route('account.avatar.upload.store') }}"
                        current-image="placeholder.jpg"></avatar-upload>

                {{--<div class="form-group">--}}
                    {{--<button type="submit" class="btn btn-primary">Save</button>--}}
                {{--</div>--}}
            </form>
        </div><!-- /.card-body -->
    </div><!-- /.card -->
@endsection
@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.areas.index') }}">Areas</a>
    </li>
    <li class='breadcrumb-item active'>Create</li>
@endsection

@section('admin.content')

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Add new area</h4>

            <form action="{{ route('admin.areas.store') }}" method="post">
                {{ csrf_field() }}

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label col-md-4">Name</label>
                    <div class="col-md-6">
                        <input type="text" name="name"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                               value="{{ old('name') }}" required autofocus>

                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('parent_id') ? ' has-error' : '' }}">
                    <label for="parent_id" class="control-label col-md-4">Parent</label>
                    <div class="col-md-6">
                        <select name="parent_id" id="parent_id"
                                class="custom-select form-control{{ $errors->has('parent_id') ? ' is-invalid' : '' }}">
                            <option value=""> --- Select parent area ---</option>

                            @foreach($areas as $area)
                                <option value="{{ $area->id }}"
                                        {{ old('parent_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>

                        @if($errors->has('parent_id'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('parent_id') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
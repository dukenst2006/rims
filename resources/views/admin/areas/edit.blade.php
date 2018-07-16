@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.areas.index') }}">Areas</a>
    </li>
    <li class='breadcrumb-item active'>Edit</li>
@endsection

@section('admin.content')

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit area</h4>

            <form action="{{ route('admin.areas.update', $area) }}" method="post">
                @csrf
                @method('PUT')

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label col-md-4">Name</label>
                    <div class="col-md-6">
                        <input type="text" name="name"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                               value="{{ old('name', $area->name) }}" required autofocus>

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

                            @foreach($areas as $location)
                                <option value="{{ $location->id }}"
                                        {{ old('parent_id', $area->parent_id) == $location->id ? 'selected' : '' }}
                                        {{ $area->id == $location->id ? 'disabled' : '' }}>
                                    {{ $location->name }}
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

                <div class="form-group row{{ $errors->has('usable') ? ' has-error' : '' }}">
                    <label for="usable" class="control-label col-md-4">Usable</label>
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input{{ $errors->has('usable') ? ' is-invalid' : '' }}"
                                   type="radio" name="usable" id="usable_true"
                                   value="1" {{ old('usable', $area->usable) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="usable_true">True (Active)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input{{ $errors->has('usable') ? ' is-invalid' : '' }}"
                                   type="radio" name="usable" id="usable_false"
                                   value="0" {{ old('usable', $area->usable) == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="usable_false">False (Disable)</label>
                        </div>

                        <input type="hidden" class="form-control{{ $errors->has('usable') ? ' is-invalid' : '' }}">

                        @if($errors->has('usable'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('usable') }}</strong>
                            </div>
                        @endif

                        <small class="form-text">
                            Set whether area can be used or not.
                        </small>
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
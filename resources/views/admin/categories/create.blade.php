@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.categories.index') }}">Categories</a>
    </li>
    <li class='breadcrumb-item active'>Create</li>
@endsection

@section('admin.content')

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Add new category</h4>

            <form action="{{ route('admin.categories.store') }}" method="post">
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
                            <option value=""> --- Select parent category ---</option>

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
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

                <div class="form-group row{{ $errors->has('price') ? ' has-error' : '' }}">
                    <label for="price" class="control-label col-md-4">Price</label>
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" name="price"
                                   class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" id="price"
                                   value="{{ old('price', 0.00) }}" required>

                            @if($errors->has('price'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('price') }}</strong>
                                </div>
                            @endif
                        </div>

                        <small class="form-text">The amount charged for listing within this category.</small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('needs_auth') ? ' has-error' : '' }}">
                    <label for="needs_auth" class="control-label col-md-4">Needs auth</label>
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input{{ $errors->has('needs_auth') ? ' is-invalid' : '' }}"
                                   type="radio" name="needs_auth" id="needs_auth_true" value="1"
                                   checked="{{ old('needs_auth') == 1 ? true : false }}">
                            <label class="form-check-label" for="needs_auth_true">True (required)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input{{ $errors->has('needs_auth') ? ' is-invalid' : '' }}"
                                   type="radio" name="needs_auth" id="needs_auth_false" value="0"
                                   checked="{{ old('needs_auth') == 0 ? true : old('needs_auth') == null ? true : false }}">
                            <label class="form-check-label" for="needs_auth_false">False (not required)</label>
                        </div>

                        <input type="hidden" class="form-control{{ $errors->has('needs_auth') ? ' is-invalid' : '' }}">

                        @if($errors->has('needs_auth'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('needs_auth') }}</strong>
                            </div>
                        @endif

                        <small class="form-text">
                            Whether user needs to signin to view listing within this category.
                        </small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('details') ? ' has-error' : '' }} d-none">
                    <label for="details" class="control-label col-md-4">Details</label>
                    <div class="col-md-6">
                        <textarea name="details" id="details" rows="5"
                                  class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}">{{ old('details') }}</textarea>

                        @if($errors->has('details'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('details') }}</strong>
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
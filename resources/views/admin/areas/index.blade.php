@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item active'>Areas</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <strong>Areas</strong>

                    <a href="{{ route('admin.areas.create') }}">Add new area</a>
                </div>
            </div>
        </div>

        @if($areas->count())
            <table class="table table-responsive-sm table-hover table-outline mb-0">
                <thead class="thead-light">
                <tr>
                    <th>
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="selectAll">
                            <label class="custom-control-label" for="selectAll"></label>
                        </label>
                    </th>
                    <th>Name</th>
                    <th>Parent</th>
                    <th>Usable</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($areas as $area)
                    <tr>
                        <td>
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="area{{ $area->id }}">
                                <label class="custom-control-label" for="area{{ $area->id }}"></label>
                            </label>
                        </td>
                        <td>{{ $area->name }}</td>
                        <td>
                            {{ $area->parent ? $area->parent->name : null }}
                        </td>
                        <td>{{ $area->usable ? 'Active' : 'Disabled' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.areas.edit', $area) }}">
                                    Edit
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.areas.status', $area) }}"
                                   onclick="event.preventDefault(); document.getElementById('toggle-status-area-{{ $area->id }}').submit()">
                                    {{ $area->usable ? 'Disable' : 'Activate' }}
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.areas.destroy', $area) }}"
                                   onclick="event.preventDefault(); document.getElementById('delete-area-{{ $area->id }}').submit()">
                                    Delete
                                </a>
                            </div>
                            <form action="{{ route('admin.areas.status', $area) }}" method="POST"
                                  id="toggle-status-area-{{ $area->id }}" style="display: none">
                                @csrf
                                @method('PUT')
                            </form>
                            <form action="{{ route('admin.areas.destroy', $area) }}" method="POST"
                                  id="delete-area-{{ $area->id }}" style="display: none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="card-body">
                <div class="card-text">No areas found.</div>
            </div>
        @endif
    </div>
@endsection
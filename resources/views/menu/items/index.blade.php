@extends('layouts.app')

@section('title', 'Menu Items')

@section('page-title', 'Menu Items')

@section('breadcrumb', 'Menu / Items')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Menu Items</h3>
        <div class="card-tools">
            <a href="{{ url('/menu/items/create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Item
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items ?? [] as $item)
                <tr>
                    <td>
                        @if ($item->image)
                            <img src="{{ $item->image }}" alt="{{ $item->name }}" width="50">
                        @else
                            <span class="text-muted">No image</span>
                        @endif
                    </td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? 'N/A' }}</td>
                    <td>NPR {{ number_format($item->price, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $item->is_available ? 'success' : 'danger' }}">
                            {{ $item->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ url('/menu/items/' . $item->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ url('/menu/items/' . $item->id . '/edit') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ url('/menu/items/' . $item->id) }}" method="POST" class="d-inline">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

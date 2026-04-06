@extends('layouts.app')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold mb-0">Categories</h2>
        <p class="text-muted small">Organize your inventory by types</p>
    </div>
    <div class="col-md-6 text-end">
        @if(Auth::user()->canEditEverything())
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i> Add Category
        </a>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-10">
                    <tr>
                        <th class="ps-4" style="width: 80px;">#</th>
                        <th>Category Name</th>
                        <th>Parent Category</th>
                        <th>Description</th>
                        <th>Total Products</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold text-white">{{ $category->full_name }}</div>
                        </td>
                        <td>
                            @if($category->parent)
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $category->parent->name }}
                                </span>
                            @else
                                <span class="text-muted small">None (Root)</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ Str::limit($category->description, 50) ?: 'No description provided' }}</td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3">
                                {{ $category->products_count ?? $category->products()->count() }} Items
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(Auth::user()->canEditEverything())
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category? Associated products will also be deleted!')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-grid fs-1 d-block mb-3"></i>
                            No categories found. Start by adding one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

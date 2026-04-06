@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="fw-bold">Stock Control</h2>
        <p class="text-muted small">Record stock movements and view audit trail</p>
    </div>
</div>

<div class="row">
    <!-- Stock Update Form -->
    <div class="col-md-5">
        @if(Auth::user()->canManageStock())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0"><i class="bi bi-plus-slash-minus me-2 text-primary"></i>Record Movement</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('inventory.update', ['product' => 0]) }}" method="POST" id="stockUpdateForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Product</label>
                        <select name="product_id" id="product_id_select" class="form-select" required onchange="updateFormAction(this.value)">
                            <option value="">Choose product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} (Current: {{ $product->quantity }} {{ $product->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Action</label>
                            <select name="action" class="form-select" required>
                                <option value="add">Stock IN (+)</option>
                                <option value="remove">Stock OUT (-)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" required min="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reference/Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Purchase order #123, Damaged goods, etc."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-save me-2"></i> Commit Transaction
                    </button>
                </form>
            </div>
        </div>
        
        <div class="alert alert-info border-0 shadow-sm small">
            <i class="bi bi-info-circle me-2"></i>
            Industrial stock movements are logged for auditing purposes. Ensure notes are descriptive for future reference.
        </div>
        @else
        <div class="card border-0 shadow-sm mb-4 bg-light bg-opacity-10">
            <div class="card-body text-center py-5">
                <i class="bi bi-lock fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-white">Stock Control Restricted</h5>
                <p class="text-muted small">Only Managers and Administrators can record stock movements. Please contact your supervisor for assistance.</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2 text-info"></i>Audit Trail</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="bg-light bg-opacity-10">
                            <tr>
                                <th class="ps-4">Timestamp</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>By</th>
                                <th class="pe-4">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                            <tr>
                                <td class="ps-4 text-muted">{{ $transaction->created_at->format('M d, H:i') }}</td>
                                <td>
                                    <strong>{{ $transaction->product->name }}</strong>
                                </td>
                                <td>
                                    @if($transaction->type == 'add')
                                        <span class="text-success"><i class="bi bi-arrow-up-circle"></i> IN</span>
                                    @else
                                        <span class="text-danger"><i class="bi bi-arrow-down-circle"></i> OUT</span>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $transaction->quantity }}</td>
                                <td>{{ $transaction->user->name }}</td>
                                <td class="text-truncate" style="max-width: 150px;">{{ $transaction->notes ?? '-' }}</td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('inventory.print', $transaction) }}" class="btn btn-sm btn-outline-light" target="_blank">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No transactions recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFormAction(productId) {
    const form = document.getElementById('stockUpdateForm');
    const baseUrl = "{{ route('inventory.update', ['product' => ':id']) }}";
    form.action = baseUrl.replace(':id', productId);
}
</script>
@endsection

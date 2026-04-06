<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Movement Slip #{{ $transaction->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
            body { background-color: white !important; color: black !important; }
            .card { border: 1px solid #dee2e6 !important; }
        }
        body { background-color: #f8f9fa; padding: 2rem; }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-4 no-print">
                    <button class="btn btn-primary" onclick="window.print()">Print Slip</button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">Back to Inventory</a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fw-bold text-dark">STOCK MOVEMENT SLIP</h4>
                            <span class="text-muted">#TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-6">
                                <h6 class="text-muted text-uppercase small fw-bold">Date & Time</h6>
                                <p class="mb-0 text-dark">{{ $transaction->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="col-6 text-end">
                                <h6 class="text-muted text-uppercase small fw-bold">Transaction Type</h6>
                                <p class="mb-0 fw-bold {{ $transaction->type == 'add' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type == 'add' ? 'STOCK IN (+)' : 'STOCK OUT (-)' }}
                                </p>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 mb-4 border">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h5 class="mb-1 text-dark fw-bold">{{ $transaction->product->name }}</h5>
                                    <p class="mb-0 text-muted small">SKU: {{ $transaction->product->sku }}</p>
                                </div>
                                <div class="col-4 text-end">
                                    <h3 class="mb-0 fw-bold">{{ $transaction->quantity }} {{ $transaction->product->unit }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted text-uppercase small fw-bold">Audit Information</h6>
                                <table class="table table-sm table-bordered mt-2">
                                    <tr>
                                        <th class="bg-light w-50">Performed By</th>
                                        <td>{{ $transaction->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Balance After Movement</th>
                                        <td>{{ $transaction->balance_after }} {{ $transaction->product->unit }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Reference / Notes</th>
                                        <td>{{ $transaction->notes ?: 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="mt-5 pt-5">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div style="border-top: 1px solid #dee2e6; width: 150px; margin: 0 auto;"></div>
                                    <p class="small text-muted mt-2">Issuer Signature</p>
                                </div>
                                <div class="col-6">
                                    <div style="border-top: 1px solid #dee2e6; width: 150px; margin: 0 auto;"></div>
                                    <p class="small text-muted mt-2">Receiver Signature</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-center py-3">
                        <small class="text-muted">Generated by Inventory Industrial Management System</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

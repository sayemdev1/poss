@extends('layouts.app')
@section('title', __('Create') . ' ' . __('Purchase'))

@section('content')
<div class="d-flex align-items-center justify-content-center mb-3">
    <div class="h4 mb-0 flex-grow-1">@lang('Create') @lang('Purchase')</div>
    <x-back-btn href="{{ route('purchases.index') }}" />
</div>
<form action="{{ route('purchases.store') }}" method="POST" role="form">
    @csrf
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="supplier" class="form-label">@lang('Supplier')</label>
                    <select name="supplier" id="supplier" class="form-select">
                        <option value="">@lang('Select Supplier')</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('suppliers.create') }}" class="small text-decoration-none">
                        + @lang('Create New Supplier')
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">@lang('Date')</label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                        value="{{ old('date', now()->format('Y-m-d')) }}">
                    @error('date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="reference_number" class="form-label">@lang('Reference Number')</label>
                <input type="text" name="reference_number"
                    class="form-control @error('reference_number') is-invalid @enderror"
                    value="{{ old('reference_number') }}">
                @error('reference_number')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="mb-3">
                <label for="barcode-imei-input" class="form-label">@lang('Scan Barcode or IMEI')</label>
                <input type="text" id="barcode-imei-input" class="form-control" placeholder="@lang('Enter barcode or IMEI')">
            </div>
            <div class="table-responsive mb-3">
                <table class="table table-bordered mb-1" id="table-items">
                    <thead>
                        <tr>
                            <th class="text-center fw-bold">@lang('Barcode')</th>
                            <!-- <th class="text-center fw-bold">@lang('IMEI')</th> -->
                            <th class="text-center fw-bold">@lang('Item')</th>
                            <th class="text-center fw-bold">@lang('Quantity')</th>
                            <th class="text-center fw-bold">@lang('Unit Cost') ({{ $currency }})</th>
                            <th class="text-center fw-bold"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <!-- Items will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label for="notes" class="form-label">@lang('Notes')</label>
                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                    rows="3">{{ old('notes') }}</textarea>

                @error('notes')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div>
                <button type="submit" class="btn btn-primary px-4">@lang('Save')</button>
            </div>
        </div>
    </div>
</form>
@endsection
<!--  <td>${product.imei_barcode || '-'}</td>-->
@push('script')
    <script>
        const products = @json($categories); // Categories and products data
        const tbody = document.querySelector('#tbody');
        const barcodeImeiInput = document.querySelector('#barcode-imei-input');

        // Function to add item to the table
        function addItemToTable(product) {
            const row = `
                <tr>
                    <td>${product.retail_barcode || '-'}</td>
                    
                    <td>
                        <select class="form-select" name="item[]" required>
                            <option value="${product.id}" selected>${product.name}</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control input-stock text-center" name="quantity[]" value="1" required>
                    </td>
                    <td>
                        <input type="text" class="form-control input-number text-center" name="cost[]" value="${product.cost}" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-link p-0 text-danger btn-remove">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="hero-icon-sm">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </td>
                </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        }

        // Event listener for barcode/IMEI input
        barcodeImeiInput.addEventListener('input', function () {
    const input = this.value.trim();
    if (!input) return;

    let found = false;

    // Search for product by barcode or IMEI
    products.forEach(category => {
        category.products.forEach(product => {
            let imeiList = product.imei_barcode ? product.imei_barcode.split(',').map(i => i.trim()) : [];

            if (
                (product.retail_barcode && product.retail_barcode === input) ||
                imeiList.includes(input) // Check if input IMEI exists in the list
            ) {
                addItemToTable(product); // Add the product to the table
                barcodeImeiInput.value = ''; // Clear the input
                found = true;
            }
        });
    });

    if (!found) {
        console.log("❌ No product found with this barcode or IMEI.");
    }
});
        // Remove row functionality
        document.addEventListener('click', function (event) {
            if (event.target.matches('.btn-remove, .btn-remove *')) {
                event.target.closest('tr').remove();
            }
        });
    </script>
@endpush

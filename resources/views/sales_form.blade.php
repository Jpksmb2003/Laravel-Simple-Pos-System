@extends('layouts.master')

@section('title', 'Form')

@section('content')
<main>
    <div class="custom-form-container">
        <form
            action="{{ empty($sale->id) ? route('sale.store') : route('sale.update', $sale->id) }}"
            method="POST">
            @csrf
            @if (!empty($sale->id))
                @method('PUT')
                <h2>Update Sale</h2>
            @else
                <h2>Sale Product</h2>
            @endif

            
            <!-- Customer Information -->
            <div>

                <label for="name" class="form-label">Customer Name</label>
                {{-- First Name --}}
                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name"
                    value="{{ old('first_name', $sale->first_name ?? '') }}" placeholder="First Name">
                @error('first_name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                {{-- Last Name --}}
                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name"
                    value="{{ old('last_name', $sale->last_name ?? '') }}" placeholder="Last Name">
                @error('last_name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Product Information -->
            <div class="mb-3">
                <label for="product_code" class="form-label">Product</label>
                <select class="form-select mb-3" id="product_code" name="product_code">
                    <option value="">Choose a product...</option>
                    @foreach ($inventories as $inventory)
                        <option value="{{ $inventory->product_code }}" data-price="{{ $inventory->price }}"
                            {{ old('product_code', $sale->product_code ?? '') == $inventory->product_code ? 'selected' : '' }}>
                            {{ $inventory->product_code }} | {{ $inventory->name }} | {{ $inventory->color }} | {{ $inventory->description }}
                        </option>
                    @endforeach
                </select>
                @error('product_code')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Quantity Input -->
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="{{ old('quantity', $sale->quantity ?? 1) }}" required>
            </div>

            <!-- Price per Item -->
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" value="{{ old('price', $sale->price ?? '0.00') }}" readonly>
            </div>

            <!-- Total Price -->
            <div class="mb-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" class="form-control" id="total" name="total" value="{{ old('total', $sale->total ?? '0.00') }}" readonly>
            </div>

            <div class="d-flex">
                <input type="button" class="btn btn-secondary btn-md p-2" value="Cancel" onclick="window.location.href='{{ route('sale.index') }}';">
                @if (!empty($sale->id))
                    <input type="submit" class="btn btn-success btn-md p-2 mx-3" value="Save">
                @else
                    <input type="submit" class="btn btn-primary btn-md p-2 mx-3" value="Process Sales">
                @endif
            </div>
        </form>
    </div>

    <script>
        // Update price and total based on item selection and quantity
        document.getElementById('product_code').addEventListener('change', function () {
            const selectedItem = this.options[this.selectedIndex];
            const price = selectedItem.getAttribute('data-price') || 0.00;
            document.getElementById('price').value = parseFloat(price).toFixed(2);
            updateTotal();
        });

        document.getElementById('quantity').addEventListener('input', updateTotal);

        function updateTotal() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const quantity = parseInt(document.getElementById('quantity').value) || 1;
            const vat = 7;
            const total = price * quantity;
            const submVat = (price * quantity) * (vat / 100);
            const totalVat = submVat + total;
            document.getElementById('total').value = total.toFixed(2);
        }
    </script>
</main>
@endsection

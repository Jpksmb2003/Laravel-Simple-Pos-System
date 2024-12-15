@extends('layouts.master')

@section('title', 'Form')

@section('content')
<main>
    <div class="custom-form-container">
        <form
            action="{{ empty($inventories->id) ? route('inventory.store') : route('inventory.update', $inventories->id) }}"
            method="POST">
            @csrf
            @if (!empty($inventories->id))
            @method('PUT')
            <h2>Update Product</h2>
            @else
            <h2>Increase Product</h2>
            @endif

            <input type="hidden" id="inventoryId" name="id" value="{{ $inventories->id ?? '' }}">

            <div class="mb-3">
                <label for="product_code" class="form-label">Product Code</label>
                <input type="text" class="form-control @error('product_code') is-invalid @enderror" id="product_code"
                    name="product_code" value="{{ old('product_code', $inventories->product_code ?? '') }}"
                    oninput="this.value = this.value.toUpperCase()">
                @error('product_code')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', $inventories->name ?? '') }}">
                @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Colors</label>
                <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color"
                    value="{{ old('color', $inventories->color ?? '') }}">
                @error('color')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                    name="quantity" value="{{ old('quantity', $inventories->quantity ?? '') }}">
                @error('quantity')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                    value="{{ old('price', $inventories->price ?? '') }}">
                @error('price')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                    name="description" rows="3">{{ old('description', $inventories->description ?? '') }}</textarea>
                @error('description')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <input type="button" class="btn btn-danger btn-md p-2 mx-3" value="Cancel" onclick="window.location.href='{{ route('inventory.index') }}';">
                @if (!empty($inventories->id))
                @method('PUT')
                <input type="submit" class="btn btn-success btn-md p-2" value="Save">
                @else
                <input type="submit" class="btn btn-success btn-md p-2" value="Increase">
                @endif
            </div>
        </form>

    </div>

</main>
@endsection
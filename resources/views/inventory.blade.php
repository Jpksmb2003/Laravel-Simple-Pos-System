@extends('layouts.master')

@section('title', 'Inventory Management')

@section('content')
    <main>
        <div class="container-fluid px-4">
            <div class="card mb-4 mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <i class="fa-solid fa-warehouse"></i>
                    <strong>Inventory Management</strong>
                    <a href="{{ url('/inventory/create') }}" class="btn btn-success">Increase</a>
                </div>

                <div class="card-body">

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {!! session('error') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {!! session('warning') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>ProductCode</th>
                                <th>Name</th>
                                <th>Colors</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ProductCode</th>
                                <th>Name</th>
                                <th>Colors</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Updated</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($inventories as $inventory)
                            <tr>
                                <td>{{$inventory->product_code}}</td>
                                <td>{{$inventory->name}}</td>
                                <td>{{$inventory->color}}</td>
                                <td>{{$inventory->quantity}}</td>
                                <td>{{$inventory->price}}</td>
                                <td>{{$inventory->description}}</td>
                                <td>{{\Carbon\Carbon::parse($inventory->updated_at)->format('M d, Y - H:i')}}
                                </td>
                                <td class="flex">
                                    <a href="{{ route('inventory.edit', $inventory->id) }}" role="button"
                                        class="btn btn-outline-secondary btn-sm mx-2">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('inventory.delete', $inventory->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm "
                                            onclick="return confirm('Are you sure you want to delete this item?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    @endsection
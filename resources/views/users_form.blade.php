@extends('layouts.master')

@section('title', 'Update User')

@section('content')
<main>
    <div class="custom-form-container">
        <form action="{{ route('user.update', $users->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Ensure this is present for PUT requests -->

            <h2>Update User</h2>
            <input type="hidden" id="userId" name="id" value="{{ $users->id }}">

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                    value="{{ old('email', $users->email) }}">
                @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', $users->name) }}" oninput="this.value = this.value.toTitleCase()">
                @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <input type="text" class="form-control @error('role') is-invalid @enderror" id="role" name="role"
                    value="{{ old('role', $users->role) }}" oninput="this.value = this.value.toLowerCase()">
                @error('role')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <input type="button" class="btn btn-danger btn-md p-2 mx-3" value="Cancel"
                    onclick="window.location.href='{{ route('user.index') }}';">
                <input type="submit" class="btn btn-success btn-md p-2" value="Save"> <!-- Save button -->
            </div>
        </form>
    </div>
</main>
@endsection

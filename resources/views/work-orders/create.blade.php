@extends('layouts.app')

@section('title', 'Create Work Order')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Create New Work Order</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('work-orders.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                    id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                    id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="production_deadline" class="form-label">Production Deadline</label>
                                <input type="date"
                                    class="form-control @error('production_deadline') is-invalid @enderror"
                                    id="production_deadline" name="production_deadline"
                                    value="{{ old('production_deadline', now()->format('Y-m-d')) }}" required>
                                @error('production_deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="assigned_operator_id" class="form-label">Assign Operator</label>
                                <select class="form-select @error('assigned_operator_id') is-invalid @enderror"
                                    id="assigned_operator_id" name="assigned_operator_id" required>
                                    <option value="">Select an operator</option>
                                    @foreach ($operators as $operator)
                                        <option value="{{ $operator->id }}"
                                            {{ old('assigned_operator_id') == $operator->id ? 'selected' : '' }}>
                                            {{ $operator->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_operator_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Work Order</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

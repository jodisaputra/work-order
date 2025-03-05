@extends('layouts.app')

@section('title', 'Edit Work Order')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Work Order: {{ $workOrder->order_number }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('work-orders.update', $workOrder) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if(Auth::user()->isProductionManager())
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name', $workOrder->product_name) }}" required>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $workOrder->quantity) }}" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="production_deadline" class="form-label">Production Deadline</label>
                                <input type="date" class="form-control @error('production_deadline') is-invalid @enderror" id="production_deadline" name="production_deadline" value="{{ old('production_deadline', $workOrder->production_deadline->format('Y-m-d')) }}" required>
                                @error('production_deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="assigned_operator_id" class="form-label">Assign Operator</label>
                                <select class="form-select @error('assigned_operator_id') is-invalid @enderror" id="assigned_operator_id" name="assigned_operator_id" required>
                                    @foreach($operators as $operator)
                                        <option value="{{ $operator->id }}" {{ old('assigned_operator_id', $workOrder->assigned_operator_id) == $operator->id ? 'selected' : '' }}>
                                            {{ $operator->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_operator_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="Pending" {{ old('status', $workOrder->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ old('status', $workOrder->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ old('status', $workOrder->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Canceled" {{ old('status', $workOrder->status) == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <!-- For Operators -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    @if($workOrder->status === 'Pending')
                                        <option value="Pending" selected>Pending</option>
                                        <option value="In Progress">In Progress</option>
                                    @elseif($workOrder->status === 'In Progress')
                                        <option value="In Progress" selected>In Progress</option>
                                        <option value="Completed">Completed</option>
                                    @else
                                        <option value="{{ $workOrder->status }}" selected>{{ $workOrder->status }}</option>
                                    @endif
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="quantity_processed" class="form-label">Quantity Processed</label>
                                <input type="number" class="form-control @error('quantity_processed') is-invalid @enderror" id="quantity_processed" name="quantity_processed" value="{{ old('quantity_processed') }}" min="1" max="{{ $workOrder->quantity }}" required>
                                <div class="form-text">Enter how many units are being processed in this status update (max: {{ $workOrder->quantity }})</div>
                                @error('quantity_processed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Progress Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                <div class="form-text">Add any notes about the production progress.</div>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Work Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

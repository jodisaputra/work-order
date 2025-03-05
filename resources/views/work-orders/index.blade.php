@extends('layouts.app')

@section('title', 'Work Orders')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Work Orders</h1>
        @if(Auth::user()->isProductionManager())
            <a href="{{ route('work-orders.create') }}" class="btn btn-primary">Create Work Order</a>
        @endif
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('work-orders.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Canceled" {{ request('status') === 'Canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date" class="form-label">Created Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                </div>
                @if(Auth::user()->isProductionManager())
                    <div class="col-md-3">
                        <label for="operator" class="form-label">Operator</label>
                        <select name="operator" id="operator" class="form-select">
                            <option value="">All Operators</option>
                            @foreach($operators as $operator)
                                <option value="{{ $operator->id }}" {{ request('operator') == $operator->id ? 'selected' : '' }}>
                                    {{ $operator->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($workOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workOrders as $workOrder)
                                <tr>
                                    <td>{{ $workOrder->order_number }}</td>
                                    <td>{{ $workOrder->product_name }}</td>
                                    <td>{{ $workOrder->quantity }}</td>
                                    <td>{{ $workOrder->production_deadline->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $workOrder->status === 'Pending' ? 'primary' : ($workOrder->status === 'In Progress' ? 'warning' : ($workOrder->status === 'Completed' ? 'success' : 'danger')) }}">
                                            {{ $workOrder->status }}
                                        </span>
                                    </td>
                                    <td>{{ $workOrder->operator->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('work-orders.show', $workOrder) }}" class="btn btn-sm btn-info">View</a>
                                            @if(Auth::user()->isProductionManager())
                                                <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('work-orders.destroy', $workOrder) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this work order?')">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $workOrders->links() }}
                </div>
            @else
                <p class="text-center text-muted">No work orders found.</p>
            @endif
        </div>
    </div>
</div>
@endsection

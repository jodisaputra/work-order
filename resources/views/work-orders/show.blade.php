@extends('layouts.app')

@section('title', 'Work Order Details')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Work Order: {{ $workOrder->order_number }}</h1>
        <div>
            <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">Back to List</a>
            @if(Auth::user()->isOperator() && in_array($workOrder->status, ['Pending', 'In Progress']))
                <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn btn-primary">Update Status</a>
            @endif
            @if(Auth::user()->isProductionManager())
                <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn btn-warning">Edit</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Work Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $workOrder->status === 'Pending' ? 'primary' : ($workOrder->status === 'In Progress' ? 'warning' : ($workOrder->status === 'Completed' ? 'success' : 'danger')) }} ms-2">
                            {{ $workOrder->status }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Product Name:</strong> {{ $workOrder->product_name }}
                    </div>
                    <div class="mb-3">
                        <strong>Quantity:</strong> {{ $workOrder->quantity }}
                    </div>
                    <div class="mb-3">
                        <strong>Production Deadline:</strong> {{ $workOrder->production_deadline->format('M d, Y') }}
                    </div>
                    <div class="mb-3">
                        <strong>Assigned Operator:</strong> {{ $workOrder->operator->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Created By:</strong> {{ $workOrder->creator->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Created At:</strong> {{ $workOrder->created_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong> {{ $workOrder->updated_at->format('M d, Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Progress Updates</h5>
                </div>
                <div class="card-body">
                    @if($progressUpdates->count() > 0)
                        <div class="timeline">
                            @foreach($progressUpdates as $update)
                                <div class="timeline-item mb-4">
                                    <div class="d-flex justify-content-between">
                                        <h6>{{ $update->created_at->format('M d, Y H:i') }}</h6>
                                        <span class="badge bg-info">{{ $update->updatedBy->name }}</span>
                                    </div>
                                    <p>
                                        Status changed from
                                        <span class="badge bg-{{ $update->from_status === 'Pending' ? 'primary' : ($update->from_status === 'In Progress' ? 'warning' : ($update->from_status === 'Completed' ? 'success' : 'danger')) }}">
                                            {{ $update->from_status }}
                                        </span>
                                        to
                                        <span class="badge bg-{{ $update->to_status === 'Pending' ? 'primary' : ($update->to_status === 'In Progress' ? 'warning' : ($update->to_status === 'Completed' ? 'success' : 'danger')) }}">
                                            {{ $update->to_status }}
                                        </span>
                                    </p>
                                    <p><strong>Quantity Processed:</strong> {{ $update->quantity_processed }}</p>
                                    @if($update->notes)
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <p class="mb-0"><strong>Notes:</strong> {{ $update->notes }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No progress updates yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

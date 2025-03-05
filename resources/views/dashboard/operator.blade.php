@extends('layouts.app')

@section('title', 'Operator Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Operator Dashboard</h1>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pending Work Orders</h5>
                </div>
                <div class="card-body">
                    @if($pendingWorkOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Deadline</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingWorkOrders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->product_name }}</td>
                                            <td>{{ $order->quantity }}</td>
                                            <td>{{ $order->production_deadline->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('work-orders.show', $order) }}" class="btn btn-sm btn-primary">Start</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No pending work orders assigned to you.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">In Progress Work Orders</h5>
                </div>
                <div class="card-body">
                    @if($inProgressWorkOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Deadline</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inProgressWorkOrders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->product_name }}</td>
                                            <td>{{ $order->quantity }}</td>
                                            <td>{{ $order->production_deadline->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('work-orders.show', $order) }}" class="btn btn-sm btn-success">Complete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No work orders currently in progress.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Recently Completed Work Orders</h5>
                </div>
                <div class="card-body">
                    @if($recentlyCompletedWorkOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Completed On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentlyCompletedWorkOrders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->product_name }}</td>
                                            <td>{{ $order->quantity }}</td>
                                            <td>{{ $order->updated_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No completed work orders yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

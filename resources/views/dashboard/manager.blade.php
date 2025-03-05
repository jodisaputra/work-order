@extends('layouts.app')

@section('title', 'Production Manager Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Production Manager Dashboard</h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2 class="card-text">{{ $pendingCount }}</h2>
                    <a href="{{ route('work-orders.index', ['status' => 'Pending']) }}" class="text-white">View all</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">In Progress</h5>
                    <h2 class="card-text">{{ $inProgressCount }}</h2>
                    <a href="{{ route('work-orders.index', ['status' => 'In Progress']) }}" class="text-dark">View all</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <h2 class="card-text">{{ $completedCount }}</h2>
                    <a href="{{ route('work-orders.index', ['status' => 'Completed']) }}" class="text-white">View all</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Canceled</h5>
                    <h2 class="card-text">{{ $canceledCount }}</h2>
                    <a href="{{ route('work-orders.index', ['status' => 'Canceled']) }}" class="text-white">View all</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Work Orders</h5>
                    <a href="{{ route('work-orders.create') }}" class="btn btn-sm btn-primary">Create New</a>
                </div>
                <div class="card-body">
                    @if($recentWorkOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentWorkOrders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->product_name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'Pending' ? 'primary' : ($order->status === 'In Progress' ? 'warning' : ($order->status === 'Completed' ? 'success' : 'danger')) }}">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('work-orders.show', $order) }}" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No work orders found.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Upcoming Deadlines</h5>
                </div>
                <div class="card-body">
                    @if($upcomingDeadlines->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Product</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingDeadlines as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->product_name }}</td>
                                            <td>{{ $order->production_deadline->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'Pending' ? 'primary' : 'warning' }}">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No upcoming deadlines.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

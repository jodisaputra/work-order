@extends('layouts.app')

@section('title', 'Summary Report')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Work Order Summary Report</h1>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($summary->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th class="text-center">Pending</th>
                                <th class="text-center">In Progress</th>
                                <th class="text-center">Completed</th>
                                <th class="text-center">Canceled</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($summary as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td class="text-center">{{ $item->pending_quantity }}</td>
                                    <td class="text-center">{{ $item->in_progress_quantity }}</td>
                                    <td class="text-center">{{ $item->completed_quantity }}</td>
                                    <td class="text-center">{{ $item->canceled_quantity }}</td>
                                    <td class="text-center">{{ $item->total_quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold bg-light">
                                <td>Total</td>
                                <td class="text-center">{{ $summary->sum('pending_quantity') }}</td>
                                <td class="text-center">{{ $summary->sum('in_progress_quantity') }}</td>
                                <td class="text-center">{{ $summary->sum('completed_quantity') }}</td>
                                <td class="text-center">{{ $summary->sum('canceled_quantity') }}</td>
                                <td class="text-center">{{ $summary->sum('total_quantity') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-center text-muted">No work order data found.</p>
            @endif
        </div>
    </div>
</div>
@endsection

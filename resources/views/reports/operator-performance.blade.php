@extends('layouts.app')

@section('title', 'Operator Performance')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Operator Performance Report</h1>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="row">
        @forelse($performance as $operatorData)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ $operatorData['operator']->name }}</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="text-center mb-3">
                            Total Completed: <span class="badge bg-success">{{ $operatorData['total_completed'] }}</span>
                        </h6>

                        @if(count($operatorData['products']) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Completed Quantity</th>
                                            <th class="text-center">Work Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($operatorData['products'] as $product)
                                            <tr>
                                                <td>{{ $product['product_name'] }}</td>
                                                <td class="text-center">{{ $product['completed_quantity'] }}</td>
                                                <td class="text-center">{{ $product['work_order_count'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted">No completed work orders.</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No operator performance data found.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

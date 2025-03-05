<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:production-manager']);
    }

    public function summary()
    {
        $summary = DB::table('work_orders')
            ->select(
                'product_name',
                DB::raw('SUM(CASE WHEN status = "Pending" THEN quantity ELSE 0 END) as pending_quantity'),
                DB::raw('SUM(CASE WHEN status = "In Progress" THEN quantity ELSE 0 END) as in_progress_quantity'),
                DB::raw('SUM(CASE WHEN status = "Completed" THEN quantity ELSE 0 END) as completed_quantity'),
                DB::raw('SUM(CASE WHEN status = "Canceled" THEN quantity ELSE 0 END) as canceled_quantity'),
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->groupBy('product_name')
            ->get();

        return view('reports.summary', [
            'summary' => $summary,
        ]);
    }

    public function operatorPerformance()
    {
        $operators = User::whereHas('role', function ($query) {
            $query->where('slug', 'operator');
        })->get();

        $performance = [];

        foreach ($operators as $operator) {
            $completedWorkOrders = WorkOrder::where('assigned_operator_id', $operator->id)
                ->where('status', 'Completed')
                ->get();

            $productPerformance = [];

            foreach ($completedWorkOrders->groupBy('product_name') as $productName => $orders) {
                $productPerformance[] = [
                    'product_name' => $productName,
                    'completed_quantity' => $orders->sum('quantity'),
                    'work_order_count' => $orders->count(),
                ];
            }

            $performance[$operator->id] = [
                'operator' => $operator,
                'products' => $productPerformance,
                'total_completed' => $completedWorkOrders->sum('quantity'),
            ];
        }

        return view('reports.operator-performance', [
            'performance' => $performance,
        ]);
    }
}

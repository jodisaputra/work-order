<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isProductionManager()) {
            return $this->managerDashboard();
        } else {
            return $this->operatorDashboard();
        }
    }

    private function managerDashboard()
    {
        $pendingCount = WorkOrder::where('status', 'Pending')->count();
        $inProgressCount = WorkOrder::where('status', 'In Progress')->count();
        $completedCount = WorkOrder::where('status', 'Completed')->count();
        $canceledCount = WorkOrder::where('status', 'Canceled')->count();

        $recentWorkOrders = WorkOrder::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $upcomingDeadlines = WorkOrder::whereIn('status', ['Pending', 'In Progress'])
            ->where('production_deadline', '>=', now())
            ->orderBy('production_deadline', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard.manager', [
            'pendingCount' => $pendingCount,
            'inProgressCount' => $inProgressCount,
            'completedCount' => $completedCount,
            'canceledCount' => $canceledCount,
            'recentWorkOrders' => $recentWorkOrders,
            'upcomingDeadlines' => $upcomingDeadlines,
        ]);
    }

    private function operatorDashboard()
    {
        $user = Auth::user();

        $pendingWorkOrders = WorkOrder::where('assigned_operator_id', $user->id)
            ->where('status', 'Pending')
            ->orderBy('production_deadline', 'asc')
            ->get();

        $inProgressWorkOrders = WorkOrder::where('assigned_operator_id', $user->id)
            ->where('status', 'In Progress')
            ->orderBy('production_deadline', 'asc')
            ->get();

        $recentlyCompletedWorkOrders = WorkOrder::where('assigned_operator_id', $user->id)
            ->where('status', 'Completed')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.operator', [
            'pendingWorkOrders' => $pendingWorkOrders,
            'inProgressWorkOrders' => $inProgressWorkOrders,
            'recentlyCompletedWorkOrders' => $recentlyCompletedWorkOrders,
        ]);
    }
}

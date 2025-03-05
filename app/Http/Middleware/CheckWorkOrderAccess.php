<?php

namespace App\Http\Middleware;

use App\Models\WorkOrder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWorkOrderAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $workOrderId = $request->route('workOrder');

        if (!$workOrderId) {
            return $next($request);
        }

        $workOrder = WorkOrder::findOrFail($workOrderId);
        $user = $request->user();

        // Production Managers have access to all work orders
        if ($user->isProductionManager()) {
            return $next($request);
        }

        // Operators only have access to their assigned work orders
        if ($user->isOperator() && $workOrder->assigned_operator_id === $user->id) {
            return $next($request);
        }

        abort(403, 'You do not have access to this work order.');
    }
}

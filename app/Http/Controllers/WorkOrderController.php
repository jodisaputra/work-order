<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.work.order.access')->except(['index', 'create', 'store']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = WorkOrder::query();

        // Filter based on user role
        if ($user->isOperator()) {
            $query->where('assigned_operator_id', $user->id);
        }

        // Apply filters only if they have actual values
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            // Handle various date formats
            try {
                $date = Carbon::parse($request->date)->toDateString();
                $query->whereDate('created_at', $date);
            } catch (\Exception $e) {
                // If date parsing fails, don't apply the filter
                // Log the error for debugging
                \Log::error('Date parsing error: ' . $e->getMessage());
            }
        }

        if ($user->isProductionManager() && $request->filled('operator')) {
            $query->where('assigned_operator_id', $request->operator);
        }

        // Always check if the query would return results
        $count = $query->count();

        // Get the results with pagination
        $workOrders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get operators for the filter dropdown
        $operators = User::whereHas('role', function ($query) {
            $query->where('slug', 'operator');
        })->get();

        // Add debug information to session
        session()->flash('debug', [
            'filter_criteria' => $request->all(),
            'record_count' => $count,
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        return view('work-orders.index', [
            'workOrders' => $workOrders,
            'operators' => $operators,
        ]);
    }


    public function create()
    {
        $this->authorize('create', WorkOrder::class);

        $operators = User::whereHas('role', function ($query) {
            $query->where('slug', 'operator');
        })->get();

        return view('work-orders.create', [
            'operators' => $operators,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', WorkOrder::class);

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'production_deadline' => 'required|date|after_or_equal:today',
            'assigned_operator_id' => 'required|exists:users,id',
        ]);

        $workOrder = new WorkOrder($validated);
        $workOrder->status = 'Pending';
        $workOrder->created_by = Auth::id();
        $workOrder->save();

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work order created successfully.');
    }

    public function show(WorkOrder $workOrder)
    {
        return view('work-orders.show', [
            'workOrder' => $workOrder,
            'progressUpdates' => $workOrder->progressUpdates()->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function edit(WorkOrder $workOrder)
    {
        $this->authorize('update', $workOrder);

        $operators = User::whereHas('role', function ($query) {
            $query->where('slug', 'operator');
        })->get();

        return view('work-orders.edit', [
            'workOrder' => $workOrder,
            'operators' => $operators,
        ]);
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $this->authorize('update', $workOrder);
        $user = Auth::user();

        // Get original status before any changes
        $oldStatus = $workOrder->status;

        if ($user->isProductionManager()) {
            // Production Manager can update all fields
            $workOrder->update($request->validate([
                'product_name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'production_deadline' => 'required|date',
                'status' => 'required|in:Pending,In Progress,Completed,Canceled',
                'assigned_operator_id' => 'required|exists:users,id',
            ]));
        } else {
            // Operator can only update status within certain rules
            $validated = $request->validate([
                'status' => 'required|in:Pending,In Progress,Completed',
                'quantity_processed' => 'required|integer|min:1|max:' . $workOrder->quantity,
                'notes' => 'nullable|string',
            ]);

            $newStatus = $validated['status'];

            // Allow updating notes even if status doesn't change
            if ($oldStatus === $newStatus) {
                // Just update the notes, don't change status
                WorkOrderProgress::create([
                    'work_order_id' => $workOrder->id,
                    'from_status' => $oldStatus,
                    'to_status' => $oldStatus, // Same status
                    'quantity_processed' => $validated['quantity_processed'],
                    'notes' => $validated['notes'] ?? null,
                    'updated_by' => $user->id,
                ]);

                return redirect()->route('work-orders.show', $workOrder)
                    ->with('success', 'Work order progress notes updated successfully.');
            }

            // Validate status transitions for operators
            if (
                ($oldStatus === 'Pending' && $newStatus === 'In Progress') ||
                ($oldStatus === 'In Progress' && $newStatus === 'Completed')
            ) {
                $workOrder->status = $newStatus;
                $workOrder->save();

                // Create progress record
                WorkOrderProgress::create([
                    'work_order_id' => $workOrder->id,
                    'from_status' => $oldStatus,
                    'to_status' => $newStatus,
                    'quantity_processed' => $validated['quantity_processed'],
                    'notes' => $validated['notes'] ?? null,
                    'updated_by' => $user->id,
                ]);
            } else {
                return redirect()->back()
                    ->with('error', 'Invalid status transition. Operators can only move from Pending to In Progress or from In Progress to Completed.');
            }
        }

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work order updated successfully.');
    }


    public function destroy(WorkOrder $workOrder)
    {
        $this->authorize('delete', $workOrder);

        $workOrder->delete();

        return redirect()->route('work-orders.index')
            ->with('success', 'Work order deleted successfully.');
    }
}

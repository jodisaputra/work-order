<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkOrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        // Both Production Managers and Operators can view work orders
        return true;
    }

    public function view(User $user, WorkOrder $workOrder)
    {
        // Production Managers can view any work order
        if ($user->isProductionManager()) {
            return true;
        }

        // Operators can only view their assigned work orders
        return $user->isOperator() && $workOrder->assigned_operator_id === $user->id;
    }

    public function create(User $user)
    {
        // Only Production Managers can create work orders
        return $user->isProductionManager();
    }

    public function update(User $user, WorkOrder $workOrder)
    {
        // Production Managers can update any work order
        if ($user->isProductionManager()) {
            return true;
        }

        // Operators can only update their assigned work orders
        // and only change the status from Pending to In Progress or In Progress to Completed
        if ($user->isOperator() && $workOrder->assigned_operator_id === $user->id) {
            $status = $workOrder->status;
            return $status === 'Pending' || $status === 'In Progress';
        }

        return false;
    }

    public function delete(User $user, WorkOrder $workOrder)
    {
        // Only Production Managers can delete work orders
        return $user->isProductionManager();
    }
}

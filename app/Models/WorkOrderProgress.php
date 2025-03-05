<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkOrderProgress extends Model
{
    use HasFactory;

    protected $table = 'work_order_progress';

    protected $fillable = [
        'work_order_id',
        'from_status',
        'to_status',
        'quantity_processed',
        'notes',
        'updated_by',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

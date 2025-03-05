<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'product_name',
        'quantity',
        'production_deadline',
        'status',
        'assigned_operator_id',
        'created_by',
    ];

    protected $casts = [
        'production_deadline' => 'date',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($workOrder) {
            if (!$workOrder->order_number) {
                $workOrder->order_number = self::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastWorkOrder = self::whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastWorkOrder ? intval(substr($lastWorkOrder->order_number, -3)) + 1 : 1;
        return 'WO-' . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'assigned_operator_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function progressUpdates()
    {
        return $this->hasMany(WorkOrderProgress::class);
    }

    public function scopeFilterByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeFilterByDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    public function scopeFilterByOperator($query, $operatorId)
    {
        return $query->where('assigned_operator_id', $operatorId);
    }
}

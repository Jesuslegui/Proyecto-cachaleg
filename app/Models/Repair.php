<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repair extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id', 'customer_name', 'customer_phone', 'product_description', 'service_id',
        'description', 'received_at', 'estimated_delivery_at', 'status', 'price', 'observations', 'created_by'
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'estimated_delivery_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

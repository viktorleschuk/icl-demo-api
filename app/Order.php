<?php

namespace App;

use App\Helpers\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use Filterable;

    protected $fillable = [
        'user_id',
        'client_name',
        'client_phone',
        'client_address'
    ];

    protected $appends = [
        'total_sum'
    ];

    protected $withCount = [
        'items'
    ];

    /**
     * Get the user related with the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all order items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Total sum accessor
     *
     * @return mixed
     */
    public function getTotalSumAttribute()
    {
        return $this->items()->sum(DB::raw('quantity * price'));
    }
}

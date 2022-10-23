<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    }

    /**
     * Get user website
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'relation_id', 'id');
    }

    /**
     * Get domain
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class, 'relation_id', 'id');
    }

    /**
     * Get payment method
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    /**
     * Get created user
     */
    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_id', 'id');
    }

    /**
     * Get transaction type
     */
    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id', 'id');
    }
}

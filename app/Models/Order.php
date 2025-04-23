<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'orders';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'status', 'comment', "product"];
    
    protected $attributes = [
        'status' => 'новый'
    ];    
    
    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
       /** @use HasFactory<\Database\Factories\OrderFactory> */
       use HasFactory;
    
       /**
       * The table associated with the model.
       *
       * @var string
       */
       protected $table = 'carts';
       
       /**
        * The attributes that are mass assignable.
        *
        * @var array
        */
       protected $fillable = ['user', 'product'];
       
       
       public function product(): HasMany
       {
           return $this->hasMany(Product::class);
       }

       public function user(): HasMany
       {
           return $this->hasMany(User::class);
       }
}

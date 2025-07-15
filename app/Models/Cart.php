<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    
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
       protected $fillable = ['user', 'product', 'quantity', 'price'];
       
       
       public function product(): HasMany
       {
           return $this->hasMany(Product::class);
       }

       public function user(): HasMany
       {
           return $this->hasMany(User::class);
       }
}

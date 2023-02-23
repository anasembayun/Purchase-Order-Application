<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User\User;

class PurchaseRequest extends Model
{
    use HasFactory;
    function product(){
        return $this->belongsTo(Product::class);
    }

    function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    function vendors(){
        return $this->belongsTo(User::class,'vendor_id','id');
    }
}

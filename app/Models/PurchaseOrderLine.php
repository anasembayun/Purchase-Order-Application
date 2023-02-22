<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User\User;

class PurchaseOrderLine extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_lines';
    protected $primaryKey = "id";
    protected $fillable= ['product_id','qty','price', 'discount', 'total', 'date', 'date_required','user_id','vendor_id','invoice_number','ppn','ppn_nominal','status'];

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

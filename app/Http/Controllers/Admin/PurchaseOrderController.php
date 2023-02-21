<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PurchaseOrderLine;
use Validator;
use \DateTime;

class PurchaseOrderController extends Controller
{
    public function getProductList(){
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function getProductShow($id){
        
    }

    public function getProductEdit($id){
        
    }

    public function getProductDestroy($id){
        
    }

    public function purchaseOrderLineList(){
        $purchaseOrderLines = PurchaseOrderLine::paginate(10);
        return view('admin.purchaseOrderLine.index', compact('purchaseOrderLines'));
    }

    public function purchaseOrderLineCreate(){
        return view('admin.purchaseOrderLine.create');
    }

    public function purchaseOrderLineStore(Request $request, PurchaseOrderLine $purchaseOrderLine){
        $validator = Validator::make($request->all(),[
            'qty' => 'required',
            'price' => 'required',
            'discount' => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());
        
        $purchaseOrderLine->qty = $request->post('qty');
        $purchaseOrderLine->price = $request->post('price');
        $purchaseOrderLine->discount = $request->post('discount');
        $purchaseOrderLine->total = (int)$request->post('qty') * (int)$request->post('price') - ((int)$request->post('discount')/100 * (int)$request->post('price'));
        $purchaseOrderLine->created_at = new DateTime();
        $purchaseOrderLine->updated_at = new DateTime();
        $purchaseOrderLine->save();
        return redirect()->intended(route('admin.purchase.order.lines'));
    }

    public function purchaseOrderLineShow($id){
        
    }

    public function purchaseOrderLineEdit($id){
        
    }

    public function purchaseOrderLineDestroy($id){
        
    }
}

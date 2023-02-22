<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PurchaseOrderLine;
use App\Models\Auth\User\User;
use Validator;
use \DateTime;

class PurchaseOrderController extends Controller
{
    public function getProductList(){
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function getProductPrice($id = 1)
    {
        $data = Product::where('price',$id)->first();
        return response()->json($data);
    }

    public function getProductShow($id){
        
    }

    public function getProductEdit($id){
        
    }

    public function getProductDestroy($id){
        $data = Product::find($id);
        $data->delete();
        return redirect()->route('admin.products')->withFlashSuccess('Deleted Successfully!');
    }

    public function purchaseOrderLineList(){
        $purchaseOrderLines = PurchaseOrderLine::paginate(10);
        return view('admin.purchaseOrderLine.index', compact('purchaseOrderLines'));
    }

    public function purchaseOrderLineCreate(){
        $vendors = User::with('roles')->whereHas('roles', function($query) {$query->where('role_id', 1);})->get();
        $users = User::with('roles')->whereHas('roles', function($query) {$query->where('role_id', 2);})->get();
        $products = Product::all();
        return view('admin.purchaseOrderLine.create', ['products'=>$products, 'vendors' =>$vendors,'users'=>$users]);
    }

    public function purchaseOrderLineStore(Request $request, PurchaseOrderLine $purchaseOrderLine){
        $validator = Validator::make($request->all(),[
            'date' => 'required',
            'date_required' => 'required',
            'invoice_number' => 'required',
            'vendor_id' => 'required',
            'user_id' => 'required',
            'product' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'discount' => 'required',
            'ppn' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $purchaseOrderLine->date = $request->post('date');
        $purchaseOrderLine->date_required = $request->post('date_required');
        $purchaseOrderLine->invoice_number = $request->post('invoice_number');
        $purchaseOrderLine->vendor_id = $request->post('vendor_id');
        $purchaseOrderLine->user_id = $request->post('user_id');
        $purchaseOrderLine->product_id = json_decode($request->get('product'), true)['id']; 
        $purchaseOrderLine->qty = $request->post('qty');
        $purchaseOrderLine->price = $request->post('price');
        $purchaseOrderLine->discount = $request->post('discount');
        $purchaseOrderLine->ppn = $request->post('ppn');
        $purchaseOrderLine->total = (int)$request->post('qty') * (int)$request->post('price') - ((int)$request->post('discount')/100 * (int)$request->post('price'));
        $purchaseOrderLine->ppn_nominal = (int)$request->post('ppn')/100 * (int)$purchaseOrderLine->total;
        $purchaseOrderLine->status = $request->post('status');
        $purchaseOrderLine->created_at = new DateTime();
        $purchaseOrderLine->updated_at = new DateTime();
        $purchaseOrderLine->save();
        return redirect()->intended(route('admin.purchase.order.lines'));
    }

    public function purchaseOrderLineShow($id){
        $purchaseOrderLines = PurchaseOrderLine::find($id);
        return view('admin.purchaseOrderLine.show', compact('purchaseOrderLines'));
    }

    public function purchaseOrderLineEdit($id){
        $purchaseOrderLines = PurchaseOrderLine::find($id);
        $products = Product::all();
        $vendors = User::with('roles')->whereHas('roles', function($query) {$query->where('role_id', 1);})->get();
        $users = User::with('roles')->whereHas('roles', function($query) {$query->where('role_id', 2);})->get();
        return view('admin.purchaseOrderLine.edit', compact('purchaseOrderLines','products', 'vendors', 'users'));
    }

    public function purchaseOrderLineUpdate(Request $request, $id){
        // $validator = Validator::make($request->all(),[
        //     'date' => 'required',
        //     'date_required' => 'required',
        //     'invoice_number' => 'required',
        //     'vendor_id' => 'required',
        //     'user_id' => 'required',
        //     'product' => 'required',
        //     'qty' => 'required',
        //     'price' => 'required',
        //     'discount' => 'required',
        //     'ppn' => 'required',
        //     'status' => 'required',
        // ]);

        // if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $purchaseOrderLine = PurchaseOrderLine::find($id);
        $purchaseOrderLine->date = $request->post('date');
        $purchaseOrderLine->date_required = $request->post('date_required');
        $purchaseOrderLine->invoice_number = $request->post('invoice_number');
        $purchaseOrderLine->vendor_id = $request->post('vendor_id');
        $purchaseOrderLine->user_id = $request->post('user_id');
        $purchaseOrderLine->product_id = json_decode($request->get('product'), true)['id']; 
        $purchaseOrderLine->qty = $request->post('qty');
        $purchaseOrderLine->price = $request->post('price');
        $purchaseOrderLine->discount = $request->post('discount');
        $purchaseOrderLine->ppn = $request->post('ppn');
        $purchaseOrderLine->total = (int)$request->post('qty') * (int)$request->post('price') - ((int)$request->post('discount')/100 * (int)$request->post('price'));
        $purchaseOrderLine->ppn_nominal = (int)$request->post('ppn')/100 * (int)$purchaseOrderLine->total;
        $purchaseOrderLine->status = $request->post('status');
        $purchaseOrderLine->created_at = new DateTime();
        $purchaseOrderLine->updated_at = new DateTime();
        $purchaseOrderLine->update();
        return redirect()->intended(route('admin.purchase.order.lines'))->withFlashSuccess('Updated Successfully!');
    }

    public function purchaseOrderLineDestroy($id){
        $data = PurchaseOrderLine::find($id);
        $data->delete();
        return redirect()->route('admin.purchase.order.lines')->withFlashSuccess('Deleted Successfully!');
    }
}

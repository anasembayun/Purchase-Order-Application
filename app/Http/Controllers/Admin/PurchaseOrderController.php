<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PurchaseOrderLine;
use App\Models\PurchaseRequest;
use App\Models\Auth\User\User;
use Validator;
use \DateTime;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PurchaseOrderController extends Controller 
{
    public function productIndex(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Product::query())->toJson();
        }

        return view('admin.products.product');
    }

    public function ProductImport(Request $request){
        $this->validate($request, [
            'uploaded_file' => 'required|file|mimes:xls,xlsx'
        ]);
        $the_file = $request->file('uploaded_file');
        try{
            $spreadsheet = IOFactory::load($the_file->getRealPath());
            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range( 2, $row_limit );
            $column_range = range( 'F', $column_limit );
            $startcount = 2;
            $data = array();
            foreach ( $row_range as $row ) {
                $data[] = [
                    'id' =>$sheet->getCell( 'A' . $row )->getValue(),
                    'product_name' => $sheet->getCell( 'B' . $row )->getValue(),
                    'product_code' => $sheet->getCell( 'C' . $row )->getCalculatedValue(),
                    'price' => $sheet->getCell( 'D' . $row )->getValue(),
                    'created_at' => $sheet->getCell( 'E' . $row )->getValue(),
                    'updated_at' =>$sheet->getCell( 'F' . $row )->getValue(),
                ];
                $startcount++;
            }
            DB::table('products')->insert($data);
        } catch (Exception $e) {
            $error_code = $e->errorInfo[1];
            return back()->withErrors('There was a problem uploading the data!');
        }
        return back()->withFlashSuccess('Great! Data has been successfully uploaded.');
    }

    public function ExportExcel($product_data){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($product_data);

            for ($i=2; $i<51; $i++) {
                $spreadSheet->getActiveSheet()
                ->setCellValue('C' . $i, '=CHAR(RANDBETWEEN(65,90))&CHAR(RANDBETWEEN(65,90))&CHAR(RANDBETWEEN(65,90))&RANDBETWEEN(100,99999)');
            }

            $Excel_writer = new Xlsx($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Product_ExportedData.xlsx"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }
    /**
     *This function loads the data from the database then converts it
     * into an Array that will be exported to Excel
     */
    public function ProductExport(){
        $data = DB::table('products')->orderBy('id', 'ASC')->get();
        $data_array [] = array("id","product_name","product_code","price","created_at","updated_at");
        foreach($data as $data_item)
        {
            $data_array[] = array(
                'id' =>$data_item->id,
                'product_name' =>$data_item->product_name,
                'product_code' => $data_item->product_code,
                'price' => $data_item->price,
                'created_at' => $data_item->created_at,
                'updated_at' => $data_item->updated_at,
            );
        }
        $this->ExportExcel($data_array);
    }

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
        $total_price = (int)$request->post('qty') * (int)$request->post('price') - ((int)$request->post('discount')/100 * (int)$request->post('price'));
        $purchaseOrderLine->ppn_nominal = (int)$request->post('ppn')/100 * (int)$total_price;
        $purchaseOrderLine->total = (int)$total_price + (int)$purchaseOrderLine->ppn_nominal;
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

    public function purchaseRequestList(){
        $purchaseRequests = PurchaseRequest::paginate(10);
        return view('admin.purchaseRequest.index', compact('purchaseRequests'));
    }

    public function purchaseRequestCreate(){   
        $products = Product::all();
        $vendors = User::with('roles')->whereHas('roles', function($query) {$query->where('role_id', 1);})->get();
        $users = User::with('roles')->whereHas('roles', function($query) {$query->where('role_id', 2);})->get();
        return view('admin.purchaseRequest.create', compact('vendors','users','products'));
    }

    public function purchaseRequestShow($id){
        $purchaseRequests = PurchaseRequest::find($id);
        return view('admin.purchaseRequest.show', compact('purchaseRequests'));
    }

    public function purchaseRequestEdit($id){   
        $purchaseRequests = PurchaseRequest::find($id);
        $products = Product::all();
        $vendors = User::with('roles')->whereHas('roles', function($query) {$query->where('role_id', 1);})->get();
        $users = User::with('roles')->whereHas('roles', function($query) {$query->where('role_id', 2);})->get();
        return view('admin.purchaseRequest.edit', compact('purchaseRequests','vendors','users','products'));
    }

    public function purchaseRequestStore(Request $request){
        $purchaseRequests = new PurchaseRequest;
        $purchaseRequests->date = $request->post('date');
        $purchaseRequests->date_required = $request->post('date_required');
        $purchaseRequests->invoice_number = $request->post('invoice_number');
        $purchaseRequests->vendor_id = $request->post('vendor_id');
        $purchaseRequests->user_id = $request->post('user_id');
        $purchaseRequests->product_id = $request->post('product_id');
        $purchaseRequests->qty = $request->post('qty');
        $purchaseRequests->save();
        return redirect()->route('admin.purchase.request')->withFlashSuccess('Create Successfully!');
    }

    public function purchaseRequestUpdate(Request $request, $id){
        $purchaseRequests = PurchaseRequest::find($id);
        $purchaseRequests->date = $request->post('date');
        $purchaseRequests->date_required = $request->post('date_required');
        $purchaseRequests->invoice_number = $request->post('invoice_number');
        $purchaseRequests->vendor_id = $request->post('vendor_id');
        $purchaseRequests->user_id = $request->post('user_id');
        $purchaseRequests->product_id = $request->post('product_id');
        $purchaseRequests->qty = $request->post('qty');
        $purchaseRequests->update();
        return redirect()->route('admin.purchase.request')->withFlashSuccess('Update Successfully!');
    }

    public function purchaseRequestDestroy($id){
        $data = PurchaseRequest::find($id);
        $data->delete();
        return redirect()->route('admin.purchase.request')->withFlashSuccess('Deleted Successfully!');
    }


}

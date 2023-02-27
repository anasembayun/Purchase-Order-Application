<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Bootstrap</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
        <br><br>
        <h2 align="center">Purchase Request </h2><br>
        <div class="col d-flex justify-content-center">
            <div class="card" style="width: 60rem;">
                    <div class="card-header">
                    Update Purchase Request
                    </div>
                <div class="card-body">
                <form action="{{route('admin.purchase.request.update', $purchaseRequests->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="form-group">
                        <label for="invoice_number">Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control" id="invoice_number" value="{{$purchaseRequests->invoice_number}}">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control" id="date" value="{{$purchaseRequests->date}}">
                        </div>
                        <div class="form-group col-md-6">
                        <label for="date_req">Date Required</label>
                        <input type="date" name="date_required" class="form-control" id="date_req" value="{{$purchaseRequests->date_required}}">
                        </div>
                    </div>   

                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="user_id">Customer</label>
                        <select id="user_id" name="user_id" class="form-control">
                            <option value="{{$purchaseRequests->users->id}}" selected>{{$purchaseRequests->users->name}}</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="vendor_id">Vendor</label>
                        <select id="vendor_id" name="vendor_id" class="form-control">
                            <option value="{{$purchaseRequests->vendors->id}}" selected>{{$purchaseRequests->vendors->name}}</option>
                            @foreach($vendors as $vendor)
                                <option value="{{$vendor->id}}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>   

                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="product_id">Product</label>
                        <select id="product" name="product_id" class="form-control">
                            <option value="{{$purchaseRequests->product->id}}" selected>{{$purchaseRequests->product->product_name}}</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="qty">Quantity</label>
                        <input type="text" name="qty" class="form-control" id="qty" value="{{$purchaseRequests->qty}}">
                        </div>
                    </div>   


                </div>
                <div class="card-footer text-muted">
                    <div align="right">
                        <a class="btn btn-primary" href="{{route('admin.purchase.request')}}">Cancel</a>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
  </body>
</html>

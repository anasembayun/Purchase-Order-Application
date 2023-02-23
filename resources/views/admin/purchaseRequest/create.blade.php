@extends('admin.layouts.admin')

@section('title',__('Create Purchase Request') )

@section('content')
    <div class="ui card" style="width:170rem; margin-top:5rem;">
        <div class="content">
            <div class="header">Create Purchase Request</div>
        </div>
        <div class="content">
        <form class="ui form" action="{{route('admin.purchase.request.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
            <div class="field">
                <label>Invoice Number</label>
                <input type="text" name="invoice_number" placeholder="example: invoice001">
            </div>

            <div class="field"> 
                <div class="two fields">
                    <div class="field">
                        <label>Date</label>
                        <input type="date" name="date" placeholder="Date">
                    </div>
                    <div class="field">
                        <label>Date Required</label>
                        <input type="date" name="date_required" placeholder="Date Required">
                    </div>
                </div>
            </div>

            <div class="field"> 
                <div class="two fields">
                    <div class="field">
                        <label>Customer</label>
                        <select id="user_id" name="user_id">
                            <option value="" selected>--  Select User --</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Vendor</label>
                        <select id="vendor_id" name="vendor_id">
                            <option value="" selected>--  Select vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{$vendor->id}}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="field"> 
                <div class="two fields">
                    <div class="field">
                        <label>Product</label>
                        <select id="product" name="product_id">
                            <option value="" selected>--  Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Quantity</label>
                        <input type="text" name="qty" placeholder="10">
                    </div>
                </div>
            </div>
        </div>
        <div class="extra content">
            <a class="ui right floated button" href="{{route('admin.purchase.request')}}">Cancel</a>
            <button type="submit" class="ui right floated positive button">Save</button>
        </div>
        </form>
    </div>
@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/users/edit.css')) }}
    <link rel="stylesheet" type="text/css" class="ui" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/input.css">
    <link rel="stylesheet" type="text/css" class="ui" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/input.min.css">
    <link rel="stylesheet" type="text/css" class="ui" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/card.min.css">
    <link rel="stylesheet" type="text/css" class="ui" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/button.min.css">
    <link rel="stylesheet" type="text/css" class="ui" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/form.min.css">
    <link rel="stylesheet" type="text/css" class="ui" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/dropdown.min.css">
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/edit.js')) }}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js"></script>  
@endsection

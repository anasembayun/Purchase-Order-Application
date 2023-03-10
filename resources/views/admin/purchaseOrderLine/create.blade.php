@extends('admin.layouts.admin')

@section('title',__('views.admin.purchase.order.lines.create.title') )

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            {{ Form::open(['route'=>['admin.purchase.order.lines.store'],'method' => 'post','class'=>'form-horizontal form-label-left']) }}

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date" >
                        {{ __('views.admin.purchase.order.lines.create.date') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="date" type="date" class="form-control col-md-7 col-xs-12 @if($errors->has('date')) parsley-error @endif"
                                name="date"  required>
                        @if($errors->has('date'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('date') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date_required" >
                        {{ __('views.admin.purchase.order.lines.create.date.required') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="date_required" type="date" class="form-control col-md-7 col-xs-12 @if($errors->has('date_required')) parsley-error @endif"
                                name="date_required"  required>
                        @if($errors->has('date_required'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('date_required') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="invoice_number" >
                        {{ __('views.admin.purchase.order.lines.create.invoice.number') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="invoice_number" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('invoice_number')) parsley-error @endif"
                                name="invoice_number"  required>
                        @if($errors->has('invoice_number'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('invoice_number') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendor_id">
                        {{ __('views.admin.purchase.order.lines.create.vendor') }}
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="vendor_id" name="vendor_id" class="select2" style="width: 100%" autocomplete="off">
                            <option value="" selected>--  Select vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{$vendor->id}}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_id">
                        {{ __('views.admin.purchase.order.lines.create.user') }}
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="user_id" name="user_id" class="select2" style="width: 100%" autocomplete="off">
                            <option value="" selected>--  Select User --</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product">
                        {{ __('views.admin.purchase.order.lines.create.product') }}
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="product" name="product" class="select2" style="width: 100%" autocomplete="off" onChange="getProduct()" required>
                            <option value="" selected>--  Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="qty" >
                        {{ __('views.admin.purchase.order.lines.create.qty') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="qty" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('qty')) parsley-error @endif"
                                name="qty"  required>
                        @if($errors->has('qty'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('qty') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price" >
                        {{ __('views.admin.purchase.order.lines.create.price') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="price" type="text" value=""  class="form-control col-md-7 col-xs-12 @if($errors->has('price')) parsley-error @endif"
                            name="price"  required>
                            @if($errors->has('price'))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get('price') as $error)
                                            <li class="parsley-required">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount" >
                        {{ __('views.admin.purchase.order.lines.create.discount') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="discount" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('discount')) parsley-error @endif"
                        name="discount"  required>
                        @if($errors->has('discount'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('discount') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ppn" >
                        {{ __('views.admin.purchase.order.lines.create.ppn') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="ppn" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('ppn')) parsley-error @endif"
                                name="ppn"  required>
                        @if($errors->has('ppn'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('ppn') as $error)
                                        <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">
                        {{ __('views.admin.purchase.order.lines.create.status') }}
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="status" name="status" class="select2" style="width: 100%" autocomplete="off">
                            <option value="" selected>--  Select Status --</option>
                                <option value="0">Not Compleled</option>
                                <option value="1">Complete</option>
                                <option value="2">Partial</option>
                                <option value="3">Canceled</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ URL::previous() }}"> {{ __('views.admin.purchase.order.lines.edit.cancel') }}</a>
                        <button type="submit" class="btn btn-success"> {{ __('views.admin.purchase.order.lines.edit.save') }}</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>



<script>
    function getProduct() {
        let select = document.getElementById('product');
        let product_price = document.getElementById('price');

        if(select.value === 'default') {
            product_price.value = '';
        } else {
            product_price.value = JSON.parse(select.value).price;
        }
    }
</script>

@endsection

@section('styles')
    @parent
    {{ Html::style(mix('assets/admin/css/users/edit.css')) }}
@endsection

@section('scripts')
    @parent
    {{ Html::script(mix('assets/admin/js/users/edit.js')) }}
@endsection
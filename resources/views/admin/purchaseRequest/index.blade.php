@extends('admin.layouts.admin')

@section('title', __('Purchase Request List'))

@section('content')
<div class="row" style="margin-top:5rem;">
        <div class="col-md-12">
            <a href="{{ route('admin.purchase.request.create') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-title="Add">
            <i class="fa fa-plus"></i>_Add Data
            </a>  
            <div class="pull-right">
                <div class="ui search">
                    <div class="ui icon input">
                        <input class="prompt" type="text" placeholder="Search...">
                        <i class="search icon"></i>
                    </div>
                    <div class="results"></div>
                </div>
            </div>
        </div>
    </div>                               
    <div class="row" style="margin-top:8px;">
        <table class="ui selectable celled padded table">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Date</th>
                    <th>Date Required</th>
                    <th>Customer</th>
                    <th>Vendor</th>
                    <th>Product</th>
                    <th>QTY</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseRequests as $purchaseRequest)
                <tr>
                    <td>{{$purchaseRequest->invoice_number}}</td>
                    <td>{{$purchaseRequest->date}}</td>
                    <td>{{$purchaseRequest->date_required}}</td>
                    <td>{{$purchaseRequest->user_id}}</td>
                    <td>{{$purchaseRequest->vendor_id}}</td>
                    <td>{{$purchaseRequest->product_id}}</td>
                    <td>{{$purchaseRequest->qty}}</td>
                    <td>
                        <a class="btn btn-xs btn-primary" href="{{ route('admin.purchase.request.show', [$purchaseRequest->id]) }}" data-toggle="tooltip" data-placement="top" data-title="{{ __('views.admin.users.index.show') }}">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a class="btn btn-xs btn-info" href="{{ route('admin.purchase.request.edit', [$purchaseRequest->id]) }}" data-toggle="tooltip" data-placement="top" data-title="{{ __('views.admin.users.index.edit') }}">
                            <i class="fa fa-pencil"></i>
                        </a>       
                        <a href="{{ route('admin.purchase.request.destroy', [$purchaseRequest->id]) }}" class="btn btn-xs btn-danger user_destroy" data-toggle="tooltip" data-placement="top" data-title="{{ __('views.admin.users.index.delete') }}">
                            <i class="fa fa-trash"></i>
                        </a>       
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="8">
                        <div class="pull-right">
                            {{$purchaseRequests->links()}}
                        </div>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@section('styles')
{{ Html::style(mix('assets/admin/css/admin.css')) }}
<link rel="stylesheet" type="text/css" class="ui" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/table.min.css">
<link rel="stylesheet" type="text/css" class="ui" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/icon.min.css">
<link rel="stylesheet" type="text/css" class="ui" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/input.min.css">
<link rel="stylesheet" type="text/css" class="ui" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/search.min.css">
@endsection

@section('scripts')
    {{ Html::script(mix('assets/admin/js/admin.js')) }}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js"></script>  
@endsection

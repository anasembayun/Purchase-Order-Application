@extends('admin.layouts.admin')

@section('title', __('views.purchase.order.line.show.title', ['name' => $purchaseOrderLines->invoice_number]))

@section('content')
    <div class="row">
        <table class="table table-striped table-hover">
            <tbody>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_0') }}</th>
                <td>{{ $purchaseOrderLines->invoice_number }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_1') }}</th>
                <td>{{ $purchaseOrderLines->date }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_2') }}</th>
                <td>{{ $purchaseOrderLines->date_required}}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_3') }}</th>
                <td>{{ $purchaseOrderLines->vendors->name }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_4') }}</th>
                <td>{{ $purchaseOrderLines->users->name }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_5') }}</th>
                <td>{{ $purchaseOrderLines->product->product_name }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_6') }}</th>
                <td>{{ $purchaseOrderLines->qty }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_7') }}</th>
                <td>{{ $purchaseOrderLines->price }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_8') }}</th>
                <td>{{ $purchaseOrderLines->discount }}%</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_9') }}</th>
                <td>{{ $purchaseOrderLines->ppn }}%</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_10') }}</th>
                <td>{{ $purchaseOrderLines->ppn_nominal }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_11') }}</th>
                <td>{{ $purchaseOrderLines->total }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_12') }}</th>
                <td>{{ $purchaseOrderLines->status }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_13') }}</th>
                <td>{{ $purchaseOrderLines->date }}</td>
            </tr>
            <tr>
                <th>{{ __('views.purchase.order.line.show.table_header_14') }}</th>
                <td>{{ $purchaseOrderLines->date }}</td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection

@extends('adminlte::page')

@section('title', 'Transactions')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Transactions</h1>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Transaction #</th>
                        <th>Order Type</th>
                        <th>Order #</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->transaction_number }}</td>
                            <td>
                                @if($transaction->package_order_id)
                                    <span class="badge badge-info">Package</span>
                                @elseif($transaction->service_order_id)
                                    <span class="badge badge-success">Service</span>
                                @else
                                    <span class="badge badge-secondary">Unknown</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->package_order_id)
                                    <a href="{{ route('admin.package-orders.show', $transaction->package_order_id) }}">
                                        #{{ $transaction->package_order_id }}
                                    </a>
                                @elseif($transaction->service_order_id)
                                    <a href="{{ route('admin.service-orders.show', $transaction->service_order_id) }}">
                                        #{{ $transaction->service_order_id }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td>BDT {{ number_format($transaction->amount) }}</td>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($transaction->payment_method) }}</span>
                            </td>
                            <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-group .btn {
            margin-right: 5px;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "order": [[0, "desc"]]
            });
        });
    </script>
@stop

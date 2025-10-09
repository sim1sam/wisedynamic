@extends('adminlte::page')

@section('title', 'Payment Audit Statistics')

@section('content_header')
    <h1>Payment Audit Statistics</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment Activity Overview</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.payment-audit.index') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> Back to Logs
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-credit-card"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Payment Attempts</span>
                                <span class="info-box-number">{{ $totalAttempts }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Successful Payments</span>
                                <span class="info-box-number">{{ $totalSuccesses }}</span>
                                @if($totalAttempts > 0)
                                    <span class="info-box-text">
                                        {{ round(($totalSuccesses / $totalAttempts) * 100, 1) }}% Success Rate
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Failed Payments</span>
                                <span class="info-box-number">{{ $totalFailures }}</span>
                                @if($totalAttempts > 0)
                                    <span class="info-box-text">
                                        {{ round(($totalFailures / $totalAttempts) * 100, 1) }}% Failure Rate
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Payment Activity (Last 30 Days)</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="paymentChart" style="min-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function() {
        const ctx = document.getElementById('paymentChart').getContext('2d');
        const chartData = @json($chartData);
        
        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@stop

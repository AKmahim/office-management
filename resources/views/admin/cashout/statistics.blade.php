@extends('layouts.admin')

@section('title')
    <title>Cash Out Statistics - Office Management</title>
@endsection

@section('style')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('cashout.index') }}">Cash Out</a></li>
                        <li class="breadcrumb-item active">Statistics</li>
                    </ol>
                </div>
                <h4 class="page-title">Cash Out Statistics</h4>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <h5 class="font-size-14">Total Cash Out</h5>
                        </div>
                        <div class="avatar-xs">
                            <span class="avatar-title rounded-circle bg-danger">
                                <i class="ri-money-dollar-circle-line"></i>
                            </span>
                        </div>
                    </div>
                    <h4 class="m-0 align-self-center text-danger">${{ number_format($totalCashOut, 2) }}</h4>
                    <p class="mb-0 mt-3 text-muted"><span class="text-danger">All Time</span></p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <h5 class="font-size-14">Total Records</h5>
                        </div>
                        <div class="avatar-xs">
                            <span class="avatar-title rounded-circle bg-info">
                                <i class="ri-file-list-3-line"></i>
                            </span>
                        </div>
                    </div>
                    <h4 class="m-0 align-self-center">{{ number_format($totalRecords) }}</h4>
                    <p class="mb-0 mt-3 text-muted"><span class="text-info">Transactions</span></p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <h5 class="font-size-14">Average Amount</h5>
                        </div>
                        <div class="avatar-xs">
                            <span class="avatar-title rounded-circle bg-warning">
                                <i class="ri-bar-chart-line"></i>
                            </span>
                        </div>
                    </div>
                    <h4 class="m-0 align-self-center">${{ number_format($averageAmount, 2) }}</h4>
                    <p class="mb-0 mt-3 text-muted"><span class="text-warning">Per Transaction</span></p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body">
                            <h5 class="font-size-14">This Month</h5>
                        </div>
                        <div class="avatar-xs">
                            <span class="avatar-title rounded-circle bg-success">
                                <i class="ri-calendar-line"></i>
                            </span>
                        </div>
                    </div>
                    @php
                        $thisMonth = $monthlyStats->where('month', date('n'))->first();
                        $thisMonthAmount = $thisMonth ? $thisMonth->total_amount : 0;
                    @endphp
                    <h4 class="m-0 align-self-center">${{ number_format($thisMonthAmount, 2) }}</h4>
                    <p class="mb-0 mt-3 text-muted"><span class="text-success">{{ date('F Y') }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Chart -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Monthly Cash Out Trends ({{ date('Y') }})</h4>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Payout Methods -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Payout Methods</h4>
                </div>
                <div class="card-body">
                    @forelse($payoutMethodStats as $index => $method)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle 
                                        @if($index == 0) bg-success
                                        @elseif($index == 1) bg-primary  
                                        @elseif($index == 2) bg-warning
                                        @else bg-secondary
                                        @endif">
                                        <i class="fe-credit-card"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ ucfirst(str_replace('_', ' ', $method->payout_method)) }}</h6>
                                    <small class="text-muted">${{ number_format($method->total_amount, 2) }} ({{ $method->count }} times)</small>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge 
                                    @if($index == 0) badge-success
                                    @elseif($index == 1) badge-primary
                                    @elseif($index == 2) badge-warning  
                                    @else badge-secondary
                                    @endif">
                                    {{ number_format(($method->total_amount / $totalCashOut) * 100, 1) }}%
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            <i class="ri-inbox-line" style="font-size: 48px;"></i>
                            <p>No payment methods found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Receivers -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top Receivers</h4>
                </div>
                <div class="card-body">
                    @forelse($topReceivers as $index => $receiver)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle 
                                        @if($index == 0) bg-danger
                                        @elseif($index == 1) bg-warning  
                                        @elseif($index == 2) bg-info
                                        @else bg-secondary
                                        @endif">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ Str::limit($receiver->reciever, 25) }}</h6>
                                    <small class="text-muted">${{ number_format($receiver->total_amount, 2) }}</small>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge 
                                    @if($index == 0) badge-danger
                                    @elseif($index == 1) badge-warning
                                    @elseif($index == 2) badge-info  
                                    @else badge-secondary
                                    @endif">
                                    {{ number_format(($receiver->total_amount / $totalCashOut) * 100, 1) }}%
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            <i class="ri-inbox-line" style="font-size: 48px;"></i>
                            <p>No receivers found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Sources -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top Expense Categories</h4>
                </div>
                <div class="card-body">
                    @forelse($topSources as $index => $source)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs mr-3">
                                    <span class="avatar-title rounded-circle 
                                        @if($index == 0) bg-success
                                        @elseif($index == 1) bg-primary  
                                        @elseif($index == 2) bg-warning
                                        @else bg-secondary
                                        @endif">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ Str::limit($source->source, 25) }}</h6>
                                    <small class="text-muted">${{ number_format($source->total_amount, 2) }}</small>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge 
                                    @if($index == 0) badge-success
                                    @elseif($index == 1) badge-primary
                                    @elseif($index == 2) badge-warning  
                                    @else badge-secondary
                                    @endif">
                                    {{ number_format(($source->total_amount / $totalCashOut) * 100, 1) }}%
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            <i class="ri-inbox-line" style="font-size: 48px;"></i>
                            <p>No expense categories found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Cash Outs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Recent Cash Out Records</h4>
                    <a href="{{ route('cashout.index') }}" class="btn btn-primary btn-sm">
                        <i class="fe-eye"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Source</th>
                                    <th>Amount</th>
                                    <th>Receiver</th>
                                    <th>Method</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCashOuts as $cashOut)
                                    <tr>
                                        <td>#{{ $cashOut->id }}</td>
                                        <td>{{ Str::limit($cashOut->source, 20) }}</td>
                                        <td class="text-danger font-weight-bold">${{ number_format($cashOut->amount, 2) }}</td>
                                        <td>{{ Str::limit($cashOut->reciever, 20) }}</td>
                                        <td><span class="badge badge-primary">{{ ucfirst($cashOut->payout_method) }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs mr-2">
                                                    <span class="avatar-title rounded-circle bg-danger">
                                                        {{ strtoupper(substr($cashOut->createdBy->name ?? 'U', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <span class="font-size-12">{{ $cashOut->createdBy->name ?? 'Unknown' }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $cashOut->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('cashout.show', $cashOut->id) }}" 
                                                   class="btn btn-info btn-sm" title="View">
                                                    <i class="fe-eye"></i>
                                                </a>
                                                <a href="{{ route('cashout.edit', $cashOut->id) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fe-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            No recent cash out records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12 text-center">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="mt-3">
                        <a href="{{ route('cashout.create') }}" class="btn btn-danger mr-2">
                            <i class="fe-plus"></i> Add New Cash Out
                        </a>
                        <a href="{{ route('cashout.index') }}" class="btn btn-primary mr-2">
                            <i class="fe-list"></i> View All Records
                        </a>
                        <button type="button" class="btn btn-info" onclick="window.print()">
                            <i class="fe-printer"></i> Print Statistics
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('this-page-js')
<script>
    // Monthly Chart
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    // Prepare chart data
    const chartData = Array(12).fill(0);
    @foreach($monthlyStats as $stat)
        chartData[{{ $stat->month - 1 }}] = {{ $stat->total_amount }};
    @endforeach
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Cash Out Amount ($)',
                data: chartData,
                backgroundColor: 'rgba(220, 53, 69, 0.2)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
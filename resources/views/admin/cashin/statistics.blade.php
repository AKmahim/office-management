@extends('layouts.admin')

@section('title')
    <title>Cash In Statistics - Office Management</title>
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
                        <li class="breadcrumb-item"><a href="{{ route('cashin.index') }}">Cash In</a></li>
                        <li class="breadcrumb-item active">Statistics</li>
                    </ol>
                </div>
                <h4 class="page-title">Cash In Statistics</h4>
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
                            <h5 class="font-size-14">Total Cash In</h5>
                        </div>
                        <div class="avatar-xs">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="ri-money-dollar-circle-line"></i>
                            </span>
                        </div>
                    </div>
                    <h4 class="m-0 align-self-center text-success">${{ number_format($totalCashIn, 2) }}</h4>
                    <p class="mb-0 mt-3 text-muted"><span class="text-success">All Time</span></p>
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
                    <h4 class="card-title">Monthly Cash In Trends ({{ date('Y') }})</h4>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Sources -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top Cash Sources</h4>
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
                                    <h6 class="mb-0">{{ Str::limit($source->source, 20) }}</h6>
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
                                    {{ number_format(($source->total_amount / $totalCashIn) * 100, 1) }}%
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            <i class="ri-inbox-line" style="font-size: 48px;"></i>
                            <p>No cash in records found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Cash Ins -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Recent Cash In Records</h4>
                    <a href="{{ route('cashin.index') }}" class="btn btn-primary btn-sm">
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
                                    <th>Note</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCashIns as $cashIn)
                                    <tr>
                                        <td>#{{ $cashIn->id }}</td>
                                        <td>{{ $cashIn->source }}</td>
                                        <td class="text-success font-weight-bold">${{ number_format($cashIn->amount, 2) }}</td>
                                        <td>{{ Str::limit($cashIn->note, 30) }}</td>
                                        <td>{{ $cashIn->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('cashin.show', $cashIn->id) }}" 
                                                   class="btn btn-info btn-sm" title="View">
                                                    <i class="fe-eye"></i>
                                                </a>
                                                <a href="{{ route('cashin.edit', $cashIn->id) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fe-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            No recent cash in records found.
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
                        <a href="{{ route('cashin.create') }}" class="btn btn-success mr-2">
                            <i class="fe-plus"></i> Add New Cash In
                        </a>
                        <a href="{{ route('cashin.index') }}" class="btn btn-primary mr-2">
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
                label: 'Cash In Amount ($)',
                data: chartData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
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
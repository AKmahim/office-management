@extends('layouts.admin')

@section('title')
    <title>Cash Out Management - Office Management</title>
@endsection

@section('content')
<div class="container-fluid my-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Cash Out Records</h3>
                    <div>
                        <a href="{{ route('cashout.create') }}" class="btn btn-danger">
                            <i class="fas fa-plus"></i> Add Cash Out
                        </a>
                        <a href="{{ route('cashout.statistics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Statistics
                        </a>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card-body">
                    <form method="GET" action="{{ route('cashout.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" class="form-control" 
                                       placeholder="From Date" 
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" class="form-control" 
                                       placeholder="To Date" 
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="payout_method" class="form-control">
                                    <option value="">All Methods</option>
                                    @foreach($payoutMethods as $method)
                                        <option value="{{ $method }}" 
                                                {{ request('payout_method') == $method ? 'selected' : '' }}>
                                            {{ ucfirst($method) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('cashout.index') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Summary -->
                    @if($cashOuts->count() > 0)
                    <div class="alert alert-warning">
                        <strong>Total Amount:</strong> ${{ number_format($totalAmount, 2) }} 
                        | <strong>Records:</strong> {{ $cashOuts->total() }}
                    </div>
                    @endif

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Source</th>
                                    <th>Amount</th>
                                    <th>Receiver</th>
                                    <th>Given By</th>
                                    <th>Method</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cashOuts as $cashOut)
                                <tr>
                                    <td>{{ $cashOut->id }}</td>
                                    <td>{{ Str::limit($cashOut->source, 30) }}</td>
                                    <td class="text-danger font-weight-bold">${{ number_format($cashOut->amount, 2) }}</td>
                                    <td>{{ Str::limit($cashOut->reciever, 25) }}</td>
                                    <td>{{ Str::limit($cashOut->given_by, 25) }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ ucfirst($cashOut->payout_method) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs mr-2">
                                                <span class="avatar-title rounded-circle bg-danger">
                                                    {{ strtoupper(substr($cashOut->createdBy->name ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                            <span class="font-size-14">{{ $cashOut->createdBy->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $cashOut->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('cashout.show', $cashOut->id) }}" 
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('cashout.edit', $cashOut->id) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('cashout.destroy', $cashOut->id) }}" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        No cash out records found.
                                        <a href="{{ route('cashout.create') }}">Add the first one</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $cashOuts->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title')
    <title>Cash In Management - Office Management</title>
@endsection

@section('content')
<div class="container-fluid my-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Cash In Records</h3>
                    <div>
                        <a href="{{ route('cashin.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Cash In
                        </a>
                        <a href="{{ route('cashin.statistics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Statistics
                        </a>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card-body">
                    <form method="GET" action="{{ route('cashin.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search source, amount, or note..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date_from" class="form-control" 
                                       placeholder="From Date" 
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date_to" class="form-control" 
                                       placeholder="To Date" 
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('cashin.index') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Summary -->
                    @if($cashIns->count() > 0)
                    <div class="alert alert-info">
                        <strong>Total Amount:</strong> ${{ number_format($totalAmount, 2) }} 
                        | <strong>Records:</strong> {{ $cashIns->total() }}
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
                                    <th>Note</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cashIns as $cashIn)
                                <tr>
                                    <td>{{ $cashIn->id }}</td>
                                    <td>{{ $cashIn->source }}</td>
                                    <td class="text-success font-weight-bold">${{ number_format($cashIn->amount, 2) }}</td>
                                    <td>{{ Str::limit($cashIn->note, 50) }}</td>
                                    <td>{{ $cashIn->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('cashin.show', $cashIn->id) }}" 
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('cashin.edit', $cashIn->id) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('cashin.destroy', $cashIn->id) }}" 
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
                                    <td colspan="6" class="text-center text-muted">
                                        No cash in records found.
                                        <a href="{{ route('cashin.create') }}">Add the first one</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $cashIns->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
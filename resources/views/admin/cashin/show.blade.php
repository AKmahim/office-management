@extends('layouts.admin')

@section('title')
    <title>Cash In Details - Office Management</title>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('cashin.index') }}">Cash In</a></li>
                        <li class="breadcrumb-item active">Details #{{ $cashIn->id }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Cash In Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Record #{{ $cashIn->id }}</h4>
                    <div class="btn-group" role="group">
                        <a href="{{ route('cashin.edit', $cashIn->id) }}" class="btn btn-warning btn-sm">
                            <i class="fe-edit-2"></i> Edit
                        </a>
                        <form action="{{ route('cashin.destroy', $cashIn->id) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this record? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fe-trash-2"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    
                    <!-- Display Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <!-- Main Details -->
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th scope="row" class="text-muted" style="width: 30%;">Source:</th>
                                            <td class="font-weight-bold">{{ $cashIn->source }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="text-muted">Amount:</th>
                                            <td>
                                                <span class="h4 text-success font-weight-bold">
                                                    ${{ number_format($cashIn->amount, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="text-muted">Note:</th>
                                            <td>
                                                @if($cashIn->note)
                                                    <div class="bg-light p-3 rounded">
                                                        {{ $cashIn->note }}
                                                    </div>
                                                @else
                                                    <em class="text-muted">No notes provided</em>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="text-muted">Added By:</th>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs mr-3">
                                                        <span class="avatar-title rounded-circle bg-primary">
                                                            {{ strtoupper(substr($cashIn->addedBy->name ?? 'U', 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $cashIn->addedBy->name ?? 'Unknown User' }}</h6>
                                                        <small class="text-muted">{{ $cashIn->addedBy->email ?? 'No email' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">
                                        <i class="fe-info"></i> Record Information
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Record ID</small>
                                        <strong>#{{ $cashIn->id }}</strong>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Created Date</small>
                                        <strong>{{ $cashIn->created_at->format('F j, Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $cashIn->created_at->format('g:i A') }}</small>
                                    </div>

                                    @if($cashIn->created_at != $cashIn->updated_at)
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Last Updated</small>
                                        <strong>{{ $cashIn->updated_at->format('F j, Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $cashIn->updated_at->format('g:i A') }}</small>
                                    </div>
                                    @endif

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Days Ago</small>
                                        <strong>{{ $cashIn->created_at->diffForHumans() }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="text-center">
                                <a href="{{ route('cashin.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fe-arrow-left"></i> Back to List
                                </a>
                                <a href="{{ route('cashin.edit', $cashIn->id) }}" class="btn btn-warning mr-2">
                                    <i class="fe-edit-2"></i> Edit Record
                                </a>
                                <a href="{{ route('cashin.create') }}" class="btn btn-primary">
                                    <i class="fe-plus"></i> Add New Cash In
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Card -->
    <div class="row mt-4">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fe-bar-chart-2"></i> Quick Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="bg-primary text-white p-3 rounded">
                                <h4 class="mb-1">${{ number_format($cashIn->amount, 0) }}</h4>
                                <p class="mb-0">Current Amount</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-info text-white p-3 rounded">
                                <h4 class="mb-1">{{ $cashIn->created_at->format('M Y') }}</h4>
                                <p class="mb-0">Month Added</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-success text-white p-3 rounded">
                                <h4 class="mb-1">{{ $cashIn->created_at->diffInDays(now()) + 1 }}</h4>
                                <p class="mb-0">Days Old</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('this-page-js')
<script>
    // Auto-hide success/error messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    });
</script>
@endsection
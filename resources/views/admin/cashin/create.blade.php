@extends('layouts.admin')

@section('title')
    <title>Add New Cash In - Office Management</title>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('cashin.index') }}">Cash In</a></li>
                        <li class="breadcrumb-item active">Add New</li>
                    </ol>
                </div>
                <h4 class="page-title">Add New Cash In</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cash In Details</h4>
                </div>
                <div class="card-body">
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Display Error Messages -->
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Create Form -->
                    <form action="{{ route('cashin.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="source" class="form-label">Source <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('source') is-invalid @enderror" 
                                   id="source" 
                                   name="source" 
                                   value="{{ old('source') }}" 
                                   placeholder="Enter cash source (e.g., Client Payment, Investment, etc.)"
                                   required>
                            @error('source')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                Specify where this money is coming from (max 255 characters)
                            </small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount" class="form-label">Amount ($) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" 
                                       name="amount" 
                                       value="{{ old('amount') }}" 
                                       step="0.01" 
                                       min="0.01" 
                                       placeholder="0.00"
                                       required>
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                Enter the amount in USD (minimum $0.01)
                            </small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" 
                                      name="note" 
                                      rows="4" 
                                      placeholder="Additional notes or description (optional)">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">
                                Optional additional information (max 1000 characters)
                            </small>
                        </div>

                        <div class="form-group mb-0">
                            <div class="text-right">
                                <a href="{{ route('cashin.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fe-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fe-save"></i> Save Cash In
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('this-page-js')
<script>
    // Auto-focus on source field when page loads
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('source').focus();
    });

    // Format amount input as user types
    document.getElementById('amount').addEventListener('input', function(e) {
        let value = e.target.value;
        if (value && !isNaN(value)) {
            // Optional: Add thousand separators or other formatting
        }
    });

    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const amount = document.getElementById('amount').value;
        const source = document.getElementById('source').value.trim();
        
        if (!source) {
            e.preventDefault();
            alert('Please enter a cash source.');
            document.getElementById('source').focus();
            return false;
        }
        
        if (!amount || amount <= 0) {
            e.preventDefault();
            alert('Please enter a valid amount greater than 0.');
            document.getElementById('amount').focus();
            return false;
        }
    });
</script>
@endsection
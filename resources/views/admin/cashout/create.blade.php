@extends('layouts.admin')

@section('title')
    <title>Add New Cash Out - Office Management</title>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('cashout.index') }}">Cash Out</a></li>
                        <li class="breadcrumb-item active">Add New</li>
                    </ol>
                </div>
                <h4 class="page-title">Add New Cash Out</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-md-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cash Out Details</h4>
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
                    <form action="{{ route('cashout.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="source" class="form-label">Source/Purpose <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('source') is-invalid @enderror" 
                                           id="source" 
                                           name="source" 
                                           value="{{ old('source') }}" 
                                           placeholder="e.g., Office Rent, Employee Salary, Equipment Purchase..."
                                           required>
                                    @error('source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
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
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="reciever" class="form-label">Receiver <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('reciever') is-invalid @enderror" 
                                           id="reciever" 
                                           name="reciever" 
                                           value="{{ old('reciever') }}" 
                                           placeholder="Who received the money (person/company name)"
                                           required>
                                    @error('reciever')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="given_by" class="form-label">Given By <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('given_by') is-invalid @enderror" 
                                           id="given_by" 
                                           name="given_by" 
                                           value="{{ old('given_by') }}" 
                                           placeholder="Who physically gave the money"
                                           required>
                                    @error('given_by')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payout_method" class="form-label">Payout Method <span class="text-danger">*</span></label>
                                    <select class="form-control @error('payout_method') is-invalid @enderror" 
                                            id="payout_method" 
                                            name="payout_method" 
                                            required>
                                        <option value="">Select payout method</option>
                                        <option value="cash" {{ old('payout_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('payout_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="check" {{ old('payout_method') == 'check' ? 'selected' : '' }}>Check</option>
                                        <option value="mobile_banking" {{ old('payout_method') == 'mobile_banking' ? 'selected' : '' }}>Mobile Banking</option>
                                        <option value="online_payment" {{ old('payout_method') == 'online_payment' ? 'selected' : '' }}>Online Payment</option>
                                        <option value="other" {{ old('payout_method') == 'other' ? 'selected' : '' }}>Other</option>
                                        @foreach($payoutMethods as $method)
                                            @if(!in_array($method, ['cash', 'bank_transfer', 'check', 'mobile_banking', 'online_payment', 'other']))
                                                <option value="{{ $method }}" {{ old('payout_method') == $method ? 'selected' : '' }}>
                                                    {{ ucfirst(str_replace('_', ' ', $method)) }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('payout_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" 
                                      name="note" 
                                      rows="4" 
                                      placeholder="Additional notes, invoice number, reference details...">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Optional additional information (max 1000 characters)
                            </small>
                        </div>

                        <div class="form-group mb-0">
                            <div class="text-right">
                                <a href="{{ route('cashout.index') }}" class="btn btn-secondary mr-2">
                                    <i class="fe-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fe-save"></i> Save Cash Out
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

    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const amount = document.getElementById('amount').value;
        const source = document.getElementById('source').value.trim();
        const receiver = document.getElementById('reciever').value.trim();
        const givenBy = document.getElementById('given_by').value.trim();
        const payoutMethod = document.getElementById('payout_method').value;
        
        if (!source) {
            e.preventDefault();
            alert('Please enter the source/purpose.');
            document.getElementById('source').focus();
            return false;
        }
        
        if (!amount || amount <= 0) {
            e.preventDefault();
            alert('Please enter a valid amount greater than 0.');
            document.getElementById('amount').focus();
            return false;
        }

        if (!receiver) {
            e.preventDefault();
            alert('Please enter the receiver name.');
            document.getElementById('reciever').focus();
            return false;
        }

        if (!givenBy) {
            e.preventDefault();
            alert('Please enter who gave the money.');
            document.getElementById('given_by').focus();
            return false;
        }

        if (!payoutMethod) {
            e.preventDefault();
            alert('Please select a payout method.');
            document.getElementById('payout_method').focus();
            return false;
        }
    });
</script>
@endsection
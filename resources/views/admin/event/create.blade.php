@extends('layouts.admin')

@section('title')
    <title>Create Event</title>
@endsection

@section('content')
<!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Event</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('event.create') }}">Event Create</a></li>
                                
                            </ol>
                        </div>
                        <h4 class="page-title">Event Create</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="header-title"><b>Create Event</b></h4>
                        <p class="sub-header">
                            {{-- Create Event --}}
                        </p>
                        <form action="{{ route('event.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                            {{-- CSRF token for security --}}
                            @csrf
                            <div class="form-group">
                                <label for="name">Event Name <span style="color: red;">*</span></label>
                                <input type="text" name="event_name" class="form-control" id="name" name="name" placeholder="Enter event name" required>
                            </div>
                            <div class="form-group">
                                <label for="Company Name">Company Name</label>
                                <textarea class="form-control" id="company_name" name="company_name" rows="4" placeholder="Enter company name"></textarea>
                            </div>
                            {{-- submission button --}}
                            <div class="form-group">
                                <button class="btn btn-success " type="submit" >Submit</button>
                            </div>
                            
                        </form>
                    </div>
            </div>

@endsection
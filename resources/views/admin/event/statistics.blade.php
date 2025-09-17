@extends('layouts.admin')

@section('title')
    <title>Event Statistics</title>

@endsection
@section('style')
    <!-- Include the flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Include the flatpickr JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('content')


                {{-- =============== Event date wise filter option =============== --}}
                {{-- <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="header-title"><b>Event Statistics Filter</b></h4>
                            
                        </div>

                </div> --}}

                <div class="row mt-4">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="header-title"><b>Event Statistics Filter</b></h4>
                                 <form class="mt-4" action="{{ route('event.statistics.filter') }}" method="GET" >
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                           {{-- <h3>Filter By Date:</h3> --}}
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input name="start_date" type="text" class="form-control flatpickr" style="" placeholder="Start Date" data-date-format="d-m-Y" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input name="end_date" type="text" class="form-control flatpickr" placeholder="End Date" data-date-format="d-m-Y" required>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $event_list = App\Models\Event::latest()->get();
                                                        
                                                    @endphp
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <select name="event_id" class="custom-select mr-sm-2" id="inlineFormCustomSelect" required>
                                                                <option disabled selected value="">Select Event</option>
                                                                @foreach ($event_list as $item)
                                                                    <option value="{{ $item->event_id }}" >
                                                                        {{ $item->event_name }} - {{ $item->event_id }}
                                                                        
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-info">Search</button>
                                                            {{-- <input type="submit"  class="form-control" class="btn btn-info" placeholder="search.."> --}}
            
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
    
                                            
                                            
    
                                            
                                        </div>
                                        
                                    </div>
                                </form>
                               
                            </div>
                        </div>





                        @isset($events)
                            <div class="col-12">
                                <div class="card-box table-responsive">
                                    <h4 class="header-title"><b>Event Statistics</b></h4>
                                    <p class="sub-header">
                                        {{-- Event List --}}
                                    </p>

                                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Date</th>
                                            <th>Event Name</th>
                                            <th>Event ID</th>
                                            <th>Total Participants</th>

                                        </tr>
                                        </thead>
                                        <tbody> 
                                        @isset($events)
                                            @foreach ($events as $key => $event)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $event['date'] }}</td>
                                                    <td>{{ $event['event_name'] }}</td>
                                                    <td>{{ $event['event_id'] }}</td>
                                                    <td>{{ $event['total_participants'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endisset
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endisset
                </div>
@endsection



@section('this-page-js')
     <!-- Required datatable js -->
        <script src="{{ asset('admin') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="{{ asset('admin') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Buttons examples -->
        <script src="{{ asset('admin') }}/assets/libs/datatables/dataTables.buttons.min.js"></script>
        <script src="{{ asset('admin') }}/assets/libs/datatables/buttons.bootstrap4.min.js"></script>
        <script src="{{ asset('admin') }}/assets/libs/jszip/jszip.min.js"></script>
        <script src="{{ asset('admin') }}/assets/libs/pdfmake/pdfmake.min.js"></script>
        <script src="{{ asset('admin') }}/assets/libs/pdfmake/vfs_fonts.js"></script>
        <script src="{{ asset('admin') }}/assets/libs/datatables/buttons.html5.min.js"></script>
        <script src="{{ asset('admin') }}/assets/libs/datatables/buttons.print.min.js"></script>

        <!-- Responsive examples -->
        <script src="{{ asset('admin') }}/assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="{{ asset('admin') }}/assets/libs/datatables/responsive.bootstrap4.min.js"></script>


        <script>
            $(document).ready(function () {
                $("#datatable").DataTable(), $("#datatable-buttons").DataTable({
                    lengthChange: !1,
                    buttons: ["copy", "excel", "pdf"]
                }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)")
            });

            // Initialize flatpickr for date inputs
            flatpickr('.flatpickr', {
                dateFormat: "d-m-Y", // Set the date format to d/m/Y
                allowInput: true, // Allow manual input
                altInput: true, // Use an alternative input field
                altFormat: "d-m-Y", // Format for the alternative input
                // minDate: "today" // Set minimum date to today
            });
            // flatpickr('.flatpickr');
        </script>

        
@endsection
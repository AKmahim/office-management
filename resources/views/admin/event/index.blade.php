@extends('layouts.admin')
@section('content')

<!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Event</a></li>
                                <li class="breadcrumb-item active"><a href="{{ route('event.index') }}">Event List</a></li>
                                
                            </ol>
                        </div>
                        <h4 class="page-title">Event List</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->




                <div class="row">
                        <div class="col-12">
                            <div class="card-box table-responsive">
                                <h4 class="header-title"><b>Event List</b></h4>
                                <p class="sub-header">
                                    {{-- Event List --}}
                                </p>

                                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Event Name</th>
                                        <th>Event ID</th>
                                        <th>Total Participants</th>
                                        <th>Company Name</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody> 
                                    @foreach ($events as $key => $event)
                                    <tr>
                                        @php
                                            $event->company_name = $event->company_name ? $event->company_name : 'N/A';
                                            $total_participants = App\Models\Content::where('event_id', $event->event_id)->count();
                                        @endphp
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $event->event_name }}</td>
                                        <td>{{ $event->event_id }}</td>
                                        <td>{{ $total_participants }}</td>
                                        <td>{{ $event->company_name }}</td>
                                        <td>{{ $event->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ url('event/edit/'.$event->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="{{ url('event/destroy/'.$event->id) }}" onclick="alert('are you sure?')" class="btn btn-danger btn-sm">Delete</a>
                                            
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
                    <!-- end row -->




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
        </script>
@endsection
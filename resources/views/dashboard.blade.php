@extends('layouts.admin')
@section('title')
    <title>Dashboard</title>
    
@endsection

@section('style')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection


@section('content')
<!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                              
                            </ol>
                        </div>
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

<div class="row">

    @php
        $total_events = \App\Models\Event::count();
        $total_contents = \App\Models\Content::count();
        $today_contents = \App\Models\Content::whereDate('created_at', today())->count();
    @endphp


    <div class="col-md-6 col-xl-4">
        <div class="card-box tilebox-one">
            <i class="fe-layers float-right"></i>
            <h5 class="text-muted text-uppercase mb-3 mt-0">Total Events</h5>
            <h3 class="mb-3"><span data-plugin="counterup">{{ $total_events }}</span></h3>
            {{-- <span class="badge badge-primary"> -29% </span> <span class="text-muted ml-2 vertical-middle">From previous
                period</span> --}}
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card-box tilebox-one">
            <i class="fe-tag float-right"></i>
            <h5 class="text-muted text-uppercase mb-3 mt-0">Total Content</h5>
            <h3 class="mb-3"><span data-plugin="counterup">{{ $total_contents }}</span></h3>
            
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card-box tilebox-one">
            <i class="fe-briefcase float-right"></i>
            <h5 class="text-muted text-uppercase mb-3 mt-0">Today Content</h5>
            <h3 class="mb-3" data-plugin="counterup">{{ $today_contents }}</h3>
           
        </div>
    </div>
</div>

{{-- ========================= storage pie chart ========================= --}}

 <div class="row">
     <div class="col-lg-12">
         <div class="card-box">
             <h4 class="header-title">Server Capacity (in GB)</h4>
             <div class="d-flex justify-content-center">
                <canvas id="storageChart" class="mt-4" width="400" height="400"></canvas>
            </div>
         </div>
     </div>

 </div>

{{-- ========================= storage pie chart ========================= --}}
<div class="row">
    
    <div class="col-xl-12">
        <div class="card-box">
            <h4 class="header-title">Content Statistics Bar</h4>

            <div class="row">
                

            <div style="width:100%; ">
                <canvas id="content-history" height="350"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- end row -->


@endsection


@section('this-page-js')

<script>
$(document).ready(function () {
    $.ajax({
        url: '/dashboard/storage-statistics',
        method: 'GET',
        success: function (data) {
            function parseGB(str) {
                if (!str) return 0;
                let num = parseFloat(str);
                if (str.includes('TB')) num *= 1024;
                if (str.includes('MB')) num /= 1024;
                if (str.includes('KB')) num /= (1024 * 1024);
                return num;
            }
            let free = parseGB(data.free_storage);
            let used = parseGB(data.used_storage);

            // Only destroy if it's a Chart instance
            if (window.storageChart && typeof window.storageChart.destroy === "function") {
                window.storageChart.destroy();
            }

            const ctx = document.getElementById('storageChart').getContext('2d');
            window.storageChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Free Space', 'Used Space'],
                    datasets: [{
                        label: 'Storage Capacity',
                        data: [free, used],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 99, 132, 0.6)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        title: {
                            display: true,
                            text: 'Server Storage Capacity'
                        }
                    }
                }
            });
        }
    });
});
</script>

 <!-- Vendor js -->
<script src="{{ asset('admin') }}/assets/js/vendor.min.js"></script>


<!-- Chart JS -->
<script src="{{ asset('admin') }}/assets/libs/chart-js/Chart.bundle.min.js"></script>
<!-- Init js -->
<script src="{{ asset('admin') }}/assets/js/pages/dashboard.init.js?v={{ time() }}"></script>

@endsection
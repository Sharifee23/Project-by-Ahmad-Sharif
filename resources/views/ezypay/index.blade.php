@include('ezypay.include.css')

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

    @include('ezypay.include.sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">


            <div class="container-fluid">
                <div class="row">
                    <div class="col">

                        <div class="h-100">
                            <div class="row mb-3 pb-1">
                                <div class="col-12">
                                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                        <div class="flex-grow-1">
                                            <h4 class="fs-16 mb-1">Good Morning,</h4>
                                            <p class="text-muted mb-0">Here's what's happening today.</p>
                                        </div>
                                        <div class="mt-3 mt-lg-0">
                                            <form action="javascript:void(0);">
                                                <div class="row g-3 mb-0 align-items-center">
                                                    <!--end col-->
                                                    <div class="col-auto">
                                                        @can('record-create')
                                                        <a href="{{route('records/add')}}"><button type="button" class="btn btn-soft-success material-shadow-none"><i class="ri-add-circle-line align-middle me-1"></i> Add Record</button></a>
                                                        @endcan
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                                <!--end row-->
                                            </form>
                                        </div>
                                    </div><!-- end card header -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->

                            <div class="row">
                                @can('dasboard-cards')
                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Products</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                        <!--<i class="ri-arrow-right-up-line fs-13 align-middle"></i>-->
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$product}}">0</span></h4>
                                                    @can('product-list')
                                                    <a href="{{route('product')}}" class="text-decoration-underline">View all Products</a>
                                                    @endcan
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                                        <i class="bx bx-dollar-circle text-success"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->

                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Markets</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-danger fs-14 mb-0">
                                                        <!--<i class="ri-arrow-right-down-line fs-13 align-middle"></i> -3.57 %-->
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$market}}">0</span></h4>
                                                    @can('market-list')
                                                    <a href="{{route('markets')}}" class="text-decoration-underline">View all Markets</a>
                                                    @endcan
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                                        <i class="bx bx-shopping-bag text-info"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->

                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">States</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <h5 class="text-success fs-14 mb-0">
                                                        <!--<i class="ri-arrow-right-up-line fs-13 align-middle"></i> +29.08 %-->
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$state}}">0</h4>
                                                    @can('state-list')
                                                    <a href="{{route('states')}}" class="text-decoration-underline">View all States</a>
                                                    @endcan
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-warning-subtle rounded fs-3">
                                                        <i class="bx bx-map text-warning"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->

                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Users</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <!--<h5 class="text-muted fs-14 mb-0">
                                                        +0.00 %
                                                    </h5>-->
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$user}}">0</span></h4>
                                                    @can('user-list')
                                                    <a href="{{route('users')}}" class="text-decoration-underline">View Details</a>
                                                    @endcan
                                                </div>
                                                
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                        <i class="bx bx-user-circle text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->
                                @endcan
                            </div> <!-- end row-->

                            <div class="row">
                                <div class="col-12">
                                    @can('price-trend-chart')
                                    <div class="card">
                                        <!-- Upper Card Header -->
                                        <div class="card-header border-0 d-flex justify-content-between align-items-center p-1 m-1">
                                            <h4 class="card-title mb-0">Price Trend Chart</h4>
                                            
                                            <!-- Compare Markets Dropdown -->
                                            <div class="mb-3">
                                                <label for="compareMarketsDropdown" class="form-label">Compare Markets</label>
                                                <select id="compareMarketsDropdown" class="form-multi-select" multiple>
                                                    @foreach ($markets as $market)
                                                        <option value="{{ $market->id }}">{{ $market->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- End Upper Header -->

                                        <!-- Lower Card Header -->
                                        <div class="card-header border-0 bg-light-subtle d-flex flex-wrap align-items-center">
                                            <!-- Product Dropdown -->
                                            <div class="mb-3 me-3">
                                                <label for="productDropdown" class="form-label">Select Product</label>
                                                <select id="productDropdown" class="form-select">
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ $product->id == $firstProduct->id ? 'selected' : '' }}>
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Main Market Dropdown -->
                                            <div class="mb-3 me-3">
                                                <label for="marketDropdown" class="form-label">Select Market</label>
                                                <select id="marketDropdown" class="form-select">
                                                    @foreach ($markets as $market)
                                                        <option value="{{ $market->id }}" {{ $market->id == optional($firstMarket)->id ? 'selected' : '' }}>
                                                            {{ $market->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <!-- Filter Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Filter
                                                </button>
                                                <ul class="dropdown-menu p-3" aria-labelledby="dropdownMenuButton">
                                                    <li><a class="dropdown-item" href="#" data-value="daily">Daily</a></li>
                                                    <li><a class="dropdown-item" href="#" data-value="weekly">Weekly</a></li>
                                                    <li><a class="dropdown-item" href="#" data-value="monthly">Monthly</a></li>
                                                    <li><a class="dropdown-item" href="#" data-value="yearly">Yearly</a></li>
                                                    <li><a class="dropdown-item" href="#" data-value="all">All</a></li>
                                                    <li class="px-3">
                                                        <input type="text" id="dateRangePicker" class="form-control" placeholder="Custom" />
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- End Lower Header -->

                                        <!-- Card Body -->
                                        <div class="card-body bg-light">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="chart-container">
                                                        <canvas id="lineChart"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Card Body -->
                                    </div>
                                    @endcan
                                </div>
                            </div>

                            <div class="row">
                                @can('recent-activities')
                                <div class="col-xl-8">
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex bg-primary">
                                            <h4 class="card-title mb-0 flex-grow-1 text-white">Recent Activities</h4>
                                        </div><!-- end card header -->

                                        <div class="card-body">
                                            <div class="table-responsive table-card">
                                                <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                                    <thead>
                                                        <th>User</th>
                                                        <th>Action</th>
                                                        <th>Table</th>
                                                        <th>Date</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($activities as $a)
                                                        <tr>
                                                            <td>
                                                                {{$a->user->name}}
                                                            </td>
                                                            <td>
                                                                {{$a->action}}
                                                            </td>
                                                            <td>
                                                                {{$a->table_name}}
                                                            </td>
                                                            <td>
                                                                {{$a->timestamp}}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="align-items-center mt-4 pt-2 justify-content-between row text-center text-sm-start">
                                                <div class="col-sm">
                                                    <div class="text-muted">
                                                        <a href="{{ route('user.activities') }}">View More</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endcan
                                @can('recent-record')
                                <div class="col-xl-4">
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex bg-primary">
                                            <h4 class="card-title mb-0 flex-grow-1 text-white">Recent Added Records</h4>
                                        </div><!-- end card header -->

                                        <div class="card-body">
                                            <div class="table-responsive table-card">
                                                <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                                    <thead>
                                                        <th>Product</th>
                                                        <th>Market</th>
                                                        <th>Price</th>
                                                        <th>Date</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($records as $r)
                                                        <tr>
                                                            <td>
                                                                {{$r->product->name}}
                                                            </td>
                                                            <td>
                                                                {{$r->market->name}}
                                                            </td>
                                                            <td>
                                                                {{$r->price}}
                                                            </td>
                                                            <td>
                                                                {{$r->created_at}}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="align-items-center mt-4 pt-2 justify-content-between row text-center text-sm-start">
                                                <div class="col-sm">
                                                    <div class="text-muted">
                                                        <a href="{{ route('prices') }}">View More</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div> <!-- .col-->
                                @endcan
                            </div> <!-- end row-->


            
                            
                            <div class="row">
                                @can('competative-price')
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Competative Prices</h4>

                                            <div class="flex-shrink-0">
                                                <div class="dropdown card-header-dropdown">
                                                    <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="fw-semibold text-uppercase fs-12">View More
                                                    </a>
                                                </div>
                                            </div>
                                        </div><!-- end card header -->

                                        <div class="card-body">
                                            <div class="table-responsive table-card">
                                                <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                                    <thead>
                                                        <th>Product</th>
                                                        <th>Market</th>
                                                        <th>Price</th>
                                                        <th>Date</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($c_price as $r)
                                                        <tr>
                                                            <td>
                                                                {{$r->product->name}}
                                                            </td>
                                                            <td>
                                                                {{$r->market->name}}
                                                            </td>
                                                            <td>
                                                                {{$r->price}}
                                                            </td>
                                                            <td>
                                                                {{$r->created_at}}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="align-items-center mt-4 pt-2 justify-content-between row text-center text-sm-start">
                                                <div class="col-sm">
                                                    <div class="text-muted">
                                                        <a href="{{ route('prices') }}">View More</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endcan
                            </div> <!-- end row-->
                            


                        </div> <!-- end .h-100-->

                    </div> 
            </div>


                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © Ezypay.
                        </div>
                        <div class="col-sm-6">
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>


<script>
let productDropdown = document.getElementById('productDropdown');
let marketDropdown = document.getElementById('marketDropdown');
let compareMarketsDropdown = $('#compareMarketsDropdown');
let filterDropdownItems = document.querySelectorAll('.dropdown-item');
let dateRangePicker = $('#dateRangePicker');
let ctx = document.getElementById('lineChart').getContext('2d');
let selectedFilter = 'all'; // Default filter
let startDate = null, endDate = null; // Variables for date range

function getRandomColor() {
    return 'hsl(' + Math.random() * 360 + ', 80%, 60%)';
}

let lineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true },
            tooltip: { enabled: true }
        },
        scales: {
            x: { grid: { display: true } },
            y: { grid: { display: true }, beginAtZero: true }
        }
    }
});

function loadMarkets(productId, callback) {
    marketDropdown.innerHTML = '<option value="">Loading...</option>';
    compareMarketsDropdown.empty();

    fetch(`get-markets?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            marketDropdown.innerHTML = '';
            compareMarketsDropdown.empty();

            data.forEach((market, index) => {
                let option = document.createElement('option');
                option.value = market.id;
                option.textContent = market.name;
                if (index === 0) option.selected = true;
                marketDropdown.appendChild(option);

                compareMarketsDropdown.append(new Option(market.name, market.id));
            });

            if (callback) callback();
        })
        .catch(error => console.error('Error fetching markets:', error));
}

function updateChart() {
    let productId = productDropdown.value;
    let marketId = marketDropdown.value;
    let compareMarketIds = compareMarketsDropdown.val() || [];

    if (!productId || !marketId) return;

    let url = `get-chart-data?product_id=${productId}&market_id=${marketId}&filter=${selectedFilter}`;

    if (selectedFilter === 'custom' && startDate && endDate) {
        url += `&start_date=${startDate}&end_date=${endDate}`;
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            let allDates = data.allDates.slice(-15);
            let priceData = data.values.slice(-15);

            lineChart.data.labels = allDates;
            lineChart.data.datasets = [{
                label: `Main Market (${marketDropdown.selectedOptions[0].text})`,
                data: priceData,
                borderColor: 'blue',
                borderWidth: 2,
                fill: false,
                pointRadius: 3,
                pointBackgroundColor: 'blue'
            }];

            let compareFetches = compareMarketIds.map(marketId =>
                fetch(`get-chart-data?product_id=${productId}&market_id=${marketId}&filter=${selectedFilter}`)
                    .then(response => response.json())
                    .then(compareData => {
                        lineChart.data.datasets.push({
                            label: `Market (${document.querySelector('#compareMarketsDropdown option[value="' + marketId + '"]').textContent})`,
                            data: compareData.values.slice(-15),
                            borderColor: getRandomColor(),
                            borderWidth: 2,
                            fill: false,
                            pointRadius: 3,
                            pointBackgroundColor: getRandomColor()
                        });
                    })
                    .catch(error => console.error(`Error fetching market ${marketId} data:`, error))
            );

            Promise.all(compareFetches).then(() => {
                lineChart.update();
            });
        })
        .catch(error => console.error('Error fetching chart data:', error));
}

filterDropdownItems.forEach(item => {
    item.addEventListener('click', function () {
        selectedFilter = this.getAttribute('data-value');

        if (selectedFilter !== 'custom') {
            // Reset date picker when changing filters (except custom)
            startDate = null;
            endDate = null;
            dateRangePicker.val('');
        }

        updateChart();
    });
});

window.addEventListener('DOMContentLoaded', updateChart);
productDropdown.addEventListener('change', function () {
    loadMarkets(this.value, updateChart);
});
marketDropdown.addEventListener('change', updateChart);
compareMarketsDropdown.on('change', updateChart);

$(document).ready(function() {
    compareMarketsDropdown.select2({
        placeholder: "Select markets to compare",
        allowClear: true
    });

    $('#dateRangePicker').daterangepicker({
        autoUpdateInput: false,
        locale: { cancelLabel: 'Clear' }
    });

    $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
        startDate = picker.startDate.format('YYYY-MM-DD');
        endDate = picker.endDate.format('YYYY-MM-DD');
        $(this).val(startDate + ' - ' + endDate);
        selectedFilter = 'custom'; // Ensure filter is set to custom when a date range is applied
        updateChart();
    });

    $('#dateRangePicker').on('cancel.daterangepicker', function() {
        $(this).val('');
        startDate = null;
        endDate = null;
        updateChart();
    });
});

</script>














    <!-- JAVASCRIPT 
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- apexcharts -->
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Vector map-->
    <script src="assets/libs/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/libs/jsvectormap/maps/world-merc.js"></script>

    <!--Swiper slider js-->
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>

    <!-- Dashboard init -->
    <script src="assets/js/pages/dashboard-ecommerce.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>
</body>


<!-- Mirrored from themesbrand.com/velzon/html/master/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Nov 2024 09:50:54 GMT -->
</html>
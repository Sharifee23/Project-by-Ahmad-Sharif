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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-2">
                                <h4 class="card-title mb-0">Product History - {{ $product->name }}</h4>
                                <div>
                                    <select id="filterDropdown" class="form-select">
                                        <option value="all">All</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="bar_chart" data-colors='["--vz-success"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!-- end card -->
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-2">
                                <h4 class="card-title mb-0">Product - {{ $product->name }}</h4> 
                            </div>

                            <div class="card-body">
                                <table id="myTable" class="display">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Market</th>
                                            <th>Price</th>
                                            <th>Record Date</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div><!-- end card -->
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

    <!-- JAVASCRIPT -->
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let productId = document.getElementById('product_id').value; // Get product ID from hidden field
            let marketDropdown = document.getElementById('marketDropdown');
            let compareMarketsDropdown = $('#compareMarketsDropdown');
            let filterDropdownItems = document.querySelectorAll('.dropdown-item');
            let ctx = document.getElementById('lineChart').getContext('2d');
            let selectedFilter = 'all'; // Default filter

            function getRandomColor() {
                return 'hsl(' + Math.random() * 360 + ', 80%, 60%)';
            }

            let lineChart = new Chart(ctx, {
                type: 'line',
                data: { labels: [], datasets: [] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true }, tooltip: { enabled: true } },
                    scales: { x: { grid: { display: true } }, y: { grid: { display: true }, beginAtZero: true } }
                }
            });

            function loadMarkets(callback) {
                marketDropdown.innerHTML = '<option value="">Loading...</option>';
                compareMarketsDropdown.empty();

                fetch(`/get-markets/${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        marketDropdown.innerHTML = '';
                        compareMarketsDropdown.empty();

                        if (data.length === 0) {
                            marketDropdown.innerHTML = '<option value="">No Markets Available</option>';
                            return;
                        }

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
                let marketId = marketDropdown.value;
                let compareMarketIds = compareMarketsDropdown.val() || [];
                if (!marketId) return;

                fetch(`/get-chart-data/${productId}?market_id=${marketId}&filter=${selectedFilter}`)
                    .then(response => response.json())
                    .then(data => {
                        let allDates = data.allDates.slice(-15);
                        let priceData = data.values.slice(-15);

                        lineChart.data.labels = allDates;
                        lineChart.data.datasets = [{
                            label: `Main Market (${marketDropdown.selectedOptions[0]?.text || 'N/A'})`,
                            data: priceData,
                            borderColor: 'blue',
                            borderWidth: 2,
                            fill: false,
                            pointRadius: 3,
                            pointBackgroundColor: 'blue'
                        }];

                        let compareFetches = compareMarketIds.map(marketId =>
                            fetch(`/get-chart-data/${productId}?market_id=${marketId}&filter=${selectedFilter}`)
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
                    updateChart();
                });
            });

            loadMarkets(updateChart);
            marketDropdown.addEventListener('change', updateChart);
            compareMarketsDropdown.on('change', updateChart);

            $(document).ready(function() {
                compareMarketsDropdown.select2({
                    placeholder: "Select markets to compare",
                    allowClear: true
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function fetchChartData(filter) {
                fetch(`/product-prices/{{ $product->id }}?filter=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        let categories = data.map(item => item.market);
                        let seriesData = data.map(item => parseFloat(item.avg_price));

                        // Define a color palette
                        const colorPalette = [
                            "#34c38f", "#ff4560", "#008ffb", "#feb019", "#775dd0",
                            "#00e396", "#ff66c3", "#3b5998", "#f64e60", "#2a9d8f"
                        ];

                        var options = {
                            chart: { height: 350, type: "bar", toolbar: { show: false } },
                            plotOptions: { 
                                bar: { 
                                    horizontal: true,
                                    distributed: true // Ensures each bar gets a different color
                                } 
                            },
                            dataLabels: { enabled: false },
                            series: [{ name: "Price", data: seriesData }],
                            colors: colorPalette.slice(0, seriesData.length), // Assign different colors
                            grid: { borderColor: "#f1f1f1" },
                            xaxis: { categories: categories }
                        };

                        var chart = new ApexCharts(document.querySelector("#bar_chart"), options);
                        chart.render();
                    });
            }

            // Load chart initially
            fetchChartData('all');

            // Listen for dropdown change
            document.getElementById("filterDropdown").addEventListener("change", function () {
                fetchChartData(this.value);
            });
        });



        $(document).ready(function () {
            var productId = @json($product->id); // Injecting the product ID from Laravel to JS

            var table = $('#myTable').DataTable({
                "ajax": {
                    "url": "/getallprecords/" + productId, // Dynamic URL based on product ID
                    "type": "GET",
                    "dataType": "json",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": function (response) {
                        return response.status === 200 ? response.data : [];
                    }
                },
                "columns": [
                    { "data": "id" },
                    { "data": "product_name" },
                    { "data": "market_name" },
                    { "data": "price" },
                    { "data": "recorded_date" }
                ]
            });
        });


    </script>


    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- apexcharts -->
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

    <!-- barcharts init -->
    <script src="assets/js/pages/apexcharts-bar.init.js"></script>

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
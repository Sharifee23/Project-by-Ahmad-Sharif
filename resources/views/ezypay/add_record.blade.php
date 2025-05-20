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
                    <div class="col-md-12">
                        <div class="container p-3">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Add Records</h2>
                                </div>
                                <div class="card-body">
                                    <form id="add-record-form" action="{{ route('records.store') }}" method="POST">
                                        @csrf
                                        <div id="record-container">
                                            <!-- Initial record input fields will go here -->
                                            <div class="record-row" id="record-1">
                                                <div class="form-group">
                                                    <label for="product_id_1">Product</label>
                                                    <select class="form-control" name="product_id[]" id="product_id_1" required>
                                                        <option value="">Select Product</option>
                                                        @foreach($product as $p)
                                                        <option value="{{$p->id}}">{{$p->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="market_id_1">Market</label>
                                                    <select class="form-control" name="market_id[]" id="market_id_1" required>
                                                        <option value="">Select Market</option>
                                                        @foreach($market as $m)
                                                        <option value="{{$m->id}}">{{$m->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label for="price_1">Price</label>
                                                    <input type="number" class="form-control" name="price[]" id="price_1" required>
                                                </div>
                                                <button type="button" class="btn btn-danger remove-record" data-id="1">Remove</button>
                                            </div><br>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-more">Add More</button>
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        let recordCount = 1;

        // Function to add a new record
        $('#add-more').click(function() {
            recordCount++;
            const newRecord = `
                <div class="record-row" id="record-${recordCount}">
                    <div class="form-group">
                        <label for="product_id_${recordCount}">Product</label>
                        <select class="form-control" name="product_id[]" id="product_id_${recordCount}" required>
                            <option value="">Select Product</option>
                            @foreach($product as $p)
                            <option value="{{$p->id}}">{{$p->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="market_id_${recordCount}">Market</label>
                        <select class="form-control" name="market_id[]" id="market_id_${recordCount}" required>
                            <option value="">Select Market</option>
                            @foreach($market as $m)
                            <option value="{{$m->id}}">{{$m->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="price_${recordCount}">Price</label>
                        <input type="number" class="form-control" name="price[]" id="price_${recordCount}" required>
                    </div>
                    <button type="button" class="btn btn-danger remove-record" data-id="${recordCount}">Remove</button>
                </div>
            `;
            $('#record-container').append(newRecord);
        });

        // Function to remove a specific record
        $(document).on('click', '.remove-record', function() {
            const recordId = $(this).data('id');
            $(`#record-${recordId}`).remove();
        });

        // Submit the form via AJAX
        $('#add-record-form').submit(function(e) {
            e.preventDefault();

            let formData = $(this).serialize(); // Serialize the form data

            $.ajax({
                url: '{{ route('records.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    window.location.href = "{{ url('/prices')}}";
                    toastr.success('Record added successfully');
                },
                error: function(xhr, status, error) {
                    toastr.success('An error occurred. Please try again.');
                }
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
</html>
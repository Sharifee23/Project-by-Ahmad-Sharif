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
                        <div class="card mt-4 shadow">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Pending Records</h4>
                            </div>
                            <div class="card-body">
                                <table id="myTable2" class="display">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Market</th>
                                            <th>Price</th>
                                            <th>Record Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mt-4 shadow">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Products Records</h4>
                                @can('record-create')
                                <a href="/records/add" class="btn btn-primary">
                                    <i class="bi bi-database-add"></i> ADD RECORDS
                                </a>
                                @endcan
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
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-form" method="POST" action="{{ route('updateRecord') }}">
                            @csrf
                            <input type="hidden" id="edit-id" name="id">

                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <label for="edit-product_id">Select Product</label>
                                    <select name="product_id" id="edit-product_id" class="form-control">
                                        <!-- Products will be populated dynamically -->
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <label for="edit-market_id">Select Market</label>
                                    <select name="market_id" id="edit-market_id" class="form-control">
                                        <!-- Markets will be populated dynamically -->
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <label for="edit-price">Price</label>
                                    <input type="number" id="edit-price" name="price" class="form-control">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <label for="edit-recorded_date">Recorded Date</label>
                                    <input type="date" id="edit-recorded_date" name="recorded_date" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="edit-form">Update</button>
                    </div>
                </div>
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
        $(document).ready(function () {
            var table = $('#myTable').DataTable({
                "ajax": {
                    "url": "{{ route('getallrecords') }}",
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
                    { "data": "recorded_date" },
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            var actions = "";

                            // Check edit permission
                            if (data.can_edit) {
                                actions += '<a href="#" class="btn btn-sm btn-success edit-btn" ' +
                                    'data-id="'+data.id+'" ' +
                                    'data-product_id="'+data.product_id+'" ' +
                                    'data-product_name="'+data.product_name+'" ' +
                                    'data-market_id="'+data.market_id+'" ' +
                                    'data-market_name="'+data.market_name+'" ' +
                                    'data-price="'+data.price+'" ' +
                                    'data-recorded_date="'+data.recorded_date+'">Edit</a> ';
                            }

                            // Check delete permission
                            if (data.can_delete) {
                                actions += '<a href="" class="btn btn-sm btn-danger delete-btn" data-id="'+data.id+'">Delete</a>';
                            }

                            return actions;
                        }
                    }
                ]
            });




            //tricky
            $('#myTable tbody').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                var product_id = $(this).data('product_id');
                var product_name = $(this).data('product_name');
                var market_id = $(this).data('market_id');
                var market_name = $(this).data('market_name');
                var price = $(this).data('price');
                var recorded_date = $(this).data('recorded_date');

                console.log("Raw recorded date:", recorded_date);

                // Ensure the recorded_date is formatted as YYYY-MM-DD
                var formattedDate = recorded_date ? recorded_date.split(' ')[0] : '';

                console.log("Formatted recorded date:", formattedDate);

                // Populate the form fields
                $('#edit-id').val(id);
                $('#edit-name').val(product_name);
                $('#edit-price').val(price);
                $('#edit-recorded_date').val(formattedDate); // Set formatted date

                // Populate the dropdowns correctly
                var $productSelect = $('#edit-product_id');
                $productSelect.empty();
                $productSelect.append('<option value="' + product_id + '" selected>' + product_name + '</option>');

                $.ajax({
                    url: '/getProducts',
                    method: 'GET',
                    success: function (data) {
                        data.products.forEach(function (product) {
                            if (product.id !== product_id) {
                                $productSelect.append('<option value="' + product.id + '">' + product.name + '</option>');
                            }
                        });
                    }
                });

                var $marketSelect = $('#edit-market_id');
                $marketSelect.empty();
                $marketSelect.append('<option value="' + market_id + '" selected>' + market_name + '</option>');

                $.ajax({
                    url: '/getMarkets',
                    method: 'GET',
                    success: function (data) {
                        data.markets.forEach(function (market) {
                            if (market.id !== market_id) {
                                $marketSelect.append('<option value="' + market.id + '">' + market.name + '</option>');
                            }
                        });
                    }
                });

                // Show the modal
                $('#editModal').modal('show');
            });


            var table = $('#myTable2').DataTable({
                "ajax": {
                    "url": "{{ route('getallpendingrecords') }}",
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
                    { "data": "recorded_date" },
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            return `
                                <form method="POST" action="{{ route('addToPrices') }}" class="d-inline add-form">
                                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                    <input type="hidden" name="id" value="${data.id}">
                                    <input type="hidden" name="product_id" value="${data.product_id}">
                                    <input type="hidden" name="market_id" value="${data.market_id}">
                                    <input type="hidden" name="price" value="${data.price}">
                                    <input type="hidden" name="recorded_date" value="${data.recorded_date}">
                                    <button type="submit" class="btn btn-sm btn-warning">Pending</button>
                                </form>
                            `;
                        }
                    }
                ]
            });


            $(document).on('submit', '.add-form', function (e) {
                e.preventDefault();
                
                const form = $(this);
                const productdata = new FormData(this);

                $.ajax({
                    url: '{{ route('addToPrices') }}',
                    method: 'post',
                    data: productdata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            toastr.success('Record Approved successfully');
                            $('#myTable2').DataTable().ajax.reload();
                            $('#myTable').DataTable().ajax.reload();
                        } else {
                            toastr.error('Failed to add record.');
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Something went wrong.');
                    }
                });
            });



            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                if (confirm('Are you sure you want to delete this Record?')) {
                    $.ajax({
                        url: '{{ route('delete_records') }}',
                        type: 'DELETE',
                        data: {id: id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status === 200) {
                                $('#myTable').DataTable().ajax.reload();
                                toastr.success('Record deleted successfully');
                            } else {
                                alert(response.message); 
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr); 
                            toastr.warning('Error deleting Record');
                        }
                    });
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
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
                                <h4>Markets</h4>
                                @can('market-create')
                                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="bi bi-database-add"></i> ADD MARKET
                                </button>
                                @endcan
                            </div>
                            <div class="card-body">
                                <table id="myTable" class="display">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>State</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="product-form" method="post">
                                            <div class="row">
                                                <div class="col-lg">
                                                    <label>Market Name</label>
                                                    <input type="text" name="name" id="name" class="form-control">
                                                </div>
                                                <div class="col-lg">
                                                    <label>Select State</label>
                                                    <select name="state_id" id="state_id" class="form-control">
                                                        @foreach($states as $s)
                                                        <option value="{{$s->id}}">{{$s->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" form="product-form">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!--editModal-->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="edit-form" method="post">
                                    <input type="hidden" id="edit-id" name="id">
                                    <div class="row">
                                        <div class="col-lg">
                                            <label>Name</label>
                                            <input type="text" id="edit-name" name="name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg">
                                        <label>Select State</label>
                                        <select name="state_id" id="edit-state_id" class="form-control">
                                            @foreach($states as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
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
                    "url": "{{ route('getallmarkets') }}",
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
                    { "data": "name" },
                    { "data": "state_name" },
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            var actions = "";

                            // Check edit permission
                            if (data.can_edit) {
                                actions += '<a href="#" class="btn btn-sm btn-success edit-btn" data-id="'+data.id+'" data-name="'+data.name+'" data-state_name="'+data.state_name+'">Edit</a> ';
                            }

                            // Check delete permission
                            if (data.can_delete) {
                                actions += '<a href="#" class="btn btn-sm btn-danger delete-btn" data-id="'+data.id+'">Delete</a>';
                            }

                            return actions;
                        }
                    }
                ]
            });



            $('#myTable tbody').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var state_id = $(this).data('state_id');
                var state_name = $(this).data('state_name');

                $('#edit-id').val(id);
                $('#edit-name').val(name);

                var $stateSelect = $('#edit-state_id');

                // Remove any previously inserted first option
                $stateSelect.find('.temp-selected').remove();

                // Insert the current state as the first option and select it
                $stateSelect.prepend('<option class="temp-selected" value="' + state_id + '" selected>' + state_name + '</option>');

                $('#editModal').modal('show');
            });






            $('#product-form').submit(function (e) {
                e.preventDefault();
                const productdata = new FormData(this);

                $.ajax({
                    url: '{{ route('add_markets') }}',
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
                            $('#product-form')[0].reset();
                            $('#exampleModal').modal('hide');
                            $('#myTable').DataTable().ajax.reload();
                            toastr.success('Market added successfully');
                        }
                    }
                });
            });


            $('#edit-form').submit(function (e) {
                e.preventDefault();
                const productdata = new FormData(this);

                $.ajax({
                    url: '{{ route('update_markets') }}',
                    method: 'POST',
                    data: productdata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            $('#edit-form')[0].reset();
                            $('#editModal').modal('hide');
                            $('#myTable').DataTable().ajax.reload();
                            toastr.success('Market updated successfully');
                        } else {
                            toastr.success('Error updating Product');
                        }
                    }
                });
            });

            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                if (confirm('Are you sure you want to delete this employee?')) {
                    $.ajax({
                        url: '{{ route('delete_markets') }}',
                        type: 'DELETE',
                        data: {id: id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status === 200) {
                                $('#myTable').DataTable().ajax.reload();
                                toastr.warning('Market deleted successfully'); 
                            } else {
                                alert(response.message); 
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr); 
                            toastr.success('Error deleting Product');
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


<!-- Mirrored from themesbrand.com/velzon/html/master/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Nov 2024 09:50:54 GMT -->
</html>
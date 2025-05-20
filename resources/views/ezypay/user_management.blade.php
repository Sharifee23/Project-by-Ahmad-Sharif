@include('ezypay.include.top_user')

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-content">
                    
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-4 shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Users</h4>
                            @can('user-create')
                            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="bi bi-database-add"></i> ADD USER
                            </button>
                            @endcan
                        </div>
                        <div class="card-body">
                            <table id="myTable" class="display">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="product-form" method="post">
                               
                                <div class="row">
                                    <div class="col-lg">
                                        <label>Name</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>
                                    <div class="col-lg">
                                        <label>Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg">
                                        <label>Password</label>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg">
                                        <label>Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg">
                                        <label>Roles</label>
                                        <select class="form-select" name="roles[]" multiple>
                                            <option>--Select Role--</option>
                                            @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
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

            <!--view user modal-->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">View User</h5>
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
                                    <div class="col-lg">
                                        <label>Email</label>
                                        <input type="email" id="edit-email" name="email" class="form-control">
                                    </div>
                                    <div class="row">
                                    <div class="col-lg">
                                        <label>Password</label>
                                        <input type="password" name="password" id="edit-password" class="form-control" required>
                                    </div>
                                </div>
                                    <div class="col-lg">
                                        <label>Roles</label>
                                        <select class="form-select" name="roles[]" id="edit-role" multiple>
                                            <option>--Select Role--</option>
                                            @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="edit-form">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
    </div>



    <script>
        $(document).ready(function () {
            var table = $('#myTable').DataTable({
                "ajax": {
                    "url": "{{ route('getallusers') }}",
                    "type": "GET",
                    "dataType": "json",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": function (response) {
                        return response.status === 200 ? response.users : [];
                    }
                },
                "columns": [
                    { "data": "id" },
                    { "data": "name" },
                    { "data": "email" },
                    { 
                        "data": "roles",
                        "render": function (data, type, row) {
                            let roleButtons = '';
                            if (data.length > 0) {
                                data.forEach(function(role) {
                                    roleButtons += `<button class="btn btn-success btn-sm me-1">${role}</button>`;
                                });
                            } else {
                                roleButtons = '<span class="badge bg-secondary">No Role</span>';
                            }
                            return roleButtons;
                        }
                    },
                    { 
                        "data": null,
                        "render": function (data, type, row) {
                            let actions = "";
                            let editUrl = "{{ url('/editUser') }}/" + data.id;
                            let viewUrl = "{{ url('/viewUser') }}/" + data.id;

                            // View Button
                            actions += '<a href="' + viewUrl + '" class="btn btn-sm btn-info view-btn">View</a> ';

                            // Edit Permission
                            if (data.can_edit) {
                                actions += '<a href="' + editUrl + '" class="btn btn-sm btn-success edit-btn">Edit</a> ';
                            }

                            // Delete Permission
                            if (data.can_delete) {
                                actions += '<a href="#" class="btn btn-sm btn-danger delete-btn" data-id="'+data.id+'">Delete</a>';
                            }

                            return actions;
                        }
                    }
                ]
            });


            $('#product-form').submit(function (e) {
                e.preventDefault();
                const productdata = new FormData(this);

                $.ajax({
                    url: '{{ route('add.user') }}',
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
                            toastr.success('User added successfully');
                        }
                    }
                });
            });



            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');

                if (confirm('Are you sure you want to delete this User?')) {
                    $.ajax({
                        url: '{{ route('delete.user') }}',
                        type: 'DELETE',
                        data: {id: id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status === 200) {
                                $('#myTable').DataTable().ajax.reload();
                                toastr.danger('User deleted successfully'); 
                            } else {
                                alert(response.message); 
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr); 
                            toastr.warning('Error deleting User');
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

    <!-- Bootstrap Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>

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
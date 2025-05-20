@include('ezypay.include.top_user')

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-content">
                    
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>User Details</h4>
                            <a href="{{ route('users') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- User Info -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Name:</strong></label>
                                <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>Email:</strong></label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>Roles:</strong></label>
                                <div>
                                    @forelse ($user->roles as $role)
                                        <span class="badge bg-success me-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="badge bg-secondary">No Role Assigned</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="text-end mb-4">
                                <a href="{{ route('edit.user', $user->id) }}" class="btn btn-success">Edit User</a>
                            </div>

                            <!-- User Activity Table -->
                            <h5 class="mt-4">User Activities</h5>
                            <table id="myTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Action</th>
                                        <th>Table</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                            </table>
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
                "order": [[3, "desc"]],  // Ensure the Date column (index 3) is sorted descending
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": "{{ route('getuseractivities', $user->id) }}", // Dynamically pass user ID
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
                    { "data": "action" },
                    { "data": "table" },
                    { "data": "date" }
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
@include('ezypay.include.top_user')

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-content">
                    
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-4 shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                           {{ __('Create Role') }}
                        </div>
                        <div class="card-body">
                            <a href="{{ route('roles.index') }}" class="btn btn-info">Back</a>

                            <form action="{{ route('roles.store') }}" method="post">
                                @csrf

                                <div class="mt-2">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control">
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mt-2">
                                    <h3>Permissions:</h3>
                                    @foreach($permissions as $permission)
                                    <label><input type="checkbox" name="permissions[{{ $permission->name }}]" value="{{ $permission->name }}"> {{$permission->name}} </label><br/>
                                    @endforeach
                                </div>

                                <div class="mt-2">
                                    <button class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
    </div>



    <script>

       
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
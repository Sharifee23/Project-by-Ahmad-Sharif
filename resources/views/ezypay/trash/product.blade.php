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
                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0 text-white">Add Product</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 align-items-center w-100 py-3">
                            <form id="addProductForm" class="w-100">
                                @csrf
                                <div class="row justify-content-center align-items-center">
                                    <div class="col-lg-8 mb-3">
                                        <input type="text" name="name" 
                                            class="form-control border border-primary rounded-pill px-3" 
                                            placeholder="Enter product name" required>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <button type="submit" class="btn btn-primary rounded-pill w-100">
                                            <i class="ri-add-line align-bottom me-1"></i> Add Product
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div id="successMessage" class="text-success mt-3" style="display: none;"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h4 class="card-title mb-0 text-white">Products</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="listjs-table" id="customerList">
                                    <div class="row g-4 mb-3">
                                        <div class="col-sm">
                                            <div class="d-flex justify-content-sm-end">
                                                <div class="search-box ms-2">
                                                    <input type="text" class="form-control search" placeholder="Search...">
                                                    <i class="ri-search-line search-icon"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive table-card mt-3 mb-1">
                                        <table class="table table-striped table-bordered align-middle text-center" id="customerTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" class="text-primary">#</th>
                                                    <th scope="col" class="text-primary">Product</th>
                                                    <th scope="col" class="text-primary">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($product as $p)
                                                <tr>
                                                    <td class="id text-muted">#EP{{ $p->id }}</td>
                                                    <td class="customer_name text-dark">{{ $p->name }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <!-- Edit Link -->
                                                            <a href="" 
                                                            class="btn btn-sm btn-success edit-item-link" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editModal" 
                                                            data-id="{{ $p->id }}" 
                                                            data-name="{{ $p->name }}">
                                                                <i class="ri-edit-2-line align-bottom"></i> Edit
                                                            </a>

                                                            <!-- Remove Link -->
                                                            <a href="{{url('delete_product', $p->id)}}" 
                                                            class="btn btn-sm btn-danger remove-item-link">
                                                                <i class="ri-delete-bin-6-line align-bottom"></i> Remove
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="noresult" style="display: none">
                                            <div class="text-center">
                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                                <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any orders for you search.</p>
                                            </div>
                                        </div>
                                    </div>


                                    <!--edit Modal-->
                                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form id="editProductForm" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="categoryName" class="form-label">Product Name</label>
                                                            <input type="text" class="form-control" id="productName" name="product" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <div class="pagination-wrap hstack gap-2">
                                            <a class="page-item pagination-prev disabled" href="javascript:void(0);">
                                                Previous
                                            </a>
                                            <ul class="pagination listjs-pagination mb-0"></ul>
                                            <a class="page-item pagination-next" href="javascript:void(0);">
                                                Next
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end col -->
                </div>
                    <!-- end row -->
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © Velzon.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Themesbrand
                            </div>
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


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#addProductForm').on('submit', function (e) {
                e.preventDefault(); // Prevent form from submitting normally

                let formData = $(this).serialize(); // Get form data

                $.ajax({
                    url: "{{ url('add_product') }}",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            $('#successMessage').text(response.message).show(); // Show success message
                            $('#addProductForm')[0].reset(); // Reset the form
                        }
                    },
                    error: function (xhr) {
                        console.error("Error:", xhr.responseJSON);
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        // Get all edit links
        const editLinks = document.querySelectorAll(".edit-item-link");

        // Loop through each edit link and add a click event listener
        editLinks.forEach(link => {
            link.addEventListener("click", (e) => {
                // Get the data attributes from the clicked link
                const productId = link.getAttribute("data-id");
                const productName = link.getAttribute("data-name");

                // Populate the modal inputs with the current values
                const modalProductName = document.getElementById("productName");
                modalProductName.value = productName;

                // Update the form action to include the product ID if needed
                const form = document.getElementById("editProductForm");
                form.action = `/update_product/${productId}`;
            });
        });
    });

    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Get all delete links
            const deleteLinks = document.querySelectorAll(".remove-item-link");

            deleteLinks.forEach(link => {
                link.addEventListener("click", () => {
                    // Get the product ID from the clicked link
                    const productId = link.closest("tr").querySelector(".id").textContent.replace("#EP", "").trim();

                    // Confirm deletion
                    if (confirm("Are you sure you want to delete this product?")) {
                        // Send the AJAX request
                        fetch(`/delete_product/${productId}`, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content, // CSRF token
                                "Content-Type": "application/json"
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove the product row from the table
                                    const row = link.closest("tr");
                                    row.remove();

                                    // Optional: Show a success message
                                    alert("Product deleted successfully!");
                                } else {
                                    alert(data.message || "Failed to delete product.");
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                                alert("An error occurred while deleting the product.");
                            });
                    }
                });
            });
        });
    </script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
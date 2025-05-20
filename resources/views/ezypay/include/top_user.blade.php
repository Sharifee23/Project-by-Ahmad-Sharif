<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">


<head>

    <meta charset="utf-8" />
    <title>Dashboard | Ezypay</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- jsvectormap css -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{asset('assets/libs/jsvectormap/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />

    <!--Swiper slider css-->
    <link href="{{asset('assets/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{asset('assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        body {
            height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }

        .back-arrow {
            font-size: 20px;
            padding: 10px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .sidebar-menu {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centers items vertically */
            align-items: center;
        }

        .sidebar-menu a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            width: 100%;
            text-align: center;
        }

        .sidebar-menu a:hover {
            background: #495057;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
            width: calc(100% - 250px);
        }

        /* Responsive Sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
                align-items: center;
                padding-top: 10px;
            }

            .sidebar-menu a {
                font-size: 14px;
                padding: 10px;
            }

            .main-content {
                margin-left: 60px;
                width: calc(100% - 60px);
            }

            .back-arrow {
                justify-content: center;
            }
        }
    </style>



</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="back-arrow">
            <a href=" {{route('dashboard') }}" class="text-white"><i class="bi bi-arrow-left"></i> <small>Dashboard</small></a>
        </div>
        <div class="sidebar-menu">
            @can('user-list')
            <a href="{{route('users')}}">Users</a>
            @endcan
            @can('role-list')
            <a href="{{route('roles.index')}}">Roles</a>
            @endcan
            <a href="{{route('user.activities')}}">Activities</a>
        </div>
    </div>

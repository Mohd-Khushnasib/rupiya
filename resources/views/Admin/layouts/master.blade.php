@php
    use Illuminate\Support\Facades\DB;
    use Carbon\Carbon;
    $DateTime = Carbon::now('Asia/Kolkata');
    $today = $DateTime->format('Y-m-d'); // Format matching your database: 2025-03-28

    if (session()->has('admin_login')) {
        $data = session('admin_login');
        $admin = $data->first();
        $role = $admin->role ?? '';
        $username = $admin->name ?? '';
        $admin_id = $admin->id ?? null;
        $totalLeads = DB::table('tbl_lead')->count();

        // Check for today's attendance using the date column
        $todayAttendance = DB::table('tbl_attendance')
            ->where('admin_id', $admin_id)
            ->where('date', $today)
            ->first();
        
        // Default state
        $disablePunchIn = true;
        $disablePunchOut = true;

        if ($todayAttendance) {
            // Record exists for today
            if ($todayAttendance->punchin_status == 'true') {
                $disablePunchIn = true; // Already punched in
                $disablePunchOut = ($todayAttendance->punchout_status == 'true'); // Enable punch-out if not done yet
            } else {
                $disablePunchIn = false; // Can punch in
                $disablePunchOut = true; // Can't punch out yet
            }
        } else {
            // No record for today - check the latest record
            $lastAttendance = DB::table('tbl_attendance')
                ->where('admin_id', $admin_id)
                ->orderBy('date', 'desc')
                ->first();
                
            if (!$lastAttendance || ($lastAttendance->punchin_status == 'true' && 
                                    $lastAttendance->punchout_status == 'true')) {
                // Previous day is complete or no previous record - enable punch-in for new day
                $disablePunchIn = false;
                $disablePunchOut = true;
            } else {
                // Previous day has incomplete status - maintain that state
                $disablePunchIn = ($lastAttendance->punchin_status == 'true');
                $disablePunchOut = ($lastAttendance->punchout_status == 'true');
            }
        }
    } else {
        echo '<script>
            window.location.href = "/login";
        </script>';
        exit;
    }
@endphp



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Dashboard - Admin</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--base css styles-->
    <link rel="stylesheet" href="{{asset('Admin/assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('Admin/assets/font-awesome/css/font-awesome.min.css')}}">
    <!-- css styles-->
    <link rel="stylesheet" href="{{asset('Admin/css/flaty.css')}}">
    <link rel="stylesheet" href="{{asset('Admin/css/flaty-responsive.css')}}">
    <link rel="shortcut icon" href="{{asset('Admin/img/favicon.png')}}">
    <link rel="stylesheet" href="{{asset('Admin/assets/data-tables/bootstrap3/dataTables.bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Admin/assets/chosen-bootstrap/chosen.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Admin/assets/jquery-tags-input/jquery.tagsinput.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Admin/assets/jquery-pwstrength/jquery.pwstrength.css')}}">
    <link rel="stylesheet" type="text/css"
        href="{{asset('Admin/assets/bootstrap-fileupload/bootstrap-fileupload.css')}}">
    <link rel="stylesheet" type="text/css"
        href="{{asset('Admin/assets/bootstrap-duallistbox/duallistbox/bootstrap-duallistbox.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Admin/assets/dropzone/downloads/css/dropzone.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Admin/assets/bootstrap-colorpicker/css/colorpicker.css')}}">
    <link rel="stylesheet" type="text/css"
        href="{{asset('Admin/assets/bootstrap-timepicker/compiled/timepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Admin/assets/clockface/css/clockface.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Admin/assets/bootstrap-datepicker/css/datepicker.css')}}">
    <link rel="stylesheet" type="text/css"
        href="{{asset('Admin/assets/bootstrap-daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css"
        href="{{asset('Admin/assets/bootstrap-switch/static/stylesheets/bootstrap-switch.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('Admin/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css')}}">
    <!-- Alertify Css Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="wrapper">
        <!-- BEGIN Navbar -->
        <div id="" class="navbar">
            <button type="button" class="navbar-toggle navbar-btn for-nav-horizontal collapsed" data-toggle="collapse"
                data-target="#nav-horizontal">
                <span class="fa fa-bars"></span>
            </button>

            <!-- BEGIN Navbar Buttons -->
            <ul class="nav flaty-nav pull-right">


              
            <li>
                <div class="dropdown">
                    <button id="timeButton" class="btn btn-light dropdown-toggle btn-block" type="button" style="width: 160px;">
                        Time: Loading...
                    </button>
                    <ul class="dropdown-menu" id="dropdown-menu">
                        <li><a href="employee_leaves.html"><button type="button" class="btn btn-light btn-block">
                                Leave
                            </button></a></li>
                    
                        <li><a href="#"><button type="button" class="btn btn-info btn-block" id="openModalBtn" 
                                {{ $disablePunchIn ? 'disabled' : '' }}>
                                Punch In
                            </button></a></li>
                    
                        <li><a href="#"><button class="btn btn-success btn-block" id="openModalBtn1" 
                                {{ $disablePunchOut ? 'disabled' : '' }}>
                                Punch Out
                            </button></a></li>
                    </ul>
                </div>
            </li>



                <!-- BEGIN Button User -->
                <li class="user-profile">
                    <a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
                        <img class="nav-user-photo" src="{{asset('Admin/img/demo/avatar/avatar1.jpg')}}"
                            alt="Penny's Photo">
                        <span id="user_info">
                            <!-- Admin -->
                            <!-- User Name When Login Name  -->
                            {{$username}}
                        </span>
                        <b class="arrow fa fa-caret-down"></b>
                    </a>
                    <!-- BEGIN User Dropdown -->
                    <ul class="dropdown-menu dropdown-navbar" id="user_menu">
                        <li class="nav-header">
                            <i class="fa fa-clock-o"></i>
                            Logined From 20:45
                        </li>
                        <li>
                            <a href="{{url('/Settings')}}">
                                <i class="fa fa-cog"></i>
                                Account Settings
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-user"></i>
                                Edit Profile
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="fa fa-question"></i>
                                Help
                            </a>
                        </li>

                        <li class="divider visible-xs"></li>

                        <li class="visible-xs">
                            <a href="#">
                                <i class="fa fa-tasks"></i>
                                Tasks
                                <span class="badge badge-warning">4</span>
                            </a>
                        </li>
                        <li class="visible-xs">
                            <a href="#">
                                <i class="fa fa-bell"></i>
                                Notifications
                                <span class="badge badge-important">8</span>
                            </a>
                        </li>
                        <li class="visible-xs">
                            <a href="#">
                                <i class="fa fa-envelope"></i>
                                Messages
                                <span class="badge badge-success">5</span>
                            </a>
                        </li>

                        <li class="divider"></li>
                        <li>
                            <a class="logout_btn" href="javascript:void(0);">
                                Logout
                            </a>
                        </li>
                    </ul>
                    <!-- BEGIN User Dropdown -->
                </li>
                <!-- END Button User -->
            </ul>

            <ul class="nav flaty-nav navbar-collapse collapse" id="nav-horizontal">

                <li>
                    <a href="{{url('/admin-dashboard')}}">
                        <i class="fa fa-home"></i>
                        <span class="nav_text_all">Home</span>
                    </a>
                </li>

                <li class="notification_parent_div">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-desktop"></i>
                        <span class="nav_text_all">Lead CRM</span>
                        <span class="notification_nav">{{ $totalLeads }}+</span>
                        <b class="arrow fa fa-caret-down"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-navbar">
                        <div class="" role="toolbar" aria-label="...">
                            <div class="btn-group btn btn-pill" role="group" aria-label="..."><a href="">CRM
                                    Dashboard</a>
                            </div>
                            <div class="btn-group btn btn-pill" role="group" aria-label="..."><a
                                    href="{{url('/admin-lead-form')}}">Create Lead</a>
                            </div>
                            <div class="btn-group btn btn-pill" role="group" aria-label="..."><a
                                    href="{{url('/show_pl_od_leads')}}">PL & OD
                                    Leads</a>
                            </div>
                            <div class="btn-group btn btn-pill" role="group" aria-label="...">
                                <a href="{{url('/show_home_loan_leads')}}">Home Loan Leads</a>
                            </div>
                        </div>
                    </ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-edit"></i>
                        <span class="nav_text_all">Login CRM</span>
                        <b class="arrow fa fa-caret-down"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-navbar">
                        <div class="" role="toolbar" aria-label="...">
                            <div class="btn-group btn btn-pill" role="group" aria-label="..."><a href="">Dashboard</a>
                            </div>
                            <div class="btn-group btn btn-pill" role="group" aria-label="..."><a
                                    href="{{url('/show_pl_od_login')}}">PL & OD
                                    Login</a>
                            </div>
                            <div class="btn-group btn btn-pill" role="group" aria-label="...">
                                <a href="{{url('/show_home_loan_login')}}">Home Loan Login</a>
                            </div>
                        </div>
                    </ul>
                </li>
                <li>
                    <a href="{{url('/admin-task')}}" class="dropdown-toggle">
                        <i class="fa fa-edit"></i>
                        <span class="nav_text_all">Task</span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/admin-ticket')}}" class="dropdown-toggle">
                        <i class="fa fa-edit"></i>
                        <span class="nav_text_all">Ticket</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-edit"></i>
                        <span class="nav_text_all">HRMS</span>
                        <b class="arrow fa fa-caret-down"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-navbar">
                        <div class="" role="toolbar" aria-label="...">
                            <div class="btn-group btn btn-pill" role="group" aria-label="..."><a href="">Dashboard</a>
                            </div>
                            <div class="btn-group btn btn-pill" role="group" aria-label="..."><a href="">PL & OD
                                    Login</a>
                            </div>
                            <div class="btn-group btn btn-pill" role="group" aria-label="..."><a href="">Home Loan
                                    Login</a>
                            </div>
                        </div>
                    </ul>
                </li>
                <li>
                    <a href="{{url('/admin-warning')}}" class="dropdown-toggle">
                        <i class="fa fa-file"></i>
                        <span class="nav_text_all">Warning</span>
                    </a>
                </li>

                <li>
                    <a href="{{url('/admin-calculator')}}" style="font-size:20px" class="dropdown-toggle">
                        <i class="bi bi-calculator"></i>
                        <!-- <span class="nav_text_all"><i class="fa fa-calculator "></i></span> -->
                    </a>
                </li>
                {{-- <li>
                    <a href="{{url('/emi-status')}}" class="dropdown-toggle">
                        <i class="fa fa-file"></i>
                        <span class="nav_text_all">Emi Share</span>
                    </a>
                </li> --}}

                
            </ul>
            <!-- END Horizontal Menu -->
        </div>

        <!-- Main Container Start Here -->
        @yield('main-content')
        <!-- Main Container End Here -->

        <!-- Logout Modal Start Here  -->
        <div class="modal fade" id="LogoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="LogoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger text-white border-0"
                        style="background-color: #121111;color: white;">
                        <h5 class="modal-title text-white" id="LogoutModalLabel">Log Out Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="fs-5 text-muted mb-4">Are you sure you want to log out?</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center gap-3 border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                        <a href="{{url('admin-logout')}}" class="btn btn-danger px-4">Log Out</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Logout Modal End Here  -->

        <!-- Footer -->
        <footer class="footer">
            <p>{{date('Y')}} © Bhoomitechzone Pvt Ltd</p>
        </footer>
        <!-- Scroll Up Button -->
        <a id="btn-scrollup" class="btn btn-circle btn-lg" href="#"><i class="fa fa-chevron-up"></i></a>
    </div>

    <!-- Basic Scripts -->
    <script src="{{asset('Admin/assets/ajax/libs/jquery/2.1.1/jquery.min.js')}}"></script>
    <!-- <script>
    window.jQuery || document.write('<script src="{{asset('
        Admin / assets / jquery / jquery - 2.1 .1.min.js ')}}"><\/script>')
    </script> -->
    <script src="{{asset('Admin/assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('Admin/assets/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('Admin/assets/jquery-cookie/jquery.cookie.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/data-tables/jquery.dataTables.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/data-tables/bootstrap3/dataTables.bootstrap.js')}}">
    </script>
    <!--flaty scripts-->
    <script src="{{asset('Admin/js/flaty.js')}}"></script>
    <script src="{{asset('Admin/js/flaty-demo-codes.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/chosen-bootstrap/chosen.jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/bootstrap-inputmask/bootstrap-inputmask.min.js')}}">
    </script>
    <script type="text/javascript" src="{{asset('Admin/assets/jquery-tags-input/jquery.tagsinput.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/jquery-pwstrength/jquery.pwstrength.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/bootstrap-fileupload/bootstrap-fileupload.min.js')}}">
    </script>
    <script type="text/javascript"
        src="{{asset('Admin/assets/bootstrap-duallistbox/duallistbox/bootstrap-duallistbox.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/dropzone/downloads/dropzone.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/bootstrap-timepicker/js/bootstrap-timepicker.js')}}">
    </script>
    <script type="text/javascript" src="{{asset('Admin/assets/clockface/js/clockface.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js')}}">
    </script>
    <script type="text/javascript" src="{{asset('Admin/assets/bootstrap-datepicker/js/bootstrap-datepicker.js')}}">
    </script>
    <script type="text/javascript" src="{{asset('Admin/assets/bootstrap-daterangepicker/date.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/bootstrap-daterangepicker/daterangepicker.js')}}">
    </script>
    <script type="text/javascript" src="{{asset('Admin/assets/bootstrap-switch/static/js/bootstrap-switch.js')}}">
    </script>
    <script type="text/javascript" src="{{asset('Admin/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js')}}"></script>
    <script type="text/javascript" src="{{asset('Admin/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js')}}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <!-- Isko comment kiya h maine pagination k liye 2025-02-13 ko  -->
    <script>
        //  ClassicEditor
        //     .create(document.querySelector('#editor'))
        //     .then(editor => {
        //         console.log('Editor initialized:', editor);
        //     })
        //     .catch(error => {
        //         console.error('Error initializing CKEditor:', error);
        //     });
        // ClassicEditor
        //     .create(document.querySelector('#newpolicy'))
        //     .then(editor => {
        //         console.log('Editor initialized:', editor);
        //     })
        //     .catch(error => {
        //         console.error('Error initializing CKEditor:', error);
        //     });
    </script>

    <!-- AlertifyJS JS And Sweat Alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
    <script>
        $(".logout_btn").click(function () {
            $("#LogoutModal").modal("show");
        });
    </script>

    <!-- Excel Export Link Start Here  -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <!-- Excel Export Link End Here  -->

</body>

</html>
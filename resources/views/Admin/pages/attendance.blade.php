@if (session()->get('admin_login'))
    @foreach (session()->get('admin_login') as $adminlogin)

        @php
            date_default_timezone_set('Asia/Kolkata');
            $currentMonthYear = date('F Y');

            $today = date('n/j/Y');
            $attendance = DB::table('tbl_attendance')
                ->where('admin_id', $adminlogin->id)
                ->whereRaw("DATE_FORMAT(STR_TO_DATE(punchin_datetime, '%c/%e/%Y, %h:%i:%s %p'), '%c/%e/%Y') = ?", [$today])
                ->first();
        @endphp


        @extends('Admin.layouts.master')
        @section('main-content')
            <style>
                .table.table-advance,
                .table.table-advance th,
                .table.table-advance td {
                    border-top: 1px solid #03b0f5;
                    border-bottom: 1px solid #03b0f5;
                    border-right: 1px solid #03b0f5;
                    border-collapse: collapse;
                }

                .row th {
                    border-right: 1px solid #03b0f5;
                }

                .table-container {
                    width: 100%;
                    overflow-x: auto;
                    position: relative;
                }

                table {
                    width: 100%;
                    min-width: 800px;
                    border-collapse: collapse;
                }

                th,
                td {
                    white-space: nowrap;
                    text-align: left;
                }

                th {
                    font-size: 15px !important;
                    text-align: center !important;
                }

                td {
                    font-weight: normal !important;
                    text-align: center !important;
                }

                th:first-child,
                td:first-child {
                    position: sticky;
                    background-color: black;
                    left: 0;
                    z-index: 2;
                }

                th:first-child,
                td:first-child {
                    z-index: 3;
                }

                .scrollable-table {
                    display: block;
                    overflow-x: auto;
                    white-space: nowrap;
                }

                .punchin_hover:hover {
                    background-color: none;
                }

                .image-container {
                    display: flex;
                    align-items: center;
                    justify-content: space-around;
                }
            </style>

            <style>
                .table-margin {
                    margin-top: 60px;
                }

                .table-container {
                    border: 0;
                    overflow-x: auto;
                    white-space: nowrap;
                }

                .custom-table {
                    width: 100%;
                }

                .name-header {
                    width: 100px;
                    color: aliceblue !important;
                }

                input[type="month"]::-webkit-calendar-picker-indicator {
                    filter: invert(1);
                }
            </style>

            <style>
                .full-day {
                    color: black !important;
                    /* background-color: #fff3e0; */
                    background-color: green;
                    color: white !important;
                    /* Light Orange */
                }

                .half-day {
                    color: black !important;
                    background-color: #ffff00;
                    /* Yellow */
                }

                .leave-approve {
                    color: black !important;
                    background-color: #ff5722;
                    /* Red-Orange */
                }

                .leave-not-approve {
                    color: black !important;
                    /* background-color: #d81b60; */
                    background-color: red;
                    color: white !important;
                    /* Dark Pink */
                }

                .off {
                    color: white !important;
                    background-color: #0288d1;
                    /* Blue */
                }
            </style>

            <style>
                .table-container {
                    max-height: 400px;
                    overflow-y: auto;
                }

                thead {
                    position: sticky;
                    top: -1px;
                    left: 20px;
                    background-color: black;
                    z-index: 1;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                th,
                td {
                    padding: 8px;
                    /* border: 1px solid #ddd; */
                    text-align: left;
                }
            </style>

            <!-- Attendance Details Modal -->
            <style>
                .ak_container {
                    /* background-color: #fff; */
                    /* padding: 20px; */
                    /* border: 1px solid black; */
                    /* border-radius: 8px; */
                    /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
                    /* width: 400px; */
                }

                .ak_heading {
                    /* text-align: center; */
                    margin-bottom: 20px;
                    color: black;
                    font-weight: bold;
                }

                .ak_input_group {
                    display: flex;
                    gap: 10px;
                    margin-bottom: 10px;
                }

                .ak_input_group input {
                    flex: 1;
                    padding: 8px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                }

                .ak_input_group input[type="number"] {
                    width: 80px;
                }

                .ak_minus {
                    background-color: #dc3545;
                    color: white;
                    border: none;
                    padding: 8px 12px;
                    border-radius: 4px;
                    cursor: pointer;
                }

                .ak_minus:hover {
                    background-color: #c82333;
                }

                #ak_plusBtn {
                    background-color: #28a745;
                    color: white;
                    border: none;
                    padding: 10px 15px;
                    border-radius: 4px;
                    cursor: pointer;
                    margin-top: 10px;
                }

                #ak_plusBtn:hover {
                    background-color: #218838;
                }

                .ak_total_box {
                    margin-top: 20px;
                    padding: 10px;
                    background-color: #f8f9fa;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    text-align: center;
                    font-weight: bold;
                }

                .ak_error {
                    color: #dc3545;
                    font-size: 14px;
                    margin-top: 10px;
                    text-align: center;
                }
            </style>

            <style>
                .chosen-container {
                    background-color: whitesmoke;
                }
            </style>


            <div class="container" id="main-container">

                <input type="hidden" placeholder="Name" class="form-control admin_id" value="{{$adminlogin->id ?? ''}}" readonly>

                <!-- BEGIN Content -->
                <div id="main-content">
                    <!-- BEGIN Page Title -->
                    <div class="page-title">
                        <div>
                            <h1 class="theam_color_text">
                                <i class="fa fa-user theam_color"></i> Employee Name
                            </h1>
                        </div>
                    </div>
                    <!-- BEGIN Main Content -->
                    <div class="row" style="margin-top: 20px">
                        <div class="col-lg-3">
                            <div class="card punch_card">
                                <h3 class="punch_card_text">Full Day</h3>
                                <h4 class="punch_card_text">12</h4>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card punch_card">
                                <h3 class="punch_card_text">Half Day</h3>
                                <h4 class="punch_card_text">30</h4>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card punch_card">
                                <h3 class="punch_card_text">Absent</h3>
                                <h4 class="punch_card_text">29</h4>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card punch_card">
                                <h3 class="punch_card_text">Total Day</h3>
                                <h4 class="punch_card_text">30</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row"
                        style="width: 100%; display: flex; gap: 10px; align-items: center; justify-content: center; margin: 10px 0;">
                        <div style="border: none; padding: 0 10px; border-radius: 10px;">
                            <h2>Monthly Attendance - {{ $currentMonthYear }}</h2>

                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    <select name="task_type" id="edit_task_type" data-placeholder="Type Task"
                                        style="background-color: whitesmoke !important;"
                                        class="form-control chosen EditChoosen disabled-div" tabindex="-1">
                                        <option value="all">All Employee</option>
                                        <option value="emp1">EMP 1</option>
                                        <option value="emp2">EMP 2</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <select name="task_type" id="edit_task_type" data-placeholder="Type Task"
                                        style="background-color: whitesmoke !important;"
                                        class="form-control chosen EditChoosen disabled-div" tabindex="-1">
                                        <option value="all">Chooese Month</option>
                                        <option value="emp1">November</option>
                                        <option value="emp2">December</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- table start -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive table-container">
                                <table class="table table-advance custom-table">
                                    <thead>
                                        <tr>
                                            <th class="name-header">Employee Details</th>
                                            @foreach($dates as $date)
                                                <th class="day-header {{ $date['isSunday'] ? 'bg-light text-danger' : '' }}">
                                                    {{ $date['day'] }} <br> {{ $date['dayOfWeek'] }}
                                                </th>
                                            @endforeach
                                            <th class="day-header">Total <br> Present</th>
                                            <th class="day-header">Paid <br> Leave</th>
                                            <th class="day-header">Earned <br> Leave</th>
                                            <th class="day-header">Final <br> Attendance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attendanceData as $attendance)
                                                            <tr>
                                                                <td class="name-cell" style="display: flex; gap: 10px; align-items: center;">
                                                                    <div>
                                                                        @if($attendance['image'])
                                                                            <img src="{{ asset($attendance['image']) }}" alt="" height="50px" width="50px"
                                                                                style="object-fit: cover;">
                                                                        @else
                                                                            <div
                                                                                style="height: 50px; width: 50px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                                                                <span>{{ substr($attendance['name'], 0, 1) }}</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div style="text-align: start;">
                                                                        <span style="font-size: 17px;"><b>{{ $attendance['name'] }}</b></span><br>
                                                                        <span style="font-size: 12px;">Emp-ID: {{ $attendance['admin_id'] }}</span><br>
                                                                        <span style="font-size: 12px;">{{ $attendance['department'] }} |
                                                                            {{ $attendance['role'] }}</span>
                                                                    </div>
                                                                </td>

                                                                @php
                                                                    $totalPresent = 0;
                                                                    $paidLeave = 0;
                                                                    $earnedLeave = 0;
                                                                @endphp

                                                                @foreach($dates as $date)
                                                                                    @php
                                                                                        $dayData = $attendance['attendance'][$date['date']];

                                                                                        // Calculate attendance totals
                                                                                        if ($dayData['attendance_status'] == '1') {
                                                                                            $totalPresent += 1;
                                                                                            $value = 1;
                                                                                        } elseif ($dayData['attendance_status'] == '0.5') {
                                                                                            $totalPresent += 0.5;
                                                                                            $value = 0.5;
                                                                                        } elseif ($dayData['attendance_status'] == 'leave' && strpos($dayData['attendance_status'], 'paid') !== false) {
                                                                                            $paidLeave += 1;
                                                                                            $value = 0;
                                                                                        } elseif ($dayData['attendance_status'] == 'leave' && strpos($dayData['attendance_status'], 'earned') !== false) {
                                                                                            $earnedLeave += 1;
                                                                                            $value = 0;
                                                                                        } else {
                                                                                            $value = $date['isSunday'] ? 1 : 0;
                                                                                        }

                                                                                        // Set cell class based on status
                                                                                        if ($date['isSunday']) {
                                                                                            $cellClass = 'off';
                                                                                        } elseif ($dayData['attendance_status'] == '1') {
                                                                                            $cellClass = 'full-day';
                                                                                        } elseif ($dayData['attendance_status'] == '0.5') {
                                                                                            $cellClass = 'half-day';
                                                                                        } elseif ($dayData['attendance_status'] == 'leave' && strpos($dayData['attendance_status'], 'approved') !== false) {
                                                                                            $cellClass = 'leave-approve';
                                                                                        } elseif ($dayData['attendance_status'] == 'leave') {
                                                                                            $cellClass = 'leave-not-approve';
                                                                                        } elseif ($dayData['attendance_status'] == '0') {
                                                                                            $cellClass = 'absent';
                                                                                        } else {
                                                                                            $cellClass = '';
                                                                                        }
                                                                                    @endphp

                                                                                    <td class="opentdModal {{ $cellClass }}" data-employee-name="{{ $attendance['name'] }}"
                                                                                        data-employee-id="{{ $attendance['admin_id'] }}"
                                                                                        data-employee-dept="{{ $attendance['department'] }}"
                                                                                        data-employee-role="{{ $attendance['role'] }}"
                                                                                        data-employee-image="{{ $attendance['image'] }}"
                                                                                        data-attendance-date="{{ $date['date'] }}"
                                                                                        data-attendance-status="{{ $dayData['attendance_status'] }}"
                                                                                        data-punch-in-time="{{ $dayData['punch_in_time'] ?? '' }}"
                                                                                        data-punch-out-time="{{ $dayData['punch_out_time'] ?? '' }}"
                                                                                        data-punch-in-img="{{ $dayData['punchin_img'] ?? '' }}"
                                                                                        data-punch-out-img="{{ $dayData['punchout_img'] ?? '' }}">

                                                                                        <b>{{ $value }}</b><br>
                                                                                        @if($date['isSunday'])
                                                                                            <span>Off</span>
                                                                                        @elseif($dayData['attendance_status'] == 'leave')
                                                                                            <span>Leave</span>
                                                                                        @elseif($dayData['attendance_status'] == '1')
                                                                                            <span>Present<br>
                                                                                                @if($dayData['punch_in_time'])
                                                                                                    {{ $dayData['punch_in_time'] }}
                                                                                                    @if($dayData['punch_out_time'])
                                                                                                        - {{ $dayData['punch_out_time'] }}
                                                                                                    @endif
                                                                                                @endif
                                                                                            </span>
                                                                                        @elseif($dayData['attendance_status'] == '0.5')
                                                                                            <span>Half day<br>
                                                                                                @if($dayData['punch_in_time'])
                                                                                                    {{ $dayData['punch_in_time'] }}
                                                                                                    @if($dayData['punch_out_time'])
                                                                                                        - {{ $dayData['punch_out_time'] }}
                                                                                                    @endif
                                                                                                @endif
                                                                                            </span>
                                                                                        @elseif($dayData['attendance_status'] == '0')
                                                                                            <span>Absent</span>
                                                                                        @else
                                                                                            <span>{{ $dayData['attendance_status'] }}</span>
                                                                                        @endif
                                                                                    </td>
                                                                @endforeach

                                                                <td>{{ $totalPresent }}</td>
                                                                <td>{{ $paidLeave }}</td>
                                                                <td>{{ $earnedLeave }}</td>
                                                                <td>{{ $totalPresent + $paidLeave + $earnedLeave }}</td>
                                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Content End Here -->

            <!-- Js Links Start Here -->
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

            <script>
                $(document).ready(function () {
                    // Punchin Here 
                    $("#add_form").on("submit", function (e) {
                        e.preventDefault();
                        $(".add_btn").prop('disabled', true);
                        var admin_id = $(".admin_id").val();
                        var formdata = new FormData(this);
                        formdata.append('admin_id', admin_id);

                        // AJAX request
                        $.ajax({
                            type: "POST",
                            url: "{{url('/add_attendance')}}", // Make sure this URL is correct
                            data: formdata,
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function (data) {
                                // Re-enable the button
                                $(".add_btn").prop("disabled", false);

                                if (data.success == 'success') {
                                    document.getElementById("add_form").reset();
                                    $("#myModal").modal("hide");
                                    swal("Attendance Submitted Successfully", "", "success");
                                    window.location.reload();
                                } else if (data.success == 'error') {
                                    $(".add_btn").prop('disabled', false);
                                    swal("Error", data.message, "error");
                                }
                            },
                            error: function (xhr, status, error) {
                                // Handle error case
                                $(".add_btn").prop("disabled", false);
                                swal("Error", "An error occurred while submitting the form. Please try again.", "error");
                                console.error("AJAX Error:", xhr.responseText);
                            }
                        });
                    });

                    // Punchout Here 
                    // On the punch-out form submission
                    $("#punchout_form").on("submit", function (e) {
                        e.preventDefault();
                        $(".punchout_btn").prop('disabled', true);
                        var admin_id = $(".admin_id").val();
                        var formdata = new FormData(this);
                        formdata.append('admin_id', admin_id);

                        // AJAX request
                        $.ajax({
                            type: "POST",
                            url: "{{url('/punchout_attendance')}}",
                            data: formdata,
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function (data) {
                                $(".punchout_btn").prop("disabled", false);
                                if (data.success == 'success') {
                                    document.getElementById("punchout_form").reset();
                                    $("#myModal1").modal("hide");
                                    swal("Punchout Submitted Successfully", "", "success").then(() => {
                                        window.location.reload();
                                    });
                                } else if (data.success == 'error') {
                                    $(".punchout_btn").prop('disabled', false);
                                    swal("Error", data.message, "error");
                                }
                            },
                            error: function (xhr, status, error) {
                                $(".punchout_btn").prop("disabled", false);
                                swal("Error", "An error occurred while submitting the form. Please try again.", "error");
                                console.error("AJAX Error:", xhr.responseText);
                            }
                        });
                    });

                    // Add this code at the bottom of your script or in document.ready function
                    $(document).ready(function () {
                        // Check if we need to show the Work Report modal after page load
                        if (localStorage.getItem('showWorkReportModal') === 'true') {
                            // Clear the flag first to prevent it from showing again on future refreshes
                            localStorage.removeItem('showWorkReportModal');

                            // Wait a short moment to ensure the page is fully loaded
                            setTimeout(function () {
                                $("#myModal2").modal("show");
                            }, 500);
                        }
                    });

                    // end here 
                });
            </script>
            <!-- Ajax Js End Here -->












            <!-- live time -->
            <script>
                window.onload = function () {
                    function updateTime() {
                        const button = document.getElementById('timeButton');
                        const currentTime = new Date().toLocaleTimeString(); // Get current time
                        button.innerText = `Time: ${currentTime}`; // Update button text
                    }
                    // Update the time every 1000ms (1 second)
                    setInterval(updateTime, 1000);
                };
            </script>


            <!-- live time button dropdown -->
            <script>
                $(document).ready(function () {
                    $("#timeButton").on("click", function () {
                        // Update button text with current time
                        var currentTime = new Date().toLocaleTimeString();
                        $(this).text("Time: " + currentTime);

                        // Toggle dropdown manually
                        var dropdown = $(this).next(".dropdown-menu");
                        $(".dropdown-menu").not(dropdown).hide(); // Close other dropdowns
                        dropdown.toggle(); // Toggle the current one
                    });
                    // Close dropdown when clicking outside
                    $(document).on("click", function (e) {
                        if (!$(e.target).closest(".dropdown").length) {
                            $(".dropdown-menu").hide();
                        }
                    });
                });
            </script>

            <!-- find current month and show in month input box -->
            <script>
                let today = new Date();
                let currentMonth = today.toISOString().slice(0, 7);

                document.getElementById("monthPicker").value = currentMonth;
            </script>

            <!-- modal  -->

            <!-- leave modal -->
            <!-- Modal -->
            <div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" style="color: black; margin-left: 16px !important;" id="myModalLabel3">Leave
                                Request
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="form-container">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Leave Request</label>
                                                        <div class="controls">
                                                            <select class="form-control" data-placeholder="Choose a Category"
                                                                tabindex="1">
                                                                <option value="">Choose...</option>
                                                                <option value="Category 1">Male</option>
                                                                <option value="Category 1">Female</option>
                                                                <option value="Category 2">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Employee</label>
                                                        <div class="controls">
                                                            <select class="form-control" data-placeholder="Choose a Category"
                                                                tabindex="1">
                                                                <option value="">--select employee--</option>
                                                                <option value="Category 1">Ajay</option>
                                                                <option value="Category 1">Reena</option>
                                                                <option value="Category 2">Gita</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label text-light"> Date Form</label>
                                                        <input type="date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="control-label">DOB</label>
                                                        <input type="date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"> Attachements</label>
                                                        <input type="file" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Reason note</label>
                                                        <br>
                                                        <textarea placeholder="add reason note here..."
                                                            style="width: 100%; height: 80px;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-info">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- leave modal open js -->
            <script>
                $(document).ready(function () {
                    $("#openModalBtn3").click(function () {
                        $("#myModal3").modal("show");
                    });
                });
            </script>

            <!-- time store -->
            <script>
                document
                    .getElementById("openModalBtn")
                    .addEventListener("click", function () {
                        let currentTime = new Date().toLocaleTimeString();
                        document.getElementById("timeInput").value = currentTime;
                    });
            </script>


            <!-- Punch In Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                &times;
                            </button>
                            <h4 class="modal-title" style="color: black;">Punch In</h4>
                        </div>
                        <form id="add_form" action="javascript:void(0);" enctype="multipart/form-data" method="post">
                            @csrf
                            <div class="modal-body">
                                <video id="camera" width="100%" autoplay=""></video>
                                <canvas id="snapshot" style="display: none" width="640" height="480"></canvas>

                                <!-- Input Field and Capture Button -->
                                <div class="form-group">
                                    <input type="file" name="punchin_img" name="punchin_img" style="display: none"
                                        id="capturedImage" class="form-control" accept="image/png">
                                </div>
                                <button type="button" class="btn btn-success" id="captureBtn">
                                    Capture Image
                                </button>

                                <!-- Image tag to display the captured image -->
                                <img id="capturedImageDisplay" style="display: none; width: 100%; margin-top: 15px;"
                                    alt="Captured Image">

                                <!-- Button to capture again -->
                                <button type="button" class="btn btn-warning" id="captureAgainBtn"
                                    style="display: none; margin-top: 15px;">
                                    Capture Again
                                </button>

                                <!-- Input fields for date and time -->
                                <div style="margin-top: 15px">
                                    <label for="dateInput">Your punch in datetime</label>
                                    <input type="text" name="punchin_datetime" id="dateInput" class="form-control mt-3"
                                        placeholder="Current Date" readonly="">
                                </div>
                                <!-- <div style="margin-top: 15px">
                                                                                                                                                                                                                                                            <label for="timeInput">Your punch in time</label>
                                                                                                                                                                                                                                                            <input type="text" id="timeInput" class="form-control mt-3"
                                                                                                                                                                                                                                                                placeholder="Current Time" readonly="">
                                                                                                                                                                                                                                                        </div> -->
                                <textarea placeholder="Comment" name="punchin_note"
                                    style="width: 100%; height: 80px; margin-top: 15px" id="comment"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info add_btn">
                                    Submit
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--  punch in modal open  -->
            <script>
                const openModalBtn = document.getElementById("openModalBtn");
                const video = document.getElementById("camera");
                const captureBtn = document.getElementById("captureBtn");
                const captureAgainBtn = document.getElementById("captureAgainBtn");
                const canvas = document.getElementById("snapshot");
                const context = canvas.getContext("2d");
                const capturedImageInput = document.getElementById("capturedImage");
                const capturedImageDisplay = document.getElementById("capturedImageDisplay");
                const dateInput = document.getElementById("dateInput");
                const timeInput = document.getElementById("timeInput");
                let stream;

                openModalBtn.addEventListener("click", async () => {
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: true
                        });
                        video.srcObject = stream;
                        $("#myModal").modal("show");

                        // Set the current date and time in the same input
                        const now = new Date();
                        const dateTime = now.toLocaleString(); // Combine date and time
                        dateInput.value = dateTime; // Set the combined value

                    } catch (err) {
                        console.error("Camera access denied:", err);
                        alert("Camera access is required to proceed.");
                    }
                });

                captureBtn.addEventListener("click", () => {
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    canvas.toBlob((blob) => {
                        const file = new File([blob], "captured-image.png", {
                            type: "image/png",
                        });

                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);

                        capturedImageInput.files = dataTransfer.files;

                        // Display the captured image in the img tag
                        const imageURL = URL.createObjectURL(file);
                        capturedImageDisplay.src = imageURL;
                        capturedImageDisplay.style.display = "block";

                        // Hide the camera and show the capture again button
                        video.style.display = "none";
                        captureBtn.style.display = "none";
                        captureAgainBtn.style.display = "block";

                        // Disable the camera after capture
                        if (stream) {
                            stream.getTracks().forEach((track) => track.stop());
                        }
                    }, "image/png");
                });

                captureAgainBtn.addEventListener("click", async () => {
                    // Remove the captured image
                    capturedImageDisplay.src = "";
                    capturedImageDisplay.style.display = "none";

                    // Show the camera and capture button again
                    video.style.display = "block";
                    captureBtn.style.display = "block";
                    captureAgainBtn.style.display = "none";

                    // Reopen the camera
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: true
                        });
                        video.srcObject = stream;
                    } catch (err) {
                        console.error("Camera access denied:", err);
                        alert("Camera access is required to proceed.");
                    }
                });

                $("#myModal").on("hidden.bs.modal", function () {
                    if (stream) {
                        stream.getTracks().forEach((track) => track.stop());
                    }
                });
            </script>

            <!--  punch in modal open  -->
            <script>
                $(document).ready(function () {
                    $("#openModalBtn").on("click", function () {
                        $("#modalTitle").text("Add Task");
                        $("#myModal").modal("show");
                    });

                    $("#saveChangesBtn").on("click", function () {
                        alert("Your changes have been saved!");
                        $("#myModal").modal("hide");
                    });

                    $("#myModal").on("shown.bs.modal", function () {
                        console.log("Modal is now fully visible!");
                    });

                    $("#myModal").on("hidden.bs.modal", function () {
                        console.log("Modal has been closed.");
                    });
                });
            </script>

            <!--  After punch modal  -->
            <div id="secondModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                &times;
                            </button>
                            <h4 class="modal-title text-center" style="color: black; font-weight: bold">
                                Today Commitment
                            </h4>
                        </div>
                        <div class="modal-body">
                            <label class="text-dark control-label" style="font-weight: bold">Today Login
                                Commitment</label>
                            <input type="text" placeholder="" style="font-weight: bold" class="form-control">
                            <label class="text-dark control-label" style="font-weight: bold">Today Lead Commitment</label>
                            <input type="text" placeholder="" style="font-weight: bold" class="form-control">
                            <div>
                                <h4 class="modal-title text-center"
                                    style="color: black; font-weight: bold; margin-top: 20px !important">
                                    Today Work ToDo
                                </h4>
                                <p>1. Work 1</p>
                                <p>2. Work 2</p>
                                <p>3. Work 3</p>
                            </div>
                            <hr>
                            <!-- <i class="fa fa-comment theam_color" style="font-size: 25px; cursor: pointer"></i>  -->
                            <p style="font-weight: bold; margin: 0px;">
                                Write Comment Here
                            </p>
                            <div id="commentBox" class="comment-box">
                                <textarea placeholder="Write a comment..." style="width: 100%; height: 100px;"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- punch in modal submit button -->
            <script>
                $(document).ready(function () {
                    $("#submitBtn").click(function () {
                        $("#myModal").modal("hide"); // Hide the first modal
                        setTimeout(function () {
                            $("#secondModal").modal("show"); // Show the second modal
                        }, 500); // Small delay to ensure smooth transition
                    });
                });
            </script>




            <!-- Punch Out Modal -->
            <div id="myModal1" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                &times;
                            </button>
                            <h4 class="modal-title" style="color: black;">Punch Out</h4>
                        </div>

                        <form id="punchout_form" action="javascript:void(0);" enctype="multipart/form-data" method="post">
                            @csrf
                            <div class="modal-body">
                                <video id="camera1" width="100%" autoplay=""></video>
                                <canvas id="snapshot1" style="display: none" width="640" height="480"></canvas>

                                <div class="form-group">
                                    <input type="file" name="punchout_img" id="capturedImage1" style="display: none"
                                        class="form-control" accept="image/png">
                                </div>
                                <button type="button" class="btn btn-success" id="captureBtn1">
                                    Capture Image
                                </button>

                                <!-- Image tag to display the captured image -->
                                <img id="capturedImageDisplay1" style="display: none; width: 100%; margin-top: 15px;"
                                    alt="Captured Image">

                                <!-- Button to capture again -->
                                <button type="button" class="btn btn-warning" id="captureAgainBtn1"
                                    style="display: none; margin-top: 15px;">
                                    Capture Again
                                </button>

                                <!-- Input fields for date and time -->
                                <div style="display: flex; justify-content: space-between">
                                    <div style="margin-top: 20px; width: 45%">
                                        <label for="dateInput1">Your punch in datetime</label>
                                        <input type="text" id="dateInput1" value="{{ $attendance->punchin_datetime ?? '' }}"
                                            class="form-control mt-3" placeholder="Punch In Date" readonly="">
                                    </div>
                                    <div style="margin-top: 20px; width: 45%">
                                        <label for="dateInput2">Your punch out datetime</label>
                                        <input type="text" name="punchout_datetime" id="dateInput2" class="form-control mt-3"
                                            placeholder="Punch Out Date" readonly="">
                                    </div>

                                </div>


                                <textarea placeholder="Comment" name="punchout_note"
                                    style="width: 100%; height: 80px; margin-top: 15px" id="comment1"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info" id="submitBtn1">
                                    Submit
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- punch out modal open -->
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const video1 = document.getElementById("camera1");
                    const captureBtn1 = document.getElementById("captureBtn1");
                    const captureAgainBtn1 = document.getElementById("captureAgainBtn1");
                    const canvas1 = document.getElementById("snapshot1");
                    const context1 = canvas1.getContext("2d");
                    const capturedImageInput1 = document.getElementById("capturedImage1");
                    const capturedImageDisplay1 = document.getElementById("capturedImageDisplay1");
                    const dateInput2 = document.getElementById("dateInput2");
                    let stream1;

                    // Ensure punch-out datetime is always updated when the modal is shown
                    $("#myModal1").on("show.bs.modal", function () {
                        const now = new Date();
                        const dateTime = now.toLocaleString('en-US', { timeZone: 'Asia/Kolkata' });
                        dateInput2.value = dateTime;
                    });

                    if (document.getElementById("openModalBtn1")) {
                        document.getElementById("openModalBtn1").addEventListener("click", async () => {
                            try {
                                stream1 = await navigator.mediaDevices.getUserMedia({ video: true });
                                video1.srcObject = stream1;
                                $("#myModal1").modal("show");
                            } catch (err) {
                                console.error("Camera access denied:", err);
                                alert("Camera access is required to proceed.");
                            }
                        });
                    }

                    captureBtn1.addEventListener("click", () => {
                        context1.drawImage(video1, 0, 0, canvas1.width, canvas1.height);
                        canvas1.toBlob((blob) => {
                            const file = new File([blob], "captured-image.png", { type: "image/png" });

                            const dataTransfer1 = new DataTransfer();
                            dataTransfer1.items.add(file);
                            capturedImageInput1.files = dataTransfer1.files;

                            // Display captured image
                            const imageURL = URL.createObjectURL(file);
                            capturedImageDisplay1.src = imageURL;
                            capturedImageDisplay1.style.display = "block";

                            // Hide video and show "capture again" button
                            video1.style.display = "none";
                            captureBtn1.style.display = "none";
                            captureAgainBtn1.style.display = "block";

                            // Stop the camera
                            if (stream1) {
                                stream1.getTracks().forEach((track) => track.stop());
                                stream1 = null;
                            }
                        }, "image/png");
                    });

                    captureAgainBtn1.addEventListener("click", async () => {
                        capturedImageDisplay1.src = "";
                        capturedImageDisplay1.style.display = "none";

                        video1.style.display = "block";
                        captureBtn1.style.display = "block";
                        captureAgainBtn1.style.display = "none";

                        try {
                            stream1 = await navigator.mediaDevices.getUserMedia({ video: true });
                            video1.srcObject = stream1;
                        } catch (err) {
                            console.error("Camera access denied:", err);
                            alert("Camera access is required to proceed.");
                        }
                    });

                    $("#myModal1").on("hidden.bs.modal", function () {
                        if (stream1) {
                            stream1.getTracks().forEach((track) => track.stop());
                            stream1 = null;
                        }
                    });
                });
            </script>

            <!-- punch out store time -->
            <script>
                $("#myModal1").on("show.bs.modal", function () {
                    var currentDateTime = new Date();
                    var formattedDateTime = currentDateTime.toLocaleString();
                    $(this).find('input[type="text"]').val(formattedDateTime);
                });

                $("#captureBtn1").on("click", function () { });

                // Optional: Submit button click event
                $("#submitBtn1").on("click", function () { });
            </script>

            <script>
                const inputContainer = document.getElementById('ak_inputContainer');
                const totalHoursSpan = document.getElementById('ak_totalHours');
                const plusBtn = document.getElementById('ak_plusBtn');
                const errorMessage = document.getElementById('ak_errorMessage');

                // Function to update total hours
                function updateTotalHours() {
                    const hourInputs = document.querySelectorAll('.ak_input_group input[type="number"]');
                    let total = 0;
                    hourInputs.forEach(input => {
                        total += parseFloat(input.value) || 0;
                    });
                    totalHoursSpan.textContent = total;

                    // Validate total hours (not more than 7)
                    if (total > 7) {
                        errorMessage.textContent = "Total working hours cannot exceed 7 hours!";
                        plusBtn.disabled = true; // Disable "+" button
                    } else {
                        errorMessage.textContent = "";
                        plusBtn.disabled = false; // Enable "+" button
                    }
                }

                // Function to create a new input box group
                function createInputGroup() {
                    const inputGroup = document.createElement('div');
                    inputGroup.className = 'ak_input_group';
                    inputGroup.innerHTML = `
                                                                                                                                                                                                                                    <input type="text" placeholder="Work description">
                                                                                                                                                                                                                                    <input type="number" placeholder="Hours" min="0">
                                                                                                                                                                                                                                    <button class="ak_minus">-</button>
                                                                                                                                                                                                                                `;

                    // Add event listener to the minus button
                    inputGroup.querySelector('.ak_minus').addEventListener('click', () => {
                        if (inputContainer.children.length > 1) {
                            inputGroup.remove();
                            updateTotalHours();
                        } else {
                            errorMessage.textContent = "At least one input box must remain!";
                        }
                    });

                    // Update total when hours input changes
                    inputGroup.querySelector('input[type="number"]').addEventListener('input', updateTotalHours);

                    return inputGroup;
                }

                // Add initial input box
                inputContainer.appendChild(createInputGroup());

                // Add new input box when "+" button is clicked
                plusBtn.addEventListener('click', () => {
                    inputContainer.appendChild(createInputGroup());
                    updateTotalHours();
                });
            </script>


            <script>
                $(document).ready(function () {
                    $("#submitBtn1").click(function () {
                        $("#myModal1").modal("hide");
                        setTimeout(function () {
                            $("#myModal2").modal("show");
                        }, 500);
                    });
                });
            </script>

            <!-- td modal js -->
            <script>
                $(document).ready(function () {
                    // Helper function to preload images
                    function preloadImage(url, callback) {
                        var img = new Image();
                        img.onload = function () {
                            callback(url, true);
                        };
                        img.onerror = function () {
                            callback(url, false);
                        };
                        img.src = url;
                    }
                    $(".opentdModal").click(function () {
                        // Get employee data from clicked cell
                        var employeeName = $(this).data('employee-name');
                        var employeeId = $(this).data('employee-id');
                        var employeeDept = $(this).data('employee-dept');
                        var employeeRole = $(this).data('employee-role');
                        var attendanceStatus = $(this).data('attendance-status');
                        var attendanceDate = $(this).data('attendance-date');

                        // Get punch data
                        var punchInTime = $(this).data('punch-in-time');
                        var punchOutTime = $(this).data('punch-out-time');

                        // Get image paths with better fallback handling
                        var punchInImg = $(this).data('punch-in-img');
                        var punchOutImg = $(this).data('punch-out-img');

                        // For debugging: Log the values to console
                        console.log('Punch in image:', punchInImg);
                        console.log('Punch out image:', punchOutImg);

                        // Default image if null, undefined, or empty string
                        var defaultImg = 'https://t3.ftcdn.net/jpg/02/43/12/34/360_F_243123463_zTooub557xEWABDLk0jJklDyLSGl2jrr.jpg';

                        // Check if image paths are valid and not empty
                        punchInImg = (punchInImg && punchInImg.trim() !== '') ? punchInImg : defaultImg;
                        punchOutImg = (punchOutImg && punchOutImg.trim() !== '') ? punchOutImg : defaultImg;

                        // Format date from YYYY-MM-DD to a more readable format
                        var formattedDate = new Date(attendanceDate).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });

                        // Update modal title and employee details
                        $("#tdModal .modal-title").text(employeeName);
                        $("#tdModal .employee-department").text(employeeDept + " | " + employeeRole);
                        $("#tdModal .employee-id").text("Emp-ID: " + employeeId);
                        $("#tdModal .attendance-status").text("Attendance: " + attendanceStatus);

                        // Update hidden fields
                        $("#employee-id-hidden").val(employeeId);
                        $("#attendance-date").val(attendanceDate);

                        // Check if employee is absent or has no punch details
                        // For debugging
                        console.log("Attendance Status:", attendanceStatus, "Type:", typeof attendanceStatus);
                        console.log("Punch In Time:", punchInTime);
                        console.log("Punch Out Time:", punchOutTime);

                        // More precise check for absence
                        var isAbsent = attendanceStatus === '0' ||
                            attendanceStatus === 0 ||
                            attendanceStatus === 'absent' ||
                            attendanceStatus === 'leave' ||
                            attendanceStatus === 'leave approved' ||
                            attendanceStatus === 'leave not approved';

                        // Only consider missing punch data for present employees
                        var isPresent = attendanceStatus === '1' ||
                            attendanceStatus === 1 ||
                            attendanceStatus === '0.5' ||
                            attendanceStatus === 0.5;

                        var hasNoPunchData = isPresent && !punchInTime && !punchOutTime;

                        console.log("Is Absent:", isAbsent);
                        console.log("Is Present:", isPresent);
                        console.log("Has No Punch Data:", hasNoPunchData);

                        if (isAbsent) {
                            // Show the no attendance message and hide attendance details
                            $("#tdModal .no-attendance-message").show();
                            $("#tdModal .attendance-details-container").hide();

                            // Set a more specific absence message
                            var absenceReason = "";
                            if (attendanceStatus === '0' || attendanceStatus === 0 || attendanceStatus === 'absent') {
                                absenceReason = "This employee was marked as absent on this date.";
                            } else if (attendanceStatus === 'leave' || attendanceStatus.includes('leave')) {
                                absenceReason = "This employee was on leave on this date.";
                            }

                            $("#tdModal .absence-reason").text(absenceReason);
                        } else {
                            // Hide the no attendance message and show attendance details
                            $("#tdModal .no-attendance-message").hide();
                            $("#tdModal .attendance-details-container").show();

                            // Update punch in details with error handling
                            var $punchInImg = $("#tdModal .punch-in-img");

                            // Check if punch in time exists
                            if (punchInTime) {
                                $punchInImg.attr("src", punchInImg)
                                    .on("error", function () {
                                        // If image fails to load, replace with default
                                        $(this).attr("src", "https://t3.ftcdn.net/jpg/02/43/12/34/360_F_243123463_zTooub557xEWABDLk0jJklDyLSGl2jrr.jpg");
                                        console.log("Punch in image failed to load, using default");
                                    });

                                $("#tdModal .punch-in-date").text("Punch In Date: " + formattedDate);
                                $("#tdModal .punch-in-time").text("Punch In Time: " + punchInTime);
                            } else {
                                // No punch in time available
                                $punchInImg.attr("src", "https://t3.ftcdn.net/jpg/02/43/12/34/360_F_243123463_zTooub557xEWABDLk0jJklDyLSGl2jrr.jpg");
                                $("#tdModal .punch-in-date").text("Punch In Date: " + formattedDate);
                                $("#tdModal .punch-in-time").text("Punch In Time: Not recorded");
                            }

                            // Update punch out details with error handling
                            var $punchOutImg = $("#tdModal .punch-out-img");

                            // Check if punch out time exists
                            if (punchOutTime) {
                                $punchOutImg.attr("src", punchOutImg)
                                    .on("error", function () {
                                        // If image fails to load, replace with default
                                        $(this).attr("src", "https://t3.ftcdn.net/jpg/02/43/12/34/360_F_243123463_zTooub557xEWABDLk0jJklDyLSGl2jrr.jpg");
                                        console.log("Punch out image failed to load, using default");
                                    });

                                $("#tdModal .punch-out-date").text("Punch Out Date: " + formattedDate);
                                $("#tdModal .punch-out-time").text("Punch Out Time: " + punchOutTime);
                            } else {
                                // No punch out time available
                                $punchOutImg.attr("src", "https://t3.ftcdn.net/jpg/02/43/12/34/360_F_243123463_zTooub557xEWABDLk0jJklDyLSGl2jrr.jpg");
                                $("#tdModal .punch-out-date").text("Punch Out Date: " + formattedDate);
                                $("#tdModal .punch-out-time").text("Punch Out Time: Not recorded");
                            }
                        }

                        // Set the current status in the dropdown
                        $("#attendance-select").val(attendanceStatus);

                        // Clear any previous comments
                        $("#attendance-comments").val("");

                        // Show the modal
                        $("#tdModal").modal("show");
                    });

                    // Handle form submission
                    $("#submitBtn").click(function () {
                        var newAttendanceStatus = $("#attendance-select").val();
                        var comments = $("#attendance-comments").val();
                        var employeeId = $("#employee-id-hidden").val();
                        var attendanceDate = $("#attendance-date").val();

                        // Show loading state
                        var $btn = $(this);
                        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

                        // Send data to the server with Ajax
                        $.ajax({
                            url: "/update-attendance",
                            method: "POST",
                            data: {
                                admin_id: employeeId,
                                date: attendanceDate,
                                attendance_status: newAttendanceStatus,
                                comments: comments,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                // Show success notification
                                toastr.success('Attendance updated successfully');

                                // Close modal
                                $("#tdModal").modal("hide");

                                // Reload the page after a brief delay to show the updated data
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            },
                            error: function (error) {
                                // Reset button state
                                $btn.prop('disabled', false).text('Update Attendance');

                                // Show error notification
                                toastr.error('Error updating attendance: ' + (error.responseJSON ? error.responseJSON.message : 'Unknown error'));
                            }
                        });
                    });
                });
            </script>


            <!-- Attendance Details Modal -->
            <div id="tdModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" style="color: black;">Employee Name</h4>
                            <p class="employee-department">Department | Role</p>
                            <p class="employee-id">Emp-ID: </p>
                            <p class="attendance-status">Attendance: </p>
                        </div>
                        <div class="modal-body">
                            <div class="image-container">
                                <div class="container">
                                    <div class="row">
                                        <!-- No Attendance Message - Hidden by default -->
                                        <div class="col-sm-12 no-attendance-message" style="display: none;">
                                            <div class="alert alert-warning text-center">
                                                <i class="fa fa-exclamation-circle fa-2x mb-2"></i>
                                                <h4>No Attendance Details Found</h4>
                                                <p>This employee was marked as absent on this date.</p>
                                            </div>
                                        </div>

                                        <!-- Attendance Details Container - Will be hidden when absent -->
                                        <div class="attendance-details-container">
                                            <!-- Punch In Details -->
                                            <div class="col-sm-6">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">Check In</h4>
                                                    </div>
                                                    <div class="panel-body text-center">
                                                        <img src="" alt="Punch In Image" height="120px"
                                                            class="punch-in-img img-thumbnail">
                                                        <div class="punch-details mt-3">
                                                            <p class="punch-in-date">Punch In Date: </p>
                                                            <p class="punch-in-time">Punch In Time: </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Punch Out Details -->
                                            <div class="col-sm-6">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">Check Out</h4>
                                                    </div>
                                                    <div class="panel-body text-center">
                                                        <img src="" alt="Punch Out Image" height="120px"
                                                            class="punch-out-img img-thumbnail">
                                                        <div class="punch-details mt-3">
                                                            <p class="punch-out-date">Punch Out Date: </p>
                                                            <p class="punch-out-time">Punch Out Time: </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Attendance Update Form -->
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="attendance-select"><b>Change Attendance: </b></label>
                                                <select name="attendance_status" id="attendance-select" class="form-control">
                                                    <option value="1">Full Day (1)</option>
                                                    <option value="0.5">Half Day (0.5)</option>
                                                    <option value="0">Absent (0)</option>
                                                    <option value="leave">Leave</option>
                                                    <option value="leave approved">Leave Approved</option>
                                                    <option value="paid leave">Paid Leave</option>
                                                    <option value="earned leave">Earned Leave</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="attendance-comments"><b>Comments: </b></label>
                                                <textarea id="attendance-comments" name="comments" class="form-control wysihtml5"
                                                    rows="4" placeholder="Add comments about this attendance record..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="attendance-date" name="date" value="">
                            <input type="hidden" id="employee-id-hidden" name="admin_id" value="">
                            <button type="button" class="btn btn-info" id="submitBtn">
                                Update Attendance
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>




        @endsection
    @endforeach
@else
    <script>
        window.location.href = "{{ url('/login') }}";
    </script>
@endif
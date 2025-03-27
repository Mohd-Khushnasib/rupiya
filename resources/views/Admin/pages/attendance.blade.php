@if(session()->get('admin_login'))
    @foreach(session()->get('admin_login') as $adminlogin)
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
                        <h2>Monthly Attendance - February 2025
                        </h2>
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
                                        <th class="name-header">Name</th>
                                        <th class="day-header">1 <br> Sat</th>
                                        <th class="day-header">2 <br> Sun</th>
                                        <th class="day-header sorting_disabled">3 <br> Mon</th>
                                        <th class="day-header sorting_disabled">4 <br> Tue</th>
                                        <th class="day-header">5 <br> Wed</th>
                                        <th class="day-header">6 <br> Thu</th>
                                        <th class="day-header">7 <br> Fri</th>
                                        <th class="day-header">8 <br> Sat</th>
                                        <th class="day-header">9 <br> Sun</th>
                                        <th class="day-header">10 <br> Mon</th>
                                        <th class="day-header">11 <br> Tue</th>
                                        <th class="day-header">12 <br> Wed</th>
                                        <th class="day-header">13 <br> Thu</th>
                                        <th class="day-header">14 <br> Fri</th>
                                        <th class="day-header">15 <br> Sat</th>
                                        <th class="day-header">16 <br> Sun</th>
                                        <th class="day-header">17 <br> Mon</th>
                                        <th class="day-header">18 <br> Tue</th>
                                        <th class="day-header">19 <br> Wed</th>
                                        <th class="day-header">20 <br> Thu</th>
                                        <th class="day-header">21 <br> Fri</th>
                                        <th class="day-header">22 <br> Sat</th>
                                        <th class="day-header">23 <br> Sun</th>
                                        <th class="day-header">24 <br> Mon</th>
                                        <th class="day-header">25 <br> Tue</th>
                                        <th class="day-header">26 <br> Wed</th>
                                        <th class="day-header">27 <br> Thu</th>
                                        <th class="day-header">28 <br> Fri</th>
                                        <th class="day-header">29 <br> Sat</th>
                                        <th class="day-header">30 <br> Sun</th>
                                        <th class="day-header">Total <br> Present</th>
                                        <th class="day-header">Paid <br> Leave</th>
                                        <th class="day-header">Earned <br> Leave</th>
                                        <th class="day-header">Final <br> Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="name-cell" style="display: flex; gap: 10px; align-items: center;">
                                            <div>
                                                <img src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&amp;q=60&amp;w=3000&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D"
                                                    alt="" height="50px" width="50px"
                                                    style="object-fit: cover; border-radius: 50%;">
                                            </div>
                                            <div style="text-align: start;">
                                                <span style="font-size: 17px;"><b>Ravindra</b></span><br>
                                                <span style="font-size: 12px;">Achievers | Sr. Executive</span><br>
                                                <span style="font-size: 12px;">Emp-ID : RM02</span>
                                            </div>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal off"><b>1</b><br>Off
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal off"><b>1</b><br>Off
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal off"><b>1</b><br>Off
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal off"><b>1</b><br>Off
                                        </td>
                                        <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full day
                                            </span>
                                        </td>
                                        <td class="opentdModal half-day"><b>0.5</b><br><span>6:00am - 12:00pm <br> half
                                                day</span>
                                        </td>
                                        <td class="opentdModal half-day"><b>0.5</b><br><span>6:00am - 12:00pm <br> half
                                                day</span>
                                        </td>
                                        <td class="opentdModal half-day"><b>0.5</b><br><span>6:00am - 12:00pm <br> half
                                                day</span>
                                        </td>
                                        <td class="opentdModal leave-approve"><b>0</b><br><span>Leave</span></td>
                                        <td class="opentdModal leave-not-approve"><b>-1</b><br><span>Leave</span></td>
                                        <td class="opentdModal off"><b>1</b><br><span>Off</span></td>
                                        <td>26.5</td>
                                        <td>1</td>
                                        <td>0</td>
                                        <td>27.5</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- table end -->

                <!-- END Main Content -->
            </div>
            <!-- END Content -->
        </div>


            <!-- live time -->
    <script>
        function updateTime() {
            const button = document.getElementById('timeButton');
            const currentTime = new Date().toLocaleTimeString(); // Get current time
            button.innerText = `Time: ${currentTime}`; // Update button text
        }
        // Update the time every 1000ms (1 second)
        setInterval(updateTime, 1000);
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





        @endsection
    @endforeach
@else
    <script>
        window.location.href = "{{url('/login')}}";
    </script>
@endif
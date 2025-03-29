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

                /* <!-- After punch out modal --> */
                .ak_custom-table {
                    width: 100%;
                    border-collapse: collapse;
                    text-align: center;
                    font-family: Arial, sans-serif;
                }

                .ak_custom-table th,
                .ak_custom-table td {
                    background-color: white !important;
                    color: black !important;
                    border: 1px solid black !important;
                    padding: 10px !important;
                }

                .ak_custom-table th {
                    font-weight: bold !important;
                    background-color: #f4f4f4 !important;
                }

                .ak_custom-table input {
                    width: 80px !important;
                    padding: 5px !important;
                    text-align: center !important;
                    border: 1px solid #ccc !important;
                    border-radius: 5px !important;
                    background: #ffff99 !important;
                    font-weight: bold !important;
                }
            </style>

            <div class="container" id="main-container">
                <!-- BEGIN Content -->
                <div id="main-content">
                    <!-- BEGIN Page Title -->
                    <div class="page-title">
                        <div>
                            <h1 class="theam_color_text">
                                <i class="fa fa-bars theam_color"></i> Daily Performance
                            </h1>
                        </div>
                    </div>
                    <!-- BEGIN Main Content -->
                    <!-- <div class="row" style="margin-top: 20px">
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
                                                                </div> -->
                    <div class="row"
                        style="width: 100%; display: flex; gap: 10px; align-items: center; justify-content: center; margin: 10px 0;">
                        <div style="border: none; padding: 0 10px; border-radius: 10px;">
                            <h2>Daily Performance - February 2025
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
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
                                            <td class="opentdModal"><span class="badge" style="background-color: green;">C : 5 | A :
                                                    2</span> <br> <br>Best 😍</td>
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


            <div id="tdModal" class="modal fade" role="dialog">
                <div class="modal-dialog" style="width: fit-content;">
                    <div class="modal-content" style="width: fit-content;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                &times;
                            </button>
                            <h4 class="modal-title text-center" style="color: black; font-weight: bold">
                                Daily Work Report
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="ak_container" style="position: relative; width: fit-content;">
                                <h4 class="ak_heading">Commitment vs Achievement</h4>
                                <table class="ak_custom-table">
                                    <thead>
                                        <tr>
                                            <th class="ak_table-header" style="width: 20px;"></th>
                                            <th class="ak_table-header" style="width: 20px;">Commitment</th>
                                            <th class="ak_table-header" style="width: 20px;">Achievement</th>
                                            <th class="ak_table-header" style="width: 20px;">Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="ak_table-row">
                                            <td class="ak_table-cell">Leads</td>
                                            <td class="ak_table-cell">3</td>
                                            <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                            <td class="ak_table-cell"></td>
                                        </tr>
                                        <tr class="ak_table-row">
                                            <td class="ak_table-cell">Logins</td>
                                            <td class="ak_table-cell">3</td>
                                            <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                            <td class="ak_table-cell"></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            <div class="ak_container" style="margin-top: 20px;">
                                <h4 class="ak_heading">Your Working Hour</h4>
                                <table class="ak_custom-table">
                                    <thead>
                                        <tr>
                                            <th class="ak_table-header">Type</th>
                                            <th class="ak_table-header">Time</th>
                                            <th class="ak_table-header">Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="ak_table-row">
                                            <td class="ak_table-cell">Working Hour</td>
                                            <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                            <td class="ak_table-cell"></td>
                                        </tr>
                                        <tr class="ak_table-row">
                                            <td class="ak_table-cell">Break</td>
                                            <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                            <td class="ak_table-cell"></td>
                                        </tr>
                                        <tr class="ak_table-row">
                                            <td class="ak_table-cell">Wrap Up Time</td>
                                            <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                            <td class="ak_table-cell"></td>
                                        </tr>
                                        <tr class="ak_table-row">
                                            <td class="ak_table-cell">Meeting</td>
                                            <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                            <td class="ak_table-cell"></td>
                                        </tr>
                                        <tr class="ak_table-row">
                                            <td class="ak_table-cell">Other Work</td>
                                            <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                            <td class="ak_table-cell"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="ak_total_box">
                                    Total Working Hours: <span id="ak_totalHours">0</span>
                                </div>
                                <div id="ak_errorMessage" class="ak_error"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- First, ensure these scripts are properly included in your master layout -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

            <!-- Replace your existing script with this updated version -->
            <script>
                $(document).ready(function () {
                    // Make sure jQuery is loaded
                    if (typeof jQuery != 'undefined') {
                        console.log("jQuery is loaded");

                        // Use direct click binding with event delegation
                        $(document).on('click', '.opentdModal', function () {
                            console.log("Modal trigger clicked");
                            $('#tdModal').modal('show');
                        });

                        // Alternative method - add direct click handlers to each cell
                        $('.opentdModal').each(function () {
                            $(this).on('click', function () {
                                console.log("Cell clicked");
                                $('#tdModal').modal('show');
                            });
                        });

                        // Test if modal can be shown programmatically
                        // Uncomment this to test if modal works at all
                        // setTimeout(function() {
                        //     $('#tdModal').modal('show');
                        // }, 2000);
                    } else {
                        console.error("jQuery is not loaded!");
                    }
                });
            </script>
        @endsection
    @endforeach
@else
    <script>
        window.location.href = "{{url('/login')}}";
    </script>
@endif
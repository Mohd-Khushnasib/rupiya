{{-- Correct structure for Blade template --}}
@if(session()->get('admin_login'))
    @extends('Admin.layouts.master')
    @section('main-content')

    {{-- Add necessary CSS styles --}}
    <style>
        /* Main styles for attendance dashboard */
        .punch_card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .punch_card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .punch_card_text {
            margin: 10px 0;
            color: #333;
        }
        
        /* Table styles */
        .custom-table {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
        }
        
        .table-container {
            overflow-x: auto;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .name-header {
            min-width: 180px;
            position: sticky;
            left: 0;
            background-color: #f5f5f5;
            z-index: 1;
        }
        
        .day-header {
            text-align: center;
            min-width: 80px;
        }
        
        .name-cell {
            position: sticky;
            left: 0;
            background-color: white;
            z-index: 1;
        }
        
        /* Attendance status styles */
        .full-day {
            background-color: #d4edda !important;
            color: #155724 !important;
            cursor: pointer;
        }
        
        .half-day {
            background-color: #fff3cd !important;
            color: #856404 !important;
            cursor: pointer;
        }
        
        .off {
            background-color: #e2e3e5 !important;
            color: #383d41 !important;
            cursor: pointer;
        }
        
        .leave-approve {
            background-color: #cce5ff !important;
            color: #004085 !important;
            cursor: pointer;
        }
        
        .leave-not-approve {
            background-color: #f8d7da !important;
            color: #721c24 !important;
            cursor: pointer;
        }
        
        /* Modal styles */
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .modal-title {
            color: #333;
            font-weight: bold;
        }
        
        .theam_color {
            color: #007bff;
        }
        
        .theam_color_text {
            color: #333;
        }
        
        /* Fix for select boxes */
        .form-control.chosen {
            height: auto;
            padding: 8px 12px;
        }
        
        /* Make the form more responsive */
        @media (max-width: 768px) {
            .col-sm-6 {
                width: 100%;
                margin-bottom: 15px;
            }
        }
    </style>

    <!-- Main Content Start Here  -->
    <div class="container" id="main-container">
        <div id="main-content">
            <div class="page-title">
                <div>
                    <h1 class="theam_color_text">
                        <i class="fa fa-user theam_color"></i> Employee Name
                    </h1>
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card punch_card">
                        <h3 class="punch_card_text">Full Day</h3>
                        <h4 class="punch_card_text">12</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card punch_card">
                        <h3 class="punch_card_text">Half Day</h3>
                        <h4 class="punch_card_text">30</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card punch_card">
                        <h3 class="punch_card_text">Absent</h3>
                        <h4 class="punch_card_text">29</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card punch_card">
                        <h3 class="punch_card_text">Total Day</h3>
                        <h4 class="punch_card_text">30</h4>
                    </div>
                </div>
            </div>
            <div class="row"
                style="width: 100%; display: flex; gap: 10px; align-items: center; justify-content: center; margin: 20px 0;">
                <div style="border: none; padding: 0 10px; border-radius: 10px; width: 100%;">
                    <h2 class="text-center">Monthly Attendance - February 2025</h2>
                    <div class="row">
                        <div class="col-sm-6">
                            <select name="employee" id="employee_filter" data-placeholder="Choose Employee"
                                style="background-color: whitesmoke !important;"
                                class="form-control chosen" tabindex="-1">
                                <option value="all">All Employee</option>
                                <option value="emp1">EMP 1</option>
                                <option value="emp2">EMP 2</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="month" id="month_filter" data-placeholder="Choose Month"
                                style="background-color: whitesmoke !important;"
                                class="form-control chosen" tabindex="-1">
                                <option value="all">Choose Month</option>
                                <option value="01">January</option>
                                <option value="02" selected>February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
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
        </div>
    </div>
    <!-- Main Content End Here  -->

    <!-- All Modals Here -->
    
    <!-- Leave request modal -->
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
                <div class="modal-body">
                    <video id="camera" width="100%" autoplay=""></video>
                    <canvas id="snapshot" style="display: none" width="640" height="480"></canvas>

                    <!-- Input Field and Capture Button -->
                    <div class="form-group">
                        <input type="file" style="display: none" id="capturedImage" class="form-control" accept="image/png">
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
                        <label for="dateInput">Your punch in date</label>
                        <input type="text" id="dateInput" class="form-control mt-3" placeholder="Current Date" readonly="">
                    </div>
                    <div style="margin-top: 15px">
                        <label for="timeInput">Your punch in time</label>
                        <input type="text" id="timeInput" class="form-control mt-3" placeholder="Current Time" readonly="">
                    </div>
                    <textarea placeholder="Comment" style="width: 100%; height: 80px; margin-top: 15px"
                        id="comment"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="submitBtn">
                        Submit
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- After punch modal -->
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
                    <label class="text-dark control-label" style="font-weight: bold">Today Login Commitment</label>
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
                <div class="modal-body">
                    <video id="camera1" width="100%" autoplay=""></video>
                    <canvas id="snapshot1" style="display: none" width="640" height="480"></canvas>

                    <div class="form-group">
                        <input type="file" id="capturedImage1" style="display: none" class="form-control"
                            accept="image/png">
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
                            <label for="dateInput1">Your punch in date</label>
                            <input type="text" id="dateInput1" class="form-control mt-3" placeholder="Punch In Date"
                                readonly="">
                        </div>
                        <div style="margin-top: 20px; width: 45%">
                            <label for="timeInput1">Your punch in time</label>
                            <input type="text" id="timeInput1" class="form-control mt-3" placeholder="Punch In Time"
                                readonly="">
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between">
                        <div style="margin-top: 20px; width: 45%">
                            <label for="dateInput2">Your punch out date</label>
                            <input type="text" id="dateInput2" class="form-control mt-3" placeholder="Punch Out Date"
                                readonly="">
                        </div>
                        <div style="margin-top: 20px; width: 45%">
                            <label for="timeInput2">Your punch out time</label>
                            <input type="text" id="timeInput2" class="form-control mt-3" placeholder="Punch Out Time"
                                readonly="">
                        </div>
                    </div>

                    <textarea placeholder="Comment" style="width: 100%; height: 80px; margin-top: 15px"
                        id="comment1"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="submitBtn1">
                        Submit
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- After punch out modal (Daily Work Report) -->
    <div id="myModal2" class="modal fade" role="dialog">
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
                    <div style="margin-top: 20px">
                        <p style="font-weight: bold; margin: 0px;">
                            Write Comment Here
                        </p>
                        <div id="commentBox" class="comment-box">
                            <textarea placeholder="Write a comment..." style="width: 100%; height: 100px;"></textarea>
                        </div>
                    </div>
                    <div style="margin-top: 20px">
                        <h4 class="text-center" style="color: black; font-weight: bold">
                            all todo task show here
                        </h4>
                        <div style="margin-top: 15px">
                            <input type="checkbox" name="" id="">
                            <span style="font-weight: bold">1. All Workers name show here</span>
                        </div>
                        <div style="margin-top: 13px">
                            <input type="checkbox" checked="" name="" id="">
                            <span style="
                color: green !important;
                margin-top: 15px;
                font-weight: bold;
              ">1. <del>All Workers name show here</del></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Details Modal -->
    <div id="tdModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="color: black;">Ravindra</h4>
                    <p>Achievers | Sr. Executive</p>
                    <p>Emp-ID : RM02</p>
                    <p>Attendance : 1</p>
                </div>
                <div class="modal-body">
                    <div class="image-container"
                        style="width: 100%; display: flex; align-items: center; justify-content: space-around;">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div>
                                        <img src="https://t3.ftcdn.net/jpg/02/43/12/34/360_F_243123463_zTooub557xEWABDLk0jJklDyLSGl2jrr.jpg"
                                            alt="Punch In Image" height="120px">
                                        <div class="punch-details">
                                            <p>Punch In Date: 2023-10-01</p>
                                            <p>Punch In Time: 09:00 AM</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div>
                                        <img src="https://t3.ftcdn.net/jpg/02/43/12/34/360_F_243123463_zTooub557xEWABDLk0jJklDyLSGl2jrr.jpg"
                                            alt="Punch Out Image" height="120px">
                                        <div class="punch-details">
                                            <p>Punch Out Date: 2023-10-01</p>
                                            <p>Punch Out Time: 06:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="col-sm-12">
                                    <label for=""><b>Change Attendance : </b></label>
                                    <b>
                                        <select name="" id="">
                                            <option value="1">Full Day (1)</option>
                                            <option value="0.5">Half Day (0.5)</option>
                                            <option value="0">Leave (0)</option>
                                            <option value="-1">Leave Not Approved (-1)</option>
                                        </select>
                                    </b>
                                </p>
                                <p class="col-sm-12" style="margin-top: 10px;"><textarea class="form-control wysihtml5"
                                        rows="6"></textarea></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="submitBtn2">
                        Submit
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Fixes -->
    <script>
        // Unified scripts to fix conflicts

        // Leave modal
        $(document).ready(function () {
            $("#openModalBtn3").click(function () {
                $("#myModal3").modal("show");
            });
        });

        // Time tracking for punch in
        document
            .getElementById("openModalBtn")?.addEventListener("click", function () {
                let currentTime = new Date().toLocaleTimeString();
                document.getElementById("timeInput").value = currentTime;
            });

        // Camera access for punch in
        const handlePunchInModal = () => {
            const openModalBtn = document.getElementById("openModalBtn");
            const video = document.getElementById("camera");
            const captureBtn = document.getElementById("captureBtn");
            const captureAgainBtn = document.getElementById("captureAgainBtn");
            const canvas = document.getElementById("snapshot");
            const context = canvas?.getContext("2d");
            const capturedImageInput = document.getElementById("capturedImage");
            const capturedImageDisplay = document.getElementById("capturedImageDisplay");
            const dateInput = document.getElementById("dateInput");
            const timeInput = document.getElementById("timeInput");
            let stream;

            if (openModalBtn) {
                openModalBtn.addEventListener("click", async () => {
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({ video: true });
                        if (video) video.srcObject = stream;
                        $("#myModal").modal("show");

                        // Set the current date and time
                        const now = new Date();
                        const date = now.toLocaleDateString();
                        const time = now.toLocaleTimeString();
                        if (dateInput) dateInput.value = date;
                        if (timeInput) timeInput.value = time;
                    } catch (err) {
                        console.error("Camera access denied:", err);
                        alert("Camera access is required to proceed.");
                    }
                });
            }

            if (captureBtn) {
                captureBtn.addEventListener("click", () => {
                    if (!context || !video) return;
                    
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    canvas.toBlob((blob) => {
                        const file = new File([blob], "captured-image.png", {
                            type: "image/png",
                        });

                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);

                        if (capturedImageInput) capturedImageInput.files = dataTransfer.files;

                        // Display the captured image in the img tag
                        const imageURL = URL.createObjectURL(file);
                        if (capturedImageDisplay) {
                            capturedImageDisplay.src = imageURL;
                            capturedImageDisplay.style.display = "block";
                        }

                        // Hide the camera and show the capture again button
                        if (video) video.style.display = "none";
                        if (captureBtn) captureBtn.style.display = "none";
                        if (captureAgainBtn) captureAgainBtn.style.display = "block";

                        // Disable the camera after capture
                        if (stream) {
                            stream.getTracks().forEach((track) => track.stop());
                        }
                    }, "image/png");
                });
            }

            if (captureAgainBtn) {
                captureAgainBtn.addEventListener("click", async () => {
                    // Remove the captured image
                    if (capturedImageDisplay) {
                        capturedImageDisplay.src = "";
                        capturedImageDisplay.style.display = "none";
                    }

                    // Show the camera and capture button again
                    if (video) video.style.display = "block";
                    if (captureBtn) captureBtn.style.display = "block";
                    if (captureAgainBtn) captureAgainBtn.style.display = "none";

                    // Reopen the camera
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({ video: true });
                        if (video) video.srcObject = stream;
                    } catch (err) {
                        console.error("Camera access denied:", err);
                        alert("Camera access is required to proceed.");
                    }
                });
            }

            // Clean up when modal is closed
            $("#myModal").on("hidden.bs.modal", function () {
                if (stream) {
                    stream.getTracks().forEach((track) => track.stop());
                }
            });
        };

        // Camera access for punch out
        const handlePunchOutModal = () => {
            const openModalBtn1 = document.getElementById("openModalBtn1");
            const video1 = document.getElementById("camera1");
            const captureBtn1 = document.getElementById("captureBtn1");
            const captureAgainBtn1 = document.getElementById("captureAgainBtn1");
            const canvas1 = document.getElementById("snapshot1");
            const context1 = canvas1?.getContext("2d");
            const capturedImageInput1 = document.getElementById("capturedImage1");
            const capturedImageDisplay1 = document.getElementById("capturedImageDisplay1");
            const dateInput1 = document.getElementById("dateInput1");
            const timeInput1 = document.getElementById("timeInput1");
            const dateInput2 = document.getElementById("dateInput2");
            const timeInput2 = document.getElementById("timeInput2");
            let stream1;

            if (openModalBtn1) {
                openModalBtn1.addEventListener("click", async () => {
                    try {
                        stream1 = await navigator.mediaDevices.getUserMedia({ video: true });
                        if (video1) video1.srcObject = stream1;
                        $("#myModal1").modal("show");

                        // Set the current date and time
                        const now = new Date();
                        const date = now.toLocaleDateString();
                        const time = now.toLocaleTimeString();
                        if (dateInput1) dateInput1.value = date;
                        if (timeInput1) timeInput1.value = time;
                        if (dateInput2) dateInput2.value = date;
                        if (timeInput2) timeInput2.value = time;
                    } catch (err) {
                        console.error("Camera access denied:", err);
                        alert("Camera access is required to proceed.");
                    }
                });
            }

            if (captureBtn1) {
                captureBtn1.addEventListener("click", () => {
                    if (!context1 || !video1) return;
                    
                    context1.drawImage(video1, 0, 0, canvas1.width, canvas1.height);
                    canvas1.toBlob((blob) => {
                        const file = new File([blob], "captured-image.png", {
                            type: "image/png",
                        });

                        const dataTransfer1 = new DataTransfer();
                        dataTransfer1.items.add(file);

                        if (capturedImageInput1) capturedImageInput1.files = dataTransfer1.files;

                        // Display the captured image in the img tag
                        const imageURL = URL.createObjectURL(file);
                        if (capturedImageDisplay1) {
                            capturedImageDisplay1.src = imageURL;
                            capturedImageDisplay1.style.display = "block";
                        }

                        // Hide the camera and show the capture again button
                        if (video1) video1.style.display = "none";
                        if (captureBtn1) captureBtn1.style.display = "none";
                        if (captureAgainBtn1) captureAgainBtn1.style.display = "block";

                        // Disable the camera after capture
                        if (stream1) {
                            stream1.getTracks().forEach((track) => track.stop());
                            stream1 = null;
                        }
                    }, "image/png");
                });
            }

            if (captureAgainBtn1) {
                captureAgainBtn1.addEventListener("click", async () => {
                    // Remove the captured image
                    if (capturedImageDisplay1) {
                        capturedImageDisplay1.src = "";
                        capturedImageDisplay1.style.display = "none";
                    }

                    // Show the camera and capture button again
                    if (video1) video1.style.display = "block";
                    if (captureBtn1) captureBtn1.style.display = "block";
                    if (captureAgainBtn1) captureAgainBtn1.style.display = "none";

                    // Reopen the camera
                    try {
                        stream1 = await navigator.mediaDevices.getUserMedia({ video: true });
                        if (video1) video1.srcObject = stream1;
                    } catch (err) {
                        console.error("Camera access denied:", err);
                        alert("Camera access is required to proceed.");
                    }
                });
            }

            // Clean up when modal is closed
            $("#myModal1").on("hidden.bs.modal", function () {
                if (stream1) {
                    stream1.getTracks().forEach((track) => track.stop());
                    stream1 = null;
                }
            });
        };

        // Modal sequences
        $(document).ready(function () {
            // Initialize handlers
            handlePunchInModal();
            handlePunchOutModal();
            
            // Modal chain from punch in to commitments
            $("#submitBtn").click(function () {
                $("#myModal").modal("hide"); // Hide the punch in modal
                setTimeout(function () {
                    $("#secondModal").modal("show"); // Show the commitment modal
                }, 500);
            });
            
            // Modal chain from punch out to daily report
            $("#submitBtn1").click(function () {
                $("#myModal1").modal("hide");
                setTimeout(function () {
                    $("#myModal2").modal("show");
                }, 500);
            });
            
            // Attendance cell click opens detail modal
            $(".opentdModal").click(function () {
                $("#tdModal").modal("show");
            });
            
            // Calculate total hours for working hour inputs
            $(".ak_input-field").on("input", function() {
                let total = 0;
                $(".ak_input-field").each(function() {
                    let val = parseFloat($(this).val()) || 0;
                    total += val;
                });
                $("#ak_totalHours").text(total.toFixed(1));
                
                // Warning if over 8 hours
                if (total > 8) {
                    $("#ak_errorMessage").text("Total working hours exceeds 8 hours!");
                } else {
                    $("#ak_errorMessage").text("");
                }
            });
            
            // Update current time and date
            function updateTime() {
                const now = new Date();
                const timeStr = now.toLocaleTimeString();
                const dateStr = now.toLocaleDateString();
                
                // If there's a time display, update it
                if ($("#timeButton").length) {
                    $("#timeButton").text("Time: " + timeStr);
                }
            }
            
            // Update time every second
            setInterval(updateTime, 1000);
            
            // Set current month in date picker
            if ($("#monthPicker").length) {
                let today = new Date();
                let currentMonth = today.toISOString().slice(0, 7);
                $("#monthPicker").val(currentMonth);
            }
        });
    </script>

    @endsection
@else
    <script>
        window.location.href = "{{url('/login')}}";
    </script>
@endif
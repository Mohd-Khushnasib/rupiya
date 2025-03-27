@if(session()->get('admin_login'))
    @extends('Admin.layouts.master')
    @section('main-content')

    <!-- Dark Theme CSS -->
    <style>
        /* Global styles for dark theme */
        body {
            background-color: #000000;
            color: white;
            font-family: Arial, sans-serif;
        }
        
        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        
        .theam_color_text {
            color: #00b0ff;
            font-size: 32px;
            text-transform: uppercase;
            margin-top: 20px;
        }
        
        .theam_color {
            color: #00b0ff;
        }
        
        /* Navigation Bar Styling */
        .top-navbar {
            background-color: #1a1a1a;
            padding: 10px 0;
            border-bottom: 1px solid #333;
            margin-bottom: 20px;
        }
        
        .top-navbar a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            font-weight: bold;
        }
        
        .top-navbar a:hover {
            color: #00b0ff;
        }
        
        .badge {
            background-color: #ff0000;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 12px;
        }
        
        /* Punch Card Styling */
        .punch_card {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .punch_card_text {
            font-size: 24px;
            font-weight: bold;
            color: #00b0ff;
            margin: 0;
            padding: 5px 0;
        }
        
        /* Table Styling */
        .table-container {
            overflow-x: auto;
            position: relative;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .table.table-advance,
        .table.table-advance th,
        .table.table-advance td {
            border: 1px solid #03b0f5;
            border-collapse: collapse;
        }
        
        .custom-table {
            width: 100%;
            min-width: 800px;
        }
        
        thead {
            position: sticky;
            top: 0;
            background-color: #1a1a1a;
            z-index: 10;
        }
        
        th, td {
            padding: 8px;
            text-align: center;
            white-space: nowrap;
        }
        
        .name-header {
            position: sticky;
            left: 0;
            background-color: #1a1a1a;
            z-index: 20;
            min-width: 180px;
            color: white !important;
        }
        
        .day-header {
            background-color: #1a1a1a;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            min-width: 60px;
        }
        
        .name-cell {
            position: sticky;
            left: 0;
            background-color: #1a1a1a;
            z-index: 15;
            text-align: left;
        }
        
        /* Attendance Status Styles */
        .full-day {
            background-color: #006400;
            color: white !important;
            cursor: pointer;
        }
        
        .half-day {
            background-color: #ffcc00;
            color: black !important;
            cursor: pointer;
        }
        
        .off {
            background-color: #0078d7;
            color: white !important;
            cursor: pointer;
        }
        
        .leave-approve {
            background-color: #00a0e4;
            color: white !important;
            cursor: pointer;
        }
        
        .leave-not-approve {
            background-color: #e74c3c;
            color: white !important;
            cursor: pointer;
        }
        
        /* Monthly title and filter section */
        .month-title {
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .filter-row {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .filter-column {
            margin: 0 10px;
            width: 200px;
        }
        
        .form-control {
            background-color: white;
            color: black;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        /* Modal Styling */
        .modal-content {
            background-color: #222;
            color: white;
            border: 1px solid #444;
        }
        
        .modal-header {
            border-bottom: 1px solid #444;
            background-color: #333;
        }
        
        .modal-footer {
            border-top: 1px solid #444;
            background-color: #333;
        }
        
        .modal-title {
            color: white;
        }
        
        .close {
            color: white;
            opacity: 0.7;
        }
        
        .close:hover {
            color: white;
            opacity: 1;
        }
        
        .btn-info {
            background-color: #00b0ff;
            border-color: #00b0ff;
        }
        
        .btn-default {
            background-color: #444;
            color: white;
            border-color: #555;
        }
        
        /* Employee Image and Info Styling */
        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .emp-name {
            font-size: 17px;
            font-weight: bold;
            color: white;
        }
        
        .emp-info {
            font-size: 12px;
            color: #aaa;
        }
        
        /* Custom Styles for AK Tables */
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
        
        .ak_heading {
            color: white;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .ak_total_box {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
            color: black;
        }
        
        /* Time display in navbar */
        .time-display {
            background-color: white;
            color: black;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            margin-right: 10px;
        }
    </style>

    <!-- Navigation Bar -->
    <div class="top-navbar">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <a href="#"><i class="fa fa-home"></i> HOME</a>
                    <a href="#"><i class="fa fa-chart-line"></i> LEAD CRM <span class="badge">8+</span></a>
                    <a href="#"><i class="fa fa-sign-in-alt"></i> LOGIN CRM <i class="fa fa-caret-down"></i></a>
                    <a href="#"><i class="fa fa-tasks"></i> TASK</a>
                    <a href="#"><i class="fa fa-ticket-alt"></i> TICKET <i class="fa fa-caret-down"></i></a>
                    <a href="#"><i class="fa fa-users"></i> HRMS <i class="fa fa-caret-down"></i></a>
                    <a href="#"><i class="fa fa-exclamation-triangle"></i> WARNING <i class="fa fa-caret-down"></i></a>
                </div>
                <div class="col-md-4 text-right">
                    <span class="time-display">TIME: <span id="current-time">11:27:19 AM</span></span>
                    <a href="#"><img src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&amp;q=60&amp;w=50" class="profile-img" style="width: 30px; height: 30px; margin-left: 15px;"> ADMIN <i class="fa fa-caret-down"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Start Here -->
    <div class="container" id="main-container">
        <div id="main-content">
            <div class="page-title">
                <div>
                    <h1 class="theam_color_text">
                        <i class="fa fa-user theam_color"></i> EMPLOYEE NAME
                    </h1>
                </div>
            </div>
            
            <!-- Attendance Card Summary -->
            <div class="row" style="margin-top: 20px">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="punch_card">
                        <h3 class="punch_card_text">FULL DAY</h3>
                        <h4 class="punch_card_text">12</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="punch_card">
                        <h3 class="punch_card_text">HALF DAY</h3>
                        <h4 class="punch_card_text">30</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="punch_card">
                        <h3 class="punch_card_text">ABSENT</h3>
                        <h4 class="punch_card_text">29</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="punch_card">
                        <h3 class="punch_card_text">TOTAL DAY</h3>
                        <h4 class="punch_card_text">30</h4>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Title and Filters -->
            <h2 class="month-title">MONTHLY ATTENDANCE - FEBRUARY 2025</h2>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <select name="employee" id="employee_filter" class="form-control" tabindex="-1">
                                <option value="all">ALL EMPLOYEE</option>
                                <option value="emp1">EMP 1</option>
                                <option value="emp2">EMP 2</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="month" id="month_filter" class="form-control" tabindex="-1">
                                <option value="all">CHOOSE MONTH</option>
                                <option value="01">JANUARY</option>
                                <option value="02" selected>FEBRUARY</option>
                                <option value="03">MARCH</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Attendance Table -->
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-12">
                    <div class="table-responsive table-container">
                        <table class="table table-advance custom-table">
                            <thead>
                                <tr>
                                    <th class="name-header">NAME</th>
                                    <th class="day-header">1<br>SAT</th>
                                    <th class="day-header">2<br>SUN</th>
                                    <th class="day-header">3<br>MON</th>
                                    <th class="day-header">4<br>TUE</th>
                                    <th class="day-header">5<br>WED</th>
                                    <th class="day-header">6<br>THU</th>
                                    <th class="day-header">7<br>FRI</th>
                                    <th class="day-header">8<br>SAT</th>
                                    <th class="day-header">9<br>SUN</th>
                                    <th class="day-header">10<br>MON</th>
                                    <th class="day-header">11<br>TUE</th>
                                    <th class="day-header">12<br>WED</th>
                                    <th class="day-header">13<br>THU</th>
                                    <th class="day-header">14<br>FRI</th>
                                    <th class="day-header">15<br>SAT</th>
                                    <th class="day-header">16<br>SUN</th>
                                    <th class="day-header">17<br>MON</th>
                                    <th class="day-header">18<br>TUE</th>
                                    <th class="day-header">19<br>WED</th>
                                    <th class="day-header">20<br>THU</th>
                                    <th class="day-header">21<br>FRI</th>
                                    <th class="day-header">22<br>SAT</th>
                                    <th class="day-header">23<br>SUN</th>
                                    <th class="day-header">24<br>MON</th>
                                    <th class="day-header">25<br>TUE</th>
                                    <th class="day-header">26<br>WED</th>
                                    <th class="day-header">27<br>THU</th>
                                    <th class="day-header">28<br>FRI</th>
                                    <th class="day-header">29<br>SAT</th>
                                    <th class="day-header">30<br>SUN</th>
                                    <th class="day-header">TOTAL<br>PRESENT</th>
                                    <th class="day-header">PAID<br>LEAVE</th>
                                    <th class="day-header">EARNED<br>LEAVE</th>
                                    <th class="day-header">FINAL<br>ATTENDANCE</th>
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
                                            <span class="emp-name">RAVINDRA</span><br>
                                            <span class="emp-info">ACHIEVERS | SR. EXECUTIVE</span><br>
                                            <span class="emp-info">EMP-ID : RM02</span>
                                        </div>
                                    </td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal half-day"><b>0.5</b><br><span>6:00AM - 12:00PM<br>HALF DAY</span></td>
                                    <td class="opentdModal half-day"><b>0.5</b><br><span>6:00AM - 12:00PM<br>HALF DAY</span></td>
                                    <td class="opentdModal half-day"><b>0.5</b><br><span>6:00AM - 12:00PM<br>HALF DAY</span></td>
                                    <td class="opentdModal leave-approve"><b>0</b><br><span>LEAVE</span></td>
                                    <td class="opentdModal leave-not-approve"><b>-1</b><br><span>LEAVE</span></td>
                                    <td class="opentdModal off"><b>1</b><br><span>OFF</span></td>
                                    <td>26.5</td>
                                    <td>1</td>
                                    <td>0</td>
                                    <td>27.5</td>
                                </tr>
                                <!-- Duplicate rows for example -->
                                <tr>
                                    <td class="name-cell" style="display: flex; gap: 10px; align-items: center;">
                                        <div>
                                            <img src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&amp;q=60&amp;w=3000&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D"
                                                alt="" height="50px" width="50px"
                                                style="object-fit: cover; border-radius: 50%;">
                                        </div>
                                        <div style="text-align: start;">
                                            <span class="emp-name">RAVINDRA</span><br>
                                            <span class="emp-info">ACHIEVERS | SR. EXECUTIVE</span><br>
                                            <span class="emp-info">EMP-ID : RM02</span>
                                        </div>
                                    </td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal half-day"><b>0.5</b><br><span>6:00AM - 12:00PM<br>HALF DAY</span></td>
                                    <td class="opentdModal half-day"><b>0.5</b><br><span>6:00AM - 12:00PM<br>HALF DAY</span></td>
                                    <td class="opentdModal half-day"><b>0.5</b><br><span>6:00AM - 12:00PM<br>HALF DAY</span></td>
                                    <td class="opentdModal leave-approve"><b>0</b><br><span>LEAVE</span></td>
                                    <td class="opentdModal leave-not-approve"><b>-1</b><br><span>LEAVE</span></td>
                                    <td class="opentdModal off"><b>1</b><br><span>OFF</span></td>
                                    <td>26.5</td>
                                    <td>1</td>
                                    <td>0</td>
                                    <td>27.5</td>
                                </tr>
                                <tr>
                                    <td class="name-cell" style="display: flex; gap: 10px; align-items: center;">
                                        <div>
                                            <img src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&amp;q=60&amp;w=3000&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D"
                                                alt="" height="50px" width="50px"
                                                style="object-fit: cover; border-radius: 50%;">
                                        </div>
                                        <div style="text-align: start;">
                                            <span class="emp-name">RAVINDRA</span><br>
                                            <span class="emp-info">ACHIEVERS | SR. EXECUTIVE</span><br>
                                            <span class="emp-info">EMP-ID : RM02</span>
                                        </div>
                                    </td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal off"><b>1</b><br>OFF</td>
                                    <td class="opentdModal full-day"><b>1</b><br><span>6:00AM - 7:00PM<br>FULL DAY</span></td>
                                    <td class="opentdModal half-day"><b>0.5</b><br><span>6:00AM - 12:00PM<br>HALF DAY</span></td>
                                    <td class="opentdModal half-day"><b>0.5</b><br><span>6:00AM - 12:00PM<br>HALF DAY</span></td>
                                    <td class="opentdModal half-day"><b>0.5</b><br><span>6:00AM - 12:00PM<br>HALF DAY</span></td>
                                    <td class="opentdModal leave-approve"><b>0</b><br><span>LEAVE</span></td>
                                    <td class="opentdModal leave-not-approve"><b>-1</b><br><span>LEAVE</span></td>
                                    <td class="opentdModal off"><b>1</b><br><span>OFF</span></td>
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
    <!-- Main Content End Here -->

    <!-- Attendance Details Modal -->
    <div id="tdModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">RAVINDRA</h4>
                    <p>ACHIEVERS | SR. EXECUTIVE</p>
                    <p>EMP-ID : RM02</p>
                    <p>ATTENDANCE : 1</p>
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
                    <button type="button" class="btn btn-info" id="submitAttendance">
                        Submit
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- All Other Modals -->
    <!-- Leave Request Modal -->
    <div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel3">Leave Request</h4>
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

    <!-- JavaScript -->
    <script>
        // Update time display
        function updateTime() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            let seconds = now.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            
            // Convert to 12-hour format
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            
            const timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
            $('#current-time').text(timeString);
        }
        
        $(document).ready(function() {
            // Update time every second
            setInterval(updateTime, 1000);
            updateTime(); // Initial call
            
            // Attendance cell click opens detail modal
            $(".opentdModal").click(function() {
                $("#tdModal").modal("show");
            });
            
            // Open leave request modal
            $("#openModalBtn3").click(function() {
                $("#myModal3").modal("show");
            });
            
            // Attendance detail submission
            $("#submitAttendance").click(function() {
                $("#tdModal").modal("hide");
                alert("Attendance updated successfully!");
            });
        });
    </script>

    @endsection
@else
    <script>
        window.location.href = "{{url('/login')}}";
    </script>
@endif
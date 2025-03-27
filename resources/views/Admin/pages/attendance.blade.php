@if(session()->get('admin_login'))
    @extends('Admin.layouts.master')
    @section('main-content')

    <!-- Custom CSS for Dark Theme -->
    <style>
        body {
            background-color: #000000;
            color: white;
            font-family: Arial, sans-serif;
        }
        
        /* Navigation Bar Styling */
        .navbar {
            background-color: #1a1a1a;
            padding: 10px 0;
            border-bottom: 1px solid #333;
        }
        
        .navbar a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            font-weight: bold;
        }
        
        .navbar a:hover {
            color: #00b0ff;
        }
        
        .badge {
            background-color: #ff0000;
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 12px;
        }
        
        .time-display {
            background-color: white;
            color: black;
            padding: 5px 15px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        /* Employee Name Styling */
        .employee-name {
            font-size: 36px;
            font-weight: bold;
            color: #00b0ff;
            margin: 20px 0;
        }
        
        .employee-icon {
            font-size: 36px;
            color: #00b0ff;
            margin-right: 10px;
        }
        
        /* Attendance Cards */
        .attendance-cards {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .attendance-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            width: 23%;
            min-width: 200px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .card-title {
            font-size: 24px;
            font-weight: bold;
            color: #00b0ff;
            margin: 0;
        }
        
        .card-value {
            font-size: 30px;
            font-weight: bold;
            color: #00b0ff;
            text-align: right;
            margin: 0;
        }
        
        /* Monthly Attendance Section */
        .monthly-title {
            font-size: 24px;
            text-align: center;
            margin: 30px 0 20px;
            color: white;
        }
        
        .filters {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .filter-select {
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #333;
            background-color: white;
            color: #333;
            width: 200px;
        }
        
        /* Table Styling */
        .attendance-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 1px;
            overflow-x: auto;
            color: white;
        }
        
        .attendance-table th {
            background-color: #1a1a1a;
            padding: 10px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .attendance-table td {
            padding: 10px;
            text-align: center;
            vertical-align: middle;
        }
        
        .name-cell {
            background-color: #1a1a1a;
            text-align: left;
            padding: 10px;
            position: sticky;
            left: 0;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .emp-name {
            font-weight: bold;
            font-size: 16px;
            color: white;
        }
        
        .emp-info {
            font-size: 12px;
            color: #aaa;
        }
        
        /* Attendance Status Cells */
        .full-day {
            background-color: #006400;
            color: white;
            cursor: pointer;
        }
        
        .half-day {
            background-color: #ffcc00;
            color: black;
            cursor: pointer;
        }
        
        .off {
            background-color: #0078d7;
            color: white;
            cursor: pointer;
        }
        
        .leave-approve {
            background-color: #00a0e4;
            color: white;
            cursor: pointer;
        }
        
        .leave-not-approve {
            background-color: #e74c3c;
            color: white;
            cursor: pointer;
        }
        
        /* Responsive Scrollable Table */
        .table-container {
            overflow-x: auto;
            max-width: 100%;
            margin-bottom: 30px;
        }
        
        /* Modal Styling for Dark Theme */
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
        
        .close {
            color: white;
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
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #888;
        }
    </style>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="container-fluid">
            <div class="row" style="width: 100%;">
                <div class="col-md-8">
                    <a href="#"><i class="fa fa-home"></i> HOME</a>
                    <a href="#"><i class="fa fa-chart-line"></i> LEAD CRM <span class="badge">8+</span></a>
                    <a href="#"><i class="fa fa-sign-in-alt"></i> LOGIN CRM <i class="fa fa-caret-down"></i></a>
                    <a href="#"><i class="fa fa-tasks"></i> TASK</a>
                    <a href="#"><i class="fa fa-ticket-alt"></i> TICKECT <i class="fa fa-caret-down"></i></a>
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

    <!-- Main Content -->
    <div class="container">
        <!-- Employee Name Section -->
        <div class="employee-name">
            <i class="fa fa-user-circle employee-icon"></i> EMPLOYEE NAME
        </div>

        <!-- Attendance Cards -->
        <div class="attendance-cards">
            <div class="attendance-card">
                <div class="card-title">FULL DAY</div>
                <div class="card-value">12</div>
            </div>
            <div class="attendance-card">
                <div class="card-title">HALF DAY</div>
                <div class="card-value">30</div>
            </div>
            <div class="attendance-card">
                <div class="card-title">ABSENT</div>
                <div class="card-value">29</div>
            </div>
            <div class="attendance-card">
                <div class="card-title">TOTAL DAY</div>
                <div class="card-value">30</div>
            </div>
        </div>

        <!-- Monthly Attendance Section -->
        <div class="monthly-title">MONTHLY ATTENDANCE - FEBRUARY 2025</div>
        
        <!-- Filters -->
        <div class="filters">
            <select class="filter-select">
                <option>ALL EMPLOYEE</option>
                <option>EMP 1</option>
                <option>EMP 2</option>
            </select>
            <select class="filter-select">
                <option>CHOOSE MONTH</option>
                <option>JANUARY</option>
                <option selected>FEBRUARY</option>
                <option>MARCH</option>
            </select>
        </div>

        <!-- Attendance Table -->
        <div class="table-container">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th style="min-width: 200px; text-align: left;">NAME</th>
                        <th>1<br>SAT</th>
                        <th>2<br>SUN</th>
                        <th>3<br>MON</th>
                        <th>4<br>TUE</th>
                        <th>5<br>WED</th>
                        <th>6<br>THU</th>
                        <th>7<br>FRI</th>
                        <th>8<br>SAT</th>
                        <th>9<br>SUN</th>
                        <th>10<br>MON</th>
                        <th>11<br>TUE</th>
                        <th>12<br>WED</th>
                        <th>13<br>THU</th>
                        <th>14<br>FRI</th>
                        <th>15<br>SAT</th>
                        <th>16<br>SUN</th>
                        <th>17<br>MON</th>
                        <th>18<br>TUE</th>
                        <th>19<br>WED</th>
                        <th>20<br>THU</th>
                        <th>21<br>FRI</th>
                        <th>22<br>SAT</th>
                        <th>23<br>SUN</th>
                        <th>24<br>MON</th>
                        <th>25<br>TUE</th>
                        <th>26<br>WED</th>
                        <th>27<br>THU</th>
                        <th>28<br>FRI</th>
                        <th>29<br>SAT</th>
                        <th>30<br>SUN</th>
                        <th>TOTAL<br>PRESENT</th>
                        <th>PAID<br>LEAVE</th>
                        <th>EARNED<br>LEAVE</th>
                        <th>FINAL<br>ATTENDANCE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="name-cell">
                            <img src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&amp;q=60&amp;w=3000" alt="" class="profile-img">
                            <div>
                                <div class="emp-name">RAVINDRA</div>
                                <div class="emp-info">ACHIEVERS | SR. EXECUTIVE</div>
                                <div class="emp-info">EMP-ID : RM02</div>
                            </div>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="half-day opentdModal">
                            <b>0.5</b><br>
                            <span>6:00AM - 12:00PM<br>HALF DAY</span>
                        </td>
                        <td class="half-day opentdModal">
                            <b>0.5</b><br>
                            <span>6:00AM - 12:00PM<br>HALF DAY</span>
                        </td>
                        <td class="half-day opentdModal">
                            <b>0.5</b><br>
                            <span>6:00AM - 12:00PM<br>HALF DAY</span>
                        </td>
                        <td class="leave-approve opentdModal">
                            <b>0</b><br>
                            <span>LEAVE</span>
                        </td>
                        <td class="leave-not-approve opentdModal">
                            <b>-1</b><br>
                            <span>LEAVE</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td>26.5</td>
                        <td>1</td>
                        <td>0</td>
                        <td>27.5</td>
                    </tr>
                    <!-- Duplicate rows for the image example -->
                    <tr>
                        <td class="name-cell">
                            <img src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&amp;q=60&amp;w=3000" alt="" class="profile-img">
                            <div>
                                <div class="emp-name">RAVINDRA</div>
                                <div class="emp-info">ACHIEVERS | SR. EXECUTIVE</div>
                                <div class="emp-info">EMP-ID : RM02</div>
                            </div>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="half-day opentdModal">
                            <b>0.5</b><br>
                            <span>6:00AM - 12:00PM<br>HALF DAY</span>
                        </td>
                        <td class="half-day opentdModal">
                            <b>0.5</b><br>
                            <span>6:00AM - 12:00PM<br>HALF DAY</span>
                        </td>
                        <td class="half-day opentdModal">
                            <b>0.5</b><br>
                            <span>6:00AM - 12:00PM<br>HALF DAY</span>
                        </td>
                        <td class="leave-approve opentdModal">
                            <b>0</b><br>
                            <span>LEAVE</span>
                        </td>
                        <td class="leave-not-approve opentdModal">
                            <b>-1</b><br>
                            <span>LEAVE</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td>26.5</td>
                        <td>1</td>
                        <td>0</td>
                        <td>27.5</td>
                    </tr>
                    <tr>
                        <td class="name-cell">
                            <img src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&amp;q=60&amp;w=3000" alt="" class="profile-img">
                            <div>
                                <div class="emp-name">RAVINDRA</div>
                                <div class="emp-info">ACHIEVERS | SR. EXECUTIVE</div>
                                <div class="emp-info">EMP-ID : RM02</div>
                            </div>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td class="full-day opentdModal">
                            <b>1</b><br>
                            <span>6:00AM - 7:00PM<br>FULL DAY</span>
                        </td>
                        <td class="half-day opentdModal">
                            <b>0.5</b><br>
                            <span>6:00AM - 12:00PM<br>HALF DAY</span>
                        </td>
                        <td class="half-day opentdModal">
                            <b>0.5</b><br>
                            <span>6:00AM - 12:00PM<br>HALF DAY</span>
                        </td>
                        <td class="half-day opentdModal">
                            <b>0.5</b><br>
                            <span>6:00AM - 12:00PM<br>HALF DAY</span>
                        </td>
                        <td class="leave-approve opentdModal">
                            <b>0</b><br>
                            <span>LEAVE</span>
                        </td>
                        <td class="leave-not-approve opentdModal">
                            <b>-1</b><br>
                            <span>LEAVE</span>
                        </td>
                        <td class="off opentdModal">
                            <b>1</b><br>
                            <span>OFF</span>
                        </td>
                        <td>26.5</td>
                        <td>1</td>
                        <td>0</td>
                        <td>27.5</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Attendance Details Modal -->
    <div id="tdModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ravindra</h4>
                    <p>Achievers | Sr. Executive</p>
                    <p>Emp-ID : RM02</p>
                    <p>Attendance : 1</p>
                </div>
                <div class="modal-body">
                    <div class="image-container" style="width: 100%; display: flex; align-items: center; justify-content: space-around;">
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
                    <button type="button" class="btn btn-info" id="submitBtn2">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script>
        $(document).ready(function() {
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
            
            // Update time every second
            setInterval(updateTime, 1000);
            updateTime(); // Initial call
            
            // Attendance cell click opens detail modal
            $(".opentdModal").click(function() {
                $("#tdModal").modal("show");
            });
            
            // Other modal related functionality
            $("#submitBtn2").click(function() {
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
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

                    <div class="row"
                        style="width: 100%; display: flex; gap: 10px; align-items: center; justify-content: center; margin: 10px 0;">
                        <div style="border: none; padding: 0 10px; border-radius: 10px;">
                            <h2>Daily Performance - {{ $monthName }}</h2>
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
                                            @foreach($dates as $date)
                                                <th class="day-header">{{ $date['day'] }} <br> {{ $date['dayOfWeek'] }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($admins as $admin)
                                            <tr>
                                                <td class="name-cell" style="display: flex; gap: 10px; align-items: center;">
                                                    <div>
                                                        <img src="{{ $admin->image ?? 'https://via.placeholder.com/50' }}" alt=""
                                                            height="50px" width="50px" style="object-fit: cover; border-radius: 50%;">
                                                    </div>
                                                    <div style="text-align: start;">
                                                        <span style="font-size: 17px;"><b>{{ $admin->name }}</b></span><br>
                                                        <span style="font-size: 12px;">{{ $admin->team }} |
                                                            {{ $admin->role }}</span><br>
                                                        <span style="font-size: 12px;">Emp-ID : {{ $admin->id }}</span><br>
                                                        <span style="font-size: 12px;">Dept: {{ $admin->department }}</span>
                                                    </div>
                                                </td>

                                                @foreach($dates as $date)
                                                    <td class="opentdModal" data-empid="{{ $admin->id }}"
                                                        data-date="{{ $currentYear }}-{{ $currentMonth }}-{{ $date['day'] }}"
                                                        data-display-date="{{ $date['day'] }} {{ $date['dayOfWeek'] }}, {{ $monthName }}">
                                                        <span class="badge" style="background-color: green;">C : 5 | A : 2</span>
                                                        <br><br>Best 😍
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Content -->
            </div>



            <div class="modal fade" id="tdModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                <div class="modal-dialog" style="width: fit-content;">
                    <div class="modal-content" style="width: fit-content;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title text-center" id="modalLabel" style="color: black; font-weight: bold">
                                Daily Work Report - <span id="modalDate"></span>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="ak_container" style="position: relative; width: fit-content;">
                                <h4 class="ak_heading">Commitment vs Achievement</h4>
                                <table class="ak_custom-table" id="countRecordsTable">
                                    <thead>
                                        <tr>
                                            <th class="ak_table-header" style="width: 20px;">Task</th>
                                            <th class="ak_table-header" style="width: 20px;">Commitment</th>
                                            <th class="ak_table-header" style="width: 20px;">Achievement</th>
                                            <th class="ak_table-header" style="width: 20px;">Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Placeholder rows -->
                                        <tr class="ak_table-row">
                                            <td class="ak_table-cell">Leads</td>
                                            <td class="ak_table-cell">5</td>
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
                                <table class="ak_custom-table" id="timeRecordsTable">
                                    <thead>
                                        <tr>
                                            <th class="ak_table-header">Type</th>
                                            <th class="ak_table-header">Time</th>
                                            <th class="ak_table-header">Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Placeholder rows -->
                                        <tr class="ak_table-row">
                                            <td class="ak_table-cell">Working Hour</td>
                                            <td class="ak_table-cell"><input type="number" class="ak_input-field time-input"
                                                    value="8"></td>
                                            <td class="ak_table-cell"></td>
                                        </tr>
                                        <tr class="ak_table-row">
                                            <td class="ak_table-cell">Break</td>
                                            <td class="ak_table-cell"><input type="number" class="ak_input-field time-input"
                                                    value="1"></td>
                                            <td class="ak_table-cell"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="ak_total_box">
                                    Total Working Hours: <span id="ak_totalHours">9</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Replace your entire script block with this -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Pure JS click handler that doesn't rely on jQuery events
                    var cells = document.querySelectorAll('.opentdModal');

                    cells.forEach(function (cell) {
                        cell.addEventListener('click', function () {
                            var empId = this.getAttribute('data-empid');
                            var date = this.getAttribute('data-date');
                            var displayDate = this.getAttribute('data-display-date');

                            // Direct DOM manipulation for the modal title
                            document.getElementById('modalDate').textContent = displayDate || date;

                            // Force show using direct Bootstrap API
                            jQuery('#tdModal').modal('show');

                            // For demonstration, add placeholder data without AJAX
                            var countTable = document.querySelector('#countRecordsTable tbody');
                            var timeTable = document.querySelector('#timeRecordsTable tbody');

                            // Clear existing content
                            countTable.innerHTML = '';
                            timeTable.innerHTML = '';

                            // Add placeholder data
                            countTable.innerHTML = `
                                                    <tr class="ak_table-row">
                                                        <td class="ak_table-cell">Leads</td>
                                                        <td class="ak_table-cell">5</td>
                                                        <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                                        <td class="ak_table-cell"></td>
                                                    </tr>
                                                    <tr class="ak_table-row">
                                                        <td class="ak_table-cell">Logins</td>
                                                        <td class="ak_table-cell">10</td>
                                                        <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                                        <td class="ak_table-cell"></td>
                                                    </tr>
                                                `;

                            timeTable.innerHTML = `
                                                    <tr class="ak_table-row">
                                                        <td class="ak_table-cell">Working Hour</td>
                                                        <td class="ak_table-cell"><input type="number" class="ak_input-field time-input" value="8"></td>
                                                        <td class="ak_table-cell"></td>
                                                    </tr>
                                                    <tr class="ak_table-row">
                                                        <td class="ak_table-cell">Break</td>
                                                        <td class="ak_table-cell"><input type="number" class="ak_input-field time-input" value="1"></td>
                                                        <td class="ak_table-cell"></td>
                                                    </tr>
                                                `;

                            // Set up total hours calculation
                            calculateTotalHours();

                            // Add event listeners to inputs
                            var timeInputs = document.querySelectorAll('.time-input');
                            timeInputs.forEach(function (input) {
                                input.addEventListener('input', calculateTotalHours);
                            });
                        });
                    });

                    // Add a direct test button
                    var testBtn = document.createElement('button');
                    testBtn.textContent = 'Open Modal';
                    testBtn.style.position = 'fixed';
                    testBtn.style.bottom = '20px';
                    testBtn.style.right = '20px';
                    testBtn.style.zIndex = '9999';
                    testBtn.style.padding = '10px 15px';
                    testBtn.style.backgroundColor = '#4CAF50';
                    testBtn.style.color = 'white';
                    testBtn.style.border = 'none';
                    testBtn.style.borderRadius = '4px';
                    testBtn.style.cursor = 'pointer';

                    testBtn.addEventListener('click', function () {
                        // Force the modal to display
                        jQuery('#tdModal').modal({
                            show: true,
                            backdrop: true,
                            keyboard: true
                        });
                    });

                    document.body.appendChild(testBtn);

                    function calculateTotalHours() {
                        var totalHours = 0;
                        var timeInputs = document.querySelectorAll('.time-input');

                        timeInputs.forEach(function (input) {
                            totalHours += parseFloat(input.value) || 0;
                        });

                        document.getElementById('ak_totalHours').textContent = totalHours.toFixed(2);
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
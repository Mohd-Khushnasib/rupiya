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
                                        <!-- This will be populated dynamically via AJAX -->
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
                                        <!-- This will be populated dynamically via AJAX -->
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

            <!-- Replace your existing script with this updated version -->
            <script>
                // Add this script to your view or include it in a separate JS file
                $(document).ready(function () {
                    // Click event for opening the modal
                    $(document).on('click', '.opentdModal', function () {
                        // Get employee ID and date from the clicked cell's data attributes
                        var empId = $(this).data('empid');
                        var date = $(this).data('date');
                        var displayDate = $(this).data('display-date');

                        // Update modal title with the date
                        $('#modalDate').text(displayDate || date);

                        // Show the modal while loading data
                        $('#tdModal').modal('show');

                        // Fetch data via AJAX
                        $.ajax({
                            url: "/get-daily-performance-data",
                            method: "GET",
                            data: {
                                emp_id: empId,
                                date: date
                            },
                            success: function (response) {
                                // Clear existing table content
                                $('#countRecordsTable tbody').empty();
                                $('#timeRecordsTable tbody').empty();

                                // Update Count Records table
                                if (response.countRecords && response.countRecords.length > 0) {
                                    $.each(response.countRecords, function (index, record) {
                                        var row = `
                                                                            <tr class="ak_table-row">
                                                                                <td class="ak_table-cell">${record.product_name}</td>
                                                                                <td class="ak_table-cell">${record.duration}</td>
                                                                                <td class="ak_table-cell"><input type="number" class="ak_input-field achievement-input" data-record-id="${record.id}"></td>
                                                                                <td class="ak_table-cell" id="feedback-${record.id}"></td>
                                                                            </tr>
                                                                        `;
                                        $('#countRecordsTable tbody').append(row);
                                    });
                                } else {
                                    // If no count records found, show placeholder rows
                                    $('#countRecordsTable tbody').append(`
                                                                        <tr class="ak_table-row">
                                                                            <td class="ak_table-cell">Leads</td>
                                                                            <td class="ak_table-cell">0</td>
                                                                            <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                                                            <td class="ak_table-cell"></td>
                                                                        </tr>
                                                                        <tr class="ak_table-row">
                                                                            <td class="ak_table-cell">Logins</td>
                                                                            <td class="ak_table-cell">0</td>
                                                                            <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                                                            <td class="ak_table-cell"></td>
                                                                        </tr>
                                                                    `);
                                }

                                // Update Time Records table
                                if (response.timeRecords && response.timeRecords.length > 0) {
                                    var totalHours = 0;

                                    $.each(response.timeRecords, function (index, record) {
                                        var row = `
                                                                            <tr class="ak_table-row">
                                                                                <td class="ak_table-cell">${record.product_name}</td>
                                                                                <td class="ak_table-cell"><input type="number" class="ak_input-field time-input" value="${record.duration}" data-record-id="${record.id}"></td>
                                                                                <td class="ak_table-cell" id="time-feedback-${record.id}"></td>
                                                                            </tr>
                                                                        `;
                                        $('#timeRecordsTable tbody').append(row);

                                        totalHours += parseFloat(record.duration) || 0;
                                    });

                                    // Update total hours
                                    $('#ak_totalHours').text(totalHours.toFixed(2));
                                } else {
                                    // If no time records found, show placeholder rows
                                    $('#timeRecordsTable tbody').append(`
                                                                        <tr class="ak_table-row">
                                                                            <td class="ak_table-cell">Working Hour</td>
                                                                            <td class="ak_table-cell"><input type="number" class="ak_input-field time-input"></td>
                                                                            <td class="ak_table-cell"></td>
                                                                        </tr>
                                                                        <tr class="ak_table-row">
                                                                            <td class="ak_table-cell">Break</td>
                                                                            <td class="ak_table-cell"><input type="number" class="ak_input-field time-input"></td>
                                                                            <td class="ak_table-cell"></td>
                                                                        </tr>
                                                                        <tr class="ak_table-row">
                                                                            <td class="ak_table-cell">Wrap Up Time</td>
                                                                            <td class="ak_table-cell"><input type="number" class="ak_input-field time-input"></td>
                                                                            <td class="ak_table-cell"></td>
                                                                        </tr>
                                                                        <tr class="ak_table-row">
                                                                            <td class="ak_table-cell">Meeting</td>
                                                                            <td class="ak_table-cell"><input type="number" class="ak_input-field time-input"></td>
                                                                            <td class="ak_table-cell"></td>
                                                                        </tr>
                                                                        <tr class="ak_table-row">
                                                                            <td class="ak_table-cell">Other Work</td>
                                                                            <td class="ak_table-cell"><input type="number" class="ak_input-field time-input"></td>
                                                                            <td class="ak_table-cell"></td>
                                                                        </tr>
                                                                    `);
                                }

                                // Set up event listeners for time inputs to recalculate total
                                $('.time-input').on('input', calculateTotalHours);
                            },
                            error: function (xhr) {
                                console.error('Error fetching data:', xhr);
                                // Show error message
                                $('#ak_errorMessage').text('Failed to load data. Please try again.');
                            }
                        });
                    });

                    // Function to calculate total hours when time inputs change
                    function calculateTotalHours() {
                        var totalHours = 0;
                        $('.time-input').each(function () {
                            totalHours += parseFloat($(this).val()) || 0;
                        });
                        $('#ak_totalHours').text(totalHours.toFixed(2));
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
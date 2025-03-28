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
                    max-height: 400px;
                    overflow-y: auto;
                    border: 0;
                    white-space: nowrap;
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
                    padding: 8px;
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
                    z-index: 3;
                }

                thead {
                    position: sticky;
                    top: -1px;
                    left: 20px;
                    background-color: black;
                    z-index: 1;
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

                .table-margin {
                    margin-top: 60px;
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

                /* Status styling */
                .full-day {
                    color: black !important;
                    background-color: green;
                    color: white !important;
                }

                .half-day {
                    color: black !important;
                    background-color: #ffff00;
                }

                .leave-approve {
                    color: black !important;
                    background-color: #ff5722;
                }

                .leave-not-approve {
                    color: black !important;
                    background-color: red;
                    color: white !important;
                }

                .off {
                    color: white !important;
                    background-color: #0288d1;
                }

                /* Modal Styles */
                .ak_container {
                    margin-bottom: 20px;
                }

                .ak_heading {
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

                .chosen-container {
                    background-color: whitesmoke;
                }

                /* Table styling for modal */
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

                /* Make clickable cells more obvious */
                .opentdModal {
                    cursor: pointer;
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

            <!-- Modal -->
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

            <!-- JavaScript -->
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

            // Fetch data via AJAX
            fetch(`/get-daily-performance-data?emp_id=${empId}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    // Clear existing content
                    var countTable = document.querySelector('#countRecordsTable tbody');
                    var timeTable = document.querySelector('#timeRecordsTable tbody');
                    
                    countTable.innerHTML = '';
                    timeTable.innerHTML = '';

                    // Add count records
                    if (data.countRecords && data.countRecords.length > 0) {
                        data.countRecords.forEach(record => {
                            var row = `
                                <tr class="ak_table-row">
                                    <td class="ak_table-cell">${record.product_name}</td>
                                    <td class="ak_table-cell">${record.duration}</td>
                                    <td class="ak_table-cell">
                                        <input type="number" class="ak_input-field achievement-input" 
                                            data-record-id="${record.id}" 
                                            data-commitment="${record.duration}"
                                            onchange="updateFeedback(this)">
                                    </td>
                                    <td class="ak_table-cell" id="feedback-${record.id}"></td>
                                </tr>
                            `;
                            countTable.innerHTML += row;
                        });
                    } else {
                        // If no count records found, show placeholder rows
                        countTable.innerHTML = `
                            <tr class="ak_table-row">
                                <td class="ak_table-cell">No records found</td>
                                <td class="ak_table-cell">0</td>
                                <td class="ak_table-cell"><input type="number" class="ak_input-field"></td>
                                <td class="ak_table-cell"></td>
                            </tr>
                        `;
                    }

                    // Add time records
                    if (data.timeRecords && data.timeRecords.length > 0) {
                        var totalHours = 0;
                        
                        data.timeRecords.forEach(record => {
                            var row = `
                                <tr class="ak_table-row">
                                    <td class="ak_table-cell">${record.product_name}</td>
                                    <td class="ak_table-cell">
                                        <input type="number" class="ak_input-field time-input" 
                                            value="${record.duration}" 
                                            data-record-id="${record.id}" 
                                            data-commitment="${record.duration}"
                                            onchange="updateTimeFeedback(this)">
                                    </td>
                                    <td class="ak_table-cell" id="time-feedback-${record.id}"></td>
                                </tr>
                            `;
                            timeTable.innerHTML += row;
                            
                            totalHours += parseFloat(record.duration) || 0;
                        });
                        
                        // Update total hours
                        document.getElementById('ak_totalHours').textContent = totalHours.toFixed(2);
                    } else {
                        // If no time records found, show placeholder rows
                        timeTable.innerHTML = `
                            <tr class="ak_table-row">
                                <td class="ak_table-cell">No records found</td>
                                <td class="ak_table-cell"><input type="number" class="ak_input-field time-input" value="0"></td>
                                <td class="ak_table-cell"></td>
                            </tr>
                        `;
                    }

                    // Set up total hours calculation
                    calculateTotalHours();

                    // Add event listeners to inputs
                    var timeInputs = document.querySelectorAll('.time-input');
                    timeInputs.forEach(function (input) {
                        input.addEventListener('input', calculateTotalHours);
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    });
    
    // Global function to update feedback based on achievement vs commitment
    window.updateFeedback = function(input) {
        var achievement = parseFloat(input.value) || 0;
        var commitment = parseFloat(input.getAttribute('data-commitment')) || 0;
        var recordId = input.getAttribute('data-record-id');
        var feedbackElement = document.getElementById('feedback-' + recordId);
        
        if (achievement < commitment) {
            feedbackElement.textContent = 'Poor';
            feedbackElement.style.color = 'red';
        } else if (achievement === commitment) {
            feedbackElement.textContent = 'Good';
            feedbackElement.style.color = 'blue';
        } else {
            feedbackElement.textContent = 'Very Good';
            feedbackElement.style.color = 'green';
        }
    };
    
    // Global function to update time feedback
    window.updateTimeFeedback = function(input) {
        var achievement = parseFloat(input.value) || 0;
        var commitment = parseFloat(input.getAttribute('data-commitment')) || 0;
        var recordId = input.getAttribute('data-record-id');
        var feedbackElement = document.getElementById('time-feedback-' + recordId);
        
        if (achievement < commitment) {
            feedbackElement.textContent = 'Poor';
            feedbackElement.style.color = 'red';
        } else if (achievement === commitment) {
            feedbackElement.textContent = 'Good';
            feedbackElement.style.color = 'blue';
        } else {
            feedbackElement.textContent = 'Very Good';
            feedbackElement.style.color = 'green';
        }
        
        // Update total hours
        calculateTotalHours();
    };

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
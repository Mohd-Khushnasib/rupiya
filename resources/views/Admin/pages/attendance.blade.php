@if (session()->get('admin_login'))
    @foreach (session()->get('admin_login') as $adminlogin)
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
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal off"><b>1</b><br>Off
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal off"><b>1</b><br>Off
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal off"><b>1</b><br>Off
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
                                                </span>
                                            </td>
                                            <td class="opentdModal off"><b>1</b><br>Off
                                            </td>
                                            <td class="opentdModal full-day"><b>1</b><br><span>6:00am - 7:00pm <br> full
                                                    day
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
            <!-- END Content -->

            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <!-- DataTables Start Here -->


            <!-- live time -->
            <script>
                window.onload = function() {
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
                $(document).ready(function() {
                    $("#timeButton").on("click", function() {
                        // Update button text with current time
                        var currentTime = new Date().toLocaleTimeString();
                        $(this).text("Time: " + currentTime);

                        // Toggle dropdown manually
                        var dropdown = $(this).next(".dropdown-menu");
                        $(".dropdown-menu").not(dropdown).hide(); // Close other dropdowns
                        dropdown.toggle(); // Toggle the current one
                    });
                    // Close dropdown when clicking outside
                    $(document).on("click", function(e) {
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
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                            <h4 class="modal-title" style="color: black; margin-left: 16px !important;"
                                id="myModalLabel3">Leave
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
                                                            <select class="form-control"
                                                                data-placeholder="Choose a Category" tabindex="1">
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
                                                            <select class="form-control"
                                                                data-placeholder="Choose a Category" tabindex="1">
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
                                                        <textarea placeholder="add reason note here..." style="width: 100%; height: 80px;"></textarea>
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
                $(document).ready(function() {
                    $("#openModalBtn3").click(function() {
                        $("#myModal3").modal("show");
                    });
                });
            </script>

            <!-- time store -->
            <script>
                document
                    .getElementById("openModalBtn")
                    .addEventListener("click", function() {
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
                        <div class="modal-body">
                            <video id="camera" width="100%" autoplay=""></video>
                            <canvas id="snapshot" style="display: none" width="640" height="480"></canvas>

                            <!-- Input Field and Capture Button -->
                            <div class="form-group">
                                <input type="file" style="display: none" id="capturedImage" class="form-control"
                                    accept="image/png">
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
                                <input type="text" id="dateInput" class="form-control mt-3"
                                    placeholder="Current Date" readonly="">
                            </div>
                            <div style="margin-top: 15px">
                                <label for="timeInput">Your punch in time</label>
                                <input type="text" id="timeInput" class="form-control mt-3"
                                    placeholder="Current Time" readonly="">
                            </div>
                            <textarea placeholder="Comment" style="width: 100%; height: 80px; margin-top: 15px" id="comment"></textarea>
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

                        // Set the current date and time
                        const now = new Date();
                        const date = now.toLocaleDateString();
                        const time = now.toLocaleTimeString();
                        dateInput.value = date;
                        timeInput.value = time;
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

                $("#myModal").on("hidden.bs.modal", function() {
                    if (stream) {
                        stream.getTracks().forEach((track) => track.stop());
                    }
                });
            </script>

            <!--  punch in modal open  -->
            <script>
                $(document).ready(function() {
                    $("#openModalBtn").on("click", function() {
                        $("#modalTitle").text("Add Task");
                        $("#myModal").modal("show");
                    });

                    $("#saveChangesBtn").on("click", function() {
                        alert("Your changes have been saved!");
                        $("#myModal").modal("hide");
                    });

                    $("#myModal").on("shown.bs.modal", function() {
                        console.log("Modal is now fully visible!");
                    });

                    $("#myModal").on("hidden.bs.modal", function() {
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
                $(document).ready(function() {
                    $("#submitBtn").click(function() {
                        $("#myModal").modal("hide"); // Hide the first modal
                        setTimeout(function() {
                            $("#secondModal").modal("show"); // Show the second modal
                        }, 500); // Small delay to ensure smooth transition
                    });
                });
            </script>



            <!--
    -----------------------------------------------------------------------------------------------------------
    punch out modal
    -----------------------------------------------------------------------------------------------------------
    -->
            <!-- punch out Modal -->
            <!-- Punch Out Modal -->
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
                                    <input type="text" id="dateInput1" class="form-control mt-3"
                                        placeholder="Punch In Date" readonly="">
                                </div>
                                <div style="margin-top: 20px; width: 45%">
                                    <label for="timeInput1">Your punch in time</label>
                                    <input type="text" id="timeInput1" class="form-control mt-3"
                                        placeholder="Punch In Time" readonly="">
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between">
                                <div style="margin-top: 20px; width: 45%">
                                    <label for="dateInput2">Your punch out date</label>
                                    <input type="text" id="dateInput2" class="form-control mt-3"
                                        placeholder="Punch Out Date" readonly="">
                                </div>
                                <div style="margin-top: 20px; width: 45%">
                                    <label for="timeInput2">Your punch out time</label>
                                    <input type="text" id="timeInput2" class="form-control mt-3"
                                        placeholder="Punch Out Time" readonly="">
                                </div>
                            </div>

                            <textarea placeholder="Comment" style="width: 100%; height: 80px; margin-top: 15px" id="comment1"></textarea>
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

            <!-- punch out modal open -->
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const openModalBtn1 = document.getElementById("openModalBtn1");
                    const video1 = document.getElementById("camera1");
                    const captureBtn1 = document.getElementById("captureBtn1");
                    const captureAgainBtn1 = document.getElementById("captureAgainBtn1");
                    const canvas1 = document.getElementById("snapshot1");
                    const context1 = canvas1.getContext("2d");
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
                                stream1 = await navigator.mediaDevices.getUserMedia({
                                    video: true
                                });
                                video1.srcObject = stream1;
                                $("#myModal1").modal("show");

                                // Set the current date and time
                                const now = new Date();
                                const date = now.toLocaleDateString();
                                const time = now.toLocaleTimeString();
                                dateInput1.value = date;
                                timeInput1.value = time;
                                dateInput2.value = date;
                                timeInput2.value = time;
                            } catch (err) {
                                console.error("Camera access denied:", err);
                                alert("Camera access is required to proceed.");
                            }
                        });
                    }

                    captureBtn1.addEventListener("click", () => {
                        context1.drawImage(video1, 0, 0, canvas1.width, canvas1.height);
                        canvas1.toBlob((blob) => {
                            const file = new File([blob], "captured-image.png", {
                                type: "image/png",
                            });

                            const dataTransfer1 = new DataTransfer();
                            dataTransfer1.items.add(file);

                            capturedImageInput1.files = dataTransfer1.files;

                            // Display the captured image in the img tag
                            const imageURL = URL.createObjectURL(file);
                            capturedImageDisplay1.src = imageURL;
                            capturedImageDisplay1.style.display = "block";

                            // Hide the camera and show the capture again button
                            video1.style.display = "none";
                            captureBtn1.style.display = "none";
                            captureAgainBtn1.style.display = "block";

                            // Disable the camera after capture
                            if (stream1) {
                                stream1.getTracks().forEach((track) => track.stop());
                                stream1 = null;
                            }
                        }, "image/png");
                    });

                    captureAgainBtn1.addEventListener("click", async () => {
                        // Remove the captured image
                        capturedImageDisplay1.src = "";
                        capturedImageDisplay1.style.display = "none";

                        // Show the camera and capture button again
                        video1.style.display = "block";
                        captureBtn1.style.display = "block";
                        captureAgainBtn1.style.display = "none";

                        // Reopen the camera
                        try {
                            stream1 = await navigator.mediaDevices.getUserMedia({
                                video: true
                            });
                            video1.srcObject = stream1;
                        } catch (err) {
                            console.error("Camera access denied:", err);
                            alert("Camera access is required to proceed.");
                        }
                    });

                    $("#myModal1").on("hidden.bs.modal", function() {
                        if (stream1) {
                            stream1.getTracks().forEach((track) => track.stop());
                            stream1 = null;
                        }
                    });
                });
            </script>

            <!-- punch out store time -->
            <script>
                $("#myModal1").on("show.bs.modal", function() {
                    var currentDateTime = new Date();
                    var formattedDateTime = currentDateTime.toLocaleString();

                    // Set the formatted date-time in the input field
                    $(this).find('input[type="text"]').val(formattedDateTime);
                });

                $("#captureBtn1").on("click", function() {});

                // Optional: Submit button click event
                $("#submitBtn1").on("click", function() {});
            </script>

            <!-- After Punch out modal -->
            <!-- id="myModal2" class="modal fade" role="dialog" -->
            <!-- <div id="myModal2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title text-center" style="color: black; font-weight: bold">
                        Commitment vs Achievement
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="ak_container">
                        <h2 class="ak_heading">Your Working Hour</h2>
                        <div id="ak_inputContainer">
                        </div>
                        <button id="ak_plusBtn">+ Add Work History</button>
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
    </div> -->

            <!-- After punch out modal -->
            <style>
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

            <!-- class="modal fade" role="dialog" -->
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
                                <!-- <div id="ak_inputContainer">
                        </div>
                        <button id="ak_plusBtn">+ Add Work History</button>
                        <div class="ak_total_box">
                            Total Working Hours: <span id="ak_totalHours">0</span>
                        </div>
                        <div id="ak_errorMessage" class="ak_error"></div> -->
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
                                    <span
                                        style="
                color: green !important;
                margin-top: 15px;
                font-weight: bold;
              ">1.
                                        <del>All Workers name show here</del></span>
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


            <!-- punch out modal -->
            <script>
                $(document).ready(function() {
                    $("#openModalBtn1").on("click", function() {
                        $("#modalTitle1").text("Add Task");
                        $("#myModal1").modal("show");
                    });

                    $("#saveChangesBtn1").on("click", function() {
                        alert("Your changes have been saved!");
                        $("#myModal1").modal("hide");
                    });

                    $("#myModal1").on("shown.bs.modal", function() {
                        console.log("Modal is now fully visible!");
                    });

                    $("#myModal1").on("hidden.bs.modal", function() {
                        console.log("Modal has been closed.");
                    });
                });
            </script>


            <!--
    -----------------------------------------------------------------------------------------------------------
    add multiple boxes for add your work time
    -----------------------------------------------------------------------------------------------------------
    -->

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
                $(document).ready(function() {
                    $("#submitBtn1").click(function() {
                        $("#myModal1").modal("hide");
                        setTimeout(function() {
                            $("#myModal2").modal("show");
                        }, 500);
                    });
                });
            </script>

            <!-- td modal js -->
            <script>
                $(document).ready(function() {
                    $(".opentdModal").click(function() {
                        $("#tdModal").modal("show");
                    });
                });
            </script>

            <!--
    -----------------------------------------------------------------------------------------------------------
    Attendance Details Modal
    -----------------------------------------------------------------------------------------------------------
    -->

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
                                        <p class="col-sm-12" style="margin-top: 10px;">
                                            <textarea class="form-control wysihtml5" rows="6"></textarea>
                                        </p>
                                        <!-- <div class="col-sm-12">
                                    <label for="">Change Attendance </label>
                                    <select name="" id="">
                                        <option value="1">Full Day (1)</option>
                                        <option value="0.5">Half Day (0.5)</option>
                                        <option value="0">Leave (0)</option>
                                        <option value="-1">Leave Not Approved (-1)</option>
                                    </select>
                                </div> -->
                                    </div>
                                </div>

                            </div>
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
        @endsection
    @endforeach
@else
    <script>
        window.location.href = "{{ url('/login') }}";
    </script>
@endif

@if(session()->get('admin_login'))
@foreach(session()->get('admin_login') as $admin_login)
@extends('Admin.layouts.master')
@section('main-content')
<style>
        .hidden_tl,
        .hidden_manager,
        .hidden_agent,
        .hidden_loginManager,
        .hidden_excutive,
        .hidden_hr {
            display: none;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .step-indicator {
            margin-bottom: 20px;
        }

        .step-indicator span {
            margin-right: 10px;
            font-weight: bold;
        }
    </style>

<!-- Main Content Start Here  -->
<div class="container" id="main-container">
    <!-- Start Here -->
    <div id="main-content">
                <!-- BEGIN Main Content -->
                <div class="container">
                    <div class="d-flex" style="justify-self: end;">
                        <a href="{{url('/show_employee')}}">
                            <button class="btn btn-info">
                                All employee
                            </button>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <form id="add_form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    @csrf
                            <div class="step-indicator">
                                <span>Step <span id="current-step">1</span> out of 4</span>
                            </div>
                            <!-- Step 1: Employee Details -->
                            <div class="step active" id="step-1">
                                <h2 class="text-primary">Employee Details</h2>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Photo</label>
                                            <input type="file" name="image" id="image" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light"> Name</label>
                                            <input type="text" name="name" id="name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light"> Number</label>
                                            <input type="number" name="mobile" id="mobile" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Alternate Number</label>
                                            <input type="number" name="alternate_mobile" id="alternate_mobile" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Gender</label>
                                            <div class="controls">
                                                <select name="gender" class="form-control">
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Personal Email id</label>
                                            <input type="email" name="personal_email" id="personal_email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Official Email id</label>
                                            <input type="email" name="official_email" id="official_email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Pan number</label>
                                            <input type="text" name="pan_no" id="pan_no" class="form-control">
                                                <!-- aadhar_no Error min and max 10 digits -->
                                                <span id="pancardnoError" style="color:red;display:none;"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Aadhar number</label>
                                            <input type="text" name="aadhar_no" id="aadhar_no" class="form-control">
                                                <!-- aadhar_no Error min and max 12 digits -->
                                                <span id="aadharnoError" style="color:red;display:none;"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">DOB</label>
                                            <input type="text" name="dob" id="dob" class="form-control" maxlength="10" placeholder="DD-MM-YYYY">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Experience</label>
                                            <input type="text" name="experience" id="experience" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Experience in years</label>
                                            <input type="text" name="experience_in_years" id="experience_in_years" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Qualification</label>
                                            <div class="controls">
                                                <select name="qualification" class="form-control">
                                                    <option>10th</option>
                                                    <option>12th</option>
                                                    <option>Graduation</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12" style="padding: 0;">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label text-light">Permanent Address</label>
                                                <textarea name="permanent_address" class="form-control" id="permanent_address"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label text-light">Current Address</label>
                                                <textarea name="current_address" class="form-control" id="current_address"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>

                            <!-- Step 2: Team Details -->
                            <div class="step" id="step-2">
                                <h2 class="text-primary">Team Details</h2>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Select Department</label>
                                            <div class="controls">
                                                <select name="department" class="form-control" id="departmentSelect">
                                                    <option disabled="true" selected="true">-- Select Department --</option>
                                                    <option value="Sales">Sales</option>
                                                    <option value="Login">Login</option>
                                                    <option value="HR">HR</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="salesOptions" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label text-light">Select Role</label>
                                            <div class="controls">
                                                <select name="role" class="form-control" id="salesRoleSelect">
                                                    <option disabled="true" selected="true">-- Select Role --</option>
                                                    <option value="Manager">Manager</option>
                                                    <option value="TL">Team Leader</option>
                                                    <option value="Agent">Agent</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="loginOptions" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label text-light">Select Role</label>
                                            <div class="controls">
                                                <select name="role" class="form-control" id="loginRoleSelect">
                                                    <option disabled="true" selected="true">-- Select Role --</option>
                                                    <option value="Operation Manager">Manager</option>
                                                    <option value="Executive">Executive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="managerOptionsLogin" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label text-light">Select Manager Operation</label>
                                            <div class="controls">
                                                <select name="manager" class="form-control">
                                                    <option disabled selected>-- Select Manager --</option>
                                                    @php
                                                        $manager_Data = DB::table('admin')->where('role', 'Operation Manager')->get();
                                                    @endphp
                                                
                                                    @if($manager_Data->isNotEmpty())
                                                        @foreach($manager_Data as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    @else
                                                        <option disabled>No Managers Found</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <!-- Team Selection -->
                                    <div class="col-md-3" id="teamOptions" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label text-light">Select Team</label>
                                            <div class="controls">
                                                <select name="team" class="form-control SalesteamSelect" id="teamSelect">
                                                    <option disabled selected>-- Select Team --</option>
                                                    <option value="Team A">Team A</option>
                                                    <option value="Team B">Team B</option>
                                                    <option value="Team C">Team C</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Manager Selection -->
                                    <div class="col-md-3" id="managerOptions" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label text-light">Select Manager 1</label>
                                            <div class="controls">
                                                <select name="manager" class="form-control SalesmanagerSelect" id="managerSelect">
                                                    <option disabled selected>-- Select Manager --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3" id="tlOptions" style="display: none;">
                                        <div class="form-group">
                                            <label class="control-label text-light">Select Team Leader</label>
                                            <div class="controls">
                                                <select name="team_leader" class="form-control" id="tlSelect">
                                                    <option disabled selected>-- Select Team Leader --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary prev-step">Previous</button>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>

                            <!-- Step 3: Emergency Contact Details -->
                            <div class="step" id="step-3">
                                <h2 class="text-primary">Emergency Contact Details</h2>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label text-light">Name1</label>
                                            <input type="text" name="emergency_name1" id="emergency_name1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label text-light">Number1</label>
                                            <input type="text" name="emergency_mobile1" id="emergency_mobile1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label text-light">Relation1</label>
                                            <div class="controls">
                                                <select name="emergency_relation1" class="form-control">
                                                    <option>Father</option>
                                                    <option>Mother</option>
                                                    <option>Sister</option>
                                                    <option>Brother</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label text-light">Name2</label>
                                            <input type="text" name="emergency_name2" id="emergency_name2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label text-light">Number2</label>
                                            <input type="text" name="emergency_mobile2" id="emergency_mobile2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label text-light">Relation2</label>
                                            <div class="controls">
                                                <select name="emergency_relation2" class="form-control">
                                                    <option>Father</option>
                                                    <option>Mother</option>
                                                    <option>Sister</option>
                                                    <option>Brother</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary prev-step">Previous</button>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>

                            <!-- Step 4: Joining Details -->
                            <div class="step" id="step-4">
                                <h2 class="text-primary">Joining Details</h2>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Employee ID</label>
                                            <input type="text" name="employee_id" id="employee_id" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Date of Joining</label>
                                            <input type="date" name="joining_date" id="joining_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Salary</label>
                                            <input type="text" name="salary" id="salary" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Monthly Salary Target</label>
                                            <input type="text" name="monthly_salary_target" id="monthly_salary_target" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Salary Bank</label>
                                            <input type="text" name="salary_bank" id="salary_bank" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Salary Account Number</label>
                                            <input type="text" name="salary_account_no" id="salary_account_no" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">IFSC Code</label>
                                            <input type="text" name="ifsc_code" id="ifsc_code" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary prev-step">Previous</button>
                                <button type="submit" class="btn btn-success save-step">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END Main Content -->
            </div>
    <!-- End Here -->
</div>
<!-- Main Content End Here  -->


<!-- JS Links Start Here -->
<script src="{{asset('Admin/assets/ajax/libs/jquery/2.1.1/jquery.min.js')}}"></script>
<script>
window.jQuery || document.write('<script src="assets/jquery/jquery-2.1.1.min.js"><\/script>')
</script>
<!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    $(document).ready(function () {
    $("#loginRoleSelect").change(function () {
        if ($(this).val() === "Executive") {
            $("#managerOptionsLogin").show();
            $("#managerOptions").hide();
        } else {
            $("#managerOptionsLogin").hide();
        }
        
    });
});

</script>

<script>
    $(".SalesteamSelect").on("change", function () {
    let selectedTeam = $(this).val();
     let salesRoleSelect = $("#salesRoleSelect").val();
     if (salesRoleSelect === "Manager") {
        console.log("Role is Manager, stopping function!");
        return;
    }
    
    $("#managerOptions").show();
    $("#managerSelect").empty().append('<option disabled selected>-- Select Manager --</option>');

    $.ajax({
        url: "{{ url('get-managers-by-team') }}",
        type: "POST",
        data: { 
            team: selectedTeam
        },
        dataType: "json",
        success: function (response) {
            $.each(response, function (index, manager) {
                $("#managerSelect").append(`<option value="${manager.id}">${manager.name}</option>`);
            });
        },
        error: function (xhr, status, error) {
            console.log("AJAX Error:", error);
            alert("AJAX Request Failed!");
        }
    });
});



// When Manager is Selected, Fetch Team Leaders
    $(".SalesmanagerSelect").on("change", function () {
        let salesRoleSelect = $("#salesRoleSelect").val();
        let selectedTeam = $("#teamSelect").val();
        let selectedManager = $(this).val();          // manager name 
        // alert(selectedManager);
        console.log("Selected Manager:", selectedManager);

        if (salesRoleSelect === "TL") {
            console.log("Role is TL, stopping function!");
            return;
        }
        
        $("#tlOptions").show();
        $("#tlSelect").empty().append('<option disabled selected>-- Select Team Leader --</option>');

        $.ajax({
            url: "{{ url('get-team-leaders') }}",
            type: "POST",
            data: { 
                team: selectedTeam, 
                manager: selectedManager
            },
            dataType: "json",
            success: function (response) {
                $.each(response, function (index, teamLeader) {
                    $("#tlSelect").append(`<option value="${teamLeader.id}">${teamLeader.name}</option>`);
                });
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error:", error);
            }
        });
    });

</script>


<!-- Form Submitted Here -->
 <!-- Add Lead Here -->
<script>
    $("#add_form").submit(function(e) {
    e.preventDefault();
    
    var mobile = $("#mobile").val();
    var alternate_mobile = $("#alternate_mobile").val();

    $.ajax({
        type: "POST",
        url: "{{ url('/check_mobile_employee_existence') }}",
        data: {
            mobile: mobile,
            alternate_mobile: alternate_mobile
        },
        dataType: "json",
        success: function(response) {
            if (response.exists == true) {
                alertify.set('notifier', 'position', 'top-right');
                if (response.field === 'mobile') {
                    alertify.error(response.message);
                } else if (response.field === 'alternate_mobile') {
                    alertify.error(response.message);
                }
            } else {
                var formData = new FormData($("#add_form")[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ url('/add_employee') }}",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    cache: false,
                    encode: true,
                    success: function(data) {
                        if (data.success == 'success') {
                            $("#add_form")[0].reset();
                            Swal.fire("Employee Added Successfully", "", "success").then(() => {
                                window.location.href = "{{ url('/') }}";
                            });
                        } else if (data.success == 'exists') {
                            Swal.fire(data.message, "", "warning");
                        } else {
                            Swal.fire("Employee Not Added", "", "error");
                        }
                    },
                    error: function(err) {
                        console.log("Error:", err);
                    }
                });
            }
        },
        error: function(err) {
            console.log("Error:", err);
        }
    });
});

    
// $("#add_form").submit(function(e) 
// {
//     e.preventDefault();
    
//     var mobile = $("#mobile").val();
//     var alternate_mobile = $("#alternate_mobile").val();

//     $.ajax({
//         type: "POST",
//         url: "{{ url('/check_mobile_employee_existence') }}",
//         data: {
//             mobile: mobile,
//             alternate_mobile: alternate_mobile
//         },
//         dataType: "json",
//         success: function(response) {
//             if (response.exists == true) {
//                 alertify.set('notifier', 'position', 'top-right');
//                 if (response.field === 'mobile') {
//                     // alert("Mobile1 Alert");
//                     alertify.error(response.message);
//                 } else if (response.field === 'alternate_mobile') {
//                     // alert("Mobile2 ALert");
//                     alertify.error(response.message);
//                 }
//             } else {
//                 // alert("Wrong");
//                 var formData = new FormData($("#add_form")[0]);
//                 $.ajax({
//                     type: "POST",
//                     url: "{{ url('/add_employee') }}",
//                     data: formData,
//                     dataType: "json",
//                     contentType: false,
//                     processData: false,
//                     cache: false,
//                     encode: true,
//                     success: function(data) {
//                         if (data.success == 'success') {
//                             $("#add_form")[0].reset();
//                             swal("Employee Added Successfully", "", "success");
//                             window.location.href = "{{ url('/') }}";
//                         } else if (data.success == 'exists') {
//                             swal(data.message, "", "warning");
//                         } else {
//                             swal("Employee Not Added", "", "error");
//                         }
//                     },
//                     error: function(err) {
//                         console.log("Error:", err);
//                     }
//                 });
//             }
//         },
//         error: function(err) {
//             console.log("Error:", err);
//         }
//     });
// });
// add lead end here
</script>
<!-- Form Submitted Here -->








 <!-- Multi Step Form Start Here -->
 <script>
        document.addEventListener("DOMContentLoaded", function () {
            const steps = document.querySelectorAll(".step");
            const prevButtons = document.querySelectorAll(".prev-step");
            const nextButtons = document.querySelectorAll(".next-step");
            const saveButton = document.querySelector(".save-step");
            const currentStepIndicator = document.getElementById("current-step");
            let currentStep = 0;

            function showStep(stepIndex) {
                steps.forEach((step, index) => {
                    step.classList.toggle("active", index === stepIndex);
                });
                currentStepIndicator.textContent = stepIndex + 1;
            }

            prevButtons.forEach(button => {
                button.addEventListener("click", function () {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            nextButtons.forEach(button => {
                button.addEventListener("click", function () {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            saveButton.addEventListener("click", function () {
                // Handle form submission or final step logic here
                // alert("Form submitted!");
            });

            // Initialize the first step
            showStep(currentStep);

            // Department selection logic
            const departmentSelect = document.getElementById("departmentSelect");
            const salesRoleSelect = document.getElementById("salesRoleSelect");
            const loginRoleSelect = document.getElementById("loginRoleSelect");
            const teamSelect = document.getElementById("teamSelect");
            const managerSelect = document.getElementById("managerSelect");
            const tlSelect = document.getElementById("tlSelect");

            departmentSelect.addEventListener("change", function () {
                const selectedDepartment = departmentSelect.value;
                document.getElementById("salesOptions").style.display = "none";
                document.getElementById("loginOptions").style.display = "none";
                document.getElementById("teamOptions").style.display = "none";
                document.getElementById("managerOptions").style.display = "none";
                document.getElementById("tlOptions").style.display = "none";

                if (selectedDepartment === "Sales") {
                    document.getElementById("salesOptions").style.display = "block";
                } else if (selectedDepartment === "Login") {
                    document.getElementById("loginOptions").style.display = "block";
                } else if (selectedDepartment === "HR") {
                    // No additional options for HR
                }
            });

            salesRoleSelect.addEventListener("change", function () {
                const selectedRole = salesRoleSelect.value;
                document.getElementById("teamOptions").style.display = "none";
                document.getElementById("managerOptions").style.display = "none";
                document.getElementById("tlOptions").style.display = "none";

                if (selectedRole === "Manager") {
                    document.getElementById("teamOptions").style.display = "block";
                } else if (selectedRole === "TL") {
                    document.getElementById("teamOptions").style.display = "block";
                    document.getElementById("managerOptions").style.display = "block";
                } else if (selectedRole === "Agent") {
                    document.getElementById("teamOptions").style.display = "block";
                    document.getElementById("managerOptions").style.display = "block";
                    document.getElementById("tlOptions").style.display = "block";
                }
            });

            loginRoleSelect.addEventListener("change", function () {
                const selectedRole = loginRoleSelect.value;
                document.getElementById("managerOptions").style.display = "none";

                // if (selectedRole === "Executive") {
                //     document.getElementById("managerOptions").style.display = "block";
                // }
            });
        });
    </script>
<!-- Multi Step Form End Here -->

 <!-- 01-02-2025  dob--> 
<script>
document.querySelectorAll("#dob").forEach(function (input) {
    input.addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, ""); // Sirf numbers allow karega (0-9)
        if (value.length > 8) {
            value = value.substring(0, 8); // Maximum 8 digits allow karega
        }
        let formattedValue = "";
        // Date validation (DD should be 01-31)
        if (value.length >= 2) {
            let day = parseInt(value.substring(0, 2));
            if (day < 1) day = "01"; 
            if (day > 31) day = "31";
            formattedValue += day.toString().padStart(2, "0") + "-"; // Ensure two-digit format
        } else {
            formattedValue += value;
        }
        // Month validation (MM should be 01-12)
        if (value.length >= 4) {
            let month = parseInt(value.substring(2, 4));
            if (month < 1) month = "01";
            if (month > 12) month = "12";
            formattedValue += month.toString().padStart(2, "0") + "-"; // Ensure two-digit format
        } else if (value.length > 2) {
            formattedValue += value.substring(2, 4);
        }
        // Year (YYYY)
        if (value.length > 4) {
            formattedValue += value.substring(4, 8);
        }
        e.target.value = formattedValue; // Final formatted value set karega
    });
});
</script>
<!-- 01-02-2025 dob -->
 <!-- Pancard and Aadhar No -->
 <script>
    // Check 12 Digits
    $('#aadhar_no').on('input', function() {
        const aadharNumber = $(this).val();
        if (aadharNumber.length !== 12 && aadharNumber.length > 0) {
            $('#aadharnoError').show().text("Aadhar Number must be exactly 12 digits.");
        } else {
            $('#aadharnoError').hide();
        }
    });
    // Pancard no 10 Digits 
    $('#pan_no').on('input', function() {
        const pancardNumber = $(this).val();
        if (pancardNumber.length !== 10 && pancardNumber.length > 0) {
            $('#pancardnoError').show().text("Pan Card Number must be exactly 10 digits.");
        } else {
            $('#pancardnoError').hide();
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

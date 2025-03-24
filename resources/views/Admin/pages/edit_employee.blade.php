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
                    <form id="edit_form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    @csrf
                        <input type="hidden" name="id" id="id" value="{{$employee->id ?? ''}}" class="form-control">
                            <div class="step-indicator">
                                <!--<span>Step <span id="current-step">1</span> out of 4</span>-->
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
                                        <img src="{{ $employee->image }}" style="height:70px" />
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light"> Name</label>
                                            <input type="text" name="name" id="name" value="{{$employee->name ?? ''}}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light"> Number</label>
                                            <input type="number" name="mobile" id="mobile" value="{{$employee->mobile ?? ''}}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Alternate Number</label>
                                            <input type="number" name="alternate_mobile" id="alternate_mobile" value="{{$employee->alternate_mobile ?? ''}}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Gender</label>
                                            <div class="controls">
                                               <select name="gender" class="form-control">
                                                    <option value="Male" {{ isset($employee) && $employee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ isset($employee) && $employee->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Personal Email id</label>
                                            <input type="email" value="{{$employee->email ?? ''}}" name="email" id="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Official Email id</label>
                                            <input type="email" name="official_email" value="{{$employee->official_email ?? ''}}" id="official_email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Pan number</label>
                                            <input type="text" value="{{$employee->pan_no ?? ''}}" name="pan_no" id="pan_no" class="form-control">
                                                <!-- aadhar_no Error min and max 10 digits -->
                                                <span id="pancardnoError" style="color:red;display:none;"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Aadhar number</label>
                                            <input type="text" value="{{$employee->aadhar_no ?? ''}}" name="aadhar_no" id="aadhar_no" class="form-control">
                                                <!-- aadhar_no Error min and max 12 digits -->
                                                <span id="aadharnoError" style="color:red;display:none;"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">DOB</label>
                                            <input type="text" value="{{$employee->dob ?? ''}}" name="dob" id="dob" class="form-control" maxlength="10" placeholder="DD-MM-YYYY">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Experience</label>
                                            <input type="text" value="{{$employee->experience ?? ''}}" name="experience" id="experience" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label text-light">Qualification</label>
                                            <div class="controls">
                                                <select name="qualification" class="form-control">
                                                    <option value="10th" {{ isset($employee) && $employee->qualification == '10th' ? 'selected' : '' }}>10th</option>
                                                    <option value="12th" {{ isset($employee) && $employee->qualification == '12th' ? 'selected' : '' }}>12th</option>
                                                    <option value="Graduation" {{ isset($employee) && $employee->qualification == 'Graduation' ? 'selected' : '' }}>Graduation</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12" style="padding: 0;">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label text-light">Permanent Address</label>
                                                <textarea name="permanent_address" class="form-control" id="permanent_address">{{$employee->permanent_address ?? ''}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label text-light">Current Address</label>
                                                <textarea name="current_address" class="form-control" id="current_address">{{$employee->current_address ?? ''}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Step 4: Joining Details -->
                            <button type="submit" class="btn btn-success save-step">Save</button>
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

// update 
$("#edit_form").submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type: "post",
        url: "{{ url('/update_employee') }}",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        encode: true,
        success: function(data) {
            if (data.success == 'success') {
                $(".btn_update").prop("disabled", false);
                swal("Employee Updated Successfully", "", "success");

                // ✅ 1-second delay before redirecting to show_employee page
                setTimeout(function() {
                    window.location.href = "{{ url('/show_employee') }}";
                }, 1000);
            } else {
                swal("Employee Not Updated!", "", "error");
                $(".btn_update").prop('disabled', false);
            }
        },
        error: function(errResponse) {
            swal("Something Went Wrong!", "", "error");
            $(".btn_update").prop('disabled', false);
        }
    });
});

</script>
<!-- Form Submitted Here -->

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

@if(session()->get('admin_login'))
@foreach(session()->get('admin_login') as $admin_login)
@extends('Admin.layouts.master')
@section('main-content')
<style>

</style>

<!-- Main Content Start Here  -->
<div class="container" id="main-container">
    <input type="hidden" id="admin_id" value="{{$employee_profile->id}}"/>
            <!-- BEGIN Content -->
            <div id="main-content">
                <!-- BEGIN Page Title -->
                <div class="page-title lead_page_title" style="display: flex; align-items: center;">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <!--<img class="profile-image" src="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&amp;q=60&amp;w=3000&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D"-->
                        <!--    alt="" height="80px" width="80px" style="object-fit: cover; border-radius: 50%;">-->
                         <img class="profile-image" src="" height="80px" width="80px" style="object-fit: cover; border-radius: 50%;">
                        <h3 class="theam_color_text">{{$employee_profile->name ?? ''}}</h3>
                    </div>
                        <!--<button class="btn" id="fileSentBtn" data-id="2">Onbording Complete</button>-->
                        @php
                            $employeeStatus = DB::table('admin')->where('id', $employee_profile->id)->value('employee_status');
                        @endphp
                        
                        @if ($employeeStatus === 'NEW EMPLOYEE')
                            <div>
                                <button class="btn" id="onboardingCompleteBtn" data-id="{{ $employee_profile->id }}">ONBOARDING COMPLETE</button>
                            </div>
                        @endif

                </div>
                <!-- END Page Title -->
                <!-- BEGIN Main Content -->
                <div class="row">

                    <div class="col-md-12">
                        <div class="tabbable">
                            <ul id="myTab1" class="nav nav-tabs">
                                <li class="active"><a href="#employeeDetails" data-toggle="tab"><i
                                            class="fa fa-home"></i>
                                        Employee Details</a></li>
                                <li><a href="#Remark" data-toggle="tab"><i class="fa fa-user"></i>
                                        Remark</a></li>
                                <li><a href="#Attachement" data-toggle="tab"><i class="fa fa-user"></i>
                                        Attachement</a></li>
                                <li><a href="#activety" data-toggle="tab"><i class="fa fa-user"></i>
                                        Activity</a></li>
                            </ul>

                            <div id="myTabContent1" class="tab-content">
                                <div class="tab-pane fade in active all_tabs_bg" id="employeeDetails">
                                    <div class="boligation_tabls">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel-group accordion" id="accordion2">
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="accordion-toggle" data-toggle="collapse"
                                                                    data-parent="#accordion2" href="#collapseOne2"><i
                                                                        class="fa icon-chevron"></i> Employee Details
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseOne2" class="panel-collapse collapse in">
                                                            <div class="panel-body">
                                                                <div class="lead_deatails_card" style="padding: 10px;">
                                                                    <!--<a href="edit_employee.html"-->
                                                                    <!--    class="edit_btn_on_lead">-->
                                                                    <!--    <i class="fa fa-pencil"-->
                                                                    <!--        style="color: black;"></i>-->
                                                                    <!--</a>-->
                                                                    <a href="{{ url('/edit_employee') }}" class="edit_btn_on_lead">
                                                                        <i class="fa fa-pencil" style="color: black;"></i>
                                                                    </a>
                                                                    <div class="edit_btn_on_lead">
                                                                        <i class="fa fa-pencil"
                                                                            style="color: white;"></i>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <div class="all_leads_section">
                                                                                <h5>Name</h5>
                                                                                <p class="profile-name"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Gender</h5>
                                                                                <p class="profile-gender"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Pan</h5>
                                                                                <p class="profile-pan_no"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Exprience</h5>
                                                                                <p class="profile-experience"> Years</p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Current Address</h5>
                                                                                <p class="profile-current_address"></p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="all_leads_section">
                                                                                <h5>Mobile Number</h5>
                                                                                <p class="profile-mobile"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Email</h5>
                                                                                <p class="profile-email"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Adhar No</h5>
                                                                                <p class="profile-aadhar_no"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Qualification</h5>
                                                                                <p class="profile-qualification"></p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="all_leads_section">
                                                                                <h5>Alternate Number</h5>
                                                                                <p class="profile-alternate_mobile"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Official Email</h5>
                                                                                <p class="profile-official_email"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>DOB</h5>
                                                                                <p class="profile-dob"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Permanent Address</h5>
                                                                                <p class="profile-permanent_address"></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="accordion-toggle collapsed"
                                                                    data-toggle="collapse" data-parent="#accordion2"
                                                                    href="#collapseTwo2"><i
                                                                        class="fa icon-chevron"></i>Team Details</a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseTwo2" class="panel-collapse collapse">
                                                            <div class="panel-body">
                                                                <div class="lead_deatails_card" style="padding: 10px;">
                                                                    <!-- <div class="edit_btn_on_lead">
                                                                        <i class="fa fa-pencil"
                                                                            style="color: black;"></i>
                                                                    </div> -->
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <div class="all_leads_section">
                                                                                <h5>Department</h5>
                                                                                <p class="profile-department"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Team Leader</h5>
                                                                                <p class="profile-team_leader">Abdul</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="all_leads_section">
                                                                                <h5>Team</h5>
                                                                                <p class="profile-team"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Designation</h5>
                                                                                <p class="profile-role"></p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="all_leads_section">
                                                                                <h5>Manager</h5>
                                                                                <p class="profile-">Abhinav</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="accordion-toggle collapsed"
                                                                    data-toggle="collapse" data-parent="#accordion2"
                                                                    href="#collapseThree2"><i
                                                                        class="fa icon-chevron"></i>Emergency
                                                                    Contacts</a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseThree2" class="panel-collapse collapse">
                                                            <div class="panel-body">
                                                                <div class="lead_deatails_card" style="padding: 10px;">
                                                                    <!-- <div class="edit_btn_on_lead">
                                                                        <i class="fa fa-pencil"
                                                                            style="color: black;"></i>
                                                                    </div> -->
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-sm-4">
                                                                            <div class="all_leads_section">
                                                                                <h5>Name</h5>
                                                                                <p class="profile-name"></p>
                                                                            </div>
                                                                            <div class="all_leads_section">
                                                                                <h5>Number</h5>
                                                                                <p class="profile-mobile"></p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <div class="all_leads_section">
                                                                                <h5>Relation</h5>
                                                                                <p class="profile-relation"></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade all_tabs_bg" id="activety">
                                    <div class="boligation_tabls">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <button class="btn" id="download_activity">Save as pdf</button>
                                            </div>
                                        </div>
                                        <ul class="timeline status-updates">
                                           <!-- activity loop start here -->
                                           
                                           <!-- activity loop end here -->
                                            <li class="clearfix"></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-pane fade all_tabs_bg" id="Remark">
                                    <div class="boligation_tabls">
                                        <div class="row">
                                            <div class="col-md-12" style="display: flex; justify-content: space-between;">
                                                <h3 class="label_color_text">Remark</h3>
                                                <!-- Search Start Here -->
                                                <div class="custom-search" style=" width: 50%;">
                                                    <input type="text" class="form-control search_contact" style="width:100%;" placeholder="Search Here" pattern="\d*"/>
                                                </div>
                                                <!-- Search End Here -->
                                            </div>

                                            <div class="col-md-12">
                                                <div class="messages-input-form">
                                                   <form id="add_form" action="javascript:void(0);" enctype="multipart/form-data" method="post">
                                                    @csrf
                                                        <div class="input">
                                                            <input type="text" name="remark" placeholder="Write here..."
                                                                class="form-control remark">
                                                        </div>
                                                        <div class="buttons">
                                                            <button type="submit" class="btn btn-primary btn_submit"><i
                                                                    class="fa fa-share"></i></button>
                                                        </div>
                                                    </form>
                                                </div>
                                                
                                                 <!-- Remark Start Here -->
                                                <ul class="messages messages-stripped">
                                                </ul>
                                                <!-- Remark End Here -->
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="tab-pane fade all_tabs_bg" id="Attachement">-->
                                <!--    <div class="boligation_tabls">-->
                                <!--        <div class="row">-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">CIBIL REPORT</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">PASSPORT SIZE PHOTO</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">PAN CARD</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">Aadhar Card</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">3 MONTHS SALARY SLIP</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">SALARY ACCOUNT-->
                                <!--                        BANKING</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">BT LOAN DOCUMENTS</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">CREDIT CARD-->
                                <!--                        STATEMENT</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">ELECTRICITY BILL</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">FORM 16 & 26AS</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                <div class="form-group">-->
                                <!--                    <label class="control-label text-light">OTHER DOCUMENTS</label>-->
                                <!--                    <input type="file" class="form-control">-->
                                <!--                </div>-->
                                <!--            </div>-->
                                <!--            <div class="col-sm-3">-->
                                <!--                 <div class="form-group"> -->
                                <!--                <label class="control-label text-light">All file PASSPORT</label>-->
                                <!--                <textarea class="form-control" autocorrect="on"></textarea>-->
                                <!--                 </div> -->
                                <!--            </div>-->
                                <!--        </div>-->
                                <!--        <div class="row">-->
                                <!--            <div class="col-md-12">-->
                                <!--                <h3 class="label_color_text">All Attachement</h3>-->
                                <!--            </div>-->

                                <!--            <div class="col-md-4 ">-->
                                <!--                <div class="card task_card ">-->
                                <!--                    <div class="car-header">-->
                                <!--                        <h5 class="label_color_text">Timing</h5>-->
                                <!--                        <p>Today , 12:14 Am</p>-->
                                <!--                    </div>-->
                                <!--                    <div class="car-header"-->
                                <!--                        style="margin-bottom: 10px; justify-content: end;">-->
                                <!--                        <button class="btn">View</button>-->
                                <!--                    </div>-->

                                <!--                </div>-->
                                <!--            </div>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div>-->
                                
                                <!-- Start Here -->
                            <div class="tab-pane fade all_tabs_bg" id="Attachement">
                                <div class="boligation_tabls">

                                <!-- Start Attachment Here  -->
                                <form id="edit_attachment_form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="text" name="admin_id" value="{{$employee_profile->id}}">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">CIBIL REPORT</label>
                                                <input type="file" name="cibil_report_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">PASSPORT SIZE PHOTO</label>
                                                <input type="file" name="passport_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">PAN CARD</label>
                                                <input type="file" name="pan_card_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">Aadhar Card</label>
                                                <input type="file" name="aadhar_card_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">3 MONTHS SALARY SLIP</label>
                                                <input type="file" name="salary_3month_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">SALARY ACCOUNT BANKING</label>
                                                <input type="file" name="salary_account_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">BT LOAN DOCUMENTS</label>
                                                <input type="file" name="bt_loan_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">CREDIT CARD STATEMENT</label>
                                                <input type="file" name="credit_card_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">ELECTRICITY BILL</label>
                                                <input type="file" name="electric_bill_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">FORM 16 & 26AS</label>
                                                <input type="file" name="form_16_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">OTHER DOCUMENTS</label>
                                                <input type="file" name="other_document_image[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label text-light">All file PASSWORD</label>
                                                <textarea class="form-control" name="all_file_password" autocorrect="on"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary text-white btn_attachment_update" style="float:right;font-weight:bold;font-size:20px;" type="submit">Save</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- End Attachment Here  -->

                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="label_color_text">All Attachement</h3>
                                    </div>
                                    <div class="col-md-4 ">
                                        <div class="card task_card ">
                                            <div class="car-header">
                                                <h5 class="label_color_text">Timing</h5>
                                                <!-- <p>Today , 12:14 Am</p> -->
                                                @php 
                                                    $currentTime = \Carbon\Carbon::now('Asia/Kolkata')->format('h:i A'); 
                                                @endphp
                                                <p>Today, {{ $currentTime }}</p>

                                            </div>
                                            <div class="car-header" style="margin-bottom: 10px; justify-content: end;">
                                                <button class="btn view-btn" data-admin_id="{{$employee_profile->id}}" data-bs-toggle="modal"
                                                    data-bs-target="#viewModal">View</button>
                                                <button class="btn" id="downloadButton">Download</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Main Content -->
            </div>
            <!-- END Content -->
        </div>
<!-- Main Content End Here  -->


<!-- Modal View Attachments Here -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: black;font-weight: 900;" id="viewModalLabel">Attachments Details
                </h5>
                <button type="button" class="btn-close close-modal-btn" data-bs-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <!-- Start Here  -->
                <div id="modal-content"></div>
                <!-- End Here  -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal-btn" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal View Attachments Here -->





<!-- JS Links Start Here -->
<script src="{{asset('Admin/assets/ajax/libs/jquery/2.1.1/jquery.min.js')}}"></script>
<script>
window.jQuery || document.write('<script src="assets/jquery/jquery-2.1.1.min.js"><\/script>')
</script>
<!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>


<script>
// JQuery Start Here 
$(document).ready(function() {
    var searchInput = document.querySelector('.search_contact');
    $('.search_contact').keyup(function() {
        var search = $(this).val();
        showdata(search);
    });
    showdata('');
});

// Add form start here 
$("#add_form").submit(function(e) {
    e.preventDefault();
    var remark = $(".remark").val();
    if (remark) {
    } else {
        alertify.set('notifier', 'position', 'top-right');
        alertify.error('Remark Required');
        return;
    }
    var adminId = $("#admin_id").val();
    var formData = new FormData(this);
    formData.append('admin_id', adminId);
    
    $.ajax({
        type: "post",
        url: "{{url('/add_employee_remark')}}",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        encode: true,
        success: function(data) {
            if (data.success == 'success') {
                document.getElementById("add_form").reset();
                $(".btn_submit").prop('disabled', false);
                swal("Remark Added Successfully", "", "success");
                showdata('');
            } else {
                $(".btn_submit").prop("disabled", false);
                swal("Remark Not Added", "", "error");
            }
        },
        error: function(err) {
        }
    });
});
// remark end here

// Update Attachment Here 
$("#edit_attachment_form").submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type: "post",
        url: "{{url('/employee_update_attachment')}}",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        encode: true,
        success: function(data) {
            if (data.success === 'success') {
                document.getElementById("edit_attachment_form").reset(); // Reset form
                $(".btn_attachment_update").prop("disabled", false);
                swal("Attachment Updated Successfully", "", "success");
                showdata(''); // Call showdata function
            } else {
                $(".btn_attachment_update").prop("disabled", false);
                swal("Attachment Not Updated!", "", "error");
            }
        },
        error: function(errResponse) {
            swal("Something Went Wrong!", "", "error");
            $(".btn_attachment_update").prop("disabled", false);
        }
    });
});
// Attachment End Here 


// Showdata function start here 
function showdata(search) {
    var formData = new FormData();
    var adminId = $("#admin_id").val(); // Get admin ID

    formData.append('search', search);
    formData.append('admin_id', adminId);

    $.ajax({
        type: "POST",
        url: "{{ url('showdata') }}", // Replace with actual route
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        encode: true,
        success: function(response) {
            // employee details 
            if (response.profile) {
                var profile = response.profile;
                $(".profile-name").text(profile.name || '');
                $(".profile-name").text(profile.name || '');
                $(".profile-gender").text(profile.gender || '');
                $(".profile-pan_no").text(profile.pan_no || '');
                $(".profile-experience").text((profile.experience || '')+" Years");
                $(".profile-current_address").text(profile.current_address || '');
                $(".profile-mobile").text("+91 " + (profile.mobile || ''));
                $(".profile-email").text(profile.email || '');
                $(".profile-aadhar_no").text(profile.aadhar_no || '');
                $(".profile-qualification").text(profile.qualification || '');
                $(".profile-alternate_mobile").text(profile.alternate_mobile || '');
                $(".profile-official_email").text(profile.official_email || '');
                $(".profile-dob").text(profile.dob ? moment(profile.dob).format("DD/MM/YYYY") : '');
                $(".profile-permanent_address").text(profile.permanent_address || '');
                
                if (profile.id) {
                    var editUrl = `{{ url('/edit_employee') }}?id=${profile.id}`;
                    $(".edit_btn_on_lead").attr("href", editUrl);
                }
            }
             // Team Details 
            if (response.profile) {
                var profile = response.profile;
                $(".profile-department").text(profile.department || '');
                $(".profile-team").text(profile.team || '');
                $(".profile-role").text(profile.role || '');
                $(".profile-team_leader").text(profile.team_leader || '');
                
                // Set all_file_password in textarea
                $("textarea[name='all_file_password']").val(profile.all_file_password || '');
            }
            
            // Emergency Contact
            if (response.profile) {
                var profile = response.profile;
                $(".profile-name").text(profile.name || '');
                $(".profile-mobile").text("+91 " + (profile.mobile || ''));
                $(".profile-relation").text(profile.emergency_relation1 || '');
                // Profile image update
                if (profile.image) {
                    $(".profile-image").attr("src", profile.image);
                } else {
                    $(".profile-image").attr("src", "https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&amp;q=60&amp;w=3000&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D");
                }
            }
           

            var remarkList = $(".messages-stripped");
            var statusList = $(".status-updates");

            remarkList.html(""); // Clear previous remarks
            statusList.html(""); // Clear previous status updates
            // Append Remarks
            response.remarks.forEach(function(data) {
                var timeAgo = moment(data.date).format("h:i A"); // "04:41 PM"
                remarkList.append(`<li>
                    <img src="{{asset('Admin/img/demo/avatar/avatar2.jpg')}}" alt="">
                    <div>
                        <div>
                            <h5 class="theam_color">${data.name}</h5>
                            <span class="time"><i class="fa fa-clock-o"></i> ${timeAgo}</span>
                        </div>
                        <p>${data.remark ? data.remark : 'No remarks available'}</p>
                    </div>
                </li>`);
            });

            // Append Activity 
            response.statuses.forEach(function(item) {
                var formattedTime = moment(item.date).format("hh:mm A"); // Time "04:41 PM"
                var day = moment(item.date).format("DD"); // "13"
                var weekday = moment(item.date).format("dddd"); // "Thursday"
                var monthYear = moment(item.date).format("MMMM YYYY"); // "March 2025"
            
                statusList.append(`<li>
                    <span class="tl-icon"><i class="fa fa-clock-o"></i></span>
                    <span class="tl-time">${formattedTime}</span>
                    <span class="tl-title">${item.lead_status ?? 'No Changes'}</span>
                    <span class="tl-arrow"></span>
                    <div class="tl-content">
                        <p class="tl-date">
                            <span class="tl-numb-day">${day}</span>
                            <span class="tl-text-day">${weekday}</span>
                            <span class="tl-month">${monthYear}</span>
                        </p>
                    </div>
                </li>`);
            });
            // Ensure clearfix is always at the end
            statusList.append('<li class="clearfix"></li>');
        },
        error: function(err) {
            console.log("Error:", err);
        }
    });
}

// Function to convert timestamp to relative time (e.g., "26 sec ago")
function getTimeAgo(timestamp) {
    var time = new Date(timestamp);
    var now = new Date();
    var diffInSeconds = Math.floor((now - time) / 1000);

    if (diffInSeconds < 60) return `${diffInSeconds} sec ago`;
    var diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) return `${diffInMinutes} min ago`;
    var diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours} hours ago`;
    var diffInDays = Math.floor(diffInHours / 24);
    return `${diffInDays} days ago`;
}


// view Attachment in modal 
$(document).on('click', '.view-btn', function() {
        let adminId = $(this).data('admin_id');
        $.ajax({
            url: "{{ url('/employee_fetch_data') }}",
            type: 'GET',
            data: {
                admin_id: adminId
            },
            success: function(response) {
                if (response.message) {
                    $('#modal-content').html('<p class="text-danger">' + response.message + '</p>');
                } else {
                    // Create modal content dynamically
                    let content = `
                        <p><strong>All File Password  :  </strong> ${response.all_file_password}</p>
                    `;
                    let imagesContent = '';
                    const imageFields = [
                        'cibil_report_image', 'passport_image', 'pan_card_image',
                        'aadhar_card_image', 'salary_3month_image',
                        'salary_account_image',
                        'bt_loan_image', 'credit_card_image', 'electric_bill_image',
                        'form_16_image', 'other_document_image'
                    ];

                    // Iterate through all the image fields
                    imageFields.forEach(function(field) {
                        if (response[field] && response[field].length > 0) {
                            let rowContent = '';
                            // Split the URLs by commas (for multiple images or PDFs)
                            let fileUrls = response[field].split(',');

                            // Loop through each URL and generate a preview
                            fileUrls.forEach(function(fileUrl) {
                                let fileExtension = fileUrl.split('.').pop().toLowerCase();
                                let filePreview = '';

                                // Check the file type and generate preview accordingly
                                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                                    filePreview = `
                                        <img src="${fileUrl}" alt="${field}" style="width: 100px; height: 100px;" />
                                    `;
                                } else if (fileExtension === 'pdf') {
                                    filePreview = `
                                        <a href="${fileUrl}" target="_blank">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg"
                                                alt="PDF Preview" style="width: 100px; height: 100px;" />
                                        </a>
                                    `;
                                }

                                // Add the file preview in a row
                                rowContent += `
                                    <div class="col-4 col-sm-4 mb-3">
                                        <p><strong>${field.replace('_', ' ').toUpperCase()}:</strong></p>
                                        ${filePreview}
                                        <button class="btn btn-danger btn-sm delete-btn"
                                                data-admin_id="${adminId}"
                                                data-column_name="${field}"
                                                data-file_url="${fileUrl}">
                                            Delete
                                        </button>
                                    </div>
                                `;
                            });

                            // Wrap the row content in a div with class "row"
                            imagesContent += `
                                <div class="row mb-4">
                                    ${rowContent}
                                </div>
                            `;
                        }
                    });

                    // Set the modal content
                    $('#modal-content').html(content + imagesContent);
                    $('#viewModal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
                $('#modal-content').html('<p class="text-danger">Failed to load data.</p>');
            }
        });
    });
    
// Close modal button
$(document).on('click', '.close-modal-btn', function() {
    $('#viewModal').modal('hide');
});

// Employee Delete Attachment Single File 
$(document).on('click', '.delete-btn', function() {
    let adminId = $(this).data('admin_id');
    let columnName = $(this).data('column_name');
    let fileUrl = $(this).data('file_url');

    swal({
        title: "Are you sure?",
        text: "Do you want to delete this file?",
        icon: "warning",
        buttons: ["No, Cancel", "Yes, Delete it!"],
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: "{{ url('/employee_delete_attachment_single_file') }}",
                type: "POST",
                data: {
                    admin_id: adminId,
                    column_name: columnName,
                    file_url: fileUrl,
                    _token: "{{ csrf_token() }}" // CSRF protection
                },
                success: function(response) {
                    if (response.success) {
                        swal("File Deleted Successfully!", "", "success");
                         $('#viewModal').modal('hide');
                        showdata('');
                    } else {
                        swal("Failed to Delete!", response.message, "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error deleting file:", error);
                    swal("An Error Occurred!", "Unable to delete file.", "error");
                }
            });
        }
    });
});
// Delete Single Attachment End Here

// Employee Attachment Download 
document.getElementById('downloadButton').addEventListener('click', function() {
    let itemId = @json($employee_profile->id);
    const url = `/employee_download_zip/${itemId}`;
    window.location.href = url;
});
</script>



@endsection
@endforeach
@else
<script>
window.location.href = "{{url('/login')}}";
</script>
@endif

@if(session()->get('admin_login'))
@foreach(session()->get('admin_login') as $adminlogin)
@extends('Admin.layouts.master')
@section('main-content')
<style>
    

    .nav-tabs {
        border-bottom: none;
    }
    .nav-tabs li {
        margin: 0;
    }

    .nav-tabs li a {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        font-weight: bold;
        text-transform: uppercase;
        color: black;
        border-radius: 5px;
        transition: 0.3s;
        background: #d3d3d3; /* Default background (gray) */
    }

    .nav-tabs li.active a {
        background: yellow !important; /* Active tab turns yellow */
         color: #00A2FF !important;
    }

    .nav-tabs li a i {
        margin-right: 5px;
        font-size: 16px;
    }

    .notification_nav {
        background: red;
        color: white;
        font-size: 14px;
        font-weight: bold;
        padding: 2px 8px;
        border-radius: 50px;
        margin-left: 10px;
    }
    
    
    /* Switch Css Here */
    .custom-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 30px;
        }

    .custom-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .custom-switch-indicator {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: red;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .custom-switch-indicator:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 0px;
        bottom: 5px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.custom-switch-indicator {
        background-color: #1cc88a;
    }

    input:checked+.custom-switch-indicator:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }
        /* Rounded sliders */
        .custom-switch-indicator {
            border-radius: 34px;
        }

        .custom-switch-indicator:before {
            border-radius: 50%;
        }
         /* Switch Css End */
        
        

         /* card counting show here  */
         .stats-container {
    display: flex;
    justify-content: space-between;
    padding: 16px;
    background-color: black;
    margin-bottom: 16px;
    width: 100%;
  }
  
  .stats-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 24%;
    min-width: 220px;
    height: 64px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    padding: 0 20px;
  }
  
  .stats-title {
    font-weight: bold;
    text-transform: uppercase;
    color: #00A3EF;
    font-size: 16px;
  }
  
  .stats-count {
    font-weight: bold;
    font-size: 20px;
    color: #00A3EF;
  }
</style>

<!-- Main Content Start Here  -->
<div class="container" id="main-container">
    <input type="text" name="id" id="admin_id" value="{{$adminlogin->id ?? ''}}" class="admin_id" hidden>
            <!-- BEGIN Content -->
            <div id="main-content">
                <!-- BEGIN Page Title -->
                <div class="page-title">
                    <div style="display: flex; justify-content: space-between;">
                        <h3 class="theam_color_text"><i class="fa fa-list"></i> Leave</h3>
                        <div class="zxyzz">
                            <!-- <a href="{{url('/add-employee')}}" type="button" class="btn btn-info">
                                Add Employee
                            </a> -->
                            <button type="button" class="btn btn-info" id="openModalBtn">
                                Apply Leave
                            </button>
                        </div>
                    </div>
                </div>
                <!-- END Page Title -->
                <!-- BEGIN Main Content -->
                <div class="row">
                    <div class="col-md-12">
                        
                    <!-- card counting start here -->
                    <div class="stats-container">
                        <div class="stats-card">
                            <div class="stats-title">ALL LEAVES</div>
                            <div class="stats-count" id="card_all_leave">0</div>
                        </div>
                        
                        <div class="stats-card">
                            <div class="stats-title">APPROVED</div>
                            <div class="stats-count" id="card_approved_leave">0</div>
                        </div>
                        
                        <div class="stats-card">
                            <div class="stats-title">REJECTED</div>
                            <div class="stats-count" id="card_rejected_leave">0</div>
                        </div>
                        
                        <div class="stats-card">
                            <div class="stats-title">PENDING</div>
                            <div class="stats-count" id="card_pending_leave">0</div>
                        </div>
                    </div>
                     <!-- card counting end here -->

                       <!-- Tab Start Here -->
                        <div class="tabbable">
                            <ul id="adminTabs" class="nav nav-tabs">
                                <li class="active">
                                    <a href="javascript:void(0);" class="admin-tab" data-status="1">
                                        <i class="fa fa-home"></i> All Leave <span class="notification_nav" id="all_leave">0</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="admin-tab" data-status="0">
                                        <i class="fa fa-user"></i> Pending Leave <span class="notification_nav" id="pending_leave">0</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="admin-tab" data-status="0">
                                        <i class="fa fa-user"></i> Approved Leave <span class="notification_nav" id="approved_leave">0</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="admin-tab" data-status="0">
                                        <i class="fa fa-user"></i> Rejected Leave <span class="notification_nav" id="rejected_leave">0</span>
                                    </a>
                                </li>
                            </ul>
                            <br>
                        <!-- Search Start Here -->
                        <div class="custom-search">
                            <input type="text" class="form-control search_admin" style="width:50%;" placeholder="Search Here" pattern="\d*"/>
                        </div>
                        <!-- Search End Here -->
                            
                            <div class="table-responsive">
                                <table class="table table-advance" id="leaveTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employee Name</th>
                                            <th>Leave Type</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Leave Duration</th>
                                            <th>Approved By</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        
                            <!-- Single Pagination for Both Tabs -->
                            <nav class="mt-2">
                                <ul id="pagination" class="pagination" style="display: flex; justify-content: center;"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Main Content -->
            </div>
            <!-- END Content -->
        </div>
<!-- Main Content End Here  -->


<!-- Add Leave Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color: black;" id="modalTitle">Apply Leave</h4>
            </div>
            <div>
                <div class="col-md-12">
                <form id="add_form" action="javascript:void(0);" enctype="multipart/form-data" method="post">
                @csrf
                        <div class="col-sm-12">
                            <label class="control-label">Id</label>
                            <input type="text" placeholder="Name" class="form-control" value="EMP{{$adminlogin->id ?? ''}}" readonly>
                        </div>

                        <div class="col-sm-12">
                            <label class="control-label">Name</label>
                            <input type="text" placeholder="Name" class="form-control" value="{{$adminlogin->name ?? ''}}" readonly>
                        </div>

                        <div class="col-sm-12">
                            <label class="control-label">Leave Type</label>
                            <div class="controls">
                                <select name="leave_type" class="form-control" data-placeholder="Choose a Leave Type" tabindex="1">
                                    <option selected="true" disabled="true">Leave Type</option>
                                    <option value="Paid Leave">Paid Leave</option>
                                    <option value="Casual Leave">Casual Leave</option>
                                </select>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label">From Date</label>
                                    <input type="date" name="from_date" class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">To Date</label>
                                    <input type="date" name="to_date" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <label class="control-label">Leave Duration</label>
                            <input type="text" name="duration" placeholder="Enter Leave Duration ( in days )" class="form-control" readonly>
                        </div>

                        <div class="col-sm-12" style="margin-top: 10px;">
                            <textarea name="note" class="form-control" rows="6" placeholder="Write reason here ..."></textarea>
                        </div>

                        <div class="col-sm-12" style="margin-top: 10px;">
                            <button type="submit" class="btn btn-primary add_btn"><i class="fa fa-rocket"></i>
                                Add</button>
                            <a type="button" class="btn">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Add Leave Modal -->

<!-- Edit Employee Modal Here  -->
<div id="editEmployeeModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color: black;" id="modalTitle">Edit Leave</h4>
            </div>
            <div>
                <div class="col-md-12">
                <form id="edit_employee_form" action="javascript:void(0);" enctype="multipart/form-data" method="post">
                @csrf
                        <!-- leave_id  -->
                        <input type="hidden" name="leave_id" class="form-control edit_leave_id" readonly> 
                        <!-- admin_id -->
                        <div class="col-sm-12">
                            <label class="control-label">Id</label>
                            <input type="text" class="form-control edit_admin_id" readonly>
                        </div>
                        <!-- admin name like employee name -->
                        <div class="col-sm-12">
                            <label class="control-label">Name</label>
                            <input type="text" class="form-control edit_admin_name" readonly>
                        </div>

                        <div class="col-sm-12">
                            <label class="control-label">Leave Type</label>
                            <div class="controls">
                                <select name="leave_type" class="form-control edit_leave_type" data-placeholder="Choose a Department" tabindex="1">
                                    <option selected="true" disabled="true">Select Department</option>
                                    <option value="Paid Leave">Paid Leave</option>
                                    <option value="Casual Leave">Casual Leave</option>
                                </select>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="control-label">From Date</label>
                                    <input type="date" name="from_date" class="form-control edit_from_date">
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">To Date</label>
                                    <input type="date" name="to_date" class="form-control edit_to_date">
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label">Leave Duration</label>
                                <input type="text" name="duration" class="form-control edit_duration">
                            </div>
                        </div>
                        </div>
                        
                        <div class="col-sm-12" style="margin-top: 10px;">
                        <label class="control-label">Note</label>
                            <textarea name="note" class="form-control edit_note" rows="4" placeholder="Address..."></textarea>
                        </div>
                        <!-- Change Status Start Here -->
                        <div class="col-sm-12" style="margin-top: 10px; display: flex; gap: 10px; justify-content: space-between;">
                            <div>
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle" type="button" id="statusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        CHANGE STATUS
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="statusDropdown">
                                        <a class="dropdown-item status-option" href="javascript:void(0);" onclick="updateLeaveStatus($('.edit_leave_id').val(), 'pending')">Pending</a>
                                        <a class="dropdown-item status-option" href="javascript:void(0);" onclick="updateLeaveStatus($('.edit_leave_id').val(), 'approved')">Approved</a>
                                        <a class="dropdown-item status-option" href="javascript:void(0);" onclick="updateLeaveStatus($('.edit_leave_id').val(), 'rejected')">Rejected</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 10px;">
                                <button id="edit_button" class="btn btn-primary edit_button" type="button" style="background-color: #4e73f8; border-color: #4e73f8;">EDIT</button>
                                <button id="update_button" class="btn btn-success update_employee_btn" style="display: none;" type="submit"><i class="fa fa-refresh"></i> Update</button>
                            </div>
                        </div>
                        <!-- Change Status End Here -->
                    </form>

                    <!-- Remark Start Here  -->

                    <!-- Add Comment And History Start Here -->
                <div class="col-sm-12">
                    <p>
                    <div class="tabbable">
                        <ul id="myTab1" class="nav nav-tabs">
                            <li class="active"><a href="#comment1" data-toggle="tab"><i class="fa fa-home"></i>
                                    Add Comments</a></li>
                            <!-- <li><a href="#history1" data-toggle="tab"><i class="fa fa-user"></i>
                                    History</a></li> -->
                        </ul>
                        <div id="myTabContent1" class="tab-content">
                            <div class="tab-pane fade in active all_tabs_bg" id="comment1">
                                <div class="boligation_tabls">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="messages-input-form">
                                                <form class="add_comment_leave" method="POST" action="javascript:void(0);">
                                                    @csrf
                                                    <div class="input">
                                                        <input type="hidden" name="leave_id" id="comment_leave_id" class="comment_leave_id" value="">
                                                        <input type="hidden" name="admin_id" value="{{$adminlogin->id ?? ''}}">
                                                        <input type="text" name="comment" placeholder="Write here..."
                                                            class="form-control">
                                                    </div>
                                                    <div class="buttons">
                                                        <button type="submit" class="btn btn-primary"><i
                                                                class="fa fa-share"></i></button>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Data Load From Ajax  -->
                                            <ul class="messages messages-stripped" id="get_comment">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    </p>
                </div>
                <!-- Add Comment And History End Here -->

                    <!-- Remark End Here  -->


                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Edit Employee Modal Here  -->



<!-- JS Links Start Here -->
<script src="{{asset('Admin/assets/ajax/libs/jquery/2.1.1/jquery.min.js')}}"></script>
<script>
window.jQuery || document.write('<script src="assets/jquery/jquery-2.1.1.min.js"><\/script>')
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>


<script>
    function updateLeaveStatus(leaveId, newStatus) {
    $.ajax({
        url: "{{ url('update_leave_status') }}",
        type: "POST",
        data: {
            leave_id: leaveId,
            status: newStatus,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if(response.success) {
                swal(`Leave status updated to ${newStatus} successfully!`, "", "success");
                // Reload current table
                const currentStatus = $('.nav-link.active').data('status') || 'all';
                const currentPage = parseInt($('.page-item.active .page-link').text()) || 1;
                const currentSearch = $('#searchInput').val() || '';
                loadLeaves(currentStatus, currentPage, currentSearch);
                // Close the modal
                $('#editEmployeeModal').modal('hide');
            } else {
                swal("Failed to update status", "", "error");
            }
        },
        error: function(xhr, status, error) {
            swal("Something went wrong", "", "error");
            console.error("Status Update Error:", error);
        }
    });
}
</script>
<script>
// Handle form submission for employee leave update
$(document).ready(function() {
    // Edit button click event
    $(document).on('click', '.edit_button', function() {
        // Enable all form fields except ID and Name
        $('.edit_leave_type, .edit_from_date, .edit_to_date, .edit_duration, .edit_note').prop('disabled', false);
        
        // Hide Edit button and show Update button
        $('.edit_button').hide();
        $('.update_employee_btn').show();
    });

    // Click event for Edit Employee in the table
    $(document).on('click', '.edit_employee', function() {
        let leaveId = $(this).data('id');
        let adminId = $(this).data('admin_id');
        let admin_name = $(this).data('admin_name');
        let leaveType = $(this).data('leave_type');
        let fromDate = $(this).data('from_date');
        let toDate = $(this).data('to_date');
        let duration = $(this).data('duration');
        let note = $(this).data('note');

        // dropdown status on edit employee
        let status = $(this).data('status') || 'pending';
        updateStatusDropdown(status);

        // Set values in the modal using class selectors
        $('.edit_leave_id').val(leaveId);
        $('.edit_admin_id').val("EMP" + adminId);
        $('.edit_admin_name').val(admin_name);
        
        // Set dropdown value
        $('.edit_leave_type option').each(function() {
            if($(this).val() == leaveType) {
                $(this).prop("selected", true);
            } else {
                $(this).prop("selected", false);
            }
        });
        
        $('.edit_from_date').val(fromDate);
        $('.edit_to_date').val(toDate);
        $('.edit_duration').val(duration);
        $('.edit_note').val(note);
        
        // Make sure all fields are disabled initially
        $('.edit_leave_type, .edit_from_date, .edit_to_date, .edit_duration, .edit_note').prop('disabled', true);
        
        // Show the Edit button and hide the Update button
        $('.edit_button').show();
        $('.update_employee_btn').hide();


        $(".comment_leave_id").val(leaveId);
        // leave comment show start here
        $.ajax({
            url: "{{url('/get-leave-comments')}}",
            method: 'GET',
            data: {
                leave_id: leaveId
            },
            success: function(response) {
                console.log(response);
                $('#get_comment').empty();
                
                // Initialize commentHTML before the loop
                var commentHTML = '';
                
                response.forEach(function(item) {
                    console.log("Raw Date:", item.date);
                    var formattedDate = moment(item.date, "YYYY-MM-DD hh:mm A").locale('en')
                        .fromNow();
                    console.log("Formatted Date:", formattedDate); // Debugging ke liye
                    
                    // Corrected += operator (removed space)
                    commentHTML += `
                        <li>
                            <img src="{{asset('Admin/img/demo/avatar/avatar2.jpg')}}" alt="">
                            <div>
                                <div>
                                    <h5 class="theam_color">${item.createdby}</h5>
                                    <span class="time"><i class="fa fa-clock-o"></i>
                                        ${formattedDate}
                                    </span>
                                </div>
                                <p>${item.comment}</p>
                            </div>
                        </li>
                    `;
                    // No append inside the loop
                });
                
                // Set all HTML at once after the loop
                $('#get_comment').html(commentHTML);
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
        // leave comment end here
        
        $('#editEmployeeModal').modal('show');
    });


    // dropdown value start here 
    function updateStatusDropdown(status) {
        // Set the current status on the dropdown button
        $('#statusDropdown').data('current-status', status);
        // Update the button text
        $('#statusDropdown').html(status.toUpperCase());
        // Mark the current status as active in the dropdown
        $('.status-option').removeClass('active');
        $('.status-option[data-status="' + status + '"]').addClass('active');
    }

    // Form submission for updating employee leave
    $("#edit_employee_form").submit(function(e) {
        e.preventDefault();
        // Disable the update button to prevent multiple submissions
        $(".update_employee_btn").prop('disabled', true);
        
        // Get current tab status
        let currentTabStatus = $('.nav-item.active .admin-tab').data('status');
        // Convert index to string status if needed
        let activeTabIndex = $('.nav-item.active').index();
        if (activeTabIndex === 0) {
            currentTabStatus = 'all';
        } else if (activeTabIndex === 1) {
            currentTabStatus = 'pending';
        } else if (activeTabIndex === 2) {
            currentTabStatus = 'approved';
        } else if (activeTabIndex === 3) {
            currentTabStatus = 'rejected';
        }
        
        // Get current page from pagination active link
        let currentPageNumber = parseInt($("#pagination .active a").text()) || 1;
        
        // Get current search keyword
        let currentSearchText = $('.search_admin').val().trim();
        
        var formData = new FormData(this);
        
        $.ajax({
            type: "POST",
            url: "{{url('/update_employee_leave')}}",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            cache: false,
            encode: true,
            success: function(data) {
                // Re-enable the update button
                $(".update_employee_btn").prop("disabled", false);
                
                if (data.success == 'success') {
                    $("#edit_employee_form")[0].reset();
                    $("#editEmployeeModal").modal("hide");
                    swal("Leave Updated Successfully", "", "success");
                    
                    // Use the captured values to reload the leaves
                    loadLeaves(currentTabStatus, currentPageNumber, currentSearchText);
                    loadCounts();
                } else {
                    swal("Leave Update Failed!", "", "error");
                }
            },
            error: function(errResponse) {
                // Re-enable the update button
                $(".update_employee_btn").prop("disabled", false);
                swal("Something Went Wrong!", "", "error");
            }
        });
    });

});
</script>

<script>
$(document).ready(function() {
    let currentStatus = 'all'; // Default: Start with all leaves tab
    let currentPage = 1;
    let searchKeyword = ''; // Search filter variable

    // Load counts for all tabs initially
    loadCounts();
    
    // Initial load of leaves with the default status
    loadLeaves(currentStatus, currentPage, searchKeyword);

    // Handle tab click
    $('.admin-tab').on('click', function() {
        $('.admin-tab').parent().removeClass('active'); // Remove active class
        $(this).parent().addClass('active'); // Add active class

        // Get status from data attribute
        currentStatus = $(this).data('status');
        
        // Convert numeric status to string status if needed
        if ($(this).parent().index() === 0) {
            currentStatus = 'all';
        } else if ($(this).parent().index() === 1) {
            currentStatus = 'pending';
        } else if ($(this).parent().index() === 2) {
            currentStatus = 'approved';
        } else if ($(this).parent().index() === 3) {
            currentStatus = 'rejected';
        }
        
        currentPage = 1; // Reset to first page
        searchKeyword = ''; // Tab change hone par search reset
        $('.search_admin').val(''); // Input field clear karein
        
        loadLeaves(currentStatus, currentPage, searchKeyword);
    });

    // Search functionality
    $('.search_admin').keyup(function() {
        searchKeyword = $(this).val().trim();
        currentPage = 1; // Search hone par first page se start karein
        loadLeaves(currentStatus, currentPage, searchKeyword);
    });
});

// Function to load leave counts
function loadCounts() {
    $.ajax({
        url: "{{ url('fetch_leave') }}",
        type: "POST",
        data: { status: 'all', page: 1 },
        success: function(data) {
            if (data.counts) {
                $("#pending_leave").text(data.counts.pending || 0);
                $("#approved_leave").text(data.counts.approved || 0);
                $("#rejected_leave").text(data.counts.rejected || 0);
                $("#all_leave").text(data.counts.all || 0);
            }
        }
    });
}

// Add leave form submission handler with fix
$("#add_form").submit(function(e) {
    $(".add_btn").prop('disabled', true);
    e.preventDefault();
    let adminId = $(".admin_id").val();

    let currentTabStatus = $('.nav-item.active .admin-tab').data('status');
    // Convert index to string status if needed
    let activeTabIndex = $('.nav-item.active').index();
    if (activeTabIndex === 0) {
        currentTabStatus = 'all';
    } else if (activeTabIndex === 1) {
        currentTabStatus = 'pending';
    } else if (activeTabIndex === 2) {
        currentTabStatus = 'approved';
    } else if (activeTabIndex === 3) {
        currentTabStatus = 'rejected';
    }
    
    // Get current page from pagination active link
    let currentPageNumber = parseInt($("#pagination .active a").text()) || 1;
    
    // Get current search keyword
    let currentSearchText = $('.search_admin').val().trim();
    
    var formdata = new FormData(this);
    formdata.append("admin_id", adminId);

    $.ajax({
        type: "post",
        url: "{{url('/add_leave')}}",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        encode: true,
        success: function(data) {
            $(".add_btn").prop("disabled", false);
            if (data.success == 'success') {
                document.getElementById("add_form").reset();
                $("#myModal").modal("hide");
                swal("Leave Request Successfully", "", "success");
                
                // Use the captured values to reload the leaves
                loadLeaves(currentTabStatus, currentPageNumber, currentSearchText);
                loadCounts();
            } else if (data.success == 'error') {
                $(".add_btn").prop('disabled', false);
                swal("Error", data.message, "error");
            }
        },
        error: function() {
            $(".add_btn").prop('disabled', false);
            swal("Error", "An unexpected error occurred", "error");
        }
    });
});
// add comment in leave 
$(".add_comment_leave").submit(function(e) {
    e.preventDefault();
    // Disable submit button
    $(".comment_btn").prop('disabled', true);
    
    // Capture current state before submission
    let currentStatus = $('.nav-item.active .admin-tab').data('status') || 'all';
    let currentPage = parseInt($("#pagination .active a").text()) || 1;
    let currentSearch = $('.search_admin').val().trim();
    
    var formData = new FormData(this);
    
    $.ajax({
        type: "post",
        url: "{{url('/add_leave_comment')}}",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        success: function(data) {
            // Re-enable button
            $(".comment_btn").prop('disabled', false);
            if (data.success == 'success') {
                // Reset form and close modal at this exact point
                document.getElementsByClassName("add_comment_leave")[0].reset();
                $("#editEmployeeModal").modal('hide');
                
                swal("Comment Added Successfully", "", "success");
                
                // Call loadLeaves with parameters
                loadLeaves(currentStatus, currentPage, currentSearch);
            } else {
                swal("Comment Not Added", "", "error");
            }
        },
        error: function() {
            // Re-enable button on error
            $(".comment_btn").prop('disabled', false);
            swal("Error", "Something went wrong", "error");
        }
    });
});

// Function to Load Leave Data with Search Filter
function loadLeaves(status, page, search = '') {
    $.ajax({
        url: "{{ url('fetch_leave') }}",
        type: "POST",
        data: { 
            status: status,
            page: page,
            search: search,
            _token: "{{ csrf_token() }}"
        },
        success: function(data) {
            let tbody = $("#leaveTable tbody");
            let pagination = $("#pagination");
            tbody.empty();
            pagination.empty();
            
            // Update leave counts in tabs
            if (data.counts) {
                $("#pending_leave").text(data.counts.pending || 0);
                $("#approved_leave").text(data.counts.approved || 0);
                $("#rejected_leave").text(data.counts.rejected || 0);
                $("#all_leave").text(data.counts.all || 0);

                // card count here 
                $("#card_pending_leave").text(data.counts.pending || 0);
                $("#card_approved_leave").text(data.counts.approved || 0);
                $("#card_rejected_leave").text(data.counts.rejected || 0);
                $("#card_all_leave").text(data.counts.all || 0);
            }
            
            if (data.data && data.data.length > 0) {
                // Populate table rows
                data.data.forEach((item, index) => {
                    let statusCell = '';
                    if(item.status == 'pending') {
                        statusCell = `<td><span style="background-color:rgb(167, 137, 40); color: white; padding: 5px 10px; border-radius: 4px;">Pending</span></td>`;
                    } else if(item.status == 'approved') {
                        statusCell = `<td><span style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 4px;">Approved</span></td>`;
                    } else if(item.status == 'rejected') {
                        statusCell = `<td><span style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 4px;">Rejected</span></td>`;
                    }
                    
                    // Create the row HTML
                    const rowHtml = `
                        <tr>
                            <td>${(page-1)*data.per_page + index + 1}</td>
                            <td>
                                <a href="javascript:void(0);" class="edit_employee"
                                    data-status="${item.status || ''}"
                                    data-admin_id="${item.admin_id || ''}"
                                    data-admin_name="${item.admin_name || ''}"
                                    data-leave_type="${item.leave_type || ''}"
                                    data-from_date="${item.from_date || ''}"
                                    data-to_date="${item.to_date || ''}"
                                    data-duration="${item.duration || ''}"
                                    data-note="${item.note || ''}"
                                    data-id="${item.id || ''}">
                                    ${item.admin_name || ''} - ${item.role || ''}
                                </a>
                            </td>
                            <td>${item.leave_type || ''}</td>
                            <td>${formatDate(item.from_date)}</td>
                            <td>${formatDate(item.to_date)}</td>
                            <td>${item.duration || ''} </td>
                            <td>${item.approved_by || '-'}</td>
                            ${statusCell}
                        </tr>
                    `;
                    // Append to tbody
                    tbody.append(rowHtml);
                });

                // Generate pagination links
                generatePaginationLinks(data.current_page, data.last_page, status, search);
            } else {
                console.log('No data found or empty data array');
                tbody.append(`<tr><td colspan="8" class="text-center">No Leave Data Found</td></tr>`);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            console.log("Status:", status);
            console.log("Response:", xhr.responseText);
            
            // Show error message to user
            let tbody = $("#leaveTable tbody");
            tbody.empty();
            tbody.append(`<tr><td colspan="8" class="text-center text-danger">Error loading data. Please try again.</td></tr>`);
        }
    });
}

// Format date function
function formatDate(dateString) {
    if (!dateString) return '-';
    let date = new Date(dateString);
    let day = date.getDate().toString().padStart(2, '0');
    
    // Array of month names
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    
    let month = monthNames[date.getMonth()];
    let year = date.getFullYear();
    
    return `${day} ${month} ${year}`;
}

// Generate pagination links function
function generatePaginationLinks(currentPage, lastPage, status, search = '') {
    let pagination = $("#pagination");
    pagination.html('');

    if (lastPage > 1) {
        // Previous button
        pagination.append(`
            <li class="${currentPage === 1 ? 'disabled' : ''}">
                <a href="javascript:void(0);" ${currentPage > 1 ? `onclick="loadLeaves('${status}', ${currentPage-1}, '${search}')"` : ''}>
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
        `);

        // Page numbers
        if (currentPage > 3) {
            pagination.append(`<li><a href="javascript:void(0);" onclick="loadLeaves('${status}', 1, '${search}')">1</a></li>`);
            pagination.append(`<li class="disabled"><a>...</a></li>`);
        }

        for (let i = Math.max(1, currentPage - 2); i <= Math.min(lastPage, currentPage + 2); i++) {
            pagination.append(`
                <li class="${i === currentPage ? 'active' : ''}">
                    <a href="javascript:void(0);" onclick="loadLeaves('${status}', ${i}, '${search}')">${i}</a>
                </li>
            `);
        }

        if (currentPage < lastPage - 2) {
            pagination.append(`<li class="disabled"><a>...</a></li>`);
            pagination.append(`<li><a href="javascript:void(0);" onclick="loadLeaves('${status}', ${lastPage}, '${search}')">${lastPage}</a></li>`);
        }

        // Next button
        pagination.append(`
            <li class="${currentPage === lastPage ? 'disabled' : ''}">
                <a href="javascript:void(0);" ${currentPage < lastPage ? `onclick="loadLeaves('${status}', ${currentPage+1}, '${search}')"` : ''}>
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        `);
    }
}
</script>




<!-- Days Count from_date to to_date -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Get the input elements
    const fromDateInput = document.querySelector('input[name="from_date"]');
    const toDateInput = document.querySelector('input[name="to_date"]');
    const durationInput = document.querySelector('input[name="duration"]');

    fromDateInput.addEventListener('change', calculateDuration);
    toDateInput.addEventListener('change', calculateDuration);
    function calculateDuration() {
        const fromDate = new Date(fromDateInput.value);
        const toDate = new Date(toDateInput.value);
        if (isNaN(fromDate.getTime()) || isNaN(toDate.getTime())) {
            return;
        }
        const diffTime = Math.abs(toDate - fromDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        const durationText = diffDays === 1 ? `${diffDays} day` : `${diffDays} days`;
        durationInput.type = 'text';
        durationInput.value = durationText;
    }
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the input elements by their class names
        const fromDateInput = document.querySelector('.edit_from_date');
        const toDateInput = document.querySelector('.edit_to_date');
        const durationInput = document.querySelector('.edit_duration');

        // Add event listeners
        fromDateInput.addEventListener('change', calculateDuration);
        toDateInput.addEventListener('change', calculateDuration);

        function calculateDuration() {
            const fromDate = new Date(fromDateInput.value);
            const toDate = new Date(toDateInput.value);
            
            // Check if both dates are valid before calculating
            if (isNaN(fromDate.getTime()) || isNaN(toDate.getTime())) {
                return;
            }
            
            const diffTime = Math.abs(toDate - fromDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            const durationText = diffDays === 1 ? `${diffDays} day` : `${diffDays} days`;
            
            durationInput.type = 'text';
            durationInput.value = durationText;
        }
    });
</script>

<script>
        $(document).ready(function () {
            $('#openModalBtn').on('click', function () {
                $('#myModal').modal('show');
            });

            $('.open-modal-btn-approve').on('click', function () {
                $('#myModal1').modal('show');
            });

            $('.open-modal-btn-reject').on('click', function () {
                $('#myModal2').modal('show');
            });

            $('#saveChangesBtn').on('click', function () {
                alert('Your changes have been saved!');
                $('#myModal').modal('hide');
            });

            $('#myModal').on('shown.bs.modal', function () {
            });

            $('#myModal').on('hidden.bs.modal', function () {
            });
        });
    </script>
@endsection
@endforeach
@else
<script>
window.location.href = "{{url('/login')}}";
</script>
@endif

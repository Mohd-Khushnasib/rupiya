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
                            <textarea name="note" class="form-control wysihtml5" rows="6" placeholder="Write reason here ..."></textarea>
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
                <h4 class="modal-title" style="color: black;" id="modalTitle">Edit Employee</h4>
            </div>
            <div>
                <div class="col-md-12">
                <form id="edit_employee_form" action="javascript:void(0);" enctype="multipart/form-data" method="post">
                @csrf
                        <input type="hidden" name="employee_id" id="edit_emp_id" class="form-control">

                        <div class="col-sm-12">
                            <label class="control-label">Id</label>
                            <input type="text" id="emp_display_id" class="form-control" readonly>
                        </div>

                        <div class="col-sm-12">
                            <label class="control-label">Name</label>
                            <input type="text" name="name" id="edit_admin_name" placeholder="Employee Name" class="form-control">
                        </div>

                        <div class="col-sm-12">
                            <label class="control-label">Leave Type</label>
                            <div class="controls">
                                <select name="leave_type" id="edit_leave_type" class="form-control" data-placeholder="Choose a Department" tabindex="1">
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
                                    <input type="date" name="from_date" id="edit_from_date" class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">To Date</label>
                                    <input type="date" name="to_date" id="edit_to_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label">Leave Duration</label>
                                <input type="text" name="duration" id="edit_duration" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-12" style="margin-top: 10px;">
                            <textarea name="note" id="edit_note" class="form-control wysihtml5" rows="4" placeholder="Address..."></textarea>
                        </div>
                        <div class="col-sm-12" style="margin-top: 10px;">
                            <button type="submit" class="btn btn-success update_employee_btn"><i class="fa fa-refresh"></i>
                                Update</button>
                            <a type="button" class="btn" data-dismiss="modal">Cancel</a>
                        </div>

                    </form>
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
                        statusCell = `<td><span style="background-color:rgb(167, 137, 40); color: white; padding: 5px 10px; border-radius: 4px;">Approved</span></td>`;
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
                                    data-admin_id="${item.id || ''}"
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

// edit employee here 
$(document).on('click', '.edit_employee', function() {
    let leaveId = $(this).data('id');
    alert(leaveId);
    let adminId = $(this).data('admin_id');
    let admin_name = $(this).data('admin_name');
    let leaveType = $(this).data('leave_type');
    let fromDate = $(this).data('from_date');
    let toDate = $(this).data('to_date');
    let duration = $(this).data('duration');
    let note = $(this).data('note');

    // Set values in the modal
    $('.edit_leave_id').val(leaveId);
    $('.edit_emp_id').val(adminId);
    $('.edit_admin_name').val(admin_name);
    // Alternative approach to set the dropdown value
    $("#edit_leave_type option").each(function() {
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
    $('#editEmployeeModal').modal('show');
});
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

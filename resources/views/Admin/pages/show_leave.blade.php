@if(session()->get('admin_login'))
@foreach(session()->get('admin_login') as $admin_login)
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
        
        

</style>

<!-- Main Content Start Here  -->
<div class="container" id="main-container">
            <!-- BEGIN Content -->
            <div id="main-content">
                <!-- BEGIN Page Title -->
                <div class="page-title">
                    <div style="display: flex; justify-content: space-between;">
                        <h3 class="theam_color_text"><i class="fa fa-list"></i> Employees</h3>
                        <div class="zxyzz">
                            <a href="{{url('/add-employee')}}" type="button" class="btn btn-info">
                                Add Employee
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END Page Title -->
                <!-- BEGIN Main Content -->
                <div class="row">
                    <div class="col-md-12">
                        
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
                                <table class="table table-advance" id="adminTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
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

<!-- JS Links Start Here -->
<script src="{{asset('Admin/assets/ajax/libs/jquery/2.1.1/jquery.min.js')}}"></script>
<script>
window.jQuery || document.write('<script src="assets/jquery/jquery-2.1.1.min.js"><\/script>')
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(document).ready(function() {
    let currentStatus = 1; // Default: Active tab open
    let currentPage = 1;
    let searchKeyword = ''; // Search filter ke liye variable

    // Changed loadAdmins to loadLeaves
    loadLeaves(currentStatus, currentPage, searchKeyword);

    // Handle tab click
    $('.admin-tab').on('click', function() {
        $('.admin-tab').parent().removeClass('active'); // Remove active class
        $(this).parent().addClass('active'); // Add active class

        currentStatus = $(this).data('status'); // Get status (1 = Active, 0 = Inactive)
        currentPage = 1; // Reset to first page
        searchKeyword = ''; // Tab change hone par search reset
        $('.search_admin').val(''); // Input field clear karein
        
        // Changed loadAdmins to loadLeaves
        loadLeaves(currentStatus, currentPage, searchKeyword);
    });

    // Search functionality
    $('.search_admin').keyup(function() {
        searchKeyword = $(this).val().trim();
        currentPage = 1; // Search hone par first page se start karein
        
        // Changed loadAdmins to loadLeaves
        loadLeaves(currentStatus, currentPage, searchKeyword);
    });
});

// Function to Load Leave Data with Search Filter
function loadLeaves(status, page, search = '') {
    // Changed .search_contact to .search_admin to match the above event handler
    var search = search || $(".search_admin").val();
    
    $.ajax({
        url: "{{ url('fetch_leave') }}",
        type: "POST",
        data: { 
            status: status, 
            page: page,
            search: search // Search parameter send karna
        },
        success: function(data) {
            console.log(data);
            let tbody = $("#leaveTable tbody");
            let pagination = $("#pagination");
            tbody.empty();
            pagination.empty();

            if (data.data.length > 0) {
                // Update leave counts in tabs
                $("#all_leave").text(data.all_leave);
                $("#pending_leave").text(data.pending_leave);
                $("#approved_leave").text(data.approved_leave);
                $("#rejected_leave").text(data.rejected_leave);

                // Indexing 
                var total_enquiries = data.total;
                var per_page = data.per_page;
                result = data.data;
                
                result.forEach((item, index) => {
                    // Get status badge class
                    let statusBadge = '';
                    if(item.status == 'pending') {
                        statusBadge = '<span class="badge badge-warning">Pending</span>';
                    } else if(item.status == 'approved') {
                        statusBadge = '<span class="badge badge-success">Approved</span>';
                    } else if(item.status == 'rejected') {
                        statusBadge = '<span class="badge badge-danger">Rejected</span>';
                    }
                    
                    // Calculate leave duration
                    const fromDate = new Date(item.from_date);
                    const toDate = new Date(item.to_date);
                    const diffTime = Math.abs(toDate - fromDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include both days
                    
                    tbody.append(`
                        <tr>
                            <td>
                                <div class="custom-checkbox custom-control">
                                    <input type="checkbox" class="custom-control-input" id="checkbox-${item.id}" value="${item.id}">
                                    <label class="custom-control-label" for="checkbox-${item.id}"></label>
                                </div>
                            </td>
                            <td>${item.leave_type || ''}</td>
                            <td>${formatDate(item.from_date)}</td>
                            <td>${formatDate(item.to_date)}</td>
                            <td>${diffDays} day${diffDays > 1 ? 's' : ''}</td>
                            <td>${item.approved_by || '-'}</td>
                            <td>${statusBadge}</td>
                        </tr>
                    `);
                });

                generatePaginationLinks(data.current_page, data.last_page, status, search);
            } else {
                tbody.append(`<tr><td colspan="7" class="text-center">No Leave Data Found</td></tr>`);
            }
        }
    });
}

// Format date function
function formatDate(dateString) {
    if (!dateString) return '-';
    let date = new Date(dateString);
    let day = date.getDate().toString().padStart(2, '0');
    let month = (date.getMonth() + 1).toString().padStart(2, '0');
    let year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

// Generate pagination links function
function generatePaginationLinks(currentPage, lastPage, status, search = '') {
    let pagination = $("#pagination");
    pagination.html('');

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
}
</script>

@endsection
@endforeach
@else
<script>
window.location.href = "{{url('/login')}}";
</script>
@endif

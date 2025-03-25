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

// Function to Load Leave Data with Search Filter
function loadLeaves(status, page, search = '') {
    console.log('Loading leaves with status:', status, 'page:', page, 'search:', search);
    
    $.ajax({
        url: "{{ url('fetch_leave') }}",
        type: "POST",
        data: { 
            status: status, // String status (pending, approved, rejected, all)
            page: page,
            search: search, // Search parameter send karna
            _token: "{{ csrf_token() }}" // Add CSRF token for Laravel
        },
        success: function(data) {
            let tbody = $("#leaveTable tbody");
            let pagination = $("#pagination");
            tbody.empty();
            pagination.empty();

            console.log('AJAX response:', data);
            
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
                    // Get status badge class
                    let statusBadge = '';
                    if(item.status == 'pending') {
                        statusBadge = '<span class="badge bg-warning text-dark" style="background-color: #ffc107; color: #212529; padding: 5px 10px; border-radius: 4px;">Pending</span>';
                    } else if(item.status == 'approved') {
                        statusBadge = '<span class="badge bg-success text-white" style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 4px;">Approved</span>';
                    } else if(item.status == 'rejected') {
                        statusBadge = '<span class="badge bg-danger text-white" style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 4px;">Rejected</span>';
                    }
                    
                    // Calculate leave duration
                    const fromDate = new Date(item.from_date);
                    const toDate = new Date(item.to_date);
                    const diffTime = Math.abs(toDate - fromDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include both days
                    
                    // Create the row HTML
                    const rowHtml = `
                        <tr>
                            <td>${(page-1)*data.per_page + index + 1}</td>
                            <td>${item.leave_type || ''}</td>
                            <td>${formatDate(item.from_date)}</td>
                            <td>${formatDate(item.to_date)}</td>
                            <td>${diffDays} day${diffDays > 1 ? 's' : ''}</td>
                            <td>${item.approved_by || '-'}</td>
                            <td>${statusBadge}</td>
                        </tr>
                    `;
                    
                    // Append to tbody
                    tbody.append(rowHtml);
                });

                // Generate pagination links
                generatePaginationLinks(data.current_page, data.last_page, status, search);
            } else {
                console.log('No data found or empty data array');
                tbody.append(`<tr><td colspan="7" class="text-center">No Leave Data Found</td></tr>`);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
            console.log("Status:", status);
            console.log("Response:", xhr.responseText);
            
            // Show error message to user
            let tbody = $("#leaveTable tbody");
            tbody.empty();
            tbody.append(`<tr><td colspan="7" class="text-center text-danger">Error loading data. Please try again.</td></tr>`);
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

@endsection
@endforeach
@else
<script>
window.location.href = "{{url('/login')}}";
</script>
@endif

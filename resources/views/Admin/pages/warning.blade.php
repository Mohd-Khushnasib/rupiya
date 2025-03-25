@extends('Admin.layouts.master')
@section('main-content')

<!-- FontAwesome CDN and existing styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<style>
    /* Existing styles remain unchanged */
    .filter-section {
        margin-bottom: 20px;
        padding: 15px;
        background: #1a1a1a;
        border-radius: 5px;
    }
    .dashboard-card {
        margin: 10px 0;
    }
</style>

<div class="container" id="main-container">
    <div id="main-content">
        <div class="page-title">
            <div style="display: flex; justify-content: space-between;">
                <h3 class="theam_color_text"><i class="fa fa-exclamation-triangle"></i> Warning Management</h3>
                @php
                    $adminRole = strtolower($admin_login->role);
                @endphp
                @if($adminRole !== 'agent')
                    <div class="zxyzz">
                        <button type="button" class="btn btn-info" id="openModalBtn">
                            <i class="fa fa-plus-circle"></i> Create Warning
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tabbable">
                    <ul id="myTab1" class="nav nav-tabs">
                        @if($adminRole === 'admin')
                            <li class="active"><a href="#dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li><a href="#all" data-toggle="tab"><i class="fa fa-list"></ipraw> All Warnings</a></li>
                        @elseif($adminRole === 'hr')
                            <li class="active"><a href="#dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li><a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a></li>
                            <li><a href="#all" data-toggle="tab"><i class="fa fa-list"></i> All Warnings</a></li>
                        @elseif($adminRole === 'manager' || $adminRole === 'tl')
                            <li class="active"><a href="#dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li><a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a></li>
                            <li><a href="#teamwarning" data-toggle="tab"><i class="fa fa-users"></i> Team Warnings</a></li>
                        @elseif($adminRole === 'agent')
                            <li class="active"><a href="#dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li><a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a></li>
                        @endif
                    </ul>

                    <div id="myTabContent1" class="tab-content">
                        <!-- Dashboard Tab -->
                        <div class="tab-pane fade in active all_tabs_bg" id="dashboard">
                            <div class="boligation_tabls">
                                <div class="row" id="dashboard-content">
                                    @if($adminRole === 'admin')
                                        <div class="col-md-6 dashboard-card">
                                            <div class="card warrinig_card_new_design">
                                                <h4>Total Warnings</h4>
                                                <h2>{{ $all_warnings->count() }}</h2>
                                            </div>
                                        </div>
                                        @foreach($warningCounts as $warningCount)
                                            <div class="col-md-6 dashboard-card">
                                                <div class="card warrinig_card_new_design">
                                                    <h4>{{ $warningCount->warning_name }}</h4>
                                                    <h2>{{ $warningCount->total }}</h2>
                                                </div>
                                            </div>
                                        @endforeach
                                    @elseif($adminRole === 'hr' || $adminRole === 'manager' || $adminRole === 'tl')
                                        <div class="col-md-6 dashboard-card">
                                            <div class="card warrinig_card_new_design">
                                                <h4>My Warnings</h4>
                                                <h2>{{ $myWarnings->count() }}</h2>
                                            </div>
                                        </div>
                                        @foreach($my_warning_counts as $name => $count)
                                            <div class="col-md-6 dashboard-card">
                                                <div class="card warrinig_card_new_design">
                                                    <h4>My {{ $name }}</h4>
                                                    <h2>{{ $count }}</h2>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($adminRole === 'manager' || $adminRole === 'tl')
                                            <div class="col-md-6 dashboard-card">
                                                <div class="card warrinig_card_new_design">
                                                    <h4>Team Warnings</h4>
                                                    <h2>{{ $team_warnings->count() }}</h2>
                                                </div>
                                            </div>
                                            @foreach($team_warning_counts as $name => $count)
                                                <div class="col-md-6 dashboard-card">
                                                    <div class="card warrinig_card_new_design">
                                                        <h4>Team {{ $name }}</h4>
                                                        <h2>{{ $count }}</h2>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @elseif($adminRole === 'agent')
                                        <div class="col-md-6 dashboard-card">
                                            <div class="card warrinig_card_new_design">
                                                <h4>Total Warnings</h4>
                                                <h2>{{ $myWarnings->count() }}</h2>
                                            </div>
                                        </div>
                                        @foreach($my_warning_counts as $name => $count)
                                            <div class="col-md-6 dashboard-card">
                                                <div class="card warrinig_card_new_design">
                                                    <h4>{{ $name }}</h4>
                                                    <h2>{{ $count }}</h2>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- All Warnings Tab -->
                        @if($adminRole === 'admin' || $adminRole === 'hr')
                            <div class="tab-pane fade all_tabs_bg" id="all">
                                <div class="filter-section">
                                    <label>Filter by Employee:</label>
                                    <select id="employeeFilter" multiple class="form-control warning_chosen">
                                        @foreach($all_employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->role }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="table-responsive" style="border:0">
                                    <h3><i class="fa fa-list"></i> All Warnings</h3>
                                    <table class="table table-bordered table-striped" id="allWarningsTable">
                                        <thead>
                                            <tr>
                                                <th>Created By</th>
                                                <th>Warning Given To</th>
                                                <th>Warning Type</th>
                                                <th>Penalty</th>
                                                <th>Message</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($all_warnings->isEmpty())
                                                <tr><td colspan="7" class="text-center">No Warnings Found</td></tr>
                                            @else
                                                @foreach($all_warnings as $item)
                                                    <tr data-employee-id="{{ $item->assign }}">
                                                        <td>{{ $item->createdby ?? 'N/A' }}</td>
                                                        <td>{{ $item->warned_to ?? 'N/A' }}</td>
                                                        <td>{{ $item->warning_name ?? 'No Type' }}</td>
                                                        <td>₹{{ $item->penalty ?? '0' }}</td>
                                                        <td>{{ $item->message }}</td>
                                                        <td>{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d/m/Y') : 'N/A' }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary edit-warning" data-id="{{ $item->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            @if($adminRole === 'admin')
                                                                <button class="btn btn-sm btn-danger delete-warning" data-id="{{ $item->id }}">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Team Warnings Tab -->
                        @if($adminRole === 'manager' || $adminRole === 'tl')
                            <div class="tab-pane fade all_tabs_bg" id="teamwarning">
                                <div class="filter-section">
                                    <label>Filter by Team Member:</label>
                                    <select id="teamFilter" multiple class="form-control warning_chosen">
                                        @foreach($team_members as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="table-responsive" style="border:0">
                                    <h3><i class="fa fa-users"></i> Team Warnings</h3>
                                    <h5>Team Members: {{ implode(', ', $team_members) }}</h5>
                                    <table class="table table-bordered table-striped" id="teamWarningsTable">
                                        <thead>
                                            <tr>
                                                <th>Created By</th>
                                                <th>Warning Given To</th>
                                                <th>Warning Type</th>
                                                <th>Penalty</th>
                                                <th>Message</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($team_warnings->isEmpty())
                                                <tr><td colspan="7" class="text-center">No Team Warnings Found</td></tr>
                                            @else
                                                @foreach($team_warnings as $item)
                                                    <tr data-employee-id="{{ $item->assign }}">
                                                        <td>{{ $item->createdby ?? 'N/A' }}</td>
                                                        <td>{{ $item->warned_to ?? 'N/A' }}</td>
                                                        <td>{{ $item->warning_name ?? 'No Type' }}</td>
                                                        <td>₹{{ $item->penalty ?? '0' }}</td>
                                                        <td>{{ $item->message }}</td>
                                                        <td>{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d/m/Y') : 'N/A' }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary edit-warning" data-id="{{ $item->id }}">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- My Warnings Tab -->
                        @if($adminRole !== 'admin')
                            <div class="tab-pane fade all_tabs_bg" id="mywarning">
                                <div class="boligation_tabls">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <h3><i class="fa fa-user"></i> My Warnings</h3>
                                            @if($myWarnings->isEmpty())
                                                <div class="alert alert-info">
                                                    <i class="fa fa-info-circle"></i> No warnings found for your account.
                                                </div>
                                            @else
                                                <div class="accordion">
                                                    @foreach($myWarnings as $warningTypeId => $warnings)
                                                        <div class="accordion-header">
                                                            <i class="fa fa-exclamation-circle"></i> {{ $warnings->first()->warning_name }} ({{ $warnings->count() }})
                                                        </div>
                                                        <div class="accordion-body">
                                                            <table class="inner-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>Date</th>
                                                                        <th>Issued By</th>
                                                                        <th>Penalty</th>
                                                                        <th>Message</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($warnings as $warning)
                                                                        <tr>
                                                                            <td>{{ $warning->assign_name ?? 'N/A' }}</td>
                                                                            <td>{{ $warning->date ? \Carbon\Carbon::parse($warning->date)->format('d/m/Y') : 'N/A' }}</td>
                                                                            <td>{{ $warning->issued_by_name }}</td>
                                                                            <td>₹{{ $warning->penalty }}</td>
                                                                            <td>{{ $warning->message }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Add Warning Modal -->
            @if($adminRole !== 'agent')
                <div id="AddWarningModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title" style="color:black;"><i class="fa fa-plus-circle"></i> Add Warning</h4>
                            </div>
                            <div class="modal-body">
                                <form id="add_form" method="post" class="mail-compose form-horizontal" action="javascript:void(0);">
                                    @csrf
                                    <input type="hidden" name="admin_id" value="{{ $admin_login->id }}">
                                    <div class="form-group">
                                        <label>Issued By</label>
                                        <input type="text" value="{{ $admin_login->name }}" class="form-control" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Warning Type</label>
                                        <select name="warningtype_id" id="warningtype_id" data-placeholder="Warning Type" class="form-control warning_chosen" required>
                                            <option selected disabled>-- Select Warning Type --</option>
                                            @foreach($warning_types as $type)
                                                <option value="{{ $type->id }}">{{ $type->warning_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Issued To</label>
                                        <select name="assign" id="assign" data-placeholder="Issued To" class="form-control warning_chosen" required>
                                            <option selected disabled>-- Select Employee --</option>
                                            @php
                                                if ($adminRole === 'admin' || $adminRole === 'hr') {
                                                    $employees = DB::table('admin')->where('role', '!=', 'Admin')->orderBy('name')->get();
                                                } elseif ($adminRole === 'manager') {
                                                    $employees = DB::table('admin')->where('manager', $admin_login->id)->orderBy('name')->get();
                                                } elseif ($adminRole === 'tl') {
                                                    $employees = DB::table('admin')->where('team_leader', $admin_login->id)->orderBy('name')->get();
                                                }
                                            @endphp
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->role }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Penalty (₹):</label>
                                        <input type="number" name="penalty" class="form-control" placeholder="Enter Penalty Amount" min="0">
                                    </div>
                                    <div class="form-group">
                                        <label>Warning Message:</label>
                                        <textarea name="message" class="form-control wysihtml5" rows="6" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Edit Warning Modal remains same -->
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="{{asset('Admin/assets/ajax/libs/jquery/2.1.1/jquery.min.js')}}"></script>
<script>window.jQuery || document.write('<script src="assets/jquery/jquery-2.1.1.min.js"><\/script>')</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize accordion
    $(".accordion-header").on("click", function() {
        let body = $(this).next(".accordion-body");
        body.slideToggle(300);
    });
    
    // Initialize chosen selects
    $(".warning_chosen").chosen({ 
        width: '100%', 
        allow_single_deselect: true 
    });
    
    // Initialize DataTables
    $('#allWarningsTable, #teamWarningsTable').DataTable({
        "order": [[5, "desc"]], // Sort by date column
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
    
    // Show add warning modal
    $('#openModalBtn').on('click', function() {
        $('#AddWarningModal').modal('show');
    });
    
    // Update Dashboard function
    function updateDashboard(selectedIds, role) {
        $.ajax({
            url: '{{ url("/get-filtered-warning-counts") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                filter: selectedIds
            },
            success: function(response) {
                let dashboardHtml = '';
                if (role === 'admin') {
                    dashboardHtml += `<div class="col-md-6 dashboard-card">
                        <div class="card warrinig_card_new_design">
                            <h4>Total Warnings</h4>
                            <h2>${response.total}</h2>
                        </div>
                    </div>`;
                    Object.entries(response.type_counts).forEach(([name, count]) => {
                        dashboardHtml += `<div class="col-md-6 dashboard-card">
                            <div class="card warrinig_card_new_design">
                                <h4>${name}</h4>
                                <h2>${count}</h2>
                            </div>
                        </div>`;
                    });
                } else if (role === 'hr' || role === 'manager' || role === 'tl') {
                    dashboardHtml += `<div class="col-md-6 dashboard-card">
                        <div class="card warrinig_card_new_design">
                            <h4>My Warnings</h4>
                            <h2>${response.my_total}</h2>
                        </div>
                    </div>`;
                    Object.entries(response.my_counts).forEach(([name, count]) => {
                        dashboardHtml += `<div class="col-md-6 dashboard-card">
                            <div class="card warrinig_card_new_design">
                                <h4>My ${name}</h4>
                                <h2>${count}</h2>
                            </div>
                        </div>`;
                    });
                    if (role === 'manager' || role === 'tl') {
                        dashboardHtml += `<div class="col-md-6 dashboard-card">
                            <div class="card warrinig_card_new_design">
                                <h4>Team Warnings</h4>
                                <h2>${response.team_total}</h2>
                            </div>
                        </div>`;
                        Object.entries(response.team_counts).forEach(([name, count]) => {
                            dashboardHtml += `<div class="col-md-6 dashboard-card">
                                <div class="card warrinig_card_new_design">
                                    <h4>Team ${name}</h4>
                                    <h2>${count}</h2>
                                </div>
                            </div>`;
                        });
                    }
                } else if (role === 'agent') {
                    dashboardHtml += `<div class="col-md-6 dashboard-card">
                        <div class="card warrinig_card_new_design">
                            <h4>Total Warnings</h4>
                            <h2>${response.my_total}</h2>
                        </div>
                    </div>`;
                    Object.entries(response.my_counts).forEach(([name, count]) => {
                        dashboardHtml += `<div class="col-md-6 dashboard-card">
                            <div class="card warrinig_card_new_design">
                                <h4>${name}</h4>
                                <h2>${count}</h2>
                            </div>
                        </div>`;
                    });
                }
                $('#dashboard-content').html(dashboardHtml);
            },
            error: function() {
                swal("Error", "Failed to update dashboard", "error");
            }
        });
    }

    // Filter handlers
    $('#employeeFilter').on('change', function() {
        let selectedIds = $(this).val() || [];
        $('#allWarningsTable tbody tr').each(function() {
            let employeeId = $(this).data('employee-id').toString();
            $(this).toggle(selectedIds.length === 0 || selectedIds.includes(employeeId));
        });
        updateDashboard(selectedIds, '{{ $adminRole }}');
    });

    $('#teamFilter').on('change', function() {
        let selectedIds = $(this).val() || [];
        $('#teamWarningsTable tbody tr').each(function() {
            let employeeId = $(this).data('employee-id').toString();
            $(this).toggle(selectedIds.length === 0 || selectedIds.includes(employeeId));
        });
        updateDashboard(selectedIds, '{{ $adminRole }}');
    });

    // Add warning form submission
    $("#add_form").submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            type: "post",
            url: "{{url('/add_warning')}}",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            cache: false,
            success: function(data) {
                if (data.success == 'success') {
                    $("#add_form")[0].reset();
                    $("#AddWarningModal").modal("hide");
                    swal("Success!", "Warning added successfully", "success");
                    setTimeout(function() { window.location.reload(); }, 1000);
                } else {
                    swal("Error", "Failed to add warning", "error");
                }
            },
            error: function() {
                swal("Server Error", "Something went wrong. Please try again.", "error");
            }
        });
    });
});
</script>
@endsection
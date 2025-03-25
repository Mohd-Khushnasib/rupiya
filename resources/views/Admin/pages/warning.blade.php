@extends('Admin.layouts.master')
@section('main-content')

<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<style>
    .disabled-div {
        pointer-events: none;
        opacity: 0.6;
        background-color:rgb(12, 11, 11);
    }
    .accordion-header {
        background: rgb(12, 11, 11);;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        padding: 10px;
        margin-top: 5px;
        border-radius: 5px;
    }
    .accordion-body {
        display: none;
        padding: 10px;
        background: rgb(12, 11, 11);;
        border-radius: 5px;
    }
    .inner-table {
        width: 100%;
        border-collapse: collapse;
        background: rgb(12, 11, 11);;
        color: #fff;
    }
    .inner-table th,
    .inner-table td {
        border: 1px solid #444;
        padding: 8px;
        text-align: left;
    }
    .inner-table th {
        background: rgb(12, 11, 11);
    }
    .warning-card {
        width: 100%;
        max-width: 300px;
        padding: 20px;
        text-align: center;
        background: rgb(12, 11, 11);
        color: #7b3f00;
        font-family: Arial, sans-serif;
        font-weight: bold;
        font-size: 20px;
        border-radius: 15px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        border: 2px solid #d2691e;
        margin-bottom: 20px;
    }
    .warning-card h2 {
        margin: 0;
        font-size: 22px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }
    .warning-card .count {
        font-size: 30px;
        color: #ff5722;
        margin-top: 5px;
    }
    .warrinig_card_new_design {
        background: black;
        padding: 20px;
        font-family: Arial, sans-serif;
        font-weight: bold;
        font-size: 20px;
        border-radius: 15px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        border: 2px solid #03b0f5;
        margin-bottom: 20px;
    }
    .warrinig_card_new_design h4 {
        color: #03b0f5 !important;
        margin-top: 10px !important;
        text-align: center;
        font-weight: 700;
        font-size: 25px;
    }
    .warrinig_card_new_design h2 {
        font-size: 34px !important;
        font-weight: 800;
        color: #03b0f5 !important;
        margin-top: 20px;
        text-align: center;
    }
    .all_tabs_bg {
        background:rgb(10, 10, 10);
        padding: 15px;
        border-radius: 5px;
    }
    .nav-tabs > li.active > a {
        font-weight: bold;
        color: #fff;
        background-color: #007bff;
    }
    .table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th {
    background-color: #2d2929;
}
</style>

<div class="container" id="main-container">
    <!-- BEGIN Content -->
    <div id="main-content">
        <!-- BEGIN Page Title -->
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
        <!-- END Page Title -->

        <!-- BEGIN Main Content -->
        <div class="row">
            <!-- Show warning cards for Agents -->
            @if($adminRole === 'agent')
                <div class="col-md-12">
                    <div class="row">
                        @php
                            $totalMyWarnings = $myWarnings->sum(fn($warnings) => $warnings->count());
                        @endphp
                        <div class="col-md-3">
                            <div class="warning-card">
                                <h2>Total Warnings</h2>
                                <div class="count">{{ $totalMyWarnings }}</div>
                            </div>
                        </div>
                        @foreach($myWarnings as $warningTypeId => $warnings)
                            <div class="col-md-3">
                                <div class="warning-card">
                                    <h2>{{ $warnings->first()->warning_name }}</h2>
                                    <div class="count">{{ $warnings->count() }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="col-md-12">
                <div class="tabbable">
                    <ul id="myTab1" class="nav nav-tabs">
                        @if($adminRole === 'admin')
                            <li class="active">
                                <a href="#all" data-toggle="tab"><i class="fa fa-list"></i> All Warnings</a>
                            </li>
                            <li>
                                <a href="#dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard</a>
                            </li>
                            <li>
                                <a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a>
                            </li>
                        @elseif($adminRole === 'hr')
                            <li class="active">
                                <a href="#all" data-toggle="tab"><i class="fa fa-list"></i> All Warnings</a>
                            </li>
                            <li>
                                <a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a>
                            </li>
                        @elseif($adminRole === 'manager' || $adminRole === 'tl')
                            <li class="active">
                                <a href="#teamwarning" data-toggle="tab"><i class="fa fa-users"></i> Team Warnings</a>
                            </li>
                            <li>
                                <a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a>
                            </li>
                        @elseif($adminRole === 'agent')
                            <li class="active">
                                <a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a>
                            </li>
                        @endif
                    </ul>

                    <div id="myTabContent1" class="tab-content">
                        <!-- All Warnings Tab (Admin & HR Only) -->
                        @if($adminRole === 'admin' || $adminRole === 'hr')
                            <div class="tab-pane fade in {{ $adminRole === 'admin' || $adminRole === 'hr' ? 'active' : '' }} all_tabs_bg" id="all">
                                <div class="boligation_tabls">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <div class="table-responsive" style="border:0">
                                                <h3><i class="fa fa-list"></i> All Warnings</h3>
                                                <table class="table table-bordered table-striped" id="allWarningsTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Created By</th>
                                                            <th>Warning Given To</th>
                                                            <!-- <th>Ticket Status</th> -->
                                                            <th>Warning Type</th>
                                                            <th>Penalty</th>
                                                            <th>Message</th>
                                                            <th>Date</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($all_warnings->isEmpty())
                                                            <tr>
                                                                <td colspan="8" class="text-center">No Warnings Found</td>
                                                            </tr>
                                                        @else
                                                            @foreach($all_warnings as $item)
                                                                <tr>
                                                                    <td>{{ $item->createdby ?? 'N/A' }}</td>
                                                                    <td>{{ $item->warned_to ?? 'N/A' }}</td>
                                                                    <td>{{ $item->warning_name ?? 'No Type' }}</td>
                                                                    <td>₹{{ $item->penalty ?? '0' }}</td>
                                                                    <td>{{ $item->message }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
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
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Team Warnings Tab (Manager & TL Only) -->
                        @if($adminRole === 'manager' || $adminRole === 'tl')
                            <div class="tab-pane fade in active all_tabs_bg" id="teamwarning">
                                <div class="boligation_tabls">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <div class="table-responsive" style="border:0">
                                                <h3><i class="fa fa-users"></i> Team Warnings</h3>
                                                <h5>Team Members: {{ implode(', ', $team_members) }}</h5>
                                                <table class="table table-bordered table-striped" id="teamWarningsTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Created By</th>
                                                            <th>Warning Given To</th>
                                                            <!-- <th>Ticket Status</th> -->
                                                            <th>Warning Type</th>
                                                            <th>Penalty</th>
                                                            <th>Message</th>
                                                            <th>Date</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($team_warnings->isEmpty())
                                                            <tr>
                                                                <td colspan="8" class="text-center">No Team Warnings Found</td>
                                                            </tr>
                                                        @else
                                                            @foreach($team_warnings as $item)
                                                                <tr>
                                                                    <td>{{ $item->createdby ?? 'N/A' }}</td>
                                                                    <td>{{ $item->warned_to ?? 'N/A' }}</td>
                                                                    <td>{{ $item->warning_name ?? 'No Type' }}</td>
                                                                    <td>₹{{ $item->penalty ?? '0' }}</td>
                                                                    <td>{{ $item->message }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
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
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- My Warnings Tab (Visible to All Roles) -->
                        <div class="tab-pane fade in {{ $adminRole === 'agent' ? 'active' : '' }} all_tabs_bg" id="mywarning">
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

                        <!-- Dashboard Tab (Admin Only) -->
                        @if($adminRole === 'admin')
                            <div class="tab-pane fade in all_tabs_bg" id="dashboard">
                                <div class="boligation_tabls">
                                    <div class="row">
                                        <div class="col-md-6" style="margin-top: 20px;">
                                            <div class="card warrinig_card_new_design">
                                                <h4>Total Warnings</h4>
                                                <h2>{{ $all_warnings->count() }}</h2>
                                            </div>
                                        </div>
                                        
                                        @foreach($warningCounts as $warningCount)
                                            <div class="col-md-6" style="margin-top: 20px;">
                                                <div class="card warrinig_card_new_design">
                                                    <h4>{{ $warningCount->warning_name }}</h4>
                                                    <h2>{{ $warningCount->total }}</h2>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Add Warning Modal (Visible only to TL, Manager, HR, Admin) -->
            @if($adminRole !== 'agent')
                <div id="AddWarningModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title" style="color:black;"><i class="fa fa-plus-circle"></i> Add Warning</h4>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-12">
                                    <form id="add_form" method="post" class="mail-compose form-horizontal" action="javascript:void(0);">
                                        @csrf
                                        <input type="hidden" name="admin_id" value="{{ $admin_login->id }}">
                                        <div class="form-group">
                                            <label for="">Issued By</label>
                                            <input type="text" value="{{ $admin_login->name }}" class="form-control" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Warning Type</label>
                                            <select name="warningtype_id" id="warningtype_id" data-placeholder="Warning Type" class="form-control warning_chosen" required>
                                                <option selected disabled>-- Select Warning Type --</option>
                                                @foreach($warning_types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->warning_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Issued To</label>
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
                                        <div id="warningInfo"></div>
                                        <div class="form-group">
                                            <label for="">Penalty (₹):</label>
                                            <input type="number" name="penalty" class="form-control" placeholder="Enter Penalty Amount" min="0">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Warning Message:</label>
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
                </div>
            @endif

            <!-- Edit Warning Modal -->
            <div id="editwarningmodal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title" style="color:black;"><i class="fa fa-edit"></i> Edit Warning</h4>
                        </div>
                        <div class="modal-body">
                            <form id="edit_warning" action="javascript:void(0);" enctype="multipart/form-data" method="post">
                                @csrf
                                <input type="hidden" name="admin_id" value="{{ $admin_login->id }}" class="form-control">
                                <input type="hidden" name="warning_id" id="edit_warning_id" class="form-control">
                                <div class="form-group">
                                    <label for="">Issued By</label>
                                    <input type="text" value="{{ $admin_login->name }}" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Warning Type</label>
                                    <select name="warningtype_id" id="edit_warningtype_id" class="form-control chosen EditChoosen" required>
                                        <option selected disabled>-- Select Warning Type --</option>
                                        @foreach($warning_types as $warning)
                                            <option value="{{ $warning->id }}">{{ $warning->warning_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Warning Message</label>
                                    <textarea name="message" id="edit_message" class="form-control wysihtml5" rows="6" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Issued To</label>
                                    <select name="assign" id="edit_assign" data-placeholder="Assign" class="form-control chosen EditChoosen" required>
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
                                    <label for="">Penalty (₹):</label>
                                    <input type="number" name="penalty" id="edit_penalty" class="form-control" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="">Ticket Status:</label>
                                    <select name="task_status" id="edit_task_status" class="form-control">
                                        <option value="Pending">Pending</option>
                                        <option value="Resolved">Resolved</option>
                                    </select>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Includes and Scripts -->
<script src="{{asset('Admin/assets/ajax/libs/jquery/2.1.1/jquery.min.js')}}"></script>
<script>window.jQuery || document.write('<script src="assets/jquery/jquery-2.1.1.min.js"><\/script>')</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize accordion for warning types
        $(".accordion-header").on("click", function() {
            let body = $(this).next(".accordion-body");
            body.slideToggle(300);
        });
        
        // Initialize chosen selects
        $(".warning_chosen, .chosen").chosen({ 
            width: '100%', 
            allow_single_deselect: true 
        });
        
        // Initialize DataTables
        $('#allWarningsTable, #teamWarningsTable').DataTable({
            "order": [[6, "desc"]], // Sort by date column
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
        });
        
        // Show add warning modal
        $('#openModalBtn').on('click', function() {
            $('#AddWarningModal').modal('show');
        });
        
        // Add warning form submission
        $("#add_form").submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Basic validation
            if (!formData.get('warningtype_id') || !formData.get('assign') || !formData.get('message')) {
                swal("Missing Information", "Please fill all required fields", "warning");
                return false;
            }
            
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
        
        // Edit warning button click
        $(document).on('click', '.edit-warning', function() {
            const warningId = $(this).data('id');
            
            $.ajax({
                type: "GET",
                url: "{{url('/get-warning-details')}}/" + warningId,
                dataType: "json",
                success: function(data) {
                    $('#edit_warning_id').val(data.id);
                    $('#edit_warningtype_id').val(data.warningtype_id).trigger('chosen:updated');
                    $('#edit_message').val(data.message);
                    $('#edit_assign').val(data.assign).trigger('chosen:updated');
                    $('#edit_penalty').val(data.penalty);
                    $('#edit_task_status').val(data.task_status);
                    
                    $('#editwarningmodal').modal('show');
                },
                error: function() {
                    swal("Error", "Failed to load warning details", "error");
                }
            });
        });
        
        // Edit warning form submission
        $("#edit_warning").submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            $.ajax({
                type: "POST",
                url: "{{url('/update_warning')}}",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                success: function(data) {
                    if (data.success == 'success') {
                        $("#editwarningmodal").modal("hide");
                        swal("Success!", "Warning updated successfully", "success");
                        setTimeout(function() { window.location.reload(); }, 1000);
                    } else {
                        swal("Error", "Failed to update warning", "error");
                    }
                },
                error: function() {
                    swal("Server Error", "Something went wrong. Please try again.", "error");
                }
            });
        });
        
        // Delete warning button click
        $(document).on('click', '.delete-warning', function() {
            const warningId = $(this).data('id');
            
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this warning!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "{{url('/delete_warning')}}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "warning_id": warningId
                        },
                        dataType: "json",
                        success: function(data) {
                            if (data.success == 'success') {
                                swal("Success!", "Warning deleted successfully", "success");
                                setTimeout(function() { window.location.reload(); }, 1000);
                            } else {
                                swal("Error", "Failed to delete warning", "error");
                            }
                        },
                        error: function() {
                            swal("Server Error", "Something went wrong. Please try again.", "error");
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
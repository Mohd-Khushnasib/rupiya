@extends('Admin.layouts.master')
@section('main-content')

<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css">
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
    .filter-btn {
        margin-left: 5px;
        padding: 0 5px;
        font-size: 12px;
        background-color: #2196F3;
        border: none;
    }
    .section-title {
        color: #03b0f5;
        border-bottom: 1px solid #03b0f5;
        padding-bottom: 10px;
        margin-bottom: 20px;
        font-size: 24px;
    }
    .section-container {
        margin-bottom: 30px;
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
                <div class="zxyzz">
                    @if($adminRole !== 'agent')
                        <button type="button" class="btn btn-info" id="openModalBtn">
                            <i class="fa fa-plus-circle"></i> Create Warning
                        </button>
                    @endif
                    <!-- Removed top filter button as per requirement -->
                </div>
            </div>
        </div>
        <!-- END Page Title -->

        <!-- BEGIN Main Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="tabbable">
                    <ul id="myTab1" class="nav nav-tabs">
                        <!-- Modified tab order based on requirements -->
                        @if($adminRole === 'admin')
                            <li class="active">
                                <a href="#dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard</a>
                            </li>
                            <li>
                                <a href="#all" data-toggle="tab"><i class="fa fa-list"></i> All Warnings</a>
                            </li>
                        @elseif($adminRole === 'hr')
                            <li class="active">
                                <a href="#dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard</a>
                            </li>
                            <li>
                                <a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a>
                            </li>
                            <li>
                                <a href="#all" data-toggle="tab"><i class="fa fa-list"></i> All Warnings</a>
                            </li>
                        @elseif($adminRole === 'manager' || $adminRole === 'tl')
                            <li class="active">
                                <a href="#dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard</a>
                            </li>
                            <li>
                                <a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a>
                            </li>
                            <li>
                                <a href="#teamwarning" data-toggle="tab"><i class="fa fa-users"></i> Team Warnings</a>
                            </li>
                        @elseif($adminRole === 'agent')
                            <li class="active">
                                <a href="#dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> Dashboard</a>
                            </li>
                            <li>
                                <a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a>
                            </li>
                        @endif
                    </ul>

                    <div id="myTabContent1" class="tab-content">
                        <!-- Dashboard Tab (Now for All Roles) -->
                        <div class="tab-pane fade in active all_tabs_bg" id="dashboard">
                            <div class="boligation_tabls">
                                @if($adminRole === 'admin')
                                    <!-- Admin Dashboard -->
                                    <div class="row">
                                        <div class="col-md-6" style="margin-top: 20px;">
                                            <div class="card warrinig_card_new_design" data-warning-type="total">
                                                <h4>Total Warnings</h4>
                                                <h2 class="total-warnings-count">{{ $all_warnings->count() }}</h2>
                                            </div>
                                        </div>
                                        
                                        @foreach($warningCounts as $warningCount)
                                            <div class="col-md-6 warning-type-card" style="margin-top: 20px;" data-warning-type="{{ $warningCount->warning_name }}">
                                                <div class="card warrinig_card_new_design">
                                                    <h4>{{ $warningCount->warning_name }}</h4>
                                                    <h2 class="warning-count-{{ $warningCount->id }}">{{ $warningCount->total }}</h2>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($adminRole === 'hr')
                                    <!-- HR Dashboard with clear sections -->
                                    <div class="section-container">
                                        <h3 class="section-title"><i class="fa fa-user"></i> My Warnings</h3>
                                        <div class="row">
                                            <div class="col-md-3" style="margin-top: 20px;">
                                                <div class="card warrinig_card_new_design" data-warning-type="my-total">
                                                    <h4>My Total Warnings</h4>
                                                    <h2>{{ $myWarnings->sum(fn($warnings) => $warnings->count()) }}</h2>
                                                </div>
                                            </div>
                                            
                                            @foreach($myWarnings as $warningTypeId => $warnings)
                                                <div class="col-md-3 warning-type-card" style="margin-top: 20px;" data-warning-type="{{ $warnings->first()->warning_name }}" data-warning-section="my">
                                                    <div class="card warrinig_card_new_design">
                                                        <h4>{{ $warnings->first()->warning_name }}</h4>
                                                        <h2>{{ $warnings->count() }}</h2>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="section-container">
                                        <h3 class="section-title"><i class="fa fa-users"></i> All Warnings</h3>
                                        <div class="row">
                                            <div class="col-md-3" style="margin-top: 20px;">
                                                <div class="card warrinig_card_new_design" data-warning-type="total">
                                                    <h4>Total Warnings</h4>
                                                    <h2 class="total-warnings-count">{{ $all_warnings->count() }}</h2>
                                                </div>
                                            </div>
                                            
                                            @foreach($warningCounts as $warningCount)
                                                <div class="col-md-3 warning-type-card" style="margin-top: 20px;" data-warning-type="{{ $warningCount->warning_name }}">
                                                    <div class="card warrinig_card_new_design">
                                                        <h4>{{ $warningCount->warning_name }}</h4>
                                                        <h2 class="warning-count-{{ $warningCount->id }}">{{ $warningCount->total }}</h2>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif($adminRole === 'manager' || $adminRole === 'tl')
                                    <!-- Manager/TL Dashboard with clear sections -->
                                    <div class="section-container">
                                        <h3 class="section-title"><i class="fa fa-user"></i> My Warnings</h3>
                                        <div class="row">
                                            <div class="col-md-3" style="margin-top: 20px;">
                                                <div class="card warrinig_card_new_design" data-warning-type="my-total">
                                                    <h4>My Total Warnings</h4>
                                                    <h2>{{ $myWarnings->sum(fn($warnings) => $warnings->count()) }}</h2>
                                                </div>
                                            </div>
                                            
                                            @foreach($myWarnings as $warningTypeId => $warnings)
                                                <div class="col-md-3 warning-type-card" style="margin-top: 20px;" data-warning-type="{{ $warnings->first()->warning_name }}" data-warning-section="my">
                                                    <div class="card warrinig_card_new_design">
                                                        <h4>{{ $warnings->first()->warning_name }}</h4>
                                                        <h2>{{ $warnings->count() }}</h2>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="section-container">
                                        <h3 class="section-title"><i class="fa fa-users"></i> Team Warnings</h3>
                                        <div class="row">
                                            <div class="col-md-3" style="margin-top: 20px;">
                                                <div class="card warrinig_card_new_design" data-warning-type="team-total">
                                                    <h4>Team Total Warnings</h4>
                                                    <h2 class="total-warnings-count">{{ $team_warnings->count() }}</h2>
                                                </div>
                                            </div>
                                            
                                            @php
                                                $teamWarningsByType = $team_warnings->groupBy('warning_name');
                                            @endphp
                                            
                                            @foreach($teamWarningsByType as $warningName => $warnings)
                                                <div class="col-md-3 warning-type-card" style="margin-top: 20px;" data-warning-type="{{ $warningName }}" data-warning-section="team">
                                                    <div class="card warrinig_card_new_design">
                                                        <h4>{{ $warningName }}</h4>
                                                        <h2>{{ $warnings->count() }}</h2>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif($adminRole === 'agent')
                                    <!-- Agent Dashboard -->
                                    <div class="row">
                                        <div class="col-md-3" style="margin-top: 20px;">
                                            <div class="card warrinig_card_new_design" data-warning-type="my-total">
                                                <h4>My Total Warnings</h4>
                                                <h2 class="total-warnings-count">{{ $myWarnings->sum(fn($warnings) => $warnings->count()) }}</h2>
                                            </div>
                                        </div>
                                        
                                        @foreach($myWarnings as $warningTypeId => $warnings)
                                            <div class="col-md-3 warning-type-card" style="margin-top: 20px;" data-warning-type="{{ $warnings->first()->warning_name }}">
                                                <div class="card warrinig_card_new_design">
                                                    <h4>{{ $warnings->first()->warning_name }}</h4>
                                                    <h2>{{ $warnings->count() }}</h2>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- All Warnings Tab (Admin & HR Only) -->
                        @if($adminRole === 'admin' || $adminRole === 'hr')
                            <div class="tab-pane fade in all_tabs_bg" id="all">
                                <div class="boligation_tabls">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                                                <h3><i class="fa fa-list"></i> All Warnings</h3>
                                                <div class="filter-container">
                                                    <button type="button" class="btn btn-sm btn-primary filter-trigger">
                                                        <i class="fa fa-filter"></i> Filter By Employee
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="table-responsive" style="border:0">
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
                                                            <tr>
                                                                <td colspan="7" class="text-center">No Warnings Found</td>
                                                            </tr>
                                                        @else
                                                            @foreach($all_warnings as $item)
                                                                <tr data-warning-type="{{ $item->warning_name }}">
                                                                    <td>{{ $item->createdby ?? 'N/A' }}</td>
                                                                    <td>{{ $item->warned_to ?? 'N/A' }}</td>
                                                                    <td>{{ $item->warning_name ?? 'No Type' }}</td>
                                                                    <td>₹{{ $item->penalty ?? '0' }}</td>
                                                                    <td>
                                                                        {!! collect(explode(' ', $item->message))
                                                                                ->chunk(15)
                                                                                ->map(function ($chunk) {
                                                                                    return $chunk->join(' ');
                                                                                })
                                                                                ->join('<br>') !!}
                                                                    </td>
                                                                 
                                                                    <td>{{ isset($item->date) ? \Carbon\Carbon::parse($item->date)->format('d/m/Y') : 'N/A' }}</td>
                                                                    <td style="display:flex;gap:7px">
                                                                        <button class="btn btn-sm btn-primary edit-warning" data-id="{{ $item->id }}">
                                                                            <i class="fa fa-edit"></i>
                                                                        </button>
                                                                        @if($adminRole === 'admin')
                                                                        <button 
                                                                        class="btn btn-primary btn_delete delete" 
                                                                            title="Delete Lead" 
                                                                            data-id="${item.id}">
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
                            <div class="tab-pane fade in all_tabs_bg" id="teamwarning">
                                <div class="boligation_tabls">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                                                <h3><i class="fa fa-users"></i> Team Warnings</h3>
                                                <div class="filter-container">
                                                    <button type="button" class="btn btn-sm btn-primary filter-trigger">
                                                        <i class="fa fa-filter"></i> Filter Team Members
                                                    </button>
                                                </div>
                                            </div>
                                            <h5>Team Members: {{ implode(', ', $team_members) }}</h5>
                                            <div class="table-responsive" style="border:0">
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
                                                            <tr>
                                                                <td colspan="7" class="text-center">No Team Warnings Found</td>
                                                            </tr>
                                                        @else
                                                            @foreach($team_warnings as $item)
                                                                <tr data-warning-type="{{ $item->warning_name }}">
                                                                    <td>{{ $item->createdby ?? 'N/A' }}</td>
                                                                    <td>{{ $item->warned_to ?? 'N/A' }}</td>
                                                                    <td>{{ $item->warning_name ?? 'No Type' }}</td>
                                                                    <td>₹{{ $item->penalty ?? '0' }}</td>
                                                                    <td>{{ $item->message }}</td>
                                                                    <td>{{ isset($item->date) ? \Carbon\Carbon::parse($item->date)->format('d/m/Y') : 'N/A' }}</td>
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

                        <!-- My Warnings Tab (Visible to All except Admin) -->
                        @if($adminRole !== 'admin')
                            <div class="tab-pane fade in all_tabs_bg" id="mywarning">
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
                                                                            <td>{{ isset($warning->date) ? \Carbon\Carbon::parse($warning->date)->format('d/m/Y') : 'N/A' }}</td>
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

            <!-- Employee Filter Modal -->
            <div id="filterWarningModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title" style="color:black;"><i class="fa fa-filter"></i> Filter Warnings</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="employee_filter">Filter by Employees:</label>
                                <select id="employee_filter" class="form-control chosen" multiple>
                                    <option value="all">All Employees</option>
                                    @foreach($all_employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->role }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-right">
                                <button type="button" id="applyFilter" class="btn btn-primary"><i class="fa fa-check"></i> Apply Filter</button>
                                <button type="button" id="clearFilter" class="btn btn-default"><i class="fa fa-refresh"></i> Clear Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- delete warning modal  -->
             <!-- Delete Start Here -->
            <div id="deletemodal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" style="color: black;" id="exampleModalLabel">Delete Warning</h4>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <p>Are you sure you want to delete this? This action cannot be undone.</p>
                            <form id="delete_form" action="javascript:void(0);" method="post">
                                @csrf
                                <input type="hidden" name="id" id="delete_id" value="" class="form-control">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="d-flex justify-content-between" style="margin-top: 15px;">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn_delete">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
<!-- Delete End Here -->
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
        
        // Initialize DataTables with saving state
        var allWarningsTable, teamWarningsTable;
        
        if ($('#allWarningsTable').length) {
            allWarningsTable = $('#allWarningsTable').DataTable({
                "order": [[5, "desc"]], // Sort by date column
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "stateSave": true
            });
        }
        
        if ($('#teamWarningsTable').length) {
            teamWarningsTable = $('#teamWarningsTable').DataTable({
                "order": [[5, "desc"]], // Sort by date column
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "stateSave": true
            });
        }
        
        // Trigger filter modal
        $('#filterBtn, .filter-trigger').on('click', function() {
            $('#filterWarningModal').modal('show');
        });
        
        // Apply filter with improved dashboard updates
        $('#applyFilter').on('click', function() {
            let selectedEmployees = $('#employee_filter').val();
            
            // Determine which table to filter based on active tab
            let activeTab = $('.tab-pane.active').attr('id');
            let tableSelector = "";
            
            if (activeTab === 'all') {
                tableSelector = "#allWarningsTable";
            } else if (activeTab === 'teamwarning') {
                tableSelector = "#teamWarningsTable";
            } else {
                // Show dialog to select a table
                swal({
                    title: "Which table?",
                    text: "Please select which warnings to filter",
                    icon: "info",
                    buttons: {
                        cancel: "Cancel",
                        all: {
                            text: "All Warnings",
                            value: "all",
                        },
                        team: {
                            text: "Team Warnings",
                            value: "team",
                        },
                    },
                })
                .then((value) => {
                    if (value === "all") {
                        filterTable("#allWarningsTable", selectedEmployees);
                    } else if (value === "team") {
                        filterTable("#teamWarningsTable", selectedEmployees);
                    }
                });
                return;
            }
            
            filterTable(tableSelector, selectedEmployees);
        });
        
        // Function to filter table and update dashboard
        function filterTable(tableSelector, selectedEmployees) {
            // If All Employees selected or nothing selected, show all rows
            if (selectedEmployees.includes('all') || selectedEmployees.length === 0) {
                $(tableSelector + ' tbody tr').show();
                
                // Show all warning type cards in dashboard
                $('.warning-type-card').show();
                
                swal("Filter Cleared", "Showing all warnings", "success");
            } else {
                // Get employee names for filtering
                let employeeNames = [];
                selectedEmployees.forEach(function(id) {
                    let name = $('#employee_filter option[value="' + id + '"]').text().split(' (')[0].trim();
                    employeeNames.push(name);
                });
                
                // Hide all rows first
                $(tableSelector + ' tbody tr').hide();
                
                // Collect warning types from matching rows
                let filteredWarningTypes = [];
                
                // Show matching rows based on employee names
                $(tableSelector + ' tbody tr').each(function() {
                    let row = $(this);
                    let warnedTo = row.find('td:eq(1)').text().trim(); // Warning Given To (second column)
                    let warningType = row.find('td:eq(2)').text().trim(); // Warning Type (third column)
                    
                    for (let i = 0; i < employeeNames.length; i++) {
                        if (warnedTo.includes(employeeNames[i])) {
                            row.show();
                            if (!filteredWarningTypes.includes(warningType)) {
                                filteredWarningTypes.push(warningType);
                            }
                            break;
                        }
                    }
                });
                
                // Update dashboard: hide warning type cards that don't match
                $('.warning-type-card').each(function() {
                    let card = $(this);
                    let cardType = card.data('warning-type');
                    if (filteredWarningTypes.includes(cardType)) {
                        card.show();
                    } else {
                        card.hide();
                    }
                });
                
                // Count visible warnings
                let visibleCount = $(tableSelector + ' tbody tr:visible').length;
                $('.total-warnings-count').text(visibleCount);
                
                // Update filter counts for each visible warning type
                let typeCounts = {};
                $(tableSelector + ' tbody tr:visible').each(function() {
                    let type = $(this).find('td:eq(2)').text().trim(); // Warning Type column
                    typeCounts[type] = (typeCounts[type] || 0) + 1;
                });
                
                // Update each warning type count in dashboard
                $('.warning-type-card').each(function() {
                    let card = $(this);
                    let typeName = card.data('warning-type');
                    
                    if (typeCounts[typeName]) {
                        card.find('h2').text(typeCounts[typeName]);
                    } else if (typeName !== "total" && typeName !== "my-total" && typeName !== "team-total") {
                        card.hide(); // Hide cards with 0 count
                    }
                });
                
                swal("Filter Applied", "Showing warnings for: " + employeeNames.join(", "), "success");
            }
            
            $('#filterWarningModal').modal('hide');
        }
        
        // Clear filter
        $('#clearFilter').on('click', function() {
            $('#employee_filter').val('').trigger('chosen:updated');
            
            // Show all rows in all tables
            $('#allWarningsTable tbody tr, #teamWarningsTable tbody tr').show();
            
            // Show all warning type cards
            $('.warning-type-card').show();
            
            // Reload the page to reset all counts
            swal({
                title: "Filter Cleared",
                text: "Showing all warnings",
                icon: "success",
                timer: 1500
            }).then(function() {
                window.location.reload();
            });
            
            $('#filterWarningModal').modal('hide');
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
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
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
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
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
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
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
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", error);
                            swal("Server Error", "Something went wrong. Please try again.", "error");
                        }
                    });
                }
            });
        });
        
        // Handle tab change to reset filters when changing tabs
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            let targetTab = $(e.target).attr('href');
            
            // Adjust DataTables when tab changes
            if ($.fn.dataTable) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            }
        });
    });
</script>

<script>
                $(document).on('click', '.delete', function () {
                    var id = $(this).data("id");
                    $("#delete_id").val(id);
                    $("#deletemodal").modal("show");
                });
                // delete here
                $("#delete_form").submit(function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    $.ajax({
                        type: "POST",
                        url: "{{url('/delete_warning_id')}}",
                        data: formData,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        cache: false,
                        encode: true,
                        success: function (data) {
                            if (data.success == 'success') {
                                $("#deletemodal").modal("hide");
                                // $("#delete_form")[0].reset();
                                swal("Lead Delete Successfully! ", "", "success");
                                // setTimeout(function () {
                                //     window.location.reload();
                                // }, 1000);
                            } else {
                                swal('Lead Not Added', '', 'error');
                            }
                        },
                        error: function (error) {
                            swal('Something Went Wrong!', '', 'error');
                        }
                    });
                });

            </script>
@endsection
@if(session()->get('admin_login'))
    @foreach(session()->get('admin_login') as $adminlogin)
        @extends('Admin.layouts.master')
        @section('main-content')

            <!-- FontAwesome CDN -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <style>
                .disabled-div {
                    pointer-events: none;
                    opacity: 0.6;
                    background-color: #f5f5f5;
                }
            </style>

            <style>
                .accordion-header {
                    background: #333;
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
                    background: #222;
                    border-radius: 5px;
                }

                .inner-table {
                    width: 100%;
                    border-collapse: collapse;
                    background: #1a1a1a;
                    color: #fff;
                }

                .inner-table th,
                .inner-table td {
                    border: 1px solid #444;
                    padding: 8px;
                    text-align: left;
                }

                .inner-table th {
                    background: #333;
                }

                .warning-card {
                    width: 100%;
                    max-width: 300px;
                    padding: 20px;
                    text-align: center;
                    background: linear-gradient(to bottom, #ffecb3, #ff9800);
                    color: #7b3f00;
                    font-family: Arial, sans-serif;
                    font-weight: bold;
                    font-size: 20px;
                    border-radius: 15px;
                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
                    border: 2px solid #d2691e;
                    margin: auto;
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
                    background: #000;
                    font-family: Arial, sans-serif;
                    font-weight: bold;
                    font-size: 20px;
                    border-radius: 15px;
                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
                    border: 2px solid #03b0f5;
                    margin: auto;
                    padding: 20px;
                }

                .warrinig_card_new_design h4 {
                    color: #03b0f5 !important;
                    margin-top: 0 !important;
                    text-align: center;
                    font-weight: 700;
                    font-size: 25px;
                }

                .warrinig_card_new_design h2 {
                    font-size: 34px !important;
                    font-weight: 800;
                    color: #03b0f5 !important;
                    margin-top: 10px;
                    text-align: center;
                }
            </style>

            <div class="container" id="main-container">
                <div id="main-content">
                    <div class="page-title">
                        <div style="display: flex; justify-content: space-between;">
                            <h3 class="theam_color_text"><i class="fa fa-list"></i> Warning</h3>
                            <div class="zxyzz">
                                <button type="button" class="btn btn-info" id="openModalBtn">
                                    Create Warning
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @php
                            $adminId = $adminlogin->id;
                            $adminRole = strtolower($adminlogin->role);
                            // Personal warnings for "My Warnings" tab
                            $myWarnings = DB::table('tbl_warning')
                                ->leftJoin('admin', 'admin.id', '=', 'tbl_warning.admin_id')
                                ->leftJoin('tbl_warning_type', 'tbl_warning_type.id', '=', 'tbl_warning.warningtype_id')
                                ->select('tbl_warning.*', 'admin.name as createdby', 'tbl_warning_type.warning_name')
                                ->where('tbl_warning.admin_id', $adminId)
                                ->get()
                                ->groupBy('warningtype_id');
                            $totalMyWarnings = $myWarnings->sum(fn($warnings) => $warnings->count());
                        @endphp

                        @if ($adminRole === 'agent')
                            <div class="col-md-3 d-flex justify-content-left align-items-left" style="height: 20vh;">
                                <div class="warning-card">
                                    <h2>Total Warnings</h2>
                                    <div class="count">{{ $totalMyWarnings }}</div>
                                </div>
                            </div>

                            @foreach($myWarnings as $warningTypeId => $warnings)
                                <div class="col-md-3 d-flex justify-content-left align-items-left" style="height: 20vh;">
                                    <div class="warning-card">
                                        <h2>{{ $warnings->first()->warning_name }}</h2>
                                        <div class="count">{{ $warnings->count() }}</div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div class="col-md-12">
                            <div class="tabbable">
                                <ul id="myTab1" class="nav nav-tabs">
                                    @if ($adminRole === 'admin')
                                        <li class="active">
                                            <a href="#all" data-toggle="tab"><i class="fa fa-home"></i> All Warnings</a>
                                        </li>
                                        <li>
                                            <a href="#dashboard" data-toggle="tab"><i class="fa fa-chart-bar"></i> Dashboard</a>
                                        </li>
                                    @else
                                        <li class="active">
                                            <a href="#all" data-toggle="tab"><i class="fa fa-home"></i> All Warnings</a>
                                        </li>
                                        <li>
                                            <a href="#mywarning" data-toggle="tab"><i class="fa fa-user"></i> My Warnings</a>
                                        </li>
                                        <li>
                                            <a href="#dashboard" data-toggle="tab"><i class="fa fa-chart-bar"></i> Dashboard</a>
                                        </li>
                                    @endif
                                </ul>

                                <div id="myTabContent1" class="tab-content">
                                    <!-- All Warnings Tab -->
                                    <div class="tab-pane fade in active all_tabs_bg" id="all">
                                        <div class="boligation_tabls">
                                            <div class="row">
                                                <div class="col-md-12" style="margin-top: 20px;">
                                                    <div class="table-responsive" style="border:0">
                                                        <table class="table table-advance" id="table1">
                                                            <thead>
                                                                <tr>
                                                                    @if ($adminRole === 'admin')
                                                                        <th style="width:18px"><input type="checkbox"></th>
                                                                        <th>Action</th>
                                                                    @endif
                                                                    <th>Created By</th>
                                                                    <th>Ticket Status</th>
                                                                    <th>Warning Type</th>
                                                                    <th>Message</th>
                                                                    <th>Assign</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if($warnings->isEmpty())
                                                                    <tr><td colspan="{{ $adminRole === 'admin' ? 7 : 5 }}">No warnings found</td></tr>
                                                                @else
                                                                    @php $sr = 1; @endphp
                                                                    @foreach($warnings as $item)
                                                                        <tr class="table-flag-blue">
                                                                            @if ($adminRole === 'admin')
                                                                                <td><input type="checkbox"></td>
                                                                                <td>
                                                                                    <a href="javascript:void(0);"
                                                                                        class="text-white btn btn-danger delete small-btn"
                                                                                        data-id="{{ $item->id }}">
                                                                                        Delete
                                                                                    </a>
                                                                                </td>
                                                                            @endif
                                                                            <td>{{$item->createdby ?? ''}}</td>
                                                                            <td>{{$item->task_status ?? ''}}</td>
                                                                            <td>
                                                                                <a href="javascript:void(0);" class="edit"
                                                                                    data-warningtype_id="{{$item->warningtype_id}}"
                                                                                    data-message="{{$item->message}}"
                                                                                    data-assign="{{ json_encode($item->assign) }}"
                                                                                    data-id="{{ $item->id }}">
                                                                                    {{$item->warning_name ?? ''}}
                                                                                </a>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#" class="view-full-message"
                                                                                    data-message="{{ $item->message }}">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </a>
                                                                            </td>
                                                                            <td>{{$item->assigned_names ?? '' }}</td>
                                                                        </tr>
                                                                        @php $sr++; @endphp
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- My Warnings Tab -->
                                    @if($adminRole !== 'admin')
                                        <div class="tab-pane fade in all_tabs_bg" id="mywarning">
                                            <div class="boligation_tabls">
                                                <div class="row">
                                                    <div class="col-md-12" style="margin-top: 20px;">
                                                        <div class="accordion">
                                                            @foreach($myWarnings as $warningTypeId => $warnings)
                                                                <div class="accordion-header">{{ $warnings->first()->warning_name }}
                                                                    ({{ $warnings->count() }})</div>
                                                                <div class="accordion-body">
                                                                    <table class="inner-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Created By</th>
                                                                                <th>Date</th>
                                                                                <th>Penalty</th>
                                                                                <th>Message</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($warnings as $warning)
                                                                                <tr>
                                                                                    <td>{{ $warning->createdby ?? 'N/A' }}</td>
                                                                                    <td>{{ $warning->created_at ? \Carbon\Carbon::parse($warning->created_at)->format('d/m/Y') : 'N/A' }}</td>
                                                                                    <td>₹{{ $warning->penalty ?? 0 }}</td>
                                                                                    <td>{{ $warning->message }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Dashboard Tab -->
                                    <div class="tab-pane fade in all_tabs_bg" id="dashboard">
                                        <div class="boligation_tabls">
                                            <div class="row">
                                                <div class="col-md-6" style="margin-top: 20px;">
                                                    <div class="card warrinig_card_new_design">
                                                        <h4>Total Warnings</h4>
                                                        <h2>{{ $adminRole === 'admin' ? $warnings->count() : $totalMyWarnings }}</h2>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="margin-top: 20px;">
                                                    <div class="card warrinig_card_new_design">
                                                        <h4>Pending Warnings</h4>
                                                        <h2>
                                                            @php
                                                                $pendingCount = $adminRole === 'admin' 
                                                                    ? $warnings->where('task_status', 'Open Ticket')->count()
                                                                    : $myWarnings->flatten()->where('task_status', 'Open Ticket')->count();
                                                            @endphp
                                                            {{ $pendingCount }}
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Task Modal Start Here -->
                    <div id="AddWarningModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                    <h4 class="modal-title" style="color:black;" id="modalTitle">Add Warning</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="col-md-12">
                                        <form id="add_form" method="post" class="mail-compose form-horizontal" action="javascript:void(0);">
                                            @csrf
                                            <input type="hidden" name="admin_id" value="{{$adminlogin->id}}">
                                            <p>
                                                <label for="">Issued By</label>
                                                <input type="text" value="{{$adminlogin->name}}" class="form-control" disabled>
                                            </p>
                                            <p>
                                                <label for="">Warning Type</label>
                                                <select name="warningtype_id" data-placeholder="Issued To"
                                                    class="form-control warning_chosen" tabindex="6">
                                                    <option selected="true" disabled="true">-- Select Warning Type --</option>
                                                    @php
                                                        $warningTypes = DB::table('tbl_warning_type')->orderBy('id', 'desc')->get();
                                                    @endphp
                                                    @foreach($warningTypes as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->warning_name ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </p>
                                            <p>
                                                <label for="">Issued To</label>
                                                <select name="assign" data-placeholder="Issued To" class="form-control warning_chosen"
                                                    tabindex="6">
                                                    <optgroup>
                                                        @php
                                                            $admins = DB::table('admin')
                                                                ->where('role', '!=', 'Admin')
                                                                ->whereIn('id', $under_admin_ids ?? [$adminId])
                                                                ->orderBy('id', 'desc')
                                                                ->get();
                                                        @endphp
                                                        @foreach($admins as $item)
                                                            <option value="{{ $item->id }}">
                                                                {{ $item->name ?? '' }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </p>
                                            <p>
                                                <div id="warningInfo"></div>
                                            </p>
                                            <p>
                                                <label for="">Penalty (₹):</label>
                                                <input type="number" name="penalty" class="form-control" placeholder="Enter Penalty Amount">
                                            </p>
                                            <p><textarea name="message" class="form-control wysihtml5" rows="6"></textarea></p>
                                            <p>
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-rocket"></i>
                                                    Add</button>
                                                <a type="button" class="btn">Cancel</a>
                                            </p>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Warning Modal -->
                    <div id="editwarningmodal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" style="color: black;" id="editwarningmodalLabel">Edit Warning</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <form class="edit_warning" action="javascript:void(0);" enctype="multipart/form-data" method="post">
                                            @csrf
                                            <input type="hidden" name="admin_id" value="{{$adminlogin->id}}" class="form-control">
                                            <input type="hidden" name="warning_id" value="" class="form-control edit_warning_id">
                                            <div class="col-sm-12 mb-2">
                                                <label for="subject">Issued By</label>
                                                <input type="text" value="{{$adminlogin->name}}" class="form-control" readonly>
                                            </div>
                                            <div class="col-sm-12">
                                                <label for="subject">Warning Type</label>
                                                <select name="warningtype_id" id="edit_warningtype_id"
                                                    class="form-control chosen EditChoosen" tabindex="6" disabled>
                                                    @php
                                                        $warningTypes = DB::table('tbl_warning_type')->get();
                                                    @endphp
                                                    @foreach($warningTypes as $warning)
                                                        <option value="{{ $warning->id }}">{{ $warning->warning_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-12 disabled-div">
                                                <textarea name="message" class="form-control wysihtml5 edit_message" disabled></textarea>
                                            </div>
                                            <div class="col-sm-12">
                                                <label for="subject">Issued To</label>
                                                <select name="assign[]" id="edit_assign" data-placeholder="Assign"
                                                    class="form-control chosen EditChoosen edit_assign" tabindex="6" disabled multiple>
                                                    <optgroup label="Designation">
                                                        @php
                                                            $admins = DB::table('admin')
                                                                ->where('role', '!=', 'Admin')
                                                                ->orderBy('id', 'desc')
                                                                ->get();
                                                        @endphp
                                                        @foreach($admins as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name ?? '' }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <div class="col-sm-12" style="display: flex; justify-content: right;">
                                                <div style="display: flex; gap: 10px;">
                                                    <button id="edit_button" class="btn btn-primary edit_button" style="margin-top: 15px;"
                                                        type="button">Edit</button>
                                                    <button id="update_button" class="btn btn-success update_button"
                                                        style="margin-top: 15px; display: none;" type="submit">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Show Modal -->
                    <div id="messageModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="color:black;">Full Message</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p id="fullMessageContent" style="color:black;"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div id="deletemodal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                    <h4 class="modal-title" style="color: black;" id="exampleModalLabel">Delete Warning</h4>
                                </div>
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

                    <!-- JS Links -->
                    <script src="{{asset('Admin/assets/ajax/libs/jquery/2.1.1/jquery.min.js')}}"></script>
                    <script>
                        window.jQuery || document.write('<script src="assets/jquery/jquery-2.1.1.min.js"><\/script>')
                    </script>
                    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

                    <script>
                        document.querySelectorAll(".accordion-header").forEach(header => {
                            header.addEventListener("click", function () {
                                let body = this.nextElementSibling;
                                body.style.display = (body.style.display === "none" || body.style.display === "") ? "block" : "none";
                            });
                        });
                    </script>

                    <script>
                        $(document).ready(function () {
                            $('#openModalBtn').on('click', function () {
                                $('#AddWarningModal').modal('show');
                            });

                            $(".warning_chosen").chosen({
                                width: '100%',
                                allow_single_deselect: true
                            });

                            $(".EditChoosen").chosen({
                                width: '100%'
                            });
                        });
                    </script>

                    <!-- Add your other JavaScript code here -->
                    <!-- Delete Script -->
                    <script>
                        $(document).on('click', '.delete', function () {
                            var id = $(this).data("id");
                            $("#delete_id").val(id);
                            $("#deletemodal").modal("show");
                        });

                        $("#delete_form").submit(function (e) {
                            e.preventDefault();
                            var formData = new FormData(this);
                            $.ajax({
                                type: "POST",
                                url: "{{url('/delete_warning')}}",
                                data: formData,
                                dataType: "json",
                                contentType: false,
                                processData: false,
                                cache: false,
                                success: function (data) {
                                    if (data.success == 'success') {
                                        $("#deletemodal").modal("hide");
                                        $("#delete_form")[0].reset();
                                        swal("Warning Deleted Successfully!", "", "success");
                                        window.location.reload();
                                    } else {
                                        swal('Warning Not Deleted', '', 'error');
                                    }
                                },
                                error: function (error) {
                                    swal('Something Went Wrong!', '', 'error');
                                }
                            });
                        });
                    </script>

                    <!-- Edit Script -->
                    <script>
                        $(document).on('click', '.edit', function () {
                            var id = $(this).data('id');
                            var warningtype_id = $(this).data('warningtype_id');
                            var message = $(this).data('message');
                            var selectedAdmins = JSON.parse($(this).data('assign'));

                            $(".edit_warning_id").val(id);
                            $("#edit_warningtype_id").val(warningtype_id).trigger("chosen:updated");
                            $(".edit_message").val(message);
                            $("#edit_assign").val(selectedAdmins).trigger("chosen:updated");
                            $("#editwarningmodal").modal("show");
                        });
                    </script>

                    <!-- Message Show Script -->
                    <script>
                        $(document).ready(function () {
                            $(".view-full-message").click(function (e) {
                                e.preventDefault();
                                var message = $(this).data("message");
                                $("#fullMessageContent").text(message);
                                $("#messageModal").modal("show");
                            });
                        });
                    </script>

                </div>
            </div>
        @endsection
    @endforeach
@else
    <script>
        window.location.href = "{{url('/login')}}";
    </script>
@endif
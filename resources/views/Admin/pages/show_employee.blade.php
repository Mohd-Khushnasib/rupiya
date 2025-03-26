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
                    background: #d3d3d3;
                    /* Default background (gray) */
                }

                .nav-tabs li.active a {
                    background: yellow !important;
                    /* Active tab turns yellow */
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
                                            <i class="fa fa-home"></i> Active <span class="notification_nav"
                                                id="active_count">0</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="admin-tab" data-status="0">
                                            <i class="fa fa-user"></i> Inactive <span class="notification_nav"
                                                id="inactive_count">0</span>
                                        </a>
                                    </li>
                                </ul>
                                <br>
                                <!-- Search Start Here -->
                                <div class="custom-search">
                                    <input type="text" class="form-control search_admin" style="width:50%;"
                                        placeholder="Search Here" pattern="\d*" />
                                </div>
                                <!-- Search End Here -->

                                <div class="table-responsive">
                                    <table class="table table-advance" id="adminTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Emp Id</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Number</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Status</th>
                                                <th>Department</th>
                                                <th>Designation</th>
                                                <th>Date of Joining</th>
                                                <th>CRM Access</th>
                                                <th>Action</th>
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


            <!-- Employee Status Modal -->
            <!-- Employee Status Change Modal -->
            <div class="modal fade" id="employeeStatusModal" tabindex="-1" role="dialog" aria-labelledby="employeeStatusModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="employeeStatusModalLabel">Update Employee Status</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="statusRemarkTextarea">Remark <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="statusRemarkTextarea" rows="3"
                                    placeholder="Enter your remark here..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="saveEmployeeStatus">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JS Links Start Here -->
            <script src="{{asset('Admin/assets/ajax/libs/jquery/2.1.1/jquery.min.js')}}"></script>
            <script>
                window.jQuery || document.write('<script src="assets/jquery/jquery-2.1.1.min.js"><\/script>')
            </script>
            <!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


            <script>
                $(document).ready(function () {
                    let currentStatus = 1; // Default: Active tab open
                    let currentPage = 1;
                    let searchKeyword = ''; // Search filter ke liye variable

                    loadAdmins(currentStatus, currentPage, searchKeyword);

                    // Handle tab click
                    $('.admin-tab').on('click', function () {
                        $('.admin-tab').parent().removeClass('active'); // Remove active class
                        $(this).parent().addClass('active'); // Add active class

                        currentStatus = $(this).data('status'); // Get status (1 = Active, 0 = Inactive)
                        currentPage = 1; // Reset to first page
                        searchKeyword = ''; // Tab change hone par search reset
                        $('.search_admin').val(''); // Input field clear karein
                        loadAdmins(currentStatus, currentPage, searchKeyword);
                    });

                    // Search functionality
                    $('.search_admin').keyup(function () {
                        searchKeyword = $(this).val().trim();
                        currentPage = 1; // Search hone par first page se start karein
                        loadAdmins(currentStatus, currentPage, searchKeyword);
                    });
                });

                // Function to Load Admin Data with Search Filter
                function loadAdmins(status, page, search = '') {
                    var search = search || $(".search_contact").val();

                    $.ajax({
                        url: "{{ url('fetch_employee') }}",
                        type: "POST",
                        data: {
                            status: status,
                            page: page,
                            search: search
                        },
                        success: function (data) {
                            let tbody = $("#adminTable tbody");
                            let pagination = $("#pagination");
                            tbody.empty();
                            pagination.empty();

                            if (data.data.length > 0) {
                                $("#active_count").text(data.total_active);
                                $("#inactive_count").text(data.total_inactive);

                                // Indexing 
                                var total_enquiries = data.total;
                                var per_page = data.per_page;
                                result = data.data;

                                result.forEach((item, index) => {

                                    let crmAccessStatus = item.crm_status == 1 ? "checked" : "";
                                    let switchStatus = item.status == 1 ? "checked" : "";

                                    // Status system with two options
                                    const statusOptions = [
                                        'NEW EMPLOYEE', 'ONBOARDING COMPLETE'
                                    ];

                                    const statusDropdown = `
                                                <div class="form-group">
                                                    <select class="form-control leads_system_bg employeeStatusSelect"
                                                        data-id="${item.id}" data-selected="${item.employee_status}"
                                                        data-row="${index + 1}" tabindex="1">
                                                        ${statusOptions.map(status =>
                                        `<option value="${status}" ${item.employee_status === status ? 'selected' : ''}>${status}</option>`
                                    ).join('')}
                                                    </select>
                                                </div>
                                            `;

                                    let crmAccessSwitch = `
                                                <label class='custom-switch'>
                                                    <input type='checkbox' name='crm-switch-checkbox' class='custom-switch-input CrmAccessSwitch' value="${item.id}" ${crmAccessStatus}>
                                                    <span class='custom-switch-indicator'></span>
                                                </label>`;

                                    let statusSwitch = `
                                                <label class='custom-switch'>
                                                    <input type='checkbox' name='custom-switch-checkbox' class='custom-switch-input StatusSwitch' value="${item.id}" ${switchStatus}>
                                                    <span class='custom-switch-indicator'></span>
                                                </label>`;

                                    tbody.append(`
                                                <tr>
                                                    <td>${index + 1 + (data.current_page - 1) * data.per_page}</td>
                                                    <td>${item.employee_id}</td>
                                                    <td><img src="${item.image}" alt="" height="50px" width="50px" style="object-fit: cover; border-radius: 50%;"></td>
                                                    <td><a href="{{ url('/employee-profile') }}/${item.id}">${item.name}</a></td>
                                                    <td>${item.mobile}</td>
                                                    <td>${item.email}</td>
                                                    <td>${item.gender}</td>
                                                    <td>${statusDropdown}</td>
                                                    <td>${item.department}</td>
                                                    <td>${item.role}</td>
                                                    <td>${formatDate(item.joining_date)}</td>
                                                    <td>${crmAccessSwitch}</td>
                                                    <td>${statusSwitch}</td>
                                                </tr>
                                            `);
                                });

                                // Employee Status Change Event
                                $("#adminTable").on('change', '.employeeStatusSelect', function () {
                                    const dropdown = $(this);
                                    const originalStatus = dropdown.data('selected');
                                    const selectedStatus = $(this).val();
                                    const employeeId = $(this).data('id');
                                    const modal = $('#employeeStatusModal');
                                    const textarea = $('#statusRemarkTextarea');

                                    // Show the modal
                                    modal.modal('show');

                                    // Handle modal close event
                                    modal.off('hidden.bs.modal').on('hidden.bs.modal', function () {
                                        dropdown.val(originalStatus); // Reset dropdown to original status if modal is closed
                                    });

                                    // Handle save button click
                                    $('#saveEmployeeStatus').off('click').on('click', function () {
                                        var remark = textarea.val();

                                        // Validate remark
                                        if (!remark) {
                                            alertify.set('notifier', 'position', 'top-right');
                                            alertify.error('Remark required');
                                            return;
                                        }

                                        const formData = new FormData();
                                        const admin_id = $("#admin_id").val();
                                        formData.append('admin_id', admin_id);
                                        formData.append('id', employeeId);
                                        formData.append('employee_status', selectedStatus);
                                        formData.append('remark', remark);

                                        $.ajax({
                                            type: "POST",
                                            url: "{{ url('/changeEmployeeStatus') }}",
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            dataType: 'json',
                                            encode: true,
                                            success: function (data) {
                                                if (data.success === 'success') {
                                                    swal("Employee Status Updated Successfully", "", "success");
                                                    textarea.val(""); // Reset textarea
                                                    modal.modal("hide"); // Hide modal
                                                    loadAdmins(currentStatus, currentPage, $("#search_contact").val());
                                                } else {
                                                    swal("Employee Status Not Updated!", "", "error");
                                                }
                                            },
                                            error: function (errResponse) {
                                                swal("Something Went Wrong!", "", "error");
                                            }
                                        });
                                    });
                                });

                                // Status Switch Change Event
                                $('.StatusSwitch').change(function () {
                                    var isChecked = $(this).is(':checked');
                                    var switchLabel = this.value;
                                    var checkedVal = isChecked ? 1 : 0;
                                    var tableName = "admin";

                                    // SweetAlert Confirmation
                                    swal({
                                        title: "Are you sure?",
                                        text: "You are about to update the status.",
                                        icon: "warning",
                                        buttons: ["Cancel", "Yes, update it"],
                                        dangerMode: true,
                                    }).then((willUpdate) => {
                                        if (willUpdate) {
                                            var formData = new FormData();
                                            formData.append('id', switchLabel);
                                            formData.append('status', checkedVal);

                                            $.ajax({
                                                type: "POST",
                                                url: "{{ url('switch_status_update_active') }}/" + tableName,
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                                dataType: 'json',
                                                encode: true,
                                                success: function (response) {
                                                    swal({
                                                        title: "Status Updated Successfully",
                                                        icon: "success",
                                                        timer: 1500,
                                                        buttons: false,
                                                    });
                                                    let newStatus = checkedVal;
                                                    $('.admin-tab').parent().removeClass('active');
                                                    $('.admin-tab[data-status="' + newStatus + '"]').parent().addClass('active');
                                                    currentStatus = newStatus;
                                                    currentPage = 1;
                                                    loadAdmins(currentStatus, currentPage);
                                                },
                                                error: function () {
                                                    swal("Error", "Failed to update status", "error");
                                                }
                                            });
                                        } else {
                                            $(this).prop('checked', !isChecked);
                                            swal("Status Update Cancelled", "", "info");
                                        }
                                    });
                                });

                                // CRM Access Change Event
                                $('.CrmAccessSwitch').change(function () {
                                    var isChecked = $(this).is(':checked');
                                    var switchLabel = this.value;
                                    var checkedVal = isChecked ? 1 : 0;
                                    var tableName = "admin";

                                    swal({
                                        title: "Are you sure?",
                                        text: "You are about to update the CRM Access.",
                                        icon: "warning",
                                        buttons: ["Cancel", "Yes, update it"],
                                        dangerMode: true,
                                    }).then((willUpdate) => {
                                        if (willUpdate) {
                                            var formData = new FormData();
                                            formData.append('id', switchLabel);
                                            formData.append('crm_status', checkedVal);

                                            $.ajax({
                                                type: "POST",
                                                url: "{{ url('switch_crm_access_update') }}/" + tableName,
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                                dataType: 'json',
                                                encode: true,
                                                success: function (response) {
                                                    swal({
                                                        title: "CRM Access Updated Successfully",
                                                        icon: "success",
                                                        timer: 1500,
                                                        buttons: false,
                                                    });
                                                },
                                                error: function () {
                                                    swal("Error", "Failed to update CRM Access", "error");
                                                }
                                            });
                                        } else {
                                            $(this).prop('checked', !isChecked);
                                            swal("CRM Access Update Cancelled", "", "info");
                                        }
                                    });
                                });

                                generatePaginationLinks(data.current_page, data.last_page, status, search);
                            } else {
                                tbody.append(`<tr><td colspan="12" class="text-center">No Data Found</td></tr>`);
                            }
                        }
                    });
                }

                // Generate Pagination with Search Parameter
                function generatePaginationLinks(currentPage, lastPage, status, search = '') {
                    let pagination = $("#pagination");
                    pagination.html('');

                    if (currentPage > 3) {
                        pagination.append(`<li><a href="javascript:void(0);" onclick="loadAdmins(${status}, 1, '${search}')">1</a></li>`);
                        pagination.append(`<li class="disabled"><a>...</a></li>`);
                    }

                    for (let i = Math.max(1, currentPage - 2); i <= Math.min(lastPage, currentPage + 2); i++) {
                        pagination.append(`
                                    <li class="${i === currentPage ? 'active' : ''}">
                                        <a href="javascript:void(0);" onclick="loadAdmins(${status}, ${i}, '${search}')">${i}</a>
                                    </li>
                                `);
                    }

                    if (currentPage < lastPage - 2) {
                        pagination.append(`<li class="disabled"><a>...</a></li>`);
                        pagination.append(`<li><a href="javascript:void(0);" onclick="loadAdmins(${status}, ${lastPage}, '${search}')">${lastPage}</a></li>`);
                    }
                }

            </script>

            <script>
                function formatDate(dateString) {
                    if (!dateString) return ''; // Handle empty date
                    let date = new Date(dateString);
                    let day = date.getDate().toString().padStart(2, '0');  // Ensure two digits
                    let month = (date.getMonth() + 1).toString().padStart(2, '0'); // Month is 0-based
                    let year = date.getFullYear();
                    return `${day}/${month}/${year}`;
                }
            </script>

        @endsection
    @endforeach
@else
    <script>
        window.location.href = "{{url('/login')}}";
    </script>
@endif
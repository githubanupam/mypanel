<style type="text/css">
    .test::after{
        content: '' !important;
    }
    .test::before {
        content: '' !important;
    }
    .status_group
    {
        margin:0 !important;
    }
    .pagination {
        padding: 4px !important;
        margin: 1px !important;
        cursor: pointer !important;
        font-size: 10px !important;
    }
    .paginate_input {
        width: 50px;
        border: 1px solid #6c757d;
        border-radius: 5px;
        font-size: 11px;
        margin-top: 10px !important;
    }
    td.details-control {
        background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
    }
</style>

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Employee Listing</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?= base_url() ?>employee/add_employee"><i class="fa fa-plus"></i>Add Employee</a>
    </div>
    <?php
    $error = $this->session->flashdata('error');
    if ($error) {
        ?>
        <div class="alert alert-dismissible alert-danger">
            <button class="close" type="button" data-dismiss="alert">×</button>
            <strong> <?php echo $error; ?>!</strong>
        </div>
        <?php
    }

    $success = $this->session->flashdata('success');
    if ($success) {
        ?>
        <div class="alert alert-dismissible alert-success">
            <button class="close" type="button" data-dismiss="alert">×</button>
            <strong> <?php echo $success; ?>!</strong>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">                  
                        <table id="user_data" class="table table-bordered table-striped table-sm" width="100%">
                            <thead>  
                                <tr>
                                    <th width="5%" class="test"></th>
                                    <th width="10%">Emp Id</th>
                                    <th width="10%">Username</th>
                                    <th width="15%">Employee Name</th>
                                    <th width="10%">Phone No</th>
                                    <th width="10%">Designation</th>
                                    <th width="20%">Section(s)</th> 
                                    <th width="10%">Unit</th>   
                                    <th width="10%">Status</th> 
                                </tr>  
                            </thead>  
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript" language="javascript" >
    function format(d) {

        return '<table width="100%" border="1" style="font-size:0.35;">' +
                '<tr>' +
                '<td colspan="4" width="25%" style="text-align:center;" bgcolor="#f7ac6f">ACTIONS</td>' +
                '</tr>' +
                '<tr>' +
                '<td width="25%">View Employee Details:</td>' +
                '<td width="25%">' + ((d.view_employee == undefined) ? '-' : d.view_employee) + '</td>' +
                '<td width="25%">Edit Employee Details:</td>' +
                '<td width="25%">' + ((d.edit_employee == undefined) ? '-' : d.edit_employee) + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td width="25%">Delete Employee Details:</td>' +
                '<td width="25%">' + ((d.delete_employee == undefined) ? '-' : d.delete_employee) + '</td>' +
                '<td width="25%">Password Re-generate:</td>' +
                '<td width="25%">' + ((d.regen_pass == undefined) ? '-' : d.regen_pass) + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td width="25%">Reset IMEI:</td>' +
                '<td width="25%">' + ((d.reset_imei == undefined) ? '-' : d.reset_imei) + '</td>' +
                '<td width="25%"></td>' +
                '<td width="25%"></td>' +
                '</tr>' +
                '</table>';
    }
    $(document).ready(function () {
        $.ajax({
            url: "<?php echo base_url() . 'employee/fetch_employee'; ?>",
            method: "post",
            dataType: "json",
            success: function (data) {
                var table = $("#user_data").DataTable({
                    data: data,
                    columns: [
                        {
                            'data': null,
                            'sortable': false,
                            'className': 'details-control',
                            'defaultContent': ''
                        },
                        {
                            'data': 'id'
                        },
                        {
                            'data': 'emp_id',
                        },
                        {
                            'data': 'emp_name',
                        },
                        {
                            'data': 'emp_contactno',
                        },
                        {
                            'data': 'role_title',
                        },
                        {
                            'data': 'access_station'
//                            'render': function (access_stations) {
//                                var temp = String(access_stations);
//                                return temp.substr(0, 20);
//                            }
                        },
                        {
                            'data': 'unit_shortname',
                        },
                        {
                            'data': 'status',
                            'render': function (status) {
                                return '<select class="form-control form-control-sm">\n\
                                            <option value="A" (' + status + '=="A")? "selected":"">Active</option>\n\
                                            <option value="I" (' + status + '=="I")? "selected":"">Inactive</option>\n\
                                        </select>';
                            }
                        }
                    ],
                    pagingType: 'input',
                    bStateSave: true,
                    "fnStateSave": function (oSettings, oData) {
                        localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
                    },
                    "fnStateLoad": function (oSettings) {
                        console.log(localStorage.getItem('DataTables_' + window.location.pathname));
                        return JSON.parse(localStorage.getItem('DataTables_' + window.location.pathname));
                    }
                });

                $('#user_data tbody').on('click', 'td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(format(row.data())).show();
                        tr.addClass('shown');
                    }
                });

                $(".first,.next,.previous,.last").addClass("badge badge-primary pagination");
                $(".paginate_page,.paginate_of").addClass("badge badge-light pagination");


            }
        });
    });
</script>
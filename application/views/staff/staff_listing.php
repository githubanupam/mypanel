<style type="text/css">
    .dataTables_wrapper .dataTables_processing {
        background-color:rgb(40, 167, 69);
        font-family: "Lato", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: rgb(255, 255, 255);
        font-size: 12px;
        font-weight: 700;
    }
    table.dataTable.table-sm > thead > tr > th {
        padding-right: 5px !important;
    }
    .search,#change_status{
        border-radius: 3px !important;
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
        border-radius: 4px;
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
<script type="text/javascript">
    function regeneratePassword(id) {
        var result = confirm("<?php echo 'Want to regenerate password'; ?>");
        if (result) {
            $.ajax({
                url: "<?php echo base_url(); ?>" + "staff/ajaxRegeneratePassword/" + id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    alert(" Current password : " + data.password);
                },
                error: function (e) {
                    alert("Try again ! ");
                }
            });
        }
    }

    function resetIMEI(id) {
        var result = confirm("<?php echo 'Want to reset imei number?'; ?>");
        if (result) {
            $.ajax({
                url: "<?php echo base_url(); ?>" + "staff/ajaxResetIMEI/" + id,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    alert("Yehh Reset Successfully ");
                },
                error: function (e) {
                    alert("Try again ! ");
                }
            });
        }
    }

    function changeStatus(id, event) {
        if (confirm('Change status?')) {
            //var result=$('#change_status').val();
            //alert(result);
            var selectElement = event.target;
            var result = selectElement.value;

            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "staff/change_status/" + id,
                dataType: 'json',
                data: {
                    status: result
                },
                success: function (res) {
                    if (res) {
                        //console.log(result+" "+id);
                    } else
                    {
                        alert('Unable to change status');
                    }
                }
            });
        } else
        {
            return false;
        }

    }

    function checkDelete() {
        if (confirm('Delete emplyee?')) {
            return true;
        } else
        {
            return false;
        }
    }

    function getSectionByUnit(divId, flag) {

        var value = localStorage.getItem('access_station');
        //alert(value);
        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "staff/getPoliceStation/" + null + "/" + divId,
            dataType: 'json',
            success: function (res) {
                if (res)
                {
                    $("#access_station").find('option').remove();
                    $("#access_station").append('<option value="" selected="selected"></option>');
                    $.each(res, function () {

                        //$("#access_station").append('<option value="' + this.sec_shortunit + '">' + this.sec_shortunit + '</option>')
                        if (value == this.sec_shortunit && flag == true) {
                            $("#access_station").append('<option value="' + this.sec_shortunit + '" selected="selected">' + this.sec_shortunit + '</option>')
                        } else {
                            $("#access_station").append('<option value="' + this.sec_shortunit + '">' + this.sec_shortunit + '</option>')
                            //localStorage.removeItem('access_station');
                        }

                    });

                } else
                {
                    alert('Unable to fetch Officers');
                }
            }
        });
    }
</script>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Employee Listing</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?= base_url() ?>staff/add_staff"><i class="fa fa-plus"></i>Add Employee</a>
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
                                    <th width="7%">Sl No.</th>  
                                    <th width="15%">Name</th>
                                    <th width="10%">Username</th>
                                    <th width="10%">Mobile</th>  
                                    <th width="13%">Designation</th>
                                    <th width="10%">Station(s)</th>
                                    <th width="12%">Unit</th>
                                    <th width="13%">Reporting Officer</th> 
                                    <th width="10%">Status</th>
                                </tr> 
                            </thead>
                            <?php
                                if($role!=3){
                            ?>
                            <thead class="search-header">  
                                <tr>  
                                    <th width="7%"></th>  
                                    <th width="15%">
                                        <input type="text" data-column="0"  class="form-control form-control-sm search" id="emp_name">
                                    </th>
                                    <th width="10%">
                                        <input type="text" data-column="1"  class="form-control form-control-sm search" id="emp_id">
                                    </th> 
                                    <th width="10%">
                                        <input type="text" data-column="2"  class="form-control form-control-sm search" id="emp_contactno">
                                    </th>  
                                    <th width="10%">
                                        <select data-column="3"  class="form-control form-control-sm search" id="usertype_id">
                                            <option value=""></option>
                                            <?php
                                            $userType = getAllUsertype();
                                            foreach ($userType as $row_userType) {
                                                ?>
                                                <option value="<?= $row_userType['usertype_id'] ?>"><?= $row_userType['s_name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </th>
                                    <th width="13%">
                                        <select data-column="4"  class="form-control form-control-sm search" id="access_station">
                                            <option value=""></option>
                                        </select>
                                    </th>
                                    <th width="12%">
                                        <select data-column="5"  class="form-control form-control-sm search" id="unit_shortname">
                                        <?php
                                            if($role==2){
                                        ?>
                                            <option value="<?=$access_station?>" selected="selected"><?=divName($access_station)?></option>
                                        <?php
                                            }else{
                                        ?>
                                            <option value=""></option>
                                        <?php
                                                $units = divName();
                                                foreach ($units as $row_unit) {
                                        ?>
                                                    <option value="<?= $row_unit['id'] ?>"><?= $row_unit['unit_shortname'] ?></option>
                                        <?php   
                                                }
                                            }
                                        ?>
                                        </select>
                                    </th>
                                    <th width="13%">
                                        <input type="text" data-column="6"  class="form-control form-control-sm search" id="reporting_officer">
                                    </th> 
                                    <th width="10%">
                                        <select data-column="7"  class="form-control form-control-sm search" id="status">
                                            <option value=""></option>
                                            <option value="A">Active</option>
                                            <option value="P">Pending</option>
                                            <option value="I">Inactive</option>
                                        </select>
                                    </th>
                                </tr> 
                            </thead>
                            <?php
                                }
                            ?>
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript" language="javascript" >
    $(document).ready(function () {
        //localStorage.clear();
        function format(d) {

            return '<table width="100%" border="1" style="font-size:12px;">' +
                    '<tr>' +
                    '<td colspan="4" width="25%" style="text-align:center;" bgcolor="#f7ac6f">ACTIONS</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="25%">View:</td>' +
                    '<td width="25%">' + ((d.view_staff == undefined) ? '-' : d.view_staff) + '</td>' +
                    '<td width="25%">Edit:</td>' +
                    '<td width="25%">' + ((d.edit_staff == undefined) ? '-' : d.edit_staff) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="25%">Delete:</td>' +
                    '<td width="25%">' + ((d.delete_staff == undefined) ? '-' : d.delete_staff) + '</td>' +
                    '<td width="25%">Password Re-generate:</td>' +
                    '<td width="25%">' + ((d.regen_pass == undefined) ? '-' : d.regen_pass) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="25%">IMEI Reset:</td>' +
                    '<td width="25%">' + ((d.reset_imei == undefined) ? '-' : d.reset_imei) + '</td>' +
                    '<td width="25%"></td>' +
                    '<td width="25%"></td>' +
                    '</tr>'
            '</table>';
        }

        var dataTable = $('#user_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "<?php echo base_url() . 'staff/fetch_user'; ?>",
                type: "POST"
            },
            "columnDefs": [
                {
                    "targets": [8],
                    "orderable": false,
                    "className": "text-center",

                },
                {
                    "targets": [0],
                    'data': null,
                    'sortable': false,
                    'className': 'details-control',
                    'defaultContent': ''
                },
            ],
            pagingType: 'input',
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                console.log(localStorage.getItem('DataTables_' + window.location.pathname));
                return JSON.parse(localStorage.getItem('DataTables_' + window.location.pathname));
            }
        });

        $(".first,.next,.previous,.last").addClass("badge badge-primary pagination");
        $(".paginate_page,.paginate_of").addClass("badge badge-light pagination");

        $('#user_data tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = dataTable.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });


        $('.search').each(function () {
            var column = $(this).attr('data-column');
            var id = $(this).attr('id');
            var value = localStorage.getItem(id);
            $(this).val(value);
        });

        $('.search').on('keyup change', function () {
            var column = $(this).attr('data-column');
            var id = $(this).attr('id');
            var value = $(this).val();

            if (column == 5) {
                var prev_column = 4;
                var prev_value = '';
                localStorage.setItem(id, value);
                dataTable.columns(column).search(value).draw();
                dataTable.columns(prev_column).search(prev_value).draw();
                getSectionByUnit(value, false);

            } else {
                localStorage.setItem(id, value);
                dataTable.columns(column).search(value).draw();
            }

        });

        var unit_shortname_value = $('#unit_shortname').val();
        var unit_shortname_column = 5;
        if (unit_shortname_value != null && unit_shortname_value != '') {
            dataTable.columns(unit_shortname_column).search(unit_shortname_value).draw();
            getSectionByUnit(unit_shortname_value, true);
        }

        var access_station_value = $('#access_station').val();
        var access_station_column = 4;
        if (access_station_value != null && access_station_value != '') {
            dataTable.columns(access_station_column).search(access_station_value).draw();
        }

        var usertype_id_value = $('#usertype_id').val();
        var usertype_id_column = 3;
        if (usertype_id_value != null && usertype_id_value != '') {
            dataTable.columns(usertype_id_column).search(usertype_id_value).draw();
        }
    });
</script>

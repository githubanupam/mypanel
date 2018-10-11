<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-plus-square"></i> Edit Employee</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?= base_url() ?>staff"><i class="fa fa-backward"></i>Back</a>
    </div>
    <div class="row">
        <div class="col-md-12">
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
            <div class="tile">
                <h3 class="tile-title">Edit Details</h3>
                <?php
                if (isset($staff_master)) {
                    ?>
                    <div class="tile-body">
                    <?= form_open('staff/edit_staff_process/' . $staff_master['id'], 'class="form-horizontal"'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Employee name</label>
                                    <div>
                                        <input type="text" class="form-control" id="emp_name" name="emp_name" placeholder="Enter employee name" value="<?= set_value('emp_name', $staff_master['emp_name']) ?>">
    <?= form_error('emp_name') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Guardian name</label>
                                    <div>
                                        <input type="text" class="form-control" id="emp_guardian_name" name="emp_guardian_name" placeholder="Enter guardian name" value="<?= set_value('emp_guardian_name', $staff_master['emp_guardian_name']) ?>">
    <?= form_error('emp_guardian_name') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Contact no.</label>
                                    <div>
                                        <input type="text" class="form-control" id="emp_contactno" name="emp_contactno" placeholder="Enter contact no." value="<?= set_value('emp_contactno', $staff_master['emp_contactno']) ?>">
    <?= form_error('emp_contactno') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Email-id</label>
                                    <div>
                                        <input type="text" class="form-control" id="emp_emailid" name="emp_emailid" placeholder="Enter email id" value="<?= set_value('emp_emailid', $staff_master['emp_emailid']) ?>">
    <?= form_error('emp_emailid') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Division</label>
                                    <div>
                                        <select class="form-control" id="emp_district" name="emp_district">
                                            <option></option>
    <?php
    if (count($all_division))
        foreach ($all_division as $row_division) {
            ?>
                                                    <option value="<?= $row_division['id'] ?>" <?= ($row_division['id'] == $staff_master['emp_district']) ? "selected" : ""; ?>><?= $row_division['unit_shortname'] ?></option>
                                                    <?php
                                                }
                                            ?>      
                                        </select>
                                            <?= form_error('emp_district') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Police station</label>
                                    <div>
                                        <select class="form-control" id="access_stations" name="access_stations[]" multiple="">
                                            <option></option>
    <?php
    $access_stations = explode(',', $staff_master['access_stations']);
    if (count($all_section))
        foreach ($all_section as $row_section) {
            ?>
                                                    <option value="<?= $row_section['id'] ?>" <?= (in_array($row_section['id'], $access_stations, TRUE)) ? "selected" : ""; ?>><?= $row_section['sec_shortunit'] ?></option>
                                                    <?php
                                                }
                                            ?>      
                                        </select>
                                            <?= form_error('access_stations') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Designation</label>
                                    <div>
                                        <select class="form-control" id="usertype_id" name="usertype_id">
                                            <option></option>
    <?php
    if (count($all_designation))
        foreach ($all_designation as $row_desig) {
            ?>
                                                    <option value="<?= $row_desig['usertype_id'] ?>" <?= ($row_desig['usertype_id'] == $staff_master['usertype_id']) ? "selected" : ""; ?>><?= $row_desig['s_name'] ?></option>
                                                    <?php
                                                }
                                            ?>      
                                        </select>
                                            <?= form_error('usertype_id') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Reporting officer</label>
                                    <div>
                                        <select class="form-control" id="parent_id" name="parent_id">
                                            <option></option>
                                        </select>
    <?= form_error('parent_id') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Rank </label>
                                    <div>
                                        <select class="form-control" id="current_rank_id" name="current_rank_id">
                                            <option></option>
    <?php
    if (count($get_rank_details))
        foreach ($get_rank_details as $row_rank) {
            ?>
                                                    <option value="<?= $row_rank['id'] ?>" <?= ($row_rank['id'] == $staff_master['current_rank_id']) ? "selected" : ""; ?>><?= $row_rank['shortname'] ?></option>
                                                    <?php
                                                }
                                            ?>  
                                        </select>
                                            <?= form_error('current_rank_id') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Rank description</label>
                                    <div>
                                        <input type="text" class="form-control" id="role_title" name="role_title" value="<?= set_value('role_title', $staff_master['role_title']) ?>">
    <?= form_error('role_title') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-control">
                                    <label class="control-label">Is this employee process babu?</label>
                                    <div class="animated-radio-button">
                                        <label>
                                            <input type="radio" name="fd_authorise" value="1" <?= ($staff_master['fd_authorise'] == '1') ? "checked" : "" ?>><span class="label-text">YES</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="fd_authorise" value="0" <?= ($staff_master['fd_authorise'] == '0') ? "checked" : "" ?>><span class="label-text">NO</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-control">
                                    <label class="control-label">Is employee duty distributer?</label>
                                    <div class="animated-radio-button">
                                        <label>
                                            <input type="radio" name="allocation_task_settings" value="1" <?= ($staff_master['allocation_task_settings'] == '1') ? "checked" : "" ?>><span class="label-text">YES</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="allocation_task_settings" value="0" <?= ($staff_master['allocation_task_settings'] == '0') ? "checked" : "" ?>><span class="label-text">NO</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-control">
                                    <div class="animated-radio-button row">
                                        <div class="col-md-2">
                                            <label class="control-label">Is Leave SO</label>&nbsp;
                                            <label>
                                                <input type="radio" name="leave" value="leave_so" <?= ($staff_master['leave_so'] == '1') ? "checked" : "" ?>><span class="label-text"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Is Leave OC</label>&nbsp;
                                            <label>
                                                <input type="radio" name="leave" value="leave_oc" <?= ($staff_master['leave_oc'] == '1') ? "checked" : "" ?>><span class="label-text"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Is Leave AC</label>&nbsp;
                                            <label>
                                                <input type="radio" name="leave" value="leave_ac" <?= ($staff_master['leave_ac'] == '1') ? "checked" : "" ?>><span class="label-text"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Is Leave DC</label>&nbsp;
                                            <label>
                                                <input type="radio" name="leave" value="leave_dc" <?= ($staff_master['leave_dc'] == '1') ? "checked" : "" ?>><span class="label-text"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="control-label">Is Leave Applicable</label>&nbsp;
                                            <label>
                                                <input type="radio" name="leave" value="is_leave_applicable" <?= ($staff_master['is_leave_applicable'] == '1') ? "checked" : "" ?>><span class="label-text"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    <?php
}
?>
                    <div class="tile-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-3">
                                <input type="submit" class="btn btn-primary" value=" &check; Submit">
                                <input type="reset" class="btn btn-secondary" value=" &circlearrowright; Reset">
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clearix"></div>
    </div>
</main>
<script type="text/javascript">
    $(document).ready(function () {
        $('#access_stations').select2({
            placeholder: "Select police stations"
        });
        $('#emp_district').select2({
            placeholder: "Select a division"
        });
        $('#usertype_id').select2({
            placeholder: "Select a designation"
        });
        $('#parent_id').select2({
            placeholder: "Select a repoting officer"
        });
        $('#current_rank_id').select2({
            placeholder: "Select a rank"
        });

        function getPoliceStation(id, divId) {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "staff/getPoliceStation/" + id + "/" + divId,
                dataType: 'json',
                success: function (res) {
                    if (res)
                    {
                        $("#access_stations").find('option').remove();
                        $.each(res, function () {
                            $("#access_stations").append('<option value="' + this.id + '">' + this.sec_shortunit + '</option>')
                        });

                        //callback();
                    } else
                    {
                        alert('Unable to fetch Officers');
                    }
                }
            });
        }

        function getReportingOfficers(id = null, psId = null, divId = null, usertypeId = null) {

            //alert("divId===>" + divId + "usertypeId===>" + usertypeId);
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "staff/getReportingOfficers/" + id + "/" + psId + "/" + divId + "/" + usertypeId,
                dataType: 'json',
                success: function (res) {
                    if (res)
                    {
                        $("#parent_id").find('option').remove();
                        $.each(res, function () {
                            value="<?=$staff_master['parent_id'] ?>";
                            if(this.id==value){
                                $("#parent_id").append('<option value="' + this.id + '" selected="selected">' + this.emp_name + '</option>')
                            }else{
                                $("#parent_id").append('<option value="' + this.id + '">' + this.emp_name + '</option>')
                            }
                            
                        });
                    } else
                    {
                        alert('Unable to fetch Officers');
                    }
                }
            });
        }

        $("#emp_district").on('change', function () {
            var divId = $('#emp_district').val();
            getPoliceStation(null, divId);
            $('#usertype_id').val(null).trigger('change');
            $('#parent_id').find('option').remove();
            //getReportingOfficers(null, null, divId, null);
        });

        $("#access_stations").on('change', function () {
            var divId = $('#emp_district').val();
            var psId = $('#access_stations').val();
            var usertypeId = $('#usertype_id').val();
            //alert("divId=>" + divId + "usertypeId=>" + usertypeId);

            if (divId != '' && psId != '' && usertypeId != '') {
                if (usertypeId == 6) {
                    getReportingOfficers(null, null, null, usertypeId);
                } else if (usertypeId == 7 || usertypeId == 9) {
                    getReportingOfficers(null, null, divId, usertypeId);
                } else {
                    getReportingOfficers(null, psId, divId, usertypeId);
                }
            }
        });

        $("#usertype_id").on('change', function () {
            var divId = $('#emp_district').val();
            var psId = $('#access_stations').val();
            var usertypeId = $('#usertype_id').val();
            //alert("divId=>" + divId + "usertypeId=>" + usertypeId);

            if (divId != '' && psId != '' && usertypeId != '') {
                if (usertypeId == 6 || usertypeId == 8) {
                    getReportingOfficers(null, null, null, 6);
                } else if (usertypeId == 7 || usertypeId == 9) {
                    getReportingOfficers(null, null, divId, usertypeId);
                } else {
                    getReportingOfficers(null, psId, divId, usertypeId);
                }
            }
        });

        var divId = $('#emp_district').val();
        var psId = $('#access_stations').val();
        var usertypeId = $('#usertype_id').val();
        if (divId != '' && psId != '' && usertypeId != '') {
            if (usertypeId == 6 || usertypeId == 8) {
                getReportingOfficers(null, null, null, 6);
            } else if (usertypeId == 7 || usertypeId == 9) {
                getReportingOfficers(null, null, divId, usertypeId);
            } else {
                getReportingOfficers(null, psId, divId, usertypeId);
            }
        }
    });
</script>

<script type="text/javascript">
    function get_page_seq(moduleId, callback)
    {
        var result = $('#module').val();
        //alert(moduleId);
        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "page/get_page_seq/" + moduleId,
            dataType: 'json',
            success: function (res) {
                if (res)
                {
                    console.log(res);
                    if (res.length != 0)
                    {
                        $("#page_seq").find('option').remove();
                        $.each(res, function () {
                            $("#page_seq").append('<option value="' + this.seq + '">' + this.seq + '</option>')
                        });
                        $("#page_seq").append('<option value="' + (parseInt(res[res.length - 1].seq) + 1) + '">' + (parseInt(res[res.length - 1].seq) + 1) + '</option>')
                    } else
                    {
                        $("#page_seq").find('option').remove();
                        $("#page_seq").append('<option value="1">1</option>')
                    }

                    callback();
                } else
                {
                    alert('Unable to fetch Sequence');
                }
            }
        });
    }
</script>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-plus-square"></i> Add Page</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?= base_url() ?>page"><i class="fa fa-backward"></i>Back</a>
    </div>
    <div class="row">
        <div class="col-md-6">
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
                <h3 class="tile-title">Page Details</h3>
                <div class="tile-body">
<?= form_open('page/add_page_process', 'class="form-horizontal"'); ?>
                    <div class="form-group row">
                        <label class="control-label col-md-4">Module Name</label>
                        <div class="col-md-8">
                            <select class="form-control" name="moduleId" id="module" onchange="get_page_seq(this.value)">
<?php
//print_r($module);
if (isset($module)) {
    ?>
                                    <option value="">---Select Module---</option>
                                    <?php
                                    foreach ($module as $row_module) {
                                        ?>
                                        <option value="<?= $row_module->moduleId ?>" <?= (((isset($_POST['moduleId'])) ? $_POST['moduleId'] : '') == $row_module->moduleId) ? "selected" : "" ?>><?= $row_module->moduleName ?></option>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <option></option>
                                    <?php
                                }
                                ?>
                            </select>
                                <?= form_error('moduleId') ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-4">Page Name</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" name="pageName" placeholder="Enter Page Name..." value="<?= set_value('pageName') ?>">
<?= form_error('pageName') ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-4">File Name</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" name="fileName" placeholder="Enter File Name..." value="<?= set_value('fileName') ?>">
<?= form_error('fileName') ?>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-4">Page Sequence</label>
                        <div class="col-md-8">
                            <select class="form-control" name="seq" id="page_seq">
                              <!-- <option value="<?= ($row_page->seq + 1) ?>" <?= set_select('seq', ($row_page->seq + 1)); ?>><?= ($row_page->seq + 1) ?></option> -->
                                <option value="">---Select Sequence---</option>
                            </select>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    var result = $('#module').val();
                                    get_page_seq(result, function () {
                                        var seq =<?= set_value('seq') ?>;
                                        $('#page_seq').val(seq);
                                    });
                                });
                            </script>
<?= form_error('seq') ?>
                        </div>
                    </div>
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
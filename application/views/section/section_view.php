<style type="text/css">
    .action_btn{
        padding: inherit !important;
    }
    .action_btn .fa{
        margin-right: 0 !important;
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
    }
</style>
<script type="text/javascript">
    function changeStatus(id) {
        if (confirm('Change status?')) {
            var result = $('#change_status').val();
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "section/change_status/" + id,
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
        return confirm('Are you sure?');
    }
</script>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-th-list"></i> Section Listing</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?= base_url() ?>section/add_section"><i class="fa fa-plus"></i>Add Section</a>
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
                                    <th width="10%">Sl No.</th>  
                                    <th width="30%">Full Name</th>  
                                    <th width="15%">Short Name</th> 
                                    <th width="15%">Unit</th>   
                                    <th width="15%">Status</th> 
                                    <th width="15%">Actions</th>  
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
    $(document).ready(function () {
        var dataTable = $('#user_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [[3, 'asc']],
            "rowGroup": {
                dataSrc: 3
            },
            "ajax": {
                url: "<?php echo base_url() . 'section/fetch_user'; ?>",
                type: "POST"
            },
            "columnDefs": [
                {
                    "targets": [0, 5],
                    "orderable": false,
                    "className": "text-center",

                }
            ],
            "pagingType": 'input',
//            "bStateSave": true,
//            "fnStateSave": function (oSettings, oData) {
//                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
//            },
//            "fnStateLoad": function (oSettings) {
//                return JSON.parse(localStorage.getItem('DataTables_' + window.location.pathname));
//            }
        });

        $(".first,.next,.previous,.last").addClass("badge badge-primary pagination");
        $(".paginate_page,.paginate_of").addClass("badge badge-light pagination");
    });
</script>
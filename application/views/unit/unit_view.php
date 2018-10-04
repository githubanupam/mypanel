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
</style>
<script type="text/javascript">
  function changeStatus(id){
        var result=$('#change_status').val();
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "unit/change_status/"+id,
                dataType: 'json',
                data: {
                    status: result
                },
                success: function(res) {
                    if (res) {
                      //console.log(result+" "+id);
                    }
                    else
                    {
                       alert('Unable to change status');
                    }
                }
            });
      }
</script>
<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Unit Listing</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?=base_url()?>unit/add_unit"><i class="fa fa-plus"></i>Add Unit</a>
      </div>
      <?php
          $error = $this->session->flashdata('error');
          if($error){
      ?>
              <div class="alert alert-dismissible alert-danger">
                  <button class="close" type="button" data-dismiss="alert">×</button>
                  <strong> <?php echo $error; ?>!</strong>
              </div>
      <?php 
          }

          $success = $this->session->flashdata('success');
          if($success){ 

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
                                 <th width="30%">Short Name</th>  
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
 $(document).ready(function(){  
      var dataTable = $('#user_data').DataTable({  
           "processing":true,  
           "serverSide":true,  
           "order":[],  
           "ajax":{  
                url:"<?php echo base_url() . 'unit/fetch_user'; ?>",  
                type:"POST"  
           },  
           "columnDefs":[  
                {  
                    "targets": [0,4] ,
                    "orderable":false,
                    "className": "text-center",
                    
                },  
           ],
          "pagingType": 'simple_numbers',
          "language": {
              "paginate": {
                  "first":    '«',
                  "previous": '‹',
                  "next":     '›',
                  "last":     '»'
              }
          }
      });
      $.fn.DataTable.ext.pager.numbers_length = 4; 

      $( "#change_status" ).change(function() {
        alert( "Handler for .change() called." );
      }); 

      function changeStatus(id){
        alert(id);
      }
 });  
 </script>
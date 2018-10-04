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
        if(confirm('Change status?')){
          var result=$('#change_status').val();
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "usertype/change_status/"+id,
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
        else
        {
          return false;
        }
            
  }
  function changeSuperior(id){
        if(confirm('Change Superiority?')){
          var result=$('#change_superior').val();
            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "usertype/change_superior/"+id,
                dataType: 'json',
                data: {
                    is_superior: result
                },
                success: function(res) {
                    if (res) {
                      //console.log(result+" "+id);
                    }
                    else
                    {
                       alert('Unable to change Superiority');
                    }
                }
            });
          }
        else
        {
          return false;
        }
            
  }

  function checkDelete(){
    return confirm('Are you sure?');
  }
</script>
<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Usertype Listing</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?=base_url()?>usertype/add_usertype"><i class="fa fa-plus"></i>Add New Usertype</a>
      </div>
      <?php
          $error = $this->session->flashdata('error');
          if($error){
      ?>
              <div class="alert alert-dismissible alert-danger">
                  <button class="close" type="button" data-dismiss="alert">&#x2717;</button>
                  <strong> <?php echo $error; ?>!</strong>
              </div>
      <?php 
          }

          $success = $this->session->flashdata('success');
          if($success){ 

      ?>
              <div class="alert alert-dismissible alert-success">
                  <button class="close" type="button" data-dismiss="alert">&#x2717;</button>
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
                                 <th width="8%">Sl No.</th>  
                                 <th width="25%">Full Name</th>  
                                 <th width="12%">Short Name</th> 
                                 <th width="12%">Type Order</th>
                                 <th width="15%">Is Superior</th>   
                                 <th width="15%">Status</th> 
                                 <th width="18%">Actions</th>  
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
                url:"<?php echo base_url() . 'usertype/fetch_user'; ?>",  
                type:"POST"  
           },  
           "columnDefs":[  
                {  
                    "targets": [0,6] ,
                    "orderable":false,
                    "className": "text-center",
                    
                },  
           ],
          "pagingType": 'simple_numbers',
          // "language": {
          //     "paginate": {
          //         "first":    '«',
          //         "previous": '‹',
          //         "next":     '›',
          //         "last":     '»'
          //     }
          // },
          "bStateSave": true,
          "fnStateSave": function (oSettings, oData) {
              localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
          },
          "fnStateLoad": function (oSettings) {
              return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
          }
      });
      //$.fn.DataTable.ext.pager.numbers_length = 4; 
 }); 
 </script>
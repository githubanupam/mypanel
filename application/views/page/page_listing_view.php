
<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Page Listing</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?=base_url()?>page/add_page"><i class="fa fa-plus"></i>Add New Page</a>
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
                                 <th width="8%">Sl No.</th>  
                                 <th width="12%">Module Name</th>
                                 <th width="25%">Page Name</th>
                                 <th width="25%">File Name</th> 
                                 <th width="15%">Page Sequence</th> 
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
<?php
?>
<script type="text/javascript" language="javascript" >  
 $(document).ready(function(){  
      var dataTable = $('#user_data').DataTable({  
           "processing":true,  
           "serverSide":true,  
           "order":[[1, 'asc']],
           "rowGroup": {
              dataSrc: 1
            }, 
           "ajax":{  
                url:"<?php echo base_url() . 'page/page_listing'; ?>",  
                type:"POST"  
           },  
           "columnDefs":[  
                {  
                    "targets": [0,5] ,
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
          },
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
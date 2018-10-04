<script type="text/javascript">
  function get_page(moduleId,callback)
  {
    var result=$('#module').val();

        //alert(moduleId);
      if(result!=''){

          jQuery.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>" + "page_action/get_page/"+moduleId,
              dataType: 'json',
              success: function(res) {
                  if (res) 
                  {
                    if(res.length!=0)
                    {
                      $("#page").find('option').remove();
                      $("#page").append('<option value="">---Select Page---</option>')
                      $.each(res,function(){
                        $("#page").append('<option value="'+ this.pageId +'">'+ this.pageName+'</option>')
                      });
                    }
                    else
                    {
                        $("#page").find('option').remove();
                        $("#page").append('<option value="">---Select Page---</option>')
                    }

                    callback();
                  }
                  else
                  { 
                    alert('Unable to fetch Sequence'); 
                  }
              }
          });

      }else{
        $("#page").find('option').remove();
        $("#page").append('<option value="">---Select Page---</option>')
      }
  }

  function get_action_seq(pageId,callback)
  {
    var result=$('#page').val();

      if(result!=''){

          jQuery.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>" + "page_action/get_action_seq/"+pageId,
              dataType: 'json',
              success: function(res) {
                  if (res) 
                  {
                    if(res.length!=0)
                    {
                      $("#action_seq").find('option').remove();
                      $.each(res,function(){
                        $("#action_seq").append('<option value="'+ this.seq +'">'+ this.seq+'</option>')
                      });
                      $("#action_seq").append('<option value="'+ (parseInt(res[res.length-1].seq)+1) +'">'+ (parseInt(res[res.length-1].seq)+1)+'</option>')
                    }
                    else
                    {
                        $("#action_seq").find('option').remove();
                        $("#action_seq").append('<option value="1">1</option>')
                    }

                    callback();
                  }
                  else
                  { 
                    alert('Unable to fetch Sequence'); 
                  }
              }
          });

      }else{
        $("#action_seq").find('option').remove();
        $("#action_seq").append('<option value="">---Select Page---</option>')
      }
  }
</script>
<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-plus-square"></i> Add Action</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?=base_url()?>page_action"><i class="fa fa-backward"></i>Back</a>
      </div>
      <div class="row">
        <div class="col-md-6">
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
          <div class="tile">
            <h3 class="tile-title">Action Details</h3>
            <div class="tile-body">
                <?=form_open('page_action/add_page_action_process', 'class="form-horizontal"');?>
                <div class="form-group row">
                  <label class="control-label col-md-4">Module Name</label>
                  <div class="col-md-8">
                    <select class="form-control" name="module" id="module" onchange="get_page(this.value)">
                      <?php 
                        //print_r($module);
                        if (isset($module)) {
                      ?>
                          <option value="">---Select Module---</option>
                      <?php
                          foreach ($module as $row_module) 
                          {
                      ?>
                          <option value="<?=$row_module->moduleId?>" <?=(((isset($_POST['module']))? $_POST['module']:'')==$row_module->moduleId)? "selected":""?>><?=$row_module->moduleName?></option>
                      <?php
                          }
                        }
                        else
                        {
                      ?>
                            <option></option>
                      <?php
                        }
                      ?>
                    </select>
                    <?=form_error('module')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4">Page Name</label>
                  <div class="col-md-8">
                    <select class="form-control" name="page" id="page" onchange="get_action_seq(this.value)">
                      <option value="">---Select Page---</option>
                    </select>
                    <?=form_error('page')?>
                    <script type="text/javascript">
                       // $(document).ready(function(){

                       // });
                    </script>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4">Action Name</label>
                  <div class="col-md-8">
                    <select class="form-control" name="action_name" id="action_name">
                      <?php 
                        //print_r($module);
                        if (isset($actionLebel)) {
                      ?>
                          <option value="">---Select Action---</option>
                      <?php
                          foreach ($actionLebel as $row_actionLebel) 
                          {
                      ?>
                          <option value="<?=$row_actionLebel->actionId?>" <?=(((isset($_POST['action_name']))? $_POST['action_name']:'')==$row_actionLebel->actionId)? "selected":""?>><?=$row_actionLebel->actionName?></option>
                      <?php
                          }
                        }
                        else
                        {
                      ?>
                            <option></option>
                      <?php
                        }
                      ?>
                    </select>
                    <?=form_error('action_name')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4">Action link</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="action_link" placeholder="Enter Link..." value="<?=set_value('action_link')?>">
                    <?=form_error('action_link')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4">Action Sequence</label>
                  <div class="col-md-8">
                    <select class="form-control" name="action_seq" id="action_seq">
                      <option value="">---Select Sequence---</option>
                    </select>
                    <script type="text/javascript">
                       $(document).ready(function(){
                          var result=$('#module').val();
                          get_page(result,function(){
                            var page="<?=set_value('page')?>";
                            $('#page').val(page);
                            get_action_seq(page,function(){
                              var seq="<?=set_value('action_seq')?>";
                              $('#action_seq').val(seq);
                            }); 
                          });                   
                       });
                    </script>
                    <?=form_error('action_seq')?>
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
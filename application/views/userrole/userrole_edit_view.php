<style type="text/css">
  .action
  {
    margin-left: 10% !important;
    width: 90% !important;
  }
  .page
  {
    margin-left: 5% !important;
    width: 95% !important;
  }
  .list-group-item
  {
    border-radius: 0 !important;
  }
  .list-group{
    border:1px solid #009688;
    border-radius: 2px;
    background-color: #f4f4f4 !important;
  }
</style>
<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-plus-square"></i> Edit User Role</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?=base_url()?>userrole"><i class="fa fa-backward"></i>Back</a>
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
            <h3 class="tile-title">Edit User Role Details</h3>
            <div class="tile-body">
                
                <?php
                    if(count($role))
                    {
                ?>      
                        <?=form_open("userrole/edit_userrole_process/{$role['roleId']}", 'class="form-horizontal"');?>
                        <div class="form-group row">
                          <label class="control-label col-md-3">Role Title</label>
                          <div class="col-md-8">
                            <input class="form-control" type="text" name="role" value="<?=set_value('role',$role['role'])?>" placeholder="Enter full name" >
                          </div>
                        </div>
                <?php
                    }
                    foreach ($access as  $row_module) {

                      // echo "<pre>";
                      // print_r($row_module);
                ?>
                        <div class="bs-component">
                          <div class="list-group bg-light">
                            <a class="list-group-item list-group-item-action active" href="#"><?=$row_module['moduleName']?></a>
                            <input type="hidden" name="pageId[]">
                          <?php
                            foreach($row_module['page'] as $row_page)
                            {
                          ?> 
                            <a class="list-group-item list-group-item-action page" href="#"><input type="checkbox" name="pageId[]" value="<?=$row_page['pgId']?>" <?=(in_array($row_page['pgId'],$accesedPage)?"checked":"")?>>&nbsp;&nbsp;<?=$row_page['pageName']?></a>
                          <?php
                                foreach ($row_page['action'] as $row_action) {
                                  if(!empty($row_action['actionId']))
                                  {
                          ?>
                                    <a class="list-group-item list-group-item-action action" href="#"><input type="checkbox" name="actionId[]" value="<?=$row_action['id']?>" <?=(in_array($row_action['id'],$accesedAction)?"checked":"")?>>&nbsp;&nbsp;<?=$row_action['actionName']?></a>
                          <?php
                                  }
                                }
                            }
                          ?>

                          </div>
                        </div>
                        <br>
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
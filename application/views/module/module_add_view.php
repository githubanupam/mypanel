<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-plus-square"></i> Add Module</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?=base_url()?>module"><i class="fa fa-backward"></i>Back</a>
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
            <h3 class="tile-title">Module Details</h3>
            <div class="tile-body">
                <?=form_open('module/add_module_process', 'class="form-horizontal"');?>
                <div class="form-group row">
                  <label class="control-label col-md-4">Module Name</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="moduleName" placeholder="Enter full name" value="<?=set_value('moduleName')?>">
                    <?=form_error('moduleName')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4">Module Sequence</label>
                  <div class="col-md-8">
                    <select class="form-control" name="seq">
                      <?php 
                        if (isset($module_seq)) {
                      ?>
                          <option value="">---Select Sequence---</option>
                      <?php
                          foreach ($module_seq as $row_module) 
                          {
                      ?>
                          <option value="<?=$row_module->seq?>" <?=set_select('seq',$row_module->seq); ?>><?=$row_module->seq?></option>
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
                          <option value="<?=($row_module->seq+1)?>" <?=set_select('seq',($row_module->seq+1)); ?>><?=($row_module->seq+1)?></option>
                    </select>
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
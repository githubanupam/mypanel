<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-plus-square"></i> Edit Module</h1>
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
                
                <?php
                if(count($module_info))
                {
                ?>
                <?=form_open("module/edit_module_process/{$module_info['moduleId']}", 'class="form-horizontal"');?>
                <div class="form-group row">
                  <label class="control-label col-md-4">Module Name</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="moduleName" placeholder="Enter full name" value="<?=set_value('moduleName',$module_info['moduleName'])?>">
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
                          <option value="<?=$row_module->seq?>" <?=($row_module->seq==$module_info['seq'])? "selected":""?>><?=$row_module->seq?></option>
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
                    <input type="hidden" name="prev_seq" value="<?=$module_info['seq']?>">
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
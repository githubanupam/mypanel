<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-pencil-square-o"></i> Edit Usertype</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?=base_url()?>usertype"><i class="fa fa-backward"></i>Back</a>
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
            <h3 class="tile-title">Edit Usertype Details</h3>
            <div class="tile-body">
                
                <?php
                    if(count($usertype))
                    {
                ?>      
                        <?=form_open("usertype/edit_usertype_process/{$usertype['usertype_id']}", 'class="form-horizontal"');?>
                        <div class="form-group row">
                          <label class="control-label col-md-3">Full Name</label>
                          <div class="col-md-8">
                            <input class="form-control" type="text" name="f_name" value="<?=set_value('f_name',$usertype['f_name'])?>" placeholder="Enter full name" >
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-md-3">Short Name</label>
                          <div class="col-md-8">
                            <input class="form-control" type="text" name="s_name" value="<?=set_value('s_name',$usertype['s_name'])?>" placeholder="Enter short name">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-md-3">Usertype Order</label>
                          <div class="col-md-8">
                            <input class="form-control" type="text" name="type_order" value="<?=set_value('type_order',$usertype['type_order'])?>" placeholder="Enter post order">
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

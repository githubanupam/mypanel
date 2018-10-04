<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-plus-square"></i> Add Usertype</h1>
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
            <h3 class="tile-title">Register</h3>
            <div class="tile-body">
                <?=form_open('usertype/add_usertype_process', 'class="form-horizontal"');?>
                
                <div class="form-group row">
                  <label class="control-label col-md-5">Full Name</label>
                  <div class="col-md-7">
                    <input class="form-control" type="text" name="f_name" placeholder="Enter full name" value="<?=set_value('f_name')?>">
                    <?=form_error('f_name')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-5">Short Name</label>
                  <div class="col-md-7">
                    <input class="form-control" type="text" name="s_name" placeholder="Enter short name" value="<?=set_value('s_name')?>">
                    <?=form_error('s_name')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-5">User Type Order</label>
                  <div class="col-md-7">
                    <input class="form-control" type="text" name="type_order" placeholder="Enter usertype order" value="<?=set_value('type_order')?>">
                    <?=form_error('type_order')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-5">Is Superior</label>
                  <div class="col-md-7">
                    <select class="form-control" name="is_superior">
                      <option value="1">Yes</option>
                      <option value="0">No</option>
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
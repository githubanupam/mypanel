<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-plus-square"></i> Add Section</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?=base_url()?>section"><i class="fa fa-backward"></i>Back</a>
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
                <?=form_open('section/add_section_process', 'class="form-horizontal"');?>
                <div class="form-group row">
                  <label class="control-label col-md-3">Unit</label>
                  <div class="col-md-8">
                    <select class="form-control" name="unit">
                      <?php 
                        if (isset($units)) {
                      ?>
                          <option value="">---Select Unit---</option>
                      <?php
                          foreach ($units as $row_unit) 
                          {
                      ?>
                          <option value="<?php echo $row_unit->id.'|'.$row_unit->unit_shortname?>" <?php echo  set_select('unit', $row_unit->id.'|'.$row_unit->unit_shortname); ?>><?=$row_unit->unit_shortname?></option>
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
                    <?=form_error('unit')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-3">Full Name</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="sec_fullname" placeholder="Enter full name" value="<?=set_value('sec_shortunit')?>">
                    <?=form_error('sec_fullname')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-3">Short Name</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="sec_shortunit" placeholder="Enter short name" value="<?=set_value('sec_shortunit')?>">
                    <?=form_error('sec_shortunit')?>
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
<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-pencil-square-o"></i> Edit Section</h1>
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
            <h3 class="tile-title">Edit Section Details</h3>
            <div class="tile-body">
                
                <?php
                    if(count($section))
                    {
                ?>      
                        <?=form_open("section/edit_section_process/{$section['id']}", 'class="form-horizontal"');?>
                        <div class="form-group row">
                        <label class="control-label col-md-3">Unit</label>
                        <div class="col-md-8">
                          <select class="form-control" name="unit">
                            <?php 
                              if (isset($units)) {
                                foreach ($units as $row_unit) 
                                {
                            ?>
                                <option value="<?php echo $row_unit->id.'|'.$row_unit->unit_shortname?>" <?=($row_unit->id==$section['unit_id'])? "selected":""?>><?=$row_unit->unit_shortname?></option>
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
                        </div>
                      </div>
                        <div class="form-group row">
                          <label class="control-label col-md-3">Full Name</label>
                          <div class="col-md-8">
                            <input class="form-control" type="text" name="sec_fullname" value="<?=set_value('sec_fullname',$section['sec_fullname'])?>" placeholder="Enter full name" >
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-md-3">Short Name</label>
                          <div class="col-md-8">
                            <input class="form-control" type="text" name="sec_shortunit" value="<?=set_value('sec_shortunit',$section['sec_shortunit'])?>" placeholder="Enter short name" readonly>
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

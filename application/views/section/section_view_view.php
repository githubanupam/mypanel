<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-eye"></i> View Section</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?=base_url()?>section"><i class="fa fa-backward"></i>Back</a>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">View Section Details</h3>
            <hr>
            <div class="tile-body">
                
                <?php
                    if(count($section))
                    {
                ?>      
                        <?=form_open("section/edit_section_process/{$section['id']}", 'class="form-horizontal"');?>

                        <div class="form-group row">
                        <label class="control-label col-md-5">Unit</label>
                        <div class="col-md-7">:&nbsp;&nbsp;
                            <?php 
                              if (isset($units)) {
                                foreach ($units as $row_unit) 
                                {
                                  if($row_unit->id==$section['unit_id'])
                                  {
                                    echo $row_unit->unit_shortname;
                                  }
                                }
                              }
                              else
                              {
                                echo 'NA';
                              }
                            ?>
                        </div>
                      </div>
                        <div class="form-group row">
                          <label class="control-label col-md-5">Section Full Name</label>
                          <div class="col-md-7">
                            :&nbsp;&nbsp;<?=$section['sec_fullname']?>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-md-5">Section Short Name</label>
                          <div class="col-md-7">
                            :&nbsp;&nbsp;<?=$section['sec_shortunit']?>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-md-5">Section Latitude</label>
                          <div class="col-md-7">
                            :&nbsp;&nbsp;<?=$section['sec_lat']?>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="control-label col-md-5">Section Longitude</label>
                          <div class="col-md-7">
                            :&nbsp;&nbsp;<?=$section['sec_lon']?>
                          </div>
                        </div>

                <?php

                    }
                ?>
                <div class="tile-footer">
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="clearix"></div>
      </div>
    </main>
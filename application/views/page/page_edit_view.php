<script type="text/javascript">
  $(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
   
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="row"><div class="col-md-5"><select name="action[]" class="form-control"><option value="1">View</option><option value="2">Edit</option><option value="3">Delete</option></select></div><div class="col-md-5"><input type="text" name="action_name[]" class="form-control"/></div><div class="text-right col-md-2" style="margin: auto;"><i class="fa fa-2x fa-minus-circle remove_field text-danger"></i></div></div>'); //add input box
        }
    });
   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
    })
});
</script>
<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-plus-square"></i> Edit Page</h1>
        </div>
        <a class="btn btn-primary icon-btn" href="<?=base_url()?>page"><i class="fa fa-backward"></i>Back</a>
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
            <h3 class="tile-title">Page Details</h3>
            <div class="tile-body form-control">
                
                <?php
                if(count($page_info))
                {
                ?>
                <?=form_open("page/edit_page_process/{$page_info['pageId']}", 'class="form-horizontal"');?>
                <div class="form-group row">
                  <label class="control-label col-md-4">Module Name</label>
                  <div class="col-md-8">
                    <select class="form-control" name="moduleId" id="module" onchange="get_page_seq(this.value)">
                      <?php 
                        if (isset($module)) {
                      ?>
                          <option value="">---Select Module---</option>
                      <?php
                          foreach ($module as $row_module) 
                          {
                      ?>
                          <option value="<?=$row_module->moduleId?>" <?=($row_module->moduleId==$page_info['moduleId'])?"selected":""?>><?=$row_module->moduleName?></option>
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
                  <label class="control-label col-md-4">Page Name</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="pageName" placeholder="Enter full name" value="<?=set_value('pageName',$page_info['pageName'])?>">
                    <?=form_error('pageName')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4">File Name</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text" name="fileName" placeholder="Enter File Name..." value="<?=set_value('fileName',$page_info['fileName'])?>">
                    <?=form_error('fileName')?>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-4">Page Sequence</label>
                  <div class="col-md-8">
                    <select class="form-control" name="seq">
                      <?php 
                        if (isset($page_seq)) {
                      ?>
                          <option value="">---Select Sequence---</option>
                      <?php
                          foreach ($page_seq as $row_page) 
                          {
                      ?>
                          <option value="<?=$row_page->seq?>" <?=($row_page->seq==$page_info['seq'])? "selected":""?>><?=$row_page->seq?></option>
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
                    <input type="hidden" name="prev_seq" value="<?=$page_info['seq']?>">
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
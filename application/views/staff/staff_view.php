<main class="app-content">
   <div class="app-title">
      <div>
         <h1><i class="fa fa-eye"></i>View Employee</h1>
      </div>
      <a class="btn btn-primary icon-btn" href="<?=base_url()?>staff"><i class="fa fa-backward"></i>Back</a>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="tile">
            <h3 class="tile-title">Employee Details</h3>
            <hr>
            <?php if(count($staff_master)){?>
            <div class="card-body bg-light">
               <div class="row">
                  <div style="padding: 10px;box-sizing: border-box;" class="col-md-5 text-center">
                     <?php
                        if(isset($staff_master['emp_pic']) && !empty($staff_master['emp_pic']))
                        {
                                $image_path= $_SERVER['DOCUMENT_ROOT'].'/sanjog/assets/uploads/news/'.$staff_master['emp_pic'];
                                if(file_exists($image_path))
                                {
                        ?>
                     <a href="<?=base_url('assets/uploads/news/').$staff_master['emp_pic']?>" target="_blank"><img src="<?=base_url('assets/uploads/news/').$staff_master['emp_pic']?>" class="img-responsive" style="max-height:200px; width: 40%; border-radius: 50%"></a>
                     <?php
                        }
                        else
                        {
                        ?>
                     <a href="http://proconsultancies.org/wimages/icon-user-default.png" target="_blank"><img src="http://proconsultancies.org/wimages/icon-user-default.png"class="img-responsive" style="max-height:200px; width: 40%; border-radius: 50%"></a>
                     <?php
                        }
                        }
                        else
                        {
                        ?>
                     <a href="http://proconsultancies.org/wimages/icon-user-default.png" target="_blank"><img src="http://proconsultancies.org/wimages/icon-user-default.png" class="img-responsive" style="max-height:200px; width: 40%; border-radius: 50%"></a>
                     <?php
                        }
                        ?> 
                     <h4 class="text-primary"><?=$staff_master['emp_name']?></h4>
                     <h6 class="text-info"><?=DesignationName($staff_master['usertype_id'])?></h6>
                     <h6 class="text-info">
                     <?php
                     $access_stations=explode(',',$staff_master['access_stations']);
                     if (isset($access_stations)) {
                        foreach ($access_stations as $row_station) {
                     ?>
                           <span class="badge badge-primary"><?=psName($row_station)?></span>
                     <?php
                        }
                     }
                     ?></h6>
                     <h6 class="text-info"><span class="badge badge-dark"><?=divName($staff_master['emp_district'])?></span></h6>
                  </div>
                  <div style="padding: 10px;box-sizing: border-box;" class="col-md-7">
                     <div class="row">
                        <div class="col-md-3">
                           <b class="text-info">Name</b>
                        </div>
                        <div class="col-md-9">
                           <p class="text-info">
                           <?=strtoupper($staff_master['emp_name'])?>
                           </p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-3">
                           <b class="text-info">Guardian's Name</b>
                        </div>
                        <div class="col-md-9">
                           <p class="text-info">
                           <?=strtoupper($staff_master['emp_guardian_name'])?>
                           </p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-3">
                           <b class="text-info">Contact No.</b>
                        </div>
                        <div class="col-md-9">
                           <p class="text-info">
                           <?=strtoupper($staff_master['emp_contactno'])?>
                           </p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-3">
                           <b class="text-info">Email-id</b>
                        </div>
                        <div class="col-md-9">
                           <p class="text-info">
                           <?=($staff_master['emp_emailid'])? $staff_master['emp_emailid']:"-"?>
                           </p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-3">
                           <b class="text-info">Designation</b>
                        </div>
                        <div class="col-md-9">
                           <p class="text-info">
                           <?=strtoupper(DesignationName($staff_master['usertype_id']))?>
                           </p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-3">
                           <b class="text-info">Police station</b>
                        </div>
                        <div class="col-md-9">
                           <p class="text-info">
                           <?php
                           $access_stations=explode(',',$staff_master['access_stations']);
                           $lastElement=end($access_stations);
                           if (isset($access_stations)) {
                              foreach ($access_stations as $row_station) {
                                 if($lastElement!=$row_station)
                                 {
                           ?>
                                 <span><?=psName($row_station)?></span>,&nbsp;
                           <?php
                                 }
                           ?>
                                 <span><?=psName($row_station)?></span>
                           <?php
                              }
                           }
                           strtoupper($staff_master['emp_emailid'])?>
                           </p>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-3">
                           <b class="text-info">Police Division</b>
                        </div>
                        <div class="col-md-9">
                           <p class="text-info">
                           <?=divName($staff_master['emp_district'])?>
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <?php
            }
            ?>
         </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Score Details</h3>
            <?php
               $row_score=array();
               if(isset($staff_score))
               {
                    $row_score[]  = round($staff_score['info_score']);
                    $row_score[]  = round($staff_score['ren_person_score']);
                    $row_score[]  = round($staff_score['ren_person_score']);
                    $row_score[]  = round($staff_score['ren_person_score']);
                    $row_score[]  = round($staff_score['ren_person_score']);

            ?> 
                  <h4 class="text-center text-info">Total Score:<?=round($staff_score['total_score'])?></h4>
                  <div class="embed-responsive embed-responsive-16by9 bg-light">
                     <canvas class="embed-responsive-item" id="pieChartDemo"></canvas>
                  </div>
                  
            <?php
               }
               else
               {
            ?>
                  <h4 class="text-center text-info bg-light">Score Not Available <i class='fa fa-frown-o'></i><i class='fa fa-frown-o'></i><i class='fa fa-frown-o'></i></h4>
            <?php
               }
            ?>
          </div>
      </div>
   </div>
</main>
<script type="text/javascript">

   var ctxp = $("#pieChartDemo").get(0).getContext("2d");
   
   var information   =<?=isset($row_score[0])? $row_score[0]:0?>;
   var renewed       =<?=isset($row_score[1])? $row_score[1]:0?>;
   var criminal      =<?=isset($row_score[2])? $row_score[2]:0?>;
   var place         =<?=isset($row_score[3])? $row_score[3]:0?>;
   var incident      =<?=isset($row_score[4])? $row_score[4]:0?>;
   
   var chart = new Chart(ctxp, {
       type: 'pie',
       data: {
        labels: ["Information", "Renewed", "Criminal", "Place", "Incident"],
        datasets: [{
            label: "My First dataset",
            backgroundColor:[
                              'rgba(23, 162, 184,0.7)',
                              'rgba(40, 167, 69, 0.7)',
                              'rgba(220, 53, 69, 0.7)',
                              'rgba(0, 150, 136, 0.7)',
                              'rgba(255, 193, 7, 0.7)'
                           ],
            borderColor:   [
                              'rgba(23, 162, 184, 1)',
                              'rgba(40, 167, 69, 1)',
                              'rgba(220, 53, 69, 1)',
                              'rgba(0, 150, 136, 1)',
                              'rgba(255, 193, 7, 1)'
                           ],

            data: [information,renewed,criminal,place,incident]
        }]
    },
       options: {
           title: {
               display: true,
               text: 'Score Chart',
               position:'bottom'
           }
       }
   })
</script>


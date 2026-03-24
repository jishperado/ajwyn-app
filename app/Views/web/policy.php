<?= $this->extend('web/layout_new') ?>
<?= $this->section('content') ?>



 <div class="cmtop"></div>
  

 <div class="container-fluid cmpad cartmanager" style="margin-top: 20px;">
    <div class="row" >
          <div class="col-md-12">
             <div class="items" style="border: solid 1px; border-color: #e9e5e5; margin-top: 10px;">
              <div class="product" style="border: 2px dotted; border-color: #e9e5e5; margin-top: 10px; margin: 10px;">
                       <h5 style="margin-left: 10px; margin-top: 0px; margin-bottom: 0px; font-weight: 600; color: #333333;">
                  <i class="" aria-hidden="true"></i> <?= $policy->head ?>
                </h5>
                <div style="padding:10px;">
                  <?= $policy->content ?>
                </div>
              </div>
            </div>
          </div><!-- col-md-12 -->
    </div>
</div>

  <?= $this->endSection() ?>



  <!-----workspace end----->
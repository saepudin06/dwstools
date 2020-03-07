<?php $this->load->view('home/header.php'); ?>

<div class="flex-1" style="background: url(img/svg/pattern-1.svg) no-repeat center bottom fixed; background-size: cover;">
    <div class="container py-4 py-lg-5 my-lg-5 px-4 px-sm-0">

    <?php
            $ci = & get_instance();
            $ci->load->model('administration/modules');
            $tModules = $ci->modules;

            $modules = $tModules->getHomeModules($this->session->userdata('user_id'));
    ?>

    <div class="row">
        <?php foreach($modules as $module): ?>
        <div class="col-sm-3 col-xl-3">
            <a href="<?php echo base_url().'panel?module_id='.$module['module_id'];?>">
            <div class="<?php echo 'p-3 '.($module['module_class'] == "" ? 'bg-fusion-200' : $module['module_class']).' rounded overflow-hidden position-relative text-white mb-g'; ?>">
                <div class="">
                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                        <?php 
                            if ($module['module_title'] != ""){
                                echo $module['module_title'];    
                            } else {
                                echo "&nbsp;";
                            }
                        ?>
                        <small class="m-0 l-h-n"><?php echo $module['module_name']; ?></small>
                    </h3>
                </div>
                <i class="<?php echo $module['module_icon'].' position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1'; ?>" style="font-size:6rem"></i>
            </div>
            </a>
        </div>
        <?php endforeach; ?>
        <!-- <div class="col-sm-3 col-xl-3"> fal fa-user bg-primary-300
            <div class="p-3 bg-warning-400 rounded overflow-hidden position-relative text-white mb-g">
                <div class="">
                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                        $10,203
                        <small class="m-0 l-h-n">Visual Index Figure</small>
                    </h3>
                </div>
                <i class="fal fa-gem position-absolute pos-right pos-bottom opacity-15  mb-n1 mr-n4" style="font-size: 6rem;"></i>
            </div>
        </div>
        <div class="col-sm-3 col-xl-3">
            <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g">
                <div class="">
                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                        - 103.72
                        <small class="m-0 l-h-n">Offset Balance Ratio</small>
                    </h3>
                </div>
                <i class="fal fa-lightbulb position-absolute pos-right pos-bottom opacity-15 mb-n5 mr-n6" style="font-size: 8rem;"></i>
            </div>
        </div>
        <div class="col-sm-3 col-xl-3">
            <div class="p-3 bg-info-200 rounded overflow-hidden position-relative text-white mb-g">
                <div class="">
                    <h3 class="display-4 d-block l-h-n m-0 fw-500">
                        +40%
                        <small class="m-0 l-h-n">Product level increase</small>
                    </h3>
                </div>
                <i class="fal fa-globe position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n4" style="font-size: 6rem;"></i>
            </div>
        </div> -->
    </div>

    </div>
</div>

<!-- <div class="row">
    <div class="col-md-10 col-md-offset-1">
        <?php
                $ci = & get_instance();
                $ci->load->model('administration/modules');
                $tModules = $ci->modules;

                $modules = $tModules->getHomeModules($this->session->userdata('user_id'));
        ?>

        <div class="rows">
        <?php foreach($modules as $module): ?>
            <div class="col-xs-6 col-md-3">
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <?php echo $module['module_name']; ?>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <a href="<?php echo base_url().'panel?module_id='.$module['module_id'];?>">
                            <img class="img-app" src="<?php echo $module['module_icon']; ?>">
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

    </div>
</div> -->
<?php $this->load->view('home/footer.php'); ?>
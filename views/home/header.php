<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8">
        <title>
            DWS Tools
        </title>
        <meta name="description" content="Login">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
        <!-- Call App Mode on ios devices -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <!-- Remove Tap Highlight on Windows Phone IE -->
        <meta name="msapplication-tap-highlight" content="no">
        <!-- base css -->
        <link rel="stylesheet" media="screen, print" href="<?php echo base_url();?>assets/css/vendors.bundle.css">
        <link rel="stylesheet" media="screen, print" href="<?php echo base_url();?>assets/css/app.bundle.css">
        <!-- Place favicon.ico in the root directory -->
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url();?>assets/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url();?>assets/img/favicon/favicon-32x32.png">
        <link rel="mask-icon" href="<?php echo base_url();?>assets/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <!-- Optional: page related CSS-->
        <link rel="stylesheet" media="screen, print" href="<?php echo base_url();?>assets/css/fa-brands.css">
    </head>
    <!-- END HEAD -->
    <body>
        <div class="page-wrapper">
            <div class="page-inner bg-brand-gradient">
                <div class="page-content-wrapper bg-transparent m-0">
                    <div class="height-10 w-100 shadow-lg px-4 bg-brand-gradient">
                        <div class="d-flex align-items-center container p-0">
                            <div class="page-logo width-mobile-auto m-0 align-items-center justify-content-center p-0 bg-transparent bg-img-none shadow-0 height-9">
                                <a href="javascript:void(0)" class="page-logo-link press-scale-down d-flex align-items-center">
                                    <img src="<?php echo base_url();?>assets/img/logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
                                    <span class="page-logo-text mr-1">DWS Tools</span>
                                </a>
                            </div>
                            <!-- <a href="page_register.html" class="btn-link text-white ml-auto">
                                Create Account
                            </a> -->
                            <div class="btn-group text-white ml-auto" role="group">
                                <button id="btnGroupVerticalDrop1" type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Welcome, <?php echo $this->session->userdata('user_full_name'); ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                    <!-- <a class="dropdown-item" href="javascript:;" id="my-profile"><i class="fal fa-user"></i> My Profile</a> -->
                                    <a class="dropdown-item" href="<?php echo base_url().'auth/logout'; ?>"><i class="fal fa-share"></i> Log Out</a>
                                </div>
                            </div>
                        </div>
                    </div>


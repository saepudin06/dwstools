<!--[if lt IE 9]>
<script src="<?php echo base_url(); ?>assets/global/plugins/respond.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/excanvas.min.js"></script>
<![endif]-->

<!-- BEGIN CORE PLUGINS -->
<script src="<?php echo base_url();?>assets/js/vendors.bundle.js"></script>
<script src="<?php echo base_url();?>assets/js/app.bundle.js"></script>

<!-- The order of scripts is irrelevant. Please check out the plugin pages for more details about these plugins below: -->
<script src="<?php echo base_url();?>assets/js/dependency/moment/moment.js"></script>
<script src="<?php echo base_url();?>assets/js/miscellaneous/fullcalendar/fullcalendar.bundle.js"></script>
<script src="<?php echo base_url();?>assets/js/statistics/sparkline/sparkline.bundle.js"></script>
<script src="<?php echo base_url();?>assets/js/statistics/easypiechart/easypiechart.bundle.js"></script>
<script src="<?php echo base_url();?>assets/js/statistics/flot/flot.bundle.js"></script>
<script src="<?php echo base_url();?>assets/js/miscellaneous/jqvmap/jqvmap.bundle.js"></script>

<script src="<?php echo base_url(); ?>assets/js/notifications/sweetalert2/sweetalert2.bundle.js"></script>
<!-- begin jqgrid -->
<script src="<?php echo base_url(); ?>assets/jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jqgrid/src/jquery.jqGrid.js" type="text/javascript"></script>


<script src="<?php echo base_url(); ?>assets/bootgrid/jquery.bootgrid.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/jqgrid.function.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.blockUI.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>jqwidgets/jqxcore.js"></script>
<script src="<?php echo base_url(); ?>jqwidgets/jqxbuttons.js"></script>
<script src="<?php echo base_url(); ?>jqwidgets/jqxscrollbar.js"></script>
<script src="<?php echo base_url(); ?>jqwidgets/jqxpanel.js"></script>
<script src="<?php echo base_url(); ?>jqwidgets/jqxtree.js"></script>
<script src="<?php echo base_url(); ?>jqwidgets/jqxcheckbox.js"></script>
<script src="<?php echo base_url(); ?>jqwidgets/jqxdata.js"></script>

<script src="<?php echo base_url(); ?>assets/js/jquery.number.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/datagrid/datatables/datatables.bundle.js"></script>


<script type="text/javascript">
    $(document).ready(function () {
        // Ajax setup csrf token.
        var csfrData = {};
        csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
        $.ajaxSetup({
            data: csfrData,
            cache: false
        });
   });

    $(document).ajaxStart(function () {
        $(document).ajaxStart($.blockUI({
            message:  'Loading...',
            css: {
                border: 'none',
                padding: '5px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .6,
                color: '#fff'
            }

        })).ajaxStop($.unblockUI);
    });

    function loadContentWithParams(id, params) {
        $.ajax({
            url: "<?php echo base_url().'home/load_content/'; ?>" + id,
            type: "POST",
            data: params,
            success: function (data) {
                $( "#main-content" ).html( data );
            },
            error: function (xhr, status, error) {
                swal.fire({title: "Error!", text: xhr.responseText, html: true, type: "error"});
            }
        });
        return;
    }

    $(".nav-item").on('click', function(){
        var nav = $(this).attr('data-source');

        if(!nav){

        }else{
            var menu_id = $(this).attr('menu-id');
            $(".nav-item").removeClass("active");

            $(this).addClass("active");
            $(this).parent("ul").parent("li").addClass("active");

            loadContentWithParams(nav,{menu_id:menu_id});
        }

    });

    loadContentWithParams('dashboard',{});

    $("#my-profile").click(function(event){
        event.stopPropagation();
        loadContentWithParams('profile',{});
    });
   
    $.jgrid.defaults.responsive = true;
    $.jgrid.defaults.styleUI = 'Bootstrap';
    jQuery.fn.center = function () {

        if(this.width() > $(window).width()) {
            this.css("width", $(window).width()-40);
        }
        this.css("top",($(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
        this.css("left",( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");

        return this;
    }


</script>

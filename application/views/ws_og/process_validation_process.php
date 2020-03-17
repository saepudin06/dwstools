<!-- breadcrumb -->
<?php echo breadCrumbs(getVarClean('menu_id', 'str', '0')); ?>
<!-- end breadcrumb -->

<div class="panel">
    <div class="panel-hdr">
        <h2>
            <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" id="tab-1"><i class="fal fa-cog mr-1"></i> Batch Control</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="javascript:;" role="tab" id="tab-2"><i class="fal fa-indent mr-1"></i> Process</a>
                </li>
            </ul>
        </h2>
        <div class="panel-toolbar">
            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
            <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button>
        </div>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="frame-wrap m-0">
                <div class="row">
                    <div class="col-md-12 grid-ui">
                        <input type="hidden" id="is_set_grid" value="false">
                        <table id="grid-table"></table>
                        <div id="grid-pager"></div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-12 grid-ui">
                        <input type="hidden" id="is_set_grid_log" value="false">
                        <table id="grid-table-log"></table>
                        <div id="grid-pager-log"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<script>
    $("#tab-1").on("click", function(event) {
        event.stopPropagation();
        loadContentWithParams("ws_og.process_validation_batch_control", {
            menu_id: "<?php echo getVarClean('menu_id', 'str', '0'); ?>"
        });
    });
</script>
<script>

    jQuery(function($) {
        set_grid();
    });

    function reload_grid(grid_selector, postData){
        if (postData === null){
            $(grid_selector).jqGrid('setGridParam', {
                dataType: "json"
            }).trigger("reloadGrid", [{ current: true }]);    
        } else {
            $(grid_selector).jqGrid('setGridParam', {
                dataType: "json",
                postData: postData,
            }).trigger("reloadGrid", [{ current: true }]);   
        }
    }

    function set_grid() {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";
        var is_set_grid = $('#is_set_grid');

        if (is_set_grid.val() == "false"){
            is_set_grid.val("true");

            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."ws_og.process_validation_process_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: {input_data_control_id: '<?php echo $this->input->post('input_data_control_id'); ?>'},
                colModel: [
                    {label: 'ID', name: 'job_control_id', key: true, width: 75, align: 'center', sorttype: 'number', hidden: true},
                    {label: 'Proses', name: 'job_code', width: 150, align: "left"},
                    {label: 'Status', name: 'status_list_code', width: 150, align: 'center', sorttype: 'number'},
                    {label: 'User',name: 'operator_id', width: 150, align: "center"},
                    {label: 'Mulai',name: 'start_process_date', width: 200, align: "center"},
                    {label: 'Selesai',name: 'end_process_date', width: 200, align: "center"}
                ],
                height: '100%',
                autowidth: true,
                viewrecords: true,
                rowNum: 10,
                rowList: [10,20,50],
                rownumbers: true, // show row numbers
                rownumWidth: 35, // the width of the row numbers columns
                altRows: true,
                shrinkToFit: true,
                multiboxonly: true,
                onSelectRow: function (rowid) {
                    /*do something when selected*/
                    set_grid_log();
                },
                sortorder:'',
                pager: pager_selector,
                jsonReader: {
                    root: 'rows',
                    id: 'id',
                    repeatitems: false
                },
                loadComplete: function (response) {
                    if(response.success == false) {
                        swal.fire({title: 'Attention', text: response.message, html: true, type: "warning"});
                    } else {
                        if (response.rows.length > 0){
                            jQuery(grid_selector).jqGrid('setSelection', response.rows[0].job_control_id);
                        }
                    }

                    if ('<?php echo $this->input->post('code_status'); ?>' == 'CLOSE'){
                        $(pager_selector + '_left #btn-submit-job').hide();
                        $(pager_selector + '_left #btn-cancel-all-job').hide();
                        $(pager_selector + '_left #btn-cancel-last-job').hide();
                    }

                    responsive_jqgrid(grid_selector, pager_selector);

                },
                //memanggil controller jqgrid yang ada di controller crud
                editurl: '<?php echo WS_JQGRID."ws_og.process_validation_process_controller/crud"; ?>',
                caption: "Daftar Proses"

            });

            jQuery(grid_selector).jqGrid('navGrid', pager_selector,
                {   //navbar options
                    edit: false,
                    editicon: 'fal fa-pencil green',
                    add: false,
                    addicon: 'fal fa-plus-circle blue',
                    del: false,
                    delicon: 'fal fa-trash-alt red',
                    search: true,
                    searchicon: 'fal fa-search orange',
                    refresh: true,
                    afterRefresh: function () {
                        // some code here
                        // jQuery("#detailsPlaceholder").hide();
                    },

                    refreshicon: 'fal fa-repeat-alt orange',
                    view: false,
                    viewicon: 'fal fa-search-plus orange'
                },

                {
                    // options for the Edit Dialog
                    closeAfterEdit: true,
                    closeOnEscape:true,
                    recreateForm: true,
                    serializeEditData: serializeJSON,
                    width: 'auto',
                    errorTextFormat: function (data) {
                        return 'Error: ' + data.responseText
                    },
                    beforeShowForm: function (e, form) {
                        var form = $(e[0]);
                        style_edit_form(form);

                    },
                    afterShowForm: function(form) {
                        form.closest('.ui-jqdialog').center();
                    },
                    afterSubmit:function(response,postdata) {
                        var response = jQuery.parseJSON(response.responseText);
                        if(response.success == false) {
                            return [false,response.message,response.responseText];
                        }
                        return [true,"",response.responseText];
                    }
                },
                {
                    //new record form
                    closeAfterAdd: false,
                    clearAfterAdd : true,
                    closeOnEscape:true,
                    recreateForm: true,
                    width: 'auto',
                    errorTextFormat: function (data) {
                        return 'Error: ' + data.responseText
                    },
                    serializeEditData: serializeJSON,
                    viewPagerButtons: false,
                    beforeShowForm: function (e, form) {
                        var form = $(e[0]);
                        style_edit_form(form);
                    },
                    afterShowForm: function(form) {
                        form.closest('.ui-jqdialog').center();
                    },
                    afterSubmit:function(response,postdata) {
                        var response = jQuery.parseJSON(response.responseText);
                        if(response.success == false) {
                            return [false,response.message,response.responseText];
                        }

                        $(".tinfo").html('<div class="ui-state-success">' + response.message + '</div>');
                        var tinfoel = $(".tinfo").show();
                        tinfoel.delay(3000).fadeOut();


                        return [true,"",response.responseText];
                    }
                },
                {
                    //delete record form
                    serializeDelData: serializeJSON,
                    recreateForm: true,
                    beforeShowForm: function (e) {
                        var form = $(e[0]);
                        style_delete_form(form);

                    },
                    afterShowForm: function(form) {
                        form.closest('.ui-jqdialog').center();
                    },
                    onClick: function (e) {
                        //alert(1);
                    },
                    afterSubmit:function(response,postdata) {
                        var response = jQuery.parseJSON(response.responseText);
                        if(response.success == false) {
                            return [false,response.message,response.responseText];
                        }
                        return [true,"",response.responseText];
                    }
                },
                {
                    //search form
                    closeAfterSearch: false,
                    recreateForm: true,
                    afterShowSearch: function (e) {
                        var form = $(e[0]);
                        style_search_form(form);
                        form.closest('.ui-jqdialog').center();
                    },
                    afterRedraw: function () {
                        style_search_filters($(this));
                    }
                },
                {
                    //view record form
                    recreateForm: true,
                    beforeShowForm: function (e) {
                        var form = $(e[0]);
                    }
                }
            ).navButtonAdd(pager_selector,{
                caption:"", //Submit Job
                buttonicon:"fal fa-cog blue",
                title: "Submit Job",
                onClickButton: submit_job,
                // cursor: "pointer",
                // position: "first",
                id :"btn-submit-job",
            }).navButtonAdd(pager_selector,{
                caption:"", //Submit Job
                buttonicon:"fal fa-times-circle purple",
                title: "Cancel All Job",
                onClickButton: cancel_all_job,
                // cursor: "pointer",
                // position: "first",
                id :"btn-cancel-all-job",
            }).navButtonAdd(pager_selector,{
                caption:"", //Submit Job
                buttonicon:"fal fa-minus-circle",
                title: "Cancel Last Job",
                onClickButton: cancel_last_job,
                // cursor: "pointer",
                // position: "first",
                id :"btn-cancel-last-job",
            });
        } else {
            reload_grid(grid_selector, null);
        }
    }

    function set_grid_log() {
        var grid_selector = "#grid-table-log";
        var pager_selector = "#grid-pager-log";
        var is_set_grid = $('#is_set_grid_log');

        if (is_set_grid.val() == "false"){
            is_set_grid.val("true");

            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."ws_og.process_validation_process_controller/read_log"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: {job_control_id: get_selected_grid("#grid-table", "job_control_id")},
                colModel: [
                    {label: 'ID', name: 'job_control_id', key: true, width: 75, align: 'center', sorttype: 'number', hidden: true},
                    {label: 'No', name: 'counter_no', width: 30, align: 'center', sorttype: 'number'},
                    {label: 'Time', name: 'log_date', width: 200, align: "left"},
                    {label: 'Message', name: 'log_message', width: 300, align: 'left'}
                ],
                height: '100%',
                autowidth: true,
                viewrecords: true,
                rowNum: 10,
                rowList: [10,20,50],
                rownumbers: false, // show row numbers
                rownumWidth: 35, // the width of the row numbers columns
                altRows: true,
                shrinkToFit: true,
                multiboxonly: true,
                onSelectRow: function (rowid) {
                    /*do something when selected*/
                },
                sortorder:'',
                pager: pager_selector,
                jsonReader: {
                    root: 'rows',
                    id: 'id',
                    repeatitems: false
                },
                loadComplete: function (response) {
                    if(response.success == false) {
                        swal.fire({title: 'Attention', text: response.message, html: true, type: "warning"});
                    }

                    responsive_jqgrid(grid_selector, pager_selector);

                },
                //memanggil controller jqgrid yang ada di controller crud
                editurl: '<?php echo WS_JQGRID."ws_og.process_validation_process_controller/read_log"; ?>',
                caption: "Log Proses"

            });

            jQuery(grid_selector).jqGrid('navGrid', pager_selector,
                {   //navbar options
                    edit: false,
                    editicon: 'fal fa-pencil green',
                    add: false,
                    addicon: 'fal fa-plus-circle blue',
                    del: false,
                    delicon: 'fal fa-trash-alt red',
                    search: true,
                    searchicon: 'fal fa-search orange',
                    refresh: true,
                    afterRefresh: function () {
                        // some code here
                        // jQuery("#detailsPlaceholder").hide();
                    },

                    refreshicon: 'fal fa-repeat-alt orange',
                    view: false,
                    viewicon: 'fal fa-search-plus orange'
                },

                {
                    // options for the Edit Dialog
                    closeAfterEdit: true,
                    closeOnEscape:true,
                    recreateForm: true,
                    serializeEditData: serializeJSON,
                    width: 'auto',
                    errorTextFormat: function (data) {
                        return 'Error: ' + data.responseText
                    },
                    beforeShowForm: function (e, form) {
                        var form = $(e[0]);
                        style_edit_form(form);

                    },
                    afterShowForm: function(form) {
                        form.closest('.ui-jqdialog').center();
                    },
                    afterSubmit:function(response,postdata) {
                        var response = jQuery.parseJSON(response.responseText);
                        if(response.success == false) {
                            return [false,response.message,response.responseText];
                        }
                        return [true,"",response.responseText];
                    }
                },
                {
                    //new record form
                    closeAfterAdd: false,
                    clearAfterAdd : true,
                    closeOnEscape:true,
                    recreateForm: true,
                    width: 'auto',
                    errorTextFormat: function (data) {
                        return 'Error: ' + data.responseText
                    },
                    serializeEditData: serializeJSON,
                    viewPagerButtons: false,
                    beforeShowForm: function (e, form) {
                        var form = $(e[0]);
                        style_edit_form(form);
                    },
                    afterShowForm: function(form) {
                        form.closest('.ui-jqdialog').center();
                    },
                    afterSubmit:function(response,postdata) {
                        var response = jQuery.parseJSON(response.responseText);
                        if(response.success == false) {
                            return [false,response.message,response.responseText];
                        }

                        $(".tinfo").html('<div class="ui-state-success">' + response.message + '</div>');
                        var tinfoel = $(".tinfo").show();
                        tinfoel.delay(3000).fadeOut();


                        return [true,"",response.responseText];
                    }
                },
                {
                    //delete record form
                    serializeDelData: serializeJSON,
                    recreateForm: true,
                    beforeShowForm: function (e) {
                        var form = $(e[0]);
                        style_delete_form(form);

                    },
                    afterShowForm: function(form) {
                        form.closest('.ui-jqdialog').center();
                    },
                    onClick: function (e) {
                        //alert(1);
                    },
                    afterSubmit:function(response,postdata) {
                        var response = jQuery.parseJSON(response.responseText);
                        if(response.success == false) {
                            return [false,response.message,response.responseText];
                        }
                        return [true,"",response.responseText];
                    }
                },
                {
                    //search form
                    closeAfterSearch: false,
                    recreateForm: true,
                    afterShowSearch: function (e) {
                        var form = $(e[0]);
                        style_search_form(form);
                        form.closest('.ui-jqdialog').center();
                    },
                    afterRedraw: function () {
                        style_search_filters($(this));
                    }
                },
                {
                    //view record form
                    recreateForm: true,
                    beforeShowForm: function (e) {
                        var form = $(e[0]);
                    }
                }
            );
        } else {
            reload_grid(grid_selector, {job_control_id: get_selected_grid("#grid-table", "job_control_id")});
        }
    }

    function responsive_jqgrid(grid_selector, pager_selector) {

        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid( 'setGridWidth', $(".grid-ui").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }

    function get_selected_grid(grid_selector, field_selector){
        var grid = $(grid_selector);
        var rowid = grid.jqGrid ('getGridParam', 'selrow');
        var selected = grid.jqGrid ('getCell', rowid, field_selector);
        return selected;
    }

    $('.header-btn').on('click', function(){
        setTimeout(function(){
              responsive_jqgrid('#grid-table', '#grid-pager');
        },500); 
    });

    function submit_job() {
        Swal.fire({
            title: "Attention",
            text: 'Submit job?',
            type: "info",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Yes",
            confirmButtonColor: "#00a65a",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
        }).then((result) => {
            if (result.value) {
                var var_url = "<?php echo WS_JQGRID."ws_og.process_validation_process_controller/submit_job"; ?>";
                $.ajax({
                  url: var_url ,
                  type: "POST",
                  dataType: "json",
                  data: {data_type: '<?php echo $this->input->post('code'); ?>', input_data_control_id: '<?php echo $this->input->post('input_data_control_id'); ?>'},
                  async: false,
                  success: function (data) {
                    if (data.success){
                        Swal.fire({title: "Success", text: data.message, type: "success"});
                    } else {
                        Swal.fire({title: "Error", text: data.message, type: "error"});
                    }
                    set_grid();
                  },
                  error: function (xhr, status, error) {
                    Swal.fire({title: "Error!", text: get_error_txt(xhr, status, error), html: true, type: "error"});
                  }
                });
              }
        });
    }

    function cancel_all_job() {
        Swal.fire({
            title: "Attention",
            text: 'Cancel All job?',
            type: "info",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Yes",
            confirmButtonColor: "#00a65a",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
        }).then((result) => {
            if (result.value) {
                var var_url = "<?php echo WS_JQGRID."ws_og.process_validation_process_controller/cancel_all_job"; ?>";
                $.ajax({
                  url: var_url ,
                  type: "POST",
                  dataType: "json",
                  data: {input_data_control_id: '<?php echo $this->input->post('input_data_control_id'); ?>'},
                  async: false,
                  success: function (data) {
                    if (data.success){
                        Swal.fire({title: "Success", text: data.message, type: "success"});
                    } else {
                        Swal.fire({title: "Error", text: data.message, type: "error"});
                    }
                    set_grid();
                  },
                  error: function (xhr, status, error) {
                    Swal.fire({title: "Error!", text: get_error_txt(xhr, status, error), html: true, type: "error"});
                  }
                });
              }
        });
    }

    function cancel_last_job() {
        Swal.fire({
            title: "Attention",
            text: 'Cancel Last job?',
            type: "info",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonText: "Yes",
            confirmButtonColor: "#00a65a",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
        }).then((result) => {
            if (result.value) {
                var var_url = "<?php echo WS_JQGRID."ws_og.process_validation_process_controller/cancel_last_job"; ?>";
                $.ajax({
                  url: var_url ,
                  type: "POST",
                  dataType: "json",
                  data: {input_data_control_id: '<?php echo $this->input->post('input_data_control_id'); ?>'},
                  async: false,
                  success: function (data) {
                    if (data.success){
                        Swal.fire({title: "Success", text: data.message, type: "success"});
                    } else {
                        Swal.fire({title: "Error", text: data.message, type: "error"});
                    }
                    set_grid();
                  },
                  error: function (xhr, status, error) {
                    Swal.fire({title: "Error!", text: get_error_txt(xhr, status, error), html: true, type: "error"});
                  }
                });
              }
        });
    }

</script>
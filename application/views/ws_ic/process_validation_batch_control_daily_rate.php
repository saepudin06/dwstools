<!-- breadcrumb -->
<?php echo breadCrumbs(getVarClean('menu_id', 'str', '0')); ?>
<!-- end breadcrumb -->

<div class="panel">
    <div class="panel-hdr">
        <h2>
            <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="javascript:;" role="tab" id="tab-1"><i class="fal fa-cog mr-1"></i> Batch Control</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" id="tab-2"><i class="fal fa-indent mr-1"></i> Process</a>
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
                        <input type="hidden" id="selected_finance_period" value="">
                        <table id="grid-table"></table>
                        <div id="grid-pager"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<script>
    $("#tab-2").on("click", function(event) {
        var input_data_control_id = get_selected_grid("#grid-table", 'input_data_control_id');
        event.stopPropagation();

        if(input_data_control_id == null) {
            swal.fire('Informasi','Silahkan pilih salah satu batch process','info');
            return false;
        }

        loadContentWithParams("ws_ic.process_validation_process_daily_rate", {
            input_data_control_id: input_data_control_id,
            code: get_selected_grid("#grid-table", 'code'),
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

    var edit_options_finance_period = function(p_year_period_id) {
        $('#tr_p_finance_period_id .DataTD').empty();
        var var_url = "<?php echo WS_JQGRID.'ws_ic.process_validation_batch_controller/html_select_options_finance_periode'; ?>";
        $.ajax({
          url: var_url ,
          type: "POST",
          dataType: "json",
          data: {p_year_period_id: p_year_period_id, selected: $('#selected_finance_period').val()},
          async: false,
          success: function (data) {
            if (data.success){
                $('#tr_p_finance_period_id .DataTD').html("&nbsp;"+data.select);
                $('#selected_finance_period').val('');
            } else {
                Swal.fire({title: "Error!", text: data.message, html: true, type: "error"});    
            }
          },
          error: function (xhr, status, error) {
            Swal.fire({title: "Error!", text: get_error_txt(xhr, status, error), html: true, type: "error"});
          }
        });
    }

    function set_grid() {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";
        var is_set_grid = $('#is_set_grid');

        if (is_set_grid.val() == "false"){
            is_set_grid.val("true");

            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."ws_ic.process_validation_batch_daily_rate_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                colModel: [
                    {label: 'ID', name: 'input_data_control_id', key: true, width: 75, align: 'center', sorttype: 'number', editable: true},
                    {label: 'Parameter', name: 'parameters', width: 75 },
                    {label: 'Batch', name: 'input_file_name', width: 300 },
                    {label: 'Code', name: 'code', width: 150, align: 'center', sorttype: 'number', search: false },
                    {label: 'Year', name: 'temp_p_finance_period_id', width: 100, align: "center", editable: true, hidden: true },
                    {label: 'Year', name: 'p_year_period_id', width: 100, align: "center", editable: true, hidden: true,
                        editrules: {edithidden: true, required:false},
                        edittype: 'select',
                        editoptions: {
                            dataUrl: "<?php echo WS_JQGRID.'ws_ic.process_validation_batch_controller/html_select_options_year_periode'; ?>",
                            dataInit: function(elem) {
                                $(elem).width(250);  // set the width which you need
                            },
                            // postData : {
                                // role_id : function() {
                                //     return <?php //echo $this->input->post('role_id'); ?>;
                                // },
                            // },
                            buildSelect: function (data) {
                                var response = "";
                                try {
                                    response = $.parseJSON(data);
                                    if(response.success == false) {
                                        swal({title: 'Attention', text: response.message, html: true, type: "warning"});
                                        return "";
                                    } else {
                                        edit_options_finance_period(response.default_value);
                                        return response.select;
                                    }
                                } catch (err) {
                                    return data;
                                }
                            },
                            dataEvents: [
                                { 
                                    type: 'change', fn: function(e) {
                                        var selected = $(this).val();
                                        edit_options_finance_period(selected);
                                    }
                                },
                            ]
                        }
                    },
                    {label: 'Finance Periode', name: 'p_finance_period_id', width: 100, align: "center", editable: true, hidden: true,
                        editrules: {edithidden: true, required:false},
                        edittype: 'select',
                        editoptions: {}
                    },
                    {label: 'Finish',name: 'is_finish_processed', width: 150, align: "center"},
                    {label: 'Status',name: 'status_code', width: 150, align: "center"}
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
                editurl: '<?php echo WS_JQGRID."ws_ic.process_validation_batch_daily_rate_controller/crud"; ?>',
                caption: "File"

            });

            jQuery(grid_selector).jqGrid('navGrid', pager_selector,
                {   //navbar options
                    edit: true,
                    editicon: 'fal fa-pencil green',
                    add: true,
                    addicon: 'fal fa-plus-circle blue',
                    del: true,
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
                    closeAfterEdit: false,
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
                        $('#tr_parameters',form).hide();
                        $('#tr_input_data_control_id',form).hide();
                        $('#selected_finance_period').val($('#tr_temp_p_finance_period_id #temp_p_finance_period_id', form).val());
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
                        $('#tr_parameters',form).hide();
                        $('#tr_input_data_control_id',form).hide();
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
            reload_grid(grid_selector, null);
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

</script>
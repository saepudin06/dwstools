<!-- breadcrumb -->
<?php echo breadCrumbs(getVarClean('menu_id', 'str', '0')); ?>
<!-- end breadcrumb -->
<div class="panel">
    <div class="panel-hdr">
        <h2>
            <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" id="tab-1"><i class="fal fa-cog mr-1"></i> Year Period</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="javascript:;" role="tab" id="tab-2"><i class="fal fa-indent mr-1"></i> Day Category</a>
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
                    <div class="col-md-12">
                        <input type="hidden" id="is_set_grid" value="false">
                        <table id="grid-table"></table>
                        <div id="grid-pager"></div>
                    </div>     
                </div>
                <br>
                <div class="row" id="div-grid-detail">
                    <div class="col-md-12">
                        <input type="hidden" id="is_set_grid_detail" value="false">
                        <table id="grid-table-detail"></table>
                        <div id="grid-pager-detail"></div>
                    </div>     
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#tab-1").on("click", function(event) {
        event.stopPropagation();
        loadContentWithParams("ws_og.param_bill_year_period", {
            menu_id: "<?php echo getVarClean('menu_id', 'str', '0'); ?>"
        });
    });
</script>
<script>
    $(function() {
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

    function responsive_jqgrid(grid_selector, pager_selector) {

        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid( 'setGridWidth', $(".page-content").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }

    function get_selected_grid(grid_selector, field_selector){
        var grid = $(grid_selector);
        var rowid = grid.jqGrid ('getGridParam', 'selrow');
        var selected = grid.jqGrid ('getCell', rowid, field_selector);
        return selected;
    }

    function set_grid(){
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";
        var is_set_grid = $('#is_set_grid').val();

        if (is_set_grid != 'true'){
            $('#is_set_grid').val('true');
            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."process_admin.finance_period_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: { p_year_period_id: '<?php echo $this->input->post('p_year_period_id'); ?>' },
                colModel: [
                    {label: 'ID', name: 'p_finance_period_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                    {label: 'P Year Period Id', name: 'p_year_period_id', width: 150, align: 'left', editable: true, hidden: true,
                          editoptions:{
                                 size: 30,
                                 maxlength:22
                         }, editrules: {required: false}
                    }, 
                    {label: 'Code', name: 'finance_period_code', width: 150, align: 'center', editable: true,
                          editoptions:{
                                 size: 30,
                                 maxlength:32
                         }, editrules: {required: true}
                    }, 
                    {label: 'Start Date', name: 'start_date', width: 150, align: "center", editable: true,
                        editoptions: {
                            dataInit : function (elem) {
                                $(elem).datepicker({
                                    autoclose: true,
                                    format: 'yyyy-mm-dd',
                                    orientation: "bottom left",
                                    todayHighlight : true,
                                    setDate: new Date()
                                });
                            }
                        }
                    },
                    {label: 'End Date', name: 'end_date', width: 150, align: "center", editable: true,
                        editoptions: {
                            dataInit : function (elem) {
                                $(elem).datepicker({
                                    autoclose: true,
                                    format: 'yyyy-mm-dd',
                                    orientation: "bottom left",
                                    todayHighlight : true,
                                    showOn: 'focus',
                                    setDate: new Date()
                                });
                            }
                        }
                    },
                    {label: 'Period Status', name: 'status_code', width: 150, editable: false },
                    {label: 'Period Status', name: 'period_status_id', width: 100, align: "center", editable: true, hidden: true,
                        editrules: {edithidden: true, required:false},
                        edittype: 'select',
                        editoptions: {
                            dataUrl: "<?php echo WS_JQGRID.'process_admin.finance_period_controller/html_select_options_period_status'; ?>",
                            dataInit: function(elem) {
                                $(elem).width(210);  // set the width which you need
                            },
                            buildSelect: function (data) {
                                try {
                                    var response = $.parseJSON(data);
                                    if(response.success == false) {
                                        swal({title: 'Attention', text: response.message, html: true, type: "warning"});
                                        return "";
                                    }
                                }catch(err) {
                                    return data;
                                }
                            }
                        },
                    },
                    {label: 'Ref No', name: 'ref_no', width: 150, align: 'center', hidden: true, editable: true,
                          editoptions:{
                                 size: 30,
                                 maxlength:32
                         }
                    }, 
                    {label: 'Ref Date', name: 'ref_date', width: 150, align: "center", hidden: true, editable: true,
                        editoptions: {
                            dataInit : function (elem) {
                                $(elem).datepicker({
                                    autoclose: true,
                                    format: 'yyyy-mm-dd',
                                    orientation: "bottom left",
                                    todayHighlight : true,
                                    showOn: 'focus',
                                    setDate: new Date()
                                });
                            }
                        }
                    }, 
                    {label: 'Description', name: 'description', width: 300, align: 'left', editable: true,
                        edittype:'textarea',
                        editoptions:{
                            size: 30,
                            maxlength:128,
                            dataInit: function(elem) {
                                $(elem).width(210);  // set the width which you need
                            },
                        }, editrules: {required: true}
                     },
                     {label: 'Created By', name: 'created_by', width: 150, align: 'center', editable: false,
                          editoptions:{
                                 size: 30,
                                 maxlength:16
                         }, editrules: {required: false}
                     }, 
                    {label: 'Creation Date', name: 'creation_date', width: 150, align: 'center', editable: false,
                          editoptions:{
                                 size: 30,
                                 maxlength:7
                         }, editrules: {required: false}
                     }, 
                    {label: 'Updated Date', name: 'updated_date', width: 150, align: 'center', editable: false,
                          editoptions:{
                                 size: 30,
                                 maxlength:7
                         }, editrules: {required: false}
                     }, 
                    {label: 'Updated By', name: 'updated_by', width: 150, align: 'center', editable: false,
                          editoptions:{
                                 size: 30,
                                 maxlength:16
                         }, editrules: {required: false}
                    }
                ],
                height: '100%',
                autowidth: true,
                viewrecords: true,
                rowNum: 10,
                rowList: [10,20,50],
                rownumbers: true, // show row numbers
                rownumWidth: 35, // the width of the row numbers columns
                altRows: true,
                shrinkToFit: false,
                multiboxonly: true,
                onSelectRow: function (rowid) {
                    /*do something when selected*/
                    set_grid_detail();
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
                        swal.fire({title: 'Attention', text: response.message, type: "warning"});
                    } else {
                        $('#div-grid-detail').hide();
                    }

                },
                //memanggil controller jqgrid yang ada di controller crud
                editurl: '<?php echo WS_JQGRID."process_admin.finance_period_controller/crud"; ?>',
                caption: "Finance Period"

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
                        jQuery("#detailsPlaceholder").hide();
                    },

                    refreshicon: 'fal fa-repeat-alt orange',
                    view: false,
                    viewicon: 'fal fa-search-plus orange'
                },
                { /* options for the Edit Dialog */ },
                { /* new record form */ },
                { /* delete record form */ },
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
            reload_grid(grid_selector, { p_year_period_id: '<?php echo $this->input->post('p_year_period_id'); ?>' });
        }
    }

    function set_grid_detail(){
        var grid_selector = "#grid-table-detail";
        var pager_selector = "#grid-pager-detail";
        var is_set_grid = $('#is_set_grid_detail').val();

        $('#div-grid-detail').show();

        if (is_set_grid != 'true'){
            $('#is_set_grid_detail').val('true');
            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."ws_og.param_bill_day_category_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: {period: get_selected_grid("#grid-table", "p_finance_period_id")},
                colModel: [
                    {label: 'Period', name: 'period', width: 150, align: 'center', editable: true }, 
                    {label: 'Dates', name: 'dates', key: true, width: 150, align: "center", editable: true },
                    {label: 'Day Name', name: 'day_name', width: 150, align: "center", editable: true },
                    {label: 'Holiday', name: 'is_holiday', width: 150, editable: true, align: 'center',
                        editrules: { required: true },
                        formatter: function (cellvalue, options, rowObject) { 
                            return rowObject['is_holiday'] == 'Y' ? 'YES' : 'NO';
                        },
                        edittype: 'select',
                        editoptions: {
                            value: "N:NO;Y:YES",
                            dataInit: function(elem) {
                                $(elem).width(210);  // set the width which you need
                            }
                        }
                    },
                     {label: 'Created By', name: 'created_by', width: 150, align: 'center', editable: false,
                          editoptions:{
                                 size: 30,
                                 maxlength:16
                         }, editrules: {required: false}
                     }, 
                    {label: 'Created Date', name: 'created_date', width: 150, align: 'center', editable: false,
                          editoptions:{
                                 size: 30,
                                 maxlength:7
                         }, editrules: {required: false}
                     }, 
                    {label: 'Update Date', name: 'update_date', width: 150, align: 'center', editable: false,
                          editoptions:{
                                 size: 30,
                                 maxlength:7
                         }, editrules: {required: false}
                     }, 
                    {label: 'Update By', name: 'update_by', width: 150, align: 'center', editable: false,
                          editoptions:{
                                 size: 30,
                                 maxlength:16
                         }, editrules: {required: false}
                    }
                ],
                height: '100%',
                autowidth: true,
                viewrecords: true,
                rowNum: 10,
                rowList: [10,20,50],
                rownumbers: true, // show row numbers
                rownumWidth: 35, // the width of the row numbers columns
                altRows: true,
                shrinkToFit: false,
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
                        swal.fire({title: 'Attention', text: response.message, type: "warning"});
                    }

                },
                //memanggil controller jqgrid yang ada di controller crud
                editurl: '<?php echo WS_JQGRID."ws_og.param_bill_day_category_controller/crud"; ?>',
                caption: "Day Category"

            });

            jQuery(grid_selector).jqGrid('navGrid', pager_selector,
                {   //navbar options
                    edit: true,
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
                        jQuery("#detailsPlaceholder").hide();
                    },

                    refreshicon: 'fal fa-repeat-alt orange',
                    view: false,
                    viewicon: 'fal fa-search-plus orange'
                },
                {
                    // options for the Edit Dialog
                    editData: {
                        period: function() {
                            return get_selected_grid("#grid-table", "p_finance_period_id");
                        }
                    },
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

                        $('#tr_period', form).hide();
                        $('#tr_dates', form).hide();
                        $('#tr_day_name', form).hide();
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
                { /* new record form */ },
                { /* delete record form */ },
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
                buttonicon:"fal fa-cogs",
                title: "Generate Day Category",
                onClickButton: generate_day_category,
                // cursor: "pointer",
                // id :"btn-view-ba-l11-format",
            });
        } else {
            reload_grid(grid_selector, {period: get_selected_grid("#grid-table", "p_finance_period_id")});
        }
    }

    function generate_day_category() {
        Swal.fire({
            title: "Attention",
            text: 'Generate Day Category?',
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
                var var_url = '<?php echo WS_JQGRID."ws_og.param_bill_day_category_controller/generate_day_category"; ?>';
                $.ajax({
                  url: var_url ,
                  type: "POST",
                  dataType: "json",
                  data: {in_periode: get_selected_grid("#grid-table", "p_finance_period_id")},
                  async: false,
                  success: function (data) {
                    if (data.success){
                        Swal.fire({title: "Success", text: data.message, type: "success"});
                        set_grid_detail();
                    } else {
                        Swal.fire({title: "Error", text: data.message, type: "error"});
                    }
                  },
                  error: function (xhr, status, error) {
                    Swal.fire({title: "Error!", text: get_error_txt(xhr, status, error), html: true, type: "error"});
                  }
                });
              }
        });
    }

</script>
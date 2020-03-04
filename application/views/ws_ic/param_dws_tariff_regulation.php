<!-- breadcrumb -->
<?php echo breadCrumbs(getVarClean('menu_id', 'str', '0')); ?>
<!-- end breadcrumb -->
<div class="panel">
    <div class="panel-hdr">
        <h2>
            <!-- <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" id="tab-1"><i class="fal fa-cog mr-1"></i> Modules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="javascript:;" role="tab" id="tab-2"><i class="fal fa-indent mr-1"></i> Menus</a>
                </li>
            </ul> -->
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
                <div class="row">
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
        var postData = {};

        if (is_set_grid != 'true'){
            $('#is_set_grid').val('true');
            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."ws_ic.param_dws_p_regulation_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: postData,
                colModel: [
                    {label: 'P Regulation ID', name: 'p_regulation_id', key: true, align: 'center', width: 150, hidden: true, editable: true },
                    {label: 'Regulation No', name: 'regulation_no', align: 'left', width: 150, editable: true, 
                        editoptions:{
                            size: 30,
                            maxlength: 64
                        }, editrules: {required: true}
                    },
                    {label: 'Regulation Date', name: 'regulation_date', width: 150, align: "center", editable: true,
                        editoptions: {
                            size: 30,
                            dataInit : function (elem) {
                                $(elem).datepicker({
                                    autoclose: true,
                                    format: 'yyyy-mm-dd',
                                    orientation: "bottom left",
                                    todayHighlight : true,
                                    setDate: new Date()
                                });
                            }
                        }, editrules: {required: true}
                    },
                    {label: 'Effective Date', name: 'effective_date', width: 150, align: "center", editable: true,
                        editoptions: {
                            size: 30,
                            dataInit : function (elem) {
                                $(elem).datepicker({
                                    autoclose: true,
                                    format: 'yyyy-mm-dd',
                                    orientation: "bottom left",
                                    todayHighlight : true,
                                    setDate: new Date()
                                });
                            }
                        }, editrules: {required: true}
                    },
                    {label: 'Doc Reff File', name: 'doc_reff_file', align: 'left', width: 150, editable: true, 
                        editoptions:{
                            size: 30,
                            maxlength: 250
                        }, editrules: {required: true}
                    },
                    {label: 'Description', name: 'description', width: 300, editable: true, editable: true, 
                        edittype:'textarea',
                        editoptions:{
                            maxlength: 64,
                            dataInit: function(elem) {
                                $(elem).width(210);  // set the width which you need
                            }
                        }, editrules: {required: true}
                    },
                    {label: 'Update Date', name: 'update_date', width: 200, align: 'center' }, 
                    {label: 'Update By', name: 'update_by', width: 150, align: 'center' }
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
                    }

                },
                //memanggil controller jqgrid yang ada di controller crud
                editurl: '<?php echo WS_JQGRID."ws_ic.param_dws_p_regulation_controller/crud"; ?>',
                caption: "Regulation"

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
                        jQuery("#detailsPlaceholder").hide();
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
                        swal.fire({title: 'Success', text: response.message, type: "success"});
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
            reload_grid(grid_selector, postData);
        }
    }

    function set_grid_detail(){
        var grid_selector = "#grid-table-detail";
        var pager_selector = "#grid-pager-detail";
        var is_set_grid = $('#is_set_grid_detail').val();
        var postData = {p_regulation_id: get_selected_grid("#grid-table", 'p_regulation_id')};

        if (is_set_grid != 'true'){
            $('#is_set_grid_detail').val('true');
            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."ws_ic.param_dws_p_reg_files_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: postData,
                colModel: [
                    {label: 'P reg files ID', name: 'p_reg_files_id', key: true, align: 'center', width: 150, hidden: true, editable: true },
                    {label: 'P Regulation ID', name: 'p_regulation_id', align: 'center', width: 150, hidden: true, editable: true },
                    {label: 'Regulation No', name: 'p_regulation_no', align: 'left', width: 150, editable: true, 
                        editoptions:{
                            size: 38,
                            maxlength: 128
                        }, editrules: {required: true}
                    },
                    {label: 'Files', name: 'files', hidden: true, editable: true, 
                        edittype: 'file',
                        editoptions: {
                            enctype: "multipart/form-data",
                            dataInit: function(elem) {
                                $(elem).addClass('form-control');
                            }
                        },
                    },
                    {label: 'File Name Existing', name: 'file_name', width: 150, editable: true, 
                        editoptions: {
                            size: 38,
                            dataInit: function(elem) {
                                $(elem).attr('readonly', 'true');
                            }
                        }
                    },
                    {label: 'Directory Existing', name: 'directory', width: 300, editable: true, 
                        editoptions: {
                            size: 38,
                            dataInit: function(elem) {
                                $(elem).attr('readonly', 'true');
                            }
                        }
                    },
                    {label: 'Description', name: 'description', width: 300, editable: true, editable: true, 
                        edittype: 'textarea',
                        editoptions: {
                            maxlength: 64,
                            dataInit: function(elem) {
                                $(elem).width(258);  // set the width which you need
                            }
                        }, editrules: {required: true}
                    },
                    {label: 'Update Date', name: 'update_date', width: 200, align: 'center' }, 
                    {label: 'Update By', name: 'update_by', width: 150, align: 'center' }
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
                editurl: '<?php echo WS_JQGRID."ws_ic.param_dws_p_reg_files_controller/crud"; ?>',
                caption: "Files"

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
                        jQuery("#detailsPlaceholder").hide();
                    },

                    refreshicon: 'fal fa-repeat-alt orange',
                    view: false,
                    viewicon: 'fal fa-search-plus orange'
                },
                {
                    // options for the Edit Dialog
                    editData: {
                        p_regulation_id: function(){
                            return get_selected_grid("#grid-table", 'p_regulation_id');
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
                        $('#tr_files').show();

                    },
                    afterShowForm: function(form) {
                        form.closest('.ui-jqdialog').center();
                    },
                    afterSubmit:function(response,postdata) {
                        var response = jQuery.parseJSON(response.responseText);
                        if(response.success == false) {
                            return [false,response.message,response.responseText];
                        }

                        upload_file(response, 'update');
                        jQuery(grid_selector).jqGrid('setSelection', response.id);

                        $(".tinfo").html('<div class="ui-state-success">' + response.message + '</div>');
                        var tinfoel = $(".tinfo").show();
                        tinfoel.delay(3000).fadeOut();

                        return [true,"",response.responseText];
                    }
                },
                {
                    //new record form
                    editData: {
                        p_regulation_id: function(){
                            return get_selected_grid("#grid-table", 'p_regulation_id');
                        }
                    },
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
                        $('#tr_files').show();
                    },
                    afterShowForm: function(form) {
                        form.closest('.ui-jqdialog').center();
                    },
                    afterSubmit:function(response,postdata) {
                        var response = jQuery.parseJSON(response.responseText);
                        if(response.success == false) {
                            return [false,response.message,response.responseText];
                        }

                        upload_file(response, 'create');

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
                        swal.fire({title: 'Success', text: response.message, type: "success"});
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
            reload_grid(grid_selector, postData);
        }
    }

    function upload_file(response, crud){
        var file = $('#TblGrid_grid-table-detail #files').prop('files')[0];
        var postData = new FormData();
        postData.append('uploadParamFile', file);
        postData.append('p_reg_files_id', response.id);

        $.ajax({
            url: '<?php echo WS_JQGRID."ws_ic.param_dws_p_reg_files_controller/upload_files"; ?>',
            type: "POST",
            dataType: "json",
            contentType: false,
            cache: false,
            processData:false,
            data: postData,
            success: function (data) {
                console.log(data);
                if (!data.success){
                    swal({title: "Error!", text: data.message, html: true, type: "error"});    
                } else {
                    set_grid_detail();

                    if (crud == 'update'){
                        $('#TblGrid_grid-table-detail #file_name').val(data.file_name);
                    }
                }
            },
            error: function (xhr, status, error) {
                swal({title: "Error!", text: xhr.responseText, html: true, type: "error"});
            }
        });


    }

</script>
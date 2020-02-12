<!-- breadcrumb -->
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
    <li class="breadcrumb-item">Administrator</li>
    <li class="breadcrumb-item">Status Process</li>
    <li class="breadcrumb-item active">Status Type</li>
</ol>
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
                <div class="row" id="gridList" style="display: none;">
                   <div class="col-md-12">
                    <input type="hidden" id="is_set_grid_list" value="false">
                     <table id="grid-list"></table>
                     <div id="grid-pager-list"></div>
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

        if (is_set_grid != 'true'){
            $('#is_set_grid').val('true');
            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."ws_idd.statustype_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                colModel: [
                    {label: 'ID', name: 'p_status_type_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                    {label: 'Code', name: 'code', width: 150, align: 'center', editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
                     }, 
                    {label: 'Description', name: 'description', width: 308, align: 'left', editable: true,
                          edittype:'textarea',
                          editoptions:{
                              size: 30,
                              maxlength:128
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
                    set_grid_list();

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
                        $('#gridList').hide();
                    }

                },
                //memanggil controller jqgrid yang ada di controller crud
                editurl: '<?php echo WS_JQGRID."ws_idd.statustype_controller/crud"; ?>',
                caption: "Status Type"

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
        }
    }

    function set_grid_list() {
        var grid_selector = "#grid-list";
        var pager_selector = "#grid-pager-list";
        var is_set_grid = $('#is_set_grid_list').val();
        $('#gridList').show();

        if (is_set_grid != 'true'){
            $('#is_set_grid_list').val('true');
            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."ws_idd.statuslist_controller/crud";?>',
                postData: { p_status_type_id: get_selected_grid('#grid-table', 'p_status_type_id') },
                datatype: "json",
                mtype: "POST",
                colModel: [
                    {label: 'ID', name: 'p_status_list_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                    {label: 'Code', name: 'code', width: 150, align: 'center', editable: true,
                          editoptions:{
                                 size: 30,
                                 maxlength:32
                         }, editrules: {required: true}
                     }, 
                    {label: 'Status Type Id', name: 'p_status_type_id', width: 150, align: 'left', editable: true, hidden:true,
                          editoptions:{
                                 size: 30,
                                 maxlength:22
                         }, editrules: {required: false}
                     }, 
                    {label: 'Description', name: 'description', width: 300, align: 'left', editable: true,
                          edittype:'textarea',
                          editoptions:{
                                 size: 30,
                                 maxlength:128
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
                        swal({title: 'Attention', text: response.message, html: true, type: "warning"});
                    }
                },
                //memanggil controller jqgrid yang ada di controller crud
                editurl: '<?php echo WS_JQGRID."ws_idd.statuslist_controller/crud";?>',
                caption: "Status List"
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
                    // new record item 
                    editData: {
                        p_status_type_id: function() {
                            return get_selected_grid('#grid-table', 'p_status_type_id');
                        }
                    },
                    serializeEditData: serializeJSON,
                    //new record form
                    closeAfterAdd: true,
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
            reload_grid(grid_selector, { p_status_type_id: get_selected_grid('#grid-table', 'p_status_type_id') });
        }
    }

</script>
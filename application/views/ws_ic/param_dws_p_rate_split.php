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
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('lov/lov_p_organization'); ?>
<?php $this->load->view('lov/lov_dws_p_schembis'); ?>
<?php $this->load->view('lov/lov_dws_p_zone'); ?>
<?php $this->load->view('lov/ws_ic/lov_p_reference_list'); ?>
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
                url: '<?php echo WS_JQGRID."ws_ic.param_dws_p_rate_split_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: postData,
                colModel: [
                    {label: 'P Rate Split ID', name: 'p_rate_split_id', key: true, align: 'center', width: 150, hidden: true, editable: true },
                    {label: 'Orig ID', name: 'orig_id', align: 'center', width: 150, hidden: true, editable: true },
                    {label: 'P Schembis ID', name: 'p_schembis_id', align: 'center', width: 150, hidden: true, editable: true },
                    {label: 'P Zone ID', name: 'p_zone_id', align: 'center', width: 150, hidden: true, editable: true },
                    {label: 'Tier ID', name: 'tier_id', align: 'center', width: 150, hidden: true, editable: true },
                    {label: 'Code Org', name: 'code_org', align: 'center', width: 150, editable: true, editrules: {required: true},
                        edittype: 'custom',
                        editoptions: {
                            "custom_element":function( value  , options) {
                                var elm = $('<span></span>');

                                // give the editor time to initialize
                                setTimeout( function() {
                                    elm.append(
                                            '<input id="form_code_org" readonly type="text" class="FormElement form-control jqgrid-required">'+
                                            '<button class="btn btn-success" style="margin-bottom: 2px; margin-left: 2px;" type="button" onclick="modal_lov_organization_show(\'tr_orig_id .DataTD #orig_id\', \'form_code_org\')">'+
                                            '   <span class="fal fa-search"></span>'+
                                            '</button>');
                                    elm.parent().removeClass('jqgrid-required');
                                }, 100);

                                return elm;
                            },
                            "custom_value":function( element, oper, gridval) {
                                console.log(element);
                                console.log(oper);
                                console.log(gridval);
                                if(oper === 'get') {
                                    return $("#form_code_org").val();
                                } else if( oper === 'set') {
                                    $("#form_code_org").val(gridval);
                                    var gridId = this.id;
                                    // give the editor time to set display
                                    setTimeout(function(){
                                        var selectedRowId = $("#"+gridId).jqGrid ('getGridParam', 'selrow');
                                        if(selectedRowId != null) {
                                            var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'code_org');
                                            $("#form_code_org").val( code_display );
                                        }
                                    },100);
                                }
                            }
                        }
                    },
                    {label: 'VC Name', name: 'vc_name', align: 'center', width: 150, editable: true, editrules: {required: true},
                        edittype: 'custom',
                        editoptions: {
                            "custom_element":function( value  , options) {
                                var elm = $('<span></span>');

                                // give the editor time to initialize
                                setTimeout( function() {
                                    elm.append(
                                            '<input id="form_vc_name" readonly type="text" class="FormElement form-control jqgrid-required">'+
                                            '<button class="btn btn-success" style="margin-bottom: 2px; margin-left: 2px;" type="button" onclick="modal_lov_dws_p_schembis_show(\'tr_p_schembis_id .DataTD #p_schembis_id\', \'form_vc_name\')">'+
                                            '   <span class="fal fa-search"></span>'+
                                            '</button>');
                                    elm.parent().removeClass('jqgrid-required');
                                }, 100);

                                return elm;
                            },
                            "custom_value":function( element, oper, gridval) {
                                console.log(element);
                                console.log(oper);
                                console.log(gridval);
                                if(oper === 'get') {
                                    return $("#form_vc_name").val();
                                } else if( oper === 'set') {
                                    $("#form_vc_name").val(gridval);
                                    var gridId = this.id;
                                    // give the editor time to set display
                                    setTimeout(function(){
                                        var selectedRowId = $("#"+gridId).jqGrid ('getGridParam', 'selrow');
                                        if(selectedRowId != null) {
                                            var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'vc_name');
                                            $("#form_vc_name").val( code_display );
                                        }
                                    },100);
                                }
                            }
                        }
                    },
                    {label: 'Code Zone', name: 'code_z', align: 'center', width: 150, editable: true, editrules: {required: true},
                        edittype: 'custom',
                        editoptions: {
                            "custom_element":function( value  , options) {
                                var elm = $('<span></span>');

                                // give the editor time to initialize
                                setTimeout( function() {
                                    elm.append(
                                            '<input id="form_code_z" readonly type="text" class="FormElement form-control jqgrid-required">'+
                                            '<button class="btn btn-success" style="margin-bottom: 2px; margin-left: 2px;" type="button" onclick="modal_lov_dws_p_zone_show(\'tr_p_zone_id .DataTD #p_zone_id\', \'form_code_z\')">'+
                                            '   <span class="fal fa-search"></span>'+
                                            '</button>');
                                    elm.parent().removeClass('jqgrid-required');
                                }, 100);

                                return elm;
                            },
                            "custom_value":function( element, oper, gridval) {
                                console.log(element);
                                console.log(oper);
                                console.log(gridval);
                                if(oper === 'get') {
                                    return $("#form_code_z").val();
                                } else if( oper === 'set') {
                                    $("#form_code_z").val(gridval);
                                    var gridId = this.id;
                                    // give the editor time to set display
                                    setTimeout(function(){
                                        var selectedRowId = $("#"+gridId).jqGrid ('getGridParam', 'selrow');
                                        if(selectedRowId != null) {
                                            var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'code_z');
                                            $("#form_code_z").val( code_display );
                                        }
                                    },100);
                                }
                            }
                        }
                    },
                    {label: 'Code Tier', name: 'code_tier', align: 'center', width: 150, editable: true, editrules: {required: true},
                        edittype: 'custom',
                        editoptions: {
                            "custom_element":function( value  , options) {
                                var elm = $('<span></span>');

                                // give the editor time to initialize
                                setTimeout( function() {
                                    elm.append(
                                            '<input id="form_code_tier" readonly type="text" class="FormElement form-control jqgrid-required">'+
                                            '<button class="btn btn-success" style="margin-bottom: 2px; margin-left: 2px;" type="button" onclick="modal_lov_p_reference_list_show(\'tr_tier_id .DataTD #tier_id\', \'form_code_tier\')">'+
                                            '   <span class="fal fa-search"></span>'+
                                            '</button>');
                                    elm.parent().removeClass('jqgrid-required');
                                }, 100);

                                return elm;
                            },
                            "custom_value":function( element, oper, gridval) {
                                console.log(element);
                                console.log(oper);
                                console.log(gridval);
                                if(oper === 'get') {
                                    return $("#form_code_tier").val();
                                } else if( oper === 'set') {
                                    $("#form_code_tier").val(gridval);
                                    var gridId = this.id;
                                    // give the editor time to set display
                                    setTimeout(function(){
                                        var selectedRowId = $("#"+gridId).jqGrid ('getGridParam', 'selrow');
                                        var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'code_tier');
                                        $("#form_code_tier").val( code_display );
                                    },100);
                                }
                            }
                        }
                    },
                    {label: 'Rate',name: 'rate', width: 150, editable: true, align: 'right',
                        editoptions: {
                            size: 30,
                            maxlength: 255,
                            dataInit: function(element) {
                                $(element).keypress(function(e){
                                     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                        return false;
                                     }
                                });
                            }
                        },
                        editrules: {required: true}
                    },
                    {label: 'Created Date', name: 'created_date', width: 200, align: 'center' }, 
                    {label: 'Created By', name: 'created_by', width: 150, align: 'center' },
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
                editurl: '<?php echo WS_JQGRID."ws_ic.param_dws_p_rate_split_controller/crud"; ?>',
                caption: "Company"

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

                        setTimeout(function(){

                            $('#form_code_org').val('');
                            $('#form_vc_name').val('');
                            $('#form_code_z').val('');
                            $('#form_code_tier').val('');

                        }, 100);
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

</script>
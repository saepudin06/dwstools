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
<?php $this->load->view('lov/lov_dws_p_zone'); ?>
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
                url: '<?php echo WS_JQGRID."ws_ic.param_dws_p_tariff_used_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: postData,
                colModel: [
                    {label: 'p_tariff_used_id', name: 'p_tariff_used_id', key: true, align: 'center', width: 150, hidden: true, editable: true },
                    {label: 'Orig ID', name: 'orig_id', width: 150, editable: true, hidden: true },
                    {label: 'term ID', name: 'term_id', width: 150, editable: true, hidden: true },
                    {label: 'zone ID', name: 'zone_id', width: 150, editable: true, hidden: true },
                    {label: 'Orig', name: 'orig', align: 'center', width: 150, editable: true, editrules: {required: true},
                        edittype: 'custom',
                        editoptions: {
                            "custom_element":function( value  , options) {
                                var elm = $('<span></span>');

                                // give the editor time to initialize
                                setTimeout( function() {
                                    elm.append(
                                            '<input id="form_orig" readonly type="text" class="FormElement form-control jqgrid-required">'+
                                            '<button class="btn btn-success" style="margin-bottom: 2px; margin-left: 2px;" type="button" onclick="modal_lov_organization_show(\'tr_orig_id .DataTD #orig_id\', \'form_orig\')">'+
                                            '   <span class="fal fa-search"></span>'+
                                            '</button>');
                                    elm.parent().removeClass('jqgrid-required');
                                }, 100);

                                return elm;
                            },
                            "custom_value":function( element, oper, gridval) {
                                // console.log(element);
                                // console.log(oper);
                                // console.log(gridval);
                                if(oper === 'get') {
                                    return $("#form_orig").val();
                                } else if( oper === 'set') {
                                    $("#form_orig").val(gridval);
                                    var gridId = this.id;
                                    // give the editor time to set display
                                    setTimeout(function(){
                                        var selectedRowId = $("#"+gridId).jqGrid ('getGridParam', 'selrow');
                                        if(selectedRowId != null) {
                                            var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'orig');
                                            $("#form_orig").val( code_display );
                                        }
                                    },100);
                                }
                            }
                        }
                    },
                    {label: 'Term', name: 'term', align: 'center', width: 150, editable: true, editrules: {required: true},
                        edittype: 'custom',
                        editoptions: {
                            "custom_element":function( value  , options) {
                                var elm = $('<span></span>');

                                // give the editor time to initialize
                                setTimeout( function() {
                                    elm.append(
                                            '<input id="form_term" readonly type="text" class="FormElement form-control jqgrid-required">'+
                                            '<button class="btn btn-success" style="margin-bottom: 2px; margin-left: 2px;" type="button" onclick="modal_lov_organization_show(\'tr_term_id .DataTD #term_id\', \'form_term\')">'+
                                            '   <span class="fal fa-search"></span>'+
                                            '</button>');
                                    elm.parent().removeClass('jqgrid-required');
                                }, 100);

                                return elm;
                            },
                            "custom_value":function( element, oper, gridval) {
                                // console.log(element);
                                // console.log(oper);
                                // console.log(gridval);
                                if(oper === 'get') {
                                    return $("#form_term").val();
                                } else if( oper === 'set') {
                                    $("#form_term").val(gridval);
                                    var gridId = this.id;
                                    // give the editor time to set display
                                    setTimeout(function(){
                                        var selectedRowId = $("#"+gridId).jqGrid ('getGridParam', 'selrow');
                                        if(selectedRowId != null) {
                                            var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'term');
                                            $("#form_term").val( code_display );
                                        }
                                    },100);
                                }
                            }
                        }
                    },
                    {label: 'Orig Type', name: 'orig_type', width: 150 },
                    {label: 'Term Type', name: 'term_type', width: 150 },
                    {label: 'zones', name: 'zones', align: 'center', width: 150, editable: true, editrules: {required: true},
                        edittype: 'custom',
                        editoptions: {
                            "custom_element":function( value  , options) {
                                var elm = $('<span></span>');

                                // give the editor time to initialize
                                setTimeout( function() {
                                    elm.append(
                                            '<input id="form_zones" readonly type="text" class="FormElement form-control jqgrid-required">'+
                                            '<button class="btn btn-success" style="margin-bottom: 2px; margin-left: 2px;" type="button" onclick="modal_lov_dws_p_zone_show(\'tr_zone_id .DataTD #zone_id\', \'form_zones\')">'+
                                            '   <span class="fal fa-search"></span>'+
                                            '</button>');
                                    elm.parent().removeClass('jqgrid-required');
                                }, 100);

                                return elm;
                            },
                            "custom_value":function( element, oper, gridval) {
                                // console.log(element);
                                // console.log(oper);
                                // console.log(gridval);
                                if(oper === 'get') {
                                    return $("#form_zones").val();
                                } else if( oper === 'set') {
                                    $("#form_zones").val(gridval);
                                    var gridId = this.id;
                                    // give the editor time to set display
                                    setTimeout(function(){
                                        var selectedRowId = $("#"+gridId).jqGrid ('getGridParam', 'selrow');
                                        if(selectedRowId != null) {
                                            var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'zones');
                                            $("#form_zones").val( code_display );
                                        }
                                    },100);
                                }
                            }
                        }
                    },
                    {label: 'Sap Zone', name: 'sap_zone', width: 150 },
                    {label: 'Usage', name: 'usage', width: 150, editable: true, align: 'right', editrules: {required: true}, 
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
                        // formatter: function (cellvalue, options, rowObject) { return $.number( cellvalue ) }
                    },
                    {label: 'Usage 2', name: 'usage2', width: 150, editable: true, align: 'right', editrules: {required: true}, 
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
                        // formatter: function (cellvalue, options, rowObject) { return $.number( cellvalue ) }
                    },
                    {label: 'Total', name: 'total', width: 150, editable: true, align: 'right', editrules: {required: true}, 
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
                        // formatter: function (cellvalue, options, rowObject) { return $.number( cellvalue ) }
                    },
                    {label: 'Valid From', name: 'valid_from', width: 150, align: "center", editable: true, editrules: {required: true}, 
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
                                $(elem).attr('autocomplete', 'off');
                            }
                        }
                    },
                    {label: 'Valid Until', name: 'valid_until', width: 150, align: "center", editable: true,
                        editoptions: {
                            size: 30,
                            dataInit : function (elem) {
                                $(elem).datepicker({
                                    autoclose: true,
                                    format: 'yyyy-mm-dd',
                                    orientation: "bottom left",
                                    todayHighlight : true
                                });
                                $(elem).attr('autocomplete', 'off');
                            }
                        }
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
                editurl: '<?php echo WS_JQGRID."ws_ic.param_dws_p_tariff_used_controller/crud"; ?>',
                caption: "Tariff Used"

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

</script>
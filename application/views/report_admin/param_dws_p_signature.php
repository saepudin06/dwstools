<!-- breadcrumb -->
<?php echo breadCrumbs(getVarClean('menu_id', 'str', '0')); ?>
<!-- end breadcrumb -->
<div class="panel">
    <div class="panel-hdr">
        <h2>
        </h2>
        <div class="panel-toolbar">
            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
            <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button>
        </div>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="frame-wrap m-0">
                <br>
                <div class="row" id="div-grid-table">
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
<?php $this->load->view('lov/report_admin/lov_user'); ?>
<?php $this->load->view('lov/report_admin/lov_role_signature'); ?>
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

        $('#div-' + grid_selector.substr(1)).show();

        if (is_set_grid != 'true'){
            $('#is_set_grid').val('true');
            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."report_admin.param_dws_p_signature_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: postData,
                colModel: [
                    {label: 'P Signature ID', name: 'p_signature_id', key: true, width: 150, hidden: true, editable: true },
                    {label: 'User ID', name: 'user_id', width: 150, hidden: true, editable: true },
                    {label: 'Signature Role ID', name: 'signaturerole_id', width: 150, hidden: true, editable: true },
                    {label: 'User Name', name: 'user_name', width: 150, editable: true,
                        edittype: 'custom',
                        editoptions: {
                            "custom_element":function( value  , options) {
                                var elm = $('<span></span>');

                                // give the editor time to initialize
                                setTimeout( function() {
                                    elm.append(
                                            '<input id="form_user_name" readonly type="text" class="FormElement form-control" size="29">'+
                                            '<button class="btn btn-success" style="margin-bottom: 2px; margin-left: 2px;" type="button" onclick="modal_lov_user_show(\'tr_user_id .DataTD #user_id\', \'form_user_name\')">'+
                                            '   <span class="fal fa-search"></span>'+
                                            '</button>');
                                    elm.parent().removeClass('jqgrid-required');
                                }, 100);

                                return elm;
                            },
                            "custom_value":function( element, oper, gridval) {
                                // console.log(oper);
                                // console.log(gridval);
                                if(oper === 'get') {
                                    return $("#form_user_name").val();
                                } else if( oper === 'set') {
                                    $("#form_user_name").val(gridval);
                                    var gridId = this.id;
                                    // give the editor time to set display
                                    setTimeout(function(){
                                        var selectedRowId = $("#"+gridId).jqGrid ('getGridParam', 'selrow');
                                        if(selectedRowId != null) {
                                            var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'user_name');
                                            $("#form_user_name").val( gridval );
                                        }
                                    },100);
                                }
                            }
                        }
                    },
                    {label: 'Role Code', name: 'role_code', width: 150, editable: true,
                        edittype: 'custom',
                        editoptions: {
                            "custom_element":function( value  , options) {
                                var elm = $('<span></span>');

                                // give the editor time to initialize
                                setTimeout( function() {
                                    elm.append(
                                            '<input id="form_role_code" readonly type="text" class="FormElement form-control" size="29">'+
                                            '<button class="btn btn-success" style="margin-bottom: 2px; margin-left: 2px;" type="button" onclick="modal_lov_role_signature_show(\'tr_signaturerole_id .DataTD #signaturerole_id\', \'form_role_code\')">'+
                                            '   <span class="fal fa-search"></span>'+
                                            '</button>');
                                    elm.parent().removeClass('jqgrid-required');
                                }, 100);

                                return elm;
                            },
                            "custom_value":function( element, oper, gridval) {
                                // console.log(oper);
                                // console.log(gridval);
                                if(oper === 'get') {
                                    return $("#form_role_code").val();
                                } else if( oper === 'set') {
                                    $("#form_role_code").val(gridval);
                                    var gridId = this.id;
                                    // give the editor time to set display
                                    setTimeout(function(){
                                        var selectedRowId = $("#"+gridId).jqGrid ('getGridParam', 'selrow');
                                        if(selectedRowId != null) {
                                            var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'role_code');
                                            $("#form_role_code").val( gridval );
                                        }
                                    },100);
                                }
                            }
                        }
                    },
                    {label: 'Position Name', name: 'position_name', width: 300, editable: true, 
                        editoptions:{
                            size: 38,
                            maxlength: 50
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
                    {label: 'Signature Image Existing', name: 'signature_img', width: 300, editable: true, 
                        editoptions: {
                            size: 38,
                            dataInit: function(elem) {
                                $(elem).attr('readonly', 'true');
                            }
                        }
                    },
                    {label: 'Valid From', name: 'valid_from', width: 150, align: "center", editable: true, editrules: {required: true}, 
                        editoptions: {
                            size: 38,
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
                            size: 38,
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
                    {label: 'Updated Date', name: 'updated_date', width: 200, align: 'center' }, 
                    {label: 'Updated By', name: 'updated_by', width: 150, align: 'center' }
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
                editurl: '<?php echo WS_JQGRID."report_admin.param_dws_p_signature_controller/crud"; ?>',
                caption: "Signature"

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
                        jQuery("sPlaceholder").hide();
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
        var file = $('#TblGrid_grid-table #files').prop('files')[0];
        var postData = new FormData();
        postData.append('uploadParamFile', file);
        postData.append('p_signature_id', response.id);

        $.ajax({
            url: '<?php echo WS_JQGRID."report_admin.param_dws_p_signature_controller/upload_files"; ?>',
            type: "POST",
            dataType: "json",
            contentType: false,
            cache: false,
            processData:false,
            data: postData,
            success: function (data) {
                if (!data.success){
                    swal({title: "Error!", text: data.message, html: true, type: "error"});    
                } else {
                    set_grid();

                    if (crud == 'update'){
                        $('#TblGrid_grid-table #file_name').val(data.file_name);
                    }
                }
            },
            error: function (xhr, status, error) {
                swal({title: "Error!", text: xhr.responseText, html: true, type: "error"});
            }
        });


    }

</script>
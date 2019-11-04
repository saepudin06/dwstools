<div id="modal_lov_icon" class="modal fade" tabindex="-1" style="overflow-y: scroll;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- modal title -->
            <div class="modal-header no-padding">
                <div class="table-header">
                    <span class="form-add-edit-title"> Data Icon</span>
                </div>
            </div>
            <input type="hidden" id="modal_lov_icon_id_val" value="" />
            <input type="hidden" id="modal_lov_icon_name_val" value="" />
            <input type="hidden" id="modal_lov_icon_masuk" value="" />

            <!-- modal body -->
            <div class="modal-body">
                <div>
                  <button type="button" class="btn btn-sm btn-success" id="modal_lov_icon_btn_blank">
                    <span class="fal fa-pencil bigger-110" aria-hidden="true"></span> BLANK
                  </button>
                </div>
                
                <div style="padding-bottom: 20px;"></div>
                <div class="row">
                    <label class="control-label col-md-2">Pencarian :</label>
                    <div class="col-md-9">
                        <div class="input-group col-md-9">
                            <input type="text" class="form-control" id="i_search_lov_icon" placeholder="Search">
                            <span class="input-group-btn">
                                <button class="btn  btn-primary default" type="button" onclick="filter_lov_icon()">Cari</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div style="padding-bottom: 10px;"></div>
                <div class="row">
                    <div class="col-md-12" id="lov_icon_grid">
                        <table id="grid-table-lov_icon"></table>
                        <div id="grid-pager-lov_icon"></div>
                    </div>
                </div>
            </div>

            <!-- modal footer -->
            <div class="modal-footer no-margin-top">
                <div class="bootstrap-dialog-footer">
                    <div class="bootstrap-dialog-footer-buttons">
                        <button class="btn btn-danger btn-sm radius-4" data-dismiss="modal">
                            <i class="fal fa-times"></i>
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.end modal -->

<script type="text/javascript">
    $(function($) {
        $("#modal_lov_icon_btn_blank").on('click', function() {
            $("#"+ $("#modal_lov_icon_id_val").val()).val("");
            $("#"+ $("#modal_lov_icon_name_val").val()).val("");
            $("#modal_lov_icon").modal("toggle");
        });
    });

    function modal_lov_icon_show(the_id_field, the_code_field) {
        modal_lov_icon_set_field_value(the_id_field, the_code_field);
        $("#modal_lov_icon").modal({backdrop: 'static'});
        modal_lov_icon_prepare_table();
    }


    function modal_lov_icon_set_field_value(the_id_field, the_code_field) {
         $("#modal_lov_icon_id_val").val(the_id_field);
         $("#modal_lov_icon_name_val").val(the_code_field);
    }

    function modal_lov_icon_set_value(the_id_val, the_code_val) {
         $("#"+ $("#modal_lov_icon_id_val").val()).val(the_id_val);
         $("#"+ $("#modal_lov_icon_name_val").val()).val(the_code_val);
         $("#modal_lov_icon").modal("toggle");

         $("#"+ $("#modal_lov_icon_id_val").val()).change();
         $("#"+ $("#modal_lov_icon_name_val").val()).change();
    }


    function modal_lov_icon_prepare_table() {
        var grid_selector = "#grid-table-lov_icon";
        var pager_selector = "#grid-pager-lov_icon";

        if($('#modal_lov_icon_masuk').val() == '1'){
            jQuery("#grid-table-lov_icon").jqGrid('setGridParam',{
                url: '<?php echo WS_JQGRID."administration.icons_controller/read"; ?>',
            });
            $("#grid-table-lov_icon").trigger("reloadGrid");
            return false;
        }


        jQuery("#grid-table-lov_icon").jqGrid({
            url: '<?php echo WS_JQGRID."administration.icons_controller/read"; ?>',
            datatype: "json",
            mtype: "POST",
            loadui: "disable",
            colModel: [
                {label: 'ID', name: 'icon_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                {label: 'Display', name: 'icon_display', width: 120, align: "center", editable: false, search:false, sortable:false,
                    formatter: function(cellvalue, options, rowObject) {
                        return '<i class="'+cellvalue+' bigger-140"></i>';
                    }
                },
                {label: 'Code',name: 'icon_code',width: 150, align: "left",editable: true,
                    editoptions: {
                        size: 30,
                        maxlength:32
                    },
                    editrules: {required: true}
                }
            ],
            height: '100%',
            width: 750,
            minWidth: 750,
            viewrecords: true,
            rowNum: 5,
            // rowList: [5,10],
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            altRows: true,
            shrinkToFit: true,
            multiboxonly: true,
            onSelectRow: function (rowid) {
                /*do something when selected*/

            },
            ondblClickRow: function(rowid) {

                var grid = $('#grid-table-lov_icon');
                var sel_id = grid.jqGrid('getGridParam', 'selrow');
                var icon_code = grid.jqGrid('getCell', sel_id, 'icon_code');
                
                modal_lov_icon_set_value(icon_code,icon_code);

            },
            sortorder:'',
            pager: '#grid-pager-lov_icon',
            jsonReader: {
                root: 'rows',
                id: 'id',
                repeatitems: false
            },
            loadComplete: function (response) {
                if(response.success == false) {
                    swal({title: 'Attention', text: response.message, html: true, type: "warning"});
                }

                // setTimeout(function(){
                      // $("#grid-table-lov_icon").setSelection($("#grid-table-lov_icon").getDataIDs()[0],true);
                      responsive_jqgrid('#grid-table-lov_icon', '#grid-pager-lov_icon');
                // },500);

            },
            //memanggil controller jqgrid yang ada di controller read
            editurl: '<?php echo WS_JQGRID."administration.icons_controller/read"; ?>',
            caption: ""

        });

        jQuery('#grid-table-lov_icon').jqGrid('navGrid', '#grid-pager-lov_icon',
            {   //navbar options
                edit: false,
                editicon: 'fa fa-pencil blue bigger-120',
                add: false,
                addicon: 'fa fa-plus-circle purple bigger-120',
                del: false,
                delicon: 'fa fa-trash-o red bigger-120',
                search: false,
                searchicon: 'fa fa-search orange bigger-120',
                refresh: true,
                afterRefresh: function () {
                    // some code here
                },

                refreshicon: 'fa fa-refresh green bigger-120',
                view: false,
                viewicon: 'fa fa-search-plus grey bigger-120'
            },

            {
                // options for the Edit Dialog
                closeAfterEdit: true,
                closeOnEscape:true,
                recreateForm: true,
                // serializeEditData: serializeJSON,
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
                // serializeEditData: serializeJSON,
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
                // serializeDelData: serializeJSON,
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

    function responsive_jqgrid(grid_selector, pager_selector) {

        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid( 'setGridWidth', $("#lov_icon_grid").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }

    $('#i_search_lov_icon').on('keyup', function(event){
        event.preventDefault();
        if(event.keyCode === 13) {
            var i_search_lov_icon = $('#i_search_lov_icon').val();
            jQuery("#grid-table-lov_icon").jqGrid('setGridParam',{
                url: '<?php echo WS_JQGRID."administration.icons_controller/read"; ?>',
                postData: {
                    i_search : i_search_lov_icon
                }
            });
            $("#grid-table-lov_icon").trigger("reloadGrid");
        }
    });

    function filter_lov_icon(){
        var i_search_lov_icon = $('#i_search_lov_icon').val();
        
        jQuery("#grid-table-lov_icon").jqGrid('setGridParam',{
                url: '<?php echo WS_JQGRID."administration.icons_controller/read"; ?>',
                postData: {
                    i_search : i_search_lov_icon
                }
            });
            $("#grid-table-lov_icon").trigger("reloadGrid");
    }

    jQuery('#modal_lov_icon').on('hide.bs.modal', function(){
       $('#modal_lov_icon_masuk').val('1');
       jQuery("#grid-table-lov_icon").jqGrid('clearGridData');
    });
</script>
<div id="modal_lov_role_signature" class="modal fade" tabindex="-1" style="overflow-y: scroll;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- modal title -->
            <div class="modal-header no-padding">
                <div class="table-header">
                    <span class="form-add-edit-title"> Data Role Signature</span>
                </div>
            </div>
            <input type="hidden" id="modal_lov_role_signature_id_val" value="" />
            <input type="hidden" id="modal_lov_role_signature_name_val" value="" />
            <input type="hidden" id="modal_lov_role_signature_masuk" value="" />

            <!-- modal body -->
            <div class="modal-body">
                <div>
                  <button type="button" class="btn btn-sm btn-success" id="modal_lov_role_signature_btn_blank">
                    <span class="fal fa-pencil bigger-110" aria-hidden="true"></span> BLANK
                  </button>
                </div>
                
                <div style="padding-bottom: 20px;"></div>
                <div class="row">
                    <label class="control-label col-md-2">Search :</label>
                    <div class="col-md-9">
                        <div class="input-group col-md-9">
                            <input type="text" class="form-control" id="i_search_lov_role_signature" placeholder="Search">
                            <span class="input-group-btn">
                                <button class="btn  btn-primary default" type="button" onclick="filter_lov_role_signature()">Search</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div style="padding-bottom: 10px;"></div>
                <div class="row">
                    <div class="col-md-12" id="lov_role_signature_grid">
                        <table id="grid-table-lov_role_signature"></table>
                        <div id="grid-pager-lov_role_signature"></div>
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
        $("#modal_lov_role_signature_btn_blank").on('click', function() {
            $("#"+ $("#modal_lov_role_signature_id_val").val()).val("");
            $("#"+ $("#modal_lov_role_signature_name_val").val()).val("");
            $("#modal_lov_role_signature").modal("toggle");
        });
    });

    function modal_lov_role_signature_show(the_id_field, the_code_field) {
        modal_lov_role_signature_set_field_value(the_id_field, the_code_field);
        $("#modal_lov_role_signature").modal({backdrop: 'static'});
        modal_lov_role_signature_prepare_table();
    }


    function modal_lov_role_signature_set_field_value(the_id_field, the_code_field) {
         $("#modal_lov_role_signature_id_val").val(the_id_field);
         $("#modal_lov_role_signature_name_val").val(the_code_field);
    }

    function modal_lov_role_signature_set_value(the_id_val, the_code_val) {
         $("#"+ $("#modal_lov_role_signature_id_val").val()).val(the_id_val);
         $("#"+ $("#modal_lov_role_signature_name_val").val()).val(the_code_val);
         $("#modal_lov_role_signature").modal("toggle");

         $("#"+ $("#modal_lov_role_signature_id_val").val()).change();
         $("#"+ $("#modal_lov_role_signature_name_val").val()).change();
    }


    function modal_lov_role_signature_prepare_table() {
        var grid_selector = "#grid-table-lov_role_signature";
        var pager_selector = "#grid-pager-lov_role_signature";

        if($('#modal_lov_role_signature_masuk').val() == '1'){
            jQuery(grid_selector).jqGrid('setGridParam',{
                url: '<?php echo WS_JQGRID."report_admin.param_dws_p_signature_controller/read_lov_role_signature"; ?>',
            });
            $(grid_selector).trigger("reloadGrid");
            return false;
        }


        jQuery(grid_selector).jqGrid({
            url: '<?php echo WS_JQGRID."report_admin.param_dws_p_signature_controller/read_lov_role_signature"; ?>',
            datatype: "json",
            mtype: "POST",
            loadui: "disable",
            colModel: [
                {label: 'ID', name: 'p_reference_list_id', key: true, width: 5, hidden: true},
                {label: 'Code', name: 'code', width: 150 },
                {label: 'Code Type Ref', name: 'code_type_ref', width: 150 }
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

                var grid = $(grid_selector);
                var sel_id = grid.jqGrid('getGridParam', 'selrow');
                var p_reference_list_id = grid.jqGrid('getCell', sel_id, 'p_reference_list_id');
                var code = grid.jqGrid('getCell', sel_id, 'code');
                
                modal_lov_role_signature_set_value(p_reference_list_id, code);

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

                setTimeout(function(){
                    responsive_lov_role_signature_jqgrid(grid_selector, pager_selector);  
                }, 100);
            },
            //memanggil controller jqgrid yang ada di controller read
            editurl: '<?php echo WS_JQGRID."report_admin.param_dws_p_signature_controller/read_lov_role_signature"; ?>',
            caption: ""

        });

        jQuery(grid_selector).jqGrid('navGrid', pager_selector,
            {   //navbar options
                edit: false,
                editicon: 'fa fa-pencil blue bigger-120',
                add: false,
                addicon: 'fa fa-plus-circle purple bigger-120',
                del: false,
                delicon: 'fa fa-trash-o red bigger-120',
                search: false,
                searchicon: 'fa fa-search orange',
                refresh: true,
                afterRefresh: function () {
                    // some code here
                },

                refreshicon: 'fal fa-repeat-alt orange',
                view: false,
                viewicon: 'fa fa-search-plus grey bigger-120'
            },
            { /* options for the Edit Dialog */ },
            { /* options for the New Dialog */ },
            { /* options for the Delete Dialog */ },
            { /* options for the Search Dialog */ },
            { /* options for the View Dialog */ }
        );
    }

    function responsive_lov_role_signature_jqgrid(grid_selector, pager_selector) {

        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid( 'setGridWidth', $("#lov_role_signature_grid").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }

    $('#i_search_lov_role_signature').on('keyup', function(event){
        event.preventDefault();
        if(event.keyCode === 13) {
            var i_search_lov_role_signature = $('#i_search_lov_role_signature').val();
            jQuery("#grid-table-lov_role_signature").jqGrid('setGridParam',{
                url: '<?php echo WS_JQGRID."report_admin.param_dws_p_signature_controller/read_lov_role_signature"; ?>',
                postData: {
                    i_search : i_search_lov_role_signature
                }
            });
            $("#grid-table-lov_role_signature").trigger("reloadGrid");
        }
    });

    function filter_lov_role_signature(){
        var i_search_lov_role_signature = $('#i_search_lov_role_signature').val();
        
        jQuery("#grid-table-lov_role_signature").jqGrid('setGridParam',{
                url: '<?php echo WS_JQGRID."report_admin.param_dws_p_signature_controller/read_lov_role_signature"; ?>',
                postData: {
                    i_search : i_search_lov_role_signature
                }
        });
        $("#grid-table-lov_role_signature").trigger("reloadGrid");
    }

    jQuery('#modal_lov_role_signature').on('hide.bs.modal', function(){
       $('#modal_lov_role_signature_masuk').val('1');
       jQuery("#grid-table-lov_role_signature").jqGrid('clearGridData');
    });
</script>
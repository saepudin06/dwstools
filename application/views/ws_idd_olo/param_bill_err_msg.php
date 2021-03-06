<!-- breadcrumb -->
<?php echo breadCrumbs(getVarClean('menu_id', 'str', '0')); ?>
<!-- end breadcrumb -->

<div class="panel">
    <div class="panel-hdr">
        <h2>
            <!-- <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="javascript:;" role="tab" id="tab-1"><i class="fal fa-bolt mr-1"></i> Icons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" id="tab-2"><i class="fal fa-indent mr-1"></i> Menus</a>
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

        var postData = { module_id: "<?php echo $this->input->post('module_id'); ?>", p_job_type_id: "<?php echo $this->input->post('p_job_type_id'); ?>", parent_id: '' };

        if (is_set_grid == "false"){
            $('#is_set_grid').val("true");
            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."ws_idd_olo.param_bill_err_msg_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: postData,
                colModel: [
                    {label: 'Code', name: 'code', key: true, width: 150, align: 'center', editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
                    },
                    {label: 'Language Code', name: 'p_language_code', width: 150, align: 'center', editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
                    },
                    {label: 'Error Type Code', name: 'p_error_type_code', width: 150, align: 'center', editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
                    },
                    {label: 'Error Message', name: 'error_message', width: 300, editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
                    },
                    {label: 'Detail Message', name: 'detail_message', width: 300, editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
                    },
                    {label: 'Solution', name: 'solution', width: 300, editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
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
                    // set_grid_list();

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
                editurl: '<?php echo WS_JQGRID."ws_idd_olo.param_bill_err_msg_controller/crud"; ?>',
                caption: "Error Message"

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
            reload_grid(grid_selector, postData);
        }
    }

</script>
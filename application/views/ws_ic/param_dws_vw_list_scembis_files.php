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
                url: '<?php echo WS_JQGRID."ws_ic.param_dws_vw_list_scembis_files_controller/read"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: postData,
                colModel: [
                    { label: 'P Schembis ID', name: 'p_schembis_id', key: true, align: 'center', width: 150, hidden: true },
                    { label: 'p_regulation_id', name: 'p_regulation_id', width: 150, hidden: true },
                    { label: 'p_reg_files_id', name: 'p_reg_files_id', width: 150, hidden: true },
                    { label: 'p_schembis_type_id', name: 'p_schembis_type_id', width: 150, hidden: true },
                    { label: 'VC Name', name: 'vc_name', width: 150 },
                    { label: 'Fax', name: 'fax', width: 150 },
                    { label: 'Total Tier', name: 'total_tier', width: 150 },
                    { label: 'Limit1', name: 'limit1', width: 150 },
                    { label: 'Limit2', name: 'limit2', width: 150 },
                    { label: 'Limit3', name: 'limit3', width: 150 },
                    { label: 'Rate Tier1', name: 'rate_tier1', width: 150 },
                    { label: 'Rate Tier2', name: 'rate_tier2', width: 150 },
                    { label: 'Rate Tier3', name: 'rate_tier3', width: 150 },
                    { label: 'Rate Tier4', name: 'rate_tier4', width: 150 },
                    { label: 'Cap Revenue', name: 'cap_revenue', width: 150 },
                    { label: 'Flat Rat', name: 'flat_rat', width: 150 },
                    { label: 'Valid From', name: 'valid_from', width: 150, align: 'center' },
                    { label: 'Valid Until', name: 'valid_until', width: 150, align: 'center' },
                    { label: 'Description', name: 'description', width: 300 },
                    { label: 'Create Date', name: 'create_date', width: 150, align: 'center' },
                    { label: 'Update Date', name: 'update_date', width: 150, align: 'center' },
                    { label: 'Created By', name: 'created_by', width: 150, align: 'center' },
                    { label: 'Updated By', name: 'updated_by', width: 150, align: 'center' },
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
                subGrid: true,
                subGridRowExpanded: function(subgrid_id, row_id) {
                    // subgrid_id is a id of the div tag created within a table
                    // the row_id is the id of the row
                    // If we want to pass additional parameters to the url we can use
                    // the method getRowData(row_id) - which returns associative array in type name-value
                    // here we can easy construct the following
                    var selectedrow = $(this).jqGrid('getRowData', row_id);
                    console.log(selectedrow);
                    var p_reg_files_id = encodeURIComponent(selectedrow.p_reg_files_id);
                    p_reg_files_id = p_reg_files_id == '' ? "0" : p_reg_files_id;

                    var subgrid_table_id;
                    subgrid_table_id = subgrid_id+"_t";
                    jQuery("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>");
                    jQuery("#"+subgrid_table_id).jqGrid({
                        url: '<?php echo WS_JQGRID."ws_ic.param_dws_vw_list_scembis_files_controller/read_files"; ?>',
                        datatype: "json",
                        mtype: "POST",
                        postData: {p_reg_files_id: p_reg_files_id},
                        colModel: [
                            { label: 'p_reg_files_id', name: 'p_reg_files_id', key: true, width: 150, hidden: true },
                            {name: 'Actions', width: 150, align: "center",
                                formatter:function(cellvalue, options, rowObject) {
                                    var path_file = rowObject['directory'];
                                    var file_name = rowObject['file_name'];
                                    var location  = path_file+file_name;
                                    
                                    return '<button class="btn btn-info btn-xs"  type="button" onclick="download(\''+location+'\', \''+file_name+'\')">Download <i class="fal fa-download"></i></button>';
                                }
                            },
                            { label: 'Regulation No', name: 'regulation_no', width: 150 },
                            { label: 'File Name', name: 'file_name', width: 150 },
                            { label: 'Directory', name: 'directory', width: 150 },
                            { label: 'Regulation Date', name: 'reg_date', width: 150 },
                            { label: 'Effective Date', name: 'reg_eff_date', width: 150 },
                            { label: 'Description Regulation', name: 'desc_reg', width: 300 }
                        ],
                        height: '100%',
                        width: "100%",
                        autowidth: true,
                        viewrecords: true,
                        rowNum: 10,
                        rowList: [10,20,50],
                        rownumbers: true, // show row numbers
                        rownumWidth: 35, // the width of the row numbers columns
                        jsonReader: {
                            root: 'rows',
                            id: 'id',
                            repeatitems: false
                        }
                    });
                },
                subGridOptions : {
                    // load the subgrid data only once
                    // and the just show/hide
                    reloadOnExpand :true,
                    // select the row when the expand column is clicked
                    selectOnExpand : true,
                    plusicon : "fal fa fa-plus center bigger-110 blue",
                    minusicon  : "fal fa fa-minus center bigger-110 blue",
                    openicon : "fal fa fa-chevron-right center orange"
                 },
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
                editurl: '<?php echo WS_JQGRID."ws_ic.param_dws_vw_list_scembis_files_controller/read"; ?>',
                caption: "Schembis Type"

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
                { /* options for the New Dialog */ },
                { /* options for the Delete Dialog */ },
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

    function download(location, file_name){  
        var url = "<?php echo base_url();?>home/download?";
        url += "location=" + location;
        url += "&file_name=" + file_name;
        url += "&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>";
        window.location = url;
    }

</script>
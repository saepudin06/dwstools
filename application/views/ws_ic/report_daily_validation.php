<!-- breadcrumb -->
<?php echo breadCrumbs(getVarClean('menu_id', 'str', '0')); ?>
<!-- end breadcrumb -->
<div class="panel">
    <div class="panel-hdr">
        <h2>
            <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                <!-- <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="javascript:;" role="tab" id="tab-1"><i class="fal fa-cog mr-1"></i> Company</a>
                </li> -->
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">POTI <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="hidden" class="form-control" placeholder="Input POTI" readonly="" id="in_poti_id">
                                <input type="text" class="form-control" placeholder="Input POTI" readonly="" id="in_poti_name">
                                <div class="input-group-append">
                                    <button class="btn btn-primary waves-effect waves-themed" type="button" onclick="modal_lov_poti_show('in_poti_id', 'in_poti_name')"><i class="fal fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Periode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Input Periode" readonly="" id="in_periode">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <br>
                            <button type="button" class="btn btn-info mt-1 btn-block" onclick="set_grid()"><i class="fal fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <hr>
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
<?php $this->load->view('lov/report_ic/lov_poti'); ?>
<script>
    $('#in_periode').datepicker({
        autoclose: true,
        viewMode: "months",
        minViewMode: "months",
        format: 'yyyymm',
        orientation: "bottom left",
        todayHighlight : true,
        setDate: new Date()
    });
    $('#in_periode').attr('autocomplete', 'off');

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
        var poti = $('#in_poti_name').val();
        var period = $('#in_periode').val();

        if (poti == '' || period == ''){
            swal.fire({title: 'Attention', text: 'POTI and Period is required', type: "info"});
            return false;
        }
        var postData = {'poti': poti, 'period': period};

        if (is_set_grid != 'true'){
            $('#is_set_grid').val('true');
            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."ws_ic.report_daily_validation_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: postData,
                colModel: [
                    {label: 'Period', name: 'period', width: 150 },
                    {label: 'POTI', name: 'poti', width: 150 },
                    {label: 'Tanggal', name: 'tanggal', width: 150 },
                    {label: 'Day', name: 'day_category', width: 150 },
                    {label: 'Jam', name: 'jam', width: 150 },
                    {label: 'Duration', name: 'duration', width: 150 },
                    {label: 'AVG', name: 'avg_duration', width: 150, align: 'right', 
                        // formatter: function (cellvalue, options, rowObject) { return $.number( cellvalue ) } 
                    },
                    {label: 'Dev', name: 'dev_dur', width: 150, align: 'right', 
                        formatter: function (cellvalue, options, rowObject) { return $.number( cellvalue ) }
                    },
                    {label: 'Dev(%)', name: 'dev_prctg', width: 150, align: 'right', 
                        formatter: function (cellvalue, options, rowObject) { return cellvalue + '%' }
                    },
                    {label: 'Hasil Validasi', name: 'validation_rslt', width: 150 }
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
                editurl: '<?php echo WS_JQGRID."ws_ic.report_daily_validation_controller/crud"; ?>',
                caption: "Daily Validation"

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
            ).navButtonAdd(pager_selector,{
                caption:"", //Submit Job
                buttonicon:"fal fa-file-pdf red bigger-120",
                title: "Save to PDF",
                onClickButton: save_to_pdf,
                // cursor: "pointer",
                // position: "first",
                id :"btn-save-to-pdf",
            }).navButtonAdd(pager_selector,{
                caption:"", //Submit Job
                buttonicon:"fal fa-file-excel green bigger-120",
                title: "Save to Excel",
                onClickButton: save_to_excel,
                // cursor: "pointer",
                // position: "first",
                id :"btn-save-to-excel",
            });
        } else {
            reload_grid(grid_selector, postData);
        }
    }

    function save_to_pdf(){
        var param = "poti=" + $('#in_poti_name').val() + ":;period=" + $('#in_periode').val();
        window.open('<?php echo base_url(); ?>report_validation_daily/pageCetak?data='+btoa(param) , '_blank');
    }

    function save_to_excel(){
        var param = "poti=" + $('#in_poti_name').val() + ":;period=" + $('#in_periode').val();
        window.open('<?php echo base_url(); ?>index.php/utilities/save_to_excel?data='+btoa(param) , '_blank');
    }

</script>
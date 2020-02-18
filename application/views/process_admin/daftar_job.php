<!-- breadcrumb -->
<?php echo breadCrumbs(getVarClean('menu_id', 'str', '0')); ?>
<!-- end breadcrumb -->
<div class="panel">
    <div class="panel-hdr">
        <h2>
            <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" id="tab-1"><i class="fal fa-cog mr-1"></i> Job Type</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="javascript:;" role="tab" id="tab-2"><i class="fal fa-indent mr-1"></i> Daftar Job</a>
                </li>
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
                    <div class="col-md-4">
                        <input type="hidden" id="temp_id_tree" value="">
                        <div id="tree-job"></div>
                    </div>
                    <div class="col-md-8">
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
$("#tab-1").on("click", function(event) {

    event.stopPropagation();
    loadContentWithParams("process_admin.job_type", {
        menu_id: "<?php echo getVarClean('menu_id', 'str', '0'); ?>"
    });
});
</script>
<script>
    $(function() {
        set_tree_job();
        set_grid();

        $('#tree-job').on('select', function (event) {
            set_grid();
        });
    });

    function set_tree_job() {

        var temp_id = $("#temp_id_tree").val();
        var param_url = temp_id != "" ? "&selected=" + temp_id : "";
        var source =
        {
            datatype: "json",
            datafields: [
                { name: 'id' },
                { name: 'parentid' },
                { name: 'text' },
                { name: 'expanded' },
                { name: 'selected' },
                { name: 'icon' }
            ],
            id: 'id',
            url: '<?php echo WS_JQGRID."process_admin.daftar_job_controller/tree_job?p_job_type_id=".$this->input->post("p_job_type_id"); ?>' + param_url,
            async: false
        };

        $('#tree-job').jqxTree('clear');

        // create data adapter.
        var dataAdapter = new $.jqx.dataAdapter(source);
        dataAdapter.dataBind();
        var records = dataAdapter.getRecordsHierarchy('id', 'parentid', 'items', [{ name: 'text', map: 'label'}]);
        $('#tree-job').jqxTree({
            source: records
        });

    }

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

        var item = $('#tree-job').jqxTree('getSelectedItem');
        var parent_id = $(item).attr('id');
        var postData = { module_id: "<?php echo $this->input->post('module_id'); ?>", p_job_type_id: "<?php echo $this->input->post('p_job_type_id'); ?>", parent_id: parent_id };

        $("#temp_id_tree").val(parent_id);

        if (is_set_grid == "false"){
            $('#is_set_grid').val("true");
            jQuery(grid_selector).jqGrid({
                url: '<?php echo WS_JQGRID."process_admin.daftar_job_controller/crud"; ?>',
                datatype: "json",
                mtype: "POST",
                postData: postData,
                colModel: [
                    {label: 'ID', name: 'p_job_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                    {label: 'P Job Type ID', name: 'p_job_type_id', width: 5, sorttype: 'number', editable: true, hidden: true},
                    {label: 'Module ID', name: 'module_id', width: 5, sorttype: 'number', editable: true, hidden: true},
                    {label: 'Code', name: 'code', width: 300, editable: false,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
                    },
                    {label: 'Code', name: 'code', width: 300, align: 'center', hidden: true, editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
                    },
                    {label: 'Procedure Name', name: 'procedure_name', width: 300, editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
                    },
                    {label: 'List No', name: 'listing_no', width: 150, editable: true, align: 'center',
                        editoptions:{
                            size: 30,
                            maxlength:64,
                            dataInit: function(element) {
                                $(element).keypress(function(e){
                                     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                        return false;
                                     }
                                });
                            }
                        }, editrules: { required: true }
                    },
                    {label: 'Parallel', name: 'is_parallel', width: 150, editable: true, align: 'center',
                        editrules: { required: true },
                        formatter: function (cellvalue, options, rowObject) { 
                            return rowObject['is_parallel'] == 'Y' ? 'YES' : 'NO';
                        },
                        edittype: 'select',
                        editoptions: {
                            value: "N:NO;Y:YES",
                            dataInit: function(elem) {
                                $(elem).width(210);  // set the width which you need
                            }
                        }
                    },
                    {label: 'Parallel Degree', name: 'parallel_degree', width: 150, editable: true, align: 'center',
                        editoptions:{
                            size: 30,
                            maxlength:64,
                            dataInit: function(element) {
                                $(element).keypress(function(e){
                                     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                        return false;
                                     }
                                });
                            }
                        }, editrules: { required: true }
                    },
                    {label: 'Finish', name: 'is_finish', width: 150, editable: true, align: 'center',
                        editrules: { required: true },
                        formatter: function (cellvalue, options, rowObject) { 
                            return rowObject['is_finish'] == 'Y' ? 'YES' : 'NO';
                        },
                        edittype: 'select',
                        editoptions: {
                            value: "N:NO;Y:YES",
                            dataInit: function(elem) {
                                $(elem).width(210);  // set the width which you need
                            }
                        }
                    },
                    {label: 'Reprocess', name: 'is_reprocess', width: 150, editable: true, align: 'center',
                        editrules: { required: true },
                        formatter: function (cellvalue, options, rowObject) { 
                            return rowObject['is_reprocess'] == 'Y' ? 'YES' : 'NO';
                        },
                        edittype: 'select',
                        editoptions: {
                            value: "N:NO;Y:YES",
                            dataInit: function(elem) {
                                $(elem).width(210);  // set the width which you need
                            }
                        }
                    },
                    {label: 'Exclude in queue\'s', name: 'exclude_in_queues', width: 150, editable: true, align: 'center',
                        editrules: { required: true },
                        formatter: function (cellvalue, options, rowObject) { 
                            return rowObject['exclude_in_queues'] == 'Y' ? 'YES' : 'NO';
                        },
                        edittype: 'select',
                        editoptions: {
                            value: "N:NO;Y:YES",
                            dataInit: function(elem) {
                                $(elem).width(210);  // set the width which you need
                            }
                        }
                    },
                    {label: 'Parent ID', name: 'parent_id', width: 75, sorttype: 'number', align: 'center', editable: true},
                    // {label: 'Batalkan Parent', name: 'parent_id', width: 75, sorttype: 'number', align: 'center' },
                    {label: 'Control Table Name', name: 'control_table_name', width: 300, editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }, editrules: { required: true }
                    },
                    {label: 'Description', name: 'description', width: 308, align: 'left', editable: true,
                        edittype:'textarea',
                        editoptions:{
                            size: 30,
                            maxlength: 128,
                            dataInit: function(elem) {
                                $(elem).width(210);  // set the width which you need
                            }
                        }
                    },
                    {label: 'External Program Name', name: 'external_program', width: 300, editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }
                    },
                    {label: 'Path Program', name: 'path_prog', width: 300, editable: true,
                          editoptions:{
                              size: 30,
                              maxlength:64
                         }
                    },
                    {label: 'External Program', name: 'is_external', width: 300, editable: true,
                        formatter: function (cellvalue, options, rowObject) { 
                            return rowObject['is_external'] == 'Y' ? 'YES' : 'NO';
                        },
                        edittype: 'select',
                        editoptions: {
                            value: "N:NO;Y:YES",
                            dataInit: function(elem) {
                                $(elem).width(210);  // set the width which you need
                            }
                        }
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
                editurl: '<?php echo WS_JQGRID."process_admin.daftar_job_controller/crud"; ?>',
                caption: ""

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
                        parent_id: function() {
                            var item = $('#tree-job').jqxTree('getSelectedItem');
                            var parent_id = $(item).attr('id');
                            return parent_id;
                        },
                        module_id: function() {
                            return <?php echo $this->input->post('module_id'); ?>;
                        }, 
                        p_job_type_id: function() {
                            return <?php echo $this->input->post('p_job_type_id'); ?>;
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
                        $('#tr_code', form).show();
                        $('#tr_parent_id', form).hide();

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
                        set_tree_job();
                        return [true,"",response.responseText];
                    }
                },
                {
            
                    //new record form
                    editData: {
                        parent_id: function() {
                            var item = $('#tree-job').jqxTree('getSelectedItem');
                            var parent_id = $(item).attr('id');
                            return parent_id;
                        },
                        module_id: function() {
                            return <?php echo $this->input->post('module_id'); ?>;
                        }, 
                        p_job_type_id: function() {
                            return <?php echo $this->input->post('p_job_type_id'); ?>;
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
                        $('#tr_code', form).show();
                        $('#tr_parent_id', form).hide();
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
                        set_tree_job();
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
                        set_tree_job();
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
<!-- breadcrumb -->
<?php echo breadCrumbs(getVarClean('menu_id', 'str', '0')); ?>
<!-- end breadcrumb -->

<div class="panel">
    <div class="panel-hdr">
        <h2></h2>
        <div class="panel-toolbar">
            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
            <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button>
        </div>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="frame-wrap m-0">
                <div class="row">
                    <div class="col-md-12 grid-ui">
                        <table id="grid-table"></table>
                        <div id="grid-pager"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="panel"  id="grid_group">
    <div class="panel-hdr">
        <h2></h2>
        <div class="panel-toolbar">
            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
            <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button>
        </div>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <div class="frame-wrap m-0">
                <div class="row">
                    <div class="col-md-12 grid-ui">
                        <table id="grid-table-group"></table>
                        <div id="grid-pager-group"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    $('#grid_group').hide();

    var grid_selector = "#grid-table";
    var pager_selector = "#grid-pager";

        jQuery(grid_selector).jqGrid({
            url: '<?php echo WS_JQGRID."ws_og.parameter_trunk_controller/read"; ?>',
            datatype: "json",
            mtype: "POST",
            colModel: [

                {label: 'Trunk ID', key:true, name: 'p_trunk_id', width: 75, hidden:true },
                {label: 'Code', name: 'code', width: 150, align: "center" },
                {label: 'Company ID', name: 'p_company_id', width: 120, hidden:true},
                {label: 'Organization ID',name: 'p_organization_id', width: 100, hidden:true},
                {label: 'City Code',name: 'p_city_code', width: 150, align: "center" },
                {label: 'Em Deg', name: 'em_deg', width: 150, align: 'center'},
                {label: 'Em Min', name: 'em_min', width: 150, align: "center"},
                {label: 'Sl Deg', name: 'sl_deg', width: 150, align: 'center'},
                {label: 'Sl Min', name: 'sl_min', width: 150, align: 'center'},
                {label: 'Coverage Area ID',name: 'p_coverage_area_id', width: 150, hidden:true},
                {label: 'Description', name: 'description', width: 300 },
                {label: 'Em Sec', name: 'em_sec', width: 150, align: 'center'},
                {label: 'Sl Sec',name: 'sl_sec', width: 150, align: "center"},
                {label: 'Regulation ID', name: 'p_regulation_id', width: 150, hidden:true},
                {label: 'Access Code',name: 'p_access_code', width: 150, align: "center"},
                {label: 'Update Date',name: 'update_date', width: 150, align: "center"},
                {label: 'Update By',name: 'update_by', width: 150, align: "center"}
               
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
                  var p_trunk_id = $('#grid-table').jqGrid('getCell', rowid, 'p_trunk_id');
                  jQuery("#grid-table-group").jqGrid('setGridParam', {
                        url: '<?php echo WS_JQGRID."ws_og.parameter_trunk_controller/readGroup"; ?>',
                        postData: {p_trunk_id: p_trunk_id}
                  });
                  $("#grid-table-group").trigger("reloadGrid");
                  $('#grid_group').show();

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
                    swal.fire({title: 'Attention', text: response.message, html: true, type: "warning"});
                } else {
                    $('#grid_group').hide();
                }

                responsive_jqgrid(grid_selector, pager_selector);

            },
            //memanggil controller jqgrid yang ada di controller crud
            editurl: '<?php echo WS_JQGRID."ws_og.parameter_trunk_controller/read"; ?>',
            caption: "Trunk"

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
                    // jQuery("#detailsPlaceholder").hide();
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
     

    function responsive_jqgrid(grid_selector, pager_selector) {

        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid( 'setGridWidth', $(".grid-ui").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }


    var grid_selector = "#grid-table-group";
    var pager_selector = "#grid-pager-group";

        jQuery(grid_selector).jqGrid({
            url: '<?php echo WS_JQGRID."ws_og.parameter_trunk_controller/readGroup"; ?>',
            datatype: "json",
            mtype: "POST",
            colModel: [

                {label: 'Trunk Group ID', key:true, name: 'p_trunk_group_id', width: 75, hidden:true},
                {label: 'Code', name: 'code', width: 150, align: "center"},
                {label: 'Trunk ID', name: 'p_trunk_id', width: 120, hidden:true},
                {label: 'Company ID',name: 'p_company_id', width: 100, hidden:true},
                {label: 'Organization ID',name: 'p_organization_id', width: 100,hidden:true},
                {label: 'Service Category ID',name: 'p_service_category_id', width: 150, hidden:true},
                {label: 'Minimum Usage',name: 'minimum_usage', width: 150, hidden:true},
                {label: 'Access Code', name: 'p_access_code', width: 150, align: 'center'},
                {label: 'Is International', name: 'is_international', width: 150, align: 'center'},
                {label: 'Is Interlink', name: 'is_interlink', width: 150, align: 'center'},
                {label: 'Sl Min', name: 'sl_min', width: 150, align: 'center'},
                {label: 'Allocation',name: 'allocation', width: 150, align: 'center'},
                {label: 'Regulation ID',name: 'p_regulation_id', width: 100, hidden:true},
                {label: 'Description', name: 'description', width: 300 },
                {label: 'Valid From',name: 'valid_from', width: 150, align: 'center'},
                {label: 'Valid Until', name: 'valid_until', width: 150, align: 'center'},
                {label: 'Update Date',name: 'update_date', width: 150, align: 'center'},
                {label: 'Update By', name: 'update_by', width: 150, align: 'center'}
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
                    swal.fire({title: 'Attention', text: response.message, html: true, type: "warning"});
                }

                responsive_jqgrid(grid_selector, pager_selector);

            },
            //memanggil controller jqgrid yang ada di controller crud
            editurl: '<?php echo WS_JQGRID."ws_og.parameter_trunk_controller/readGroup"; ?>',
            caption: "Trunk Group"

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
                    // jQuery("#detailsPlaceholder").hide();
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
     

    function responsive_jqgrid(grid_selector, pager_selector) {

        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid( 'setGridWidth', $(".grid-ui").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }


</script>
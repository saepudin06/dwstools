<!-- breadcrumb -->
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
    <li class="breadcrumb-item">Administrator</li>
    <li class="breadcrumb-item active">Modules</li>
</ol>
<!-- end breadcrumb -->

<div class="panel">
    <div class="panel-hdr">
        <h2>
            <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" id="tab-1"><i class="fal fa-cog mr-1"></i> Modules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="javascript:;" role="tab" id="tab-2"><i class="fal fa-indent mr-1"></i> Menus</a>
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
                        <div class="card m-auto border">
                            <div class="card-header py-2">
                                <div class="card-title">
                                    Menu
                                </div>
                                
                            </div>
                            <div class="card-body"> 
                                <div id="tree-menu"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8" id="grid-ui">
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
    loadContentWithParams("administration.modules",{});
});
</script>

<?php $this->load->view('lov/lov_icon'); ?>

<script>
    $(function($) {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        $("#grid-table").jqGrid({
            url: '<?php echo WS_JQGRID."administration.menus_controller/crud"; ?>',
            postData: { module_id : <?php echo $this->input->post('module_id'); ?>},
            datatype: "json",
            mtype: "POST",
            colModel: [
                {label: 'ID', name: 'menu_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                {label: 'Parent ID', name: 'parent_id', width: 5, sorttype: 'number', editable: true, hidden: true},
                {label: 'Title',name: 'menu_title',width: 150, align: "left",editable: true,
                    editoptions: {
                        size: 30,
                        maxlength:255
                    },
                    editrules: {required: true}
                },
                {label: 'Url',name: 'menu_url',width: 150, align: "left",editable: true,
                    editoptions: {
                        size: 30,
                        maxlength:255
                    }
                },
                {label: 'Order',name: 'menu_order',width: 150, align: "left",editable: true,
                    editoptions: {
                        size: 30,
                        maxlength:255,
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
                {label: 'Icon', name: '', width: 120, align: "center", editable: false, search:false, sortable:false,
                    formatter: function(cellvalue, options, rowObject) {
                        var icon = rowObject['menu_icon'];
                        return '<i class="'+icon+' bigger-140"></i>';
                    }
                },
                {label: 'Icon',
                    name: 'menu_icon',
                    width: 200,
                    sortable: true,
                    editable: true,
                    hidden: true,
                    editrules: {edithidden: true, required:false},
                    edittype: 'custom',
                    editoptions: {
                        "custom_element":function( value  , options) {
                            var elm = $('<span></span>');

                            // give the editor time to initialize
                            setTimeout( function() {
                                elm.append('<input id="form_icon_id" type="text"  style="display:none;">'+
                                        '<input id="form_icon_code" readonly type="text" class="FormElement form-control" placeholder="Choose Icon">'+
                                        '<button class="btn btn-success" type="button" onclick="showLOVIcon(\'form_icon_id\',\'form_icon_code\')">'+
                                        '   <span class="fal fa-search bigger-110"></span>'+
                                        '</button>');
                                $("#form_icon_id").val(value);
                                elm.parent().removeClass('jqgrid-required');
                            }, 100);

                            return elm;
                        },
                        "custom_value":function( element, oper, gridval) {

                            if(oper === 'get') {
                                return $("#form_icon_id").val();
                            } else if( oper === 'set') {
                                $("#form_icon_id").val(gridval);
                                var gridId = this.id;
                                // give the editor time to set display
                                setTimeout(function(){
                                    var selectedRowId = $("#"+gridId).jqGrid ('getGridParam', 'selrow');
                                    if(selectedRowId != null) {
                                        var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'menu_icon');
                                        $("#form_icon_code").val( code_display );
                                    }
                                },100);
                            }
                        }
                    }
                }
            ],
            height: '100%',
            autowidth: true,
            rowNum: 10,
            viewrecords: true,
            rowList: [10, 20, 50],
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            altRows: true,
            shrinkToFit: true,
            multiboxonly: true,
            onSelectRow: function (rowid) {
                //do something

            },
            sortorder:'',
            pager: '#grid-pager',
            jsonReader: {
                root: 'rows',
                id: 'id',
                repeatitems: false
            },
            loadComplete: function (response) {
                if(response.success == false) {
                    swal.fire({title: 'Attention', text: response.message, html: true, type: "warning"});
                }

                responsive_jqgrid('#grid-table', '#grid-pager');
            },
            //memanggil controller jqgrid yang ada di controller crud
            editurl: '<?php echo WS_JQGRID."administration.menus_controller/crud"; ?>',
            caption: ""

        });


        $('#grid-table').jqGrid('navGrid', '#grid-pager',
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
                    var response = $.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }
                    reloadTreeMenu();
                    return [true,"",response.responseText];
                }
            },
            {
                editData: {
                    parent_id: function() {
                        var item = $('#tree-menu').jqxTree('getSelectedItem');
                        var id = $(item).attr('id');
                        return id;
                    },
                    module_id: function() {
                        return <?php echo $this->input->post('module_id'); ?>;
                    }
                },
                //new record form
                serializeEditData: serializeJSON,
                closeAfterAdd: true,
                clearAfterAdd : true,
                closeOnEscape:true,
                recreateForm: true,
                width: 'auto',
                errorTextFormat: function (data) {
                    return 'Error: ' + data.responseText
                },
                viewPagerButtons: false,
                beforeShowForm: function (e, form) {
                    var form = $(e[0]);
                    style_edit_form(form);

                    setTimeout(function() {
                        clearInputIcon();
                    },100);

                },
                afterShowForm: function(form) {
                    form.closest('.ui-jqdialog').center();
                },
                afterSubmit:function(response,postdata) {
                    var response = $.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }

                    $(".tinfo").html('<div class="ui-state-success">' + response.message + '</div>');
                    var tinfoel = $(".tinfo").show();
                    tinfoel.delay(3000).fadeOut();

                    clearInputIcon();
                    reloadTreeMenu();
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
                    var response = $.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }

                    reloadTreeMenu();
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

    });

    function responsive_jqgrid(grid_selector, pager_selector) {

        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid( 'setGridWidth', $("#grid-ui").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }

    $('.header-btn').on('click', function(){
        setTimeout(function(){
              responsive_jqgrid('#grid-table', '#grid-pager');
        },500);
        
        
    });
</script>


<script>
    function reloadTreeMenu() {
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
            url: '<?php echo WS_JQGRID."administration.menus_controller/tree_json?module_id=".$this->input->post("module_id")."&module_name=".$this->input->post("module_name"); ?>',
            async: false
        };

        $('#tree-menu').jqxTree('clear');

        // create data adapter.
        var dataAdapter = new $.jqx.dataAdapter(source);
        dataAdapter.dataBind();
        var records = dataAdapter.getRecordsHierarchy('id', 'parentid', 'items', [{ name: 'text', map: 'label'}]);
        $('#tree-menu').jqxTree({
            source: records
        });

    }

    $(function($) {
        reloadTreeMenu();

        $('#tree-menu').on('select', function (event) {
            var item = $('#tree-menu').jqxTree('getItem', event.args.element);
            $('#grid-table').jqGrid('setGridParam', {
                url: '<?php echo WS_JQGRID."administration.menus_controller/crud"; ?>',
                postData: {parent_id: item.id, module_id: <?php echo $this->input->post('module_id'); ?>}
            });

            // $('#grid-table').jqGrid('setCaption', 'Child Menu :: ' + item.label);
            $("#grid-table").trigger("reloadGrid");
        });
    });
</script>

<script>
/**
 * [showLOVIcon called by input menu_icon to show List Of Value (LOV) of icon]
 * @param  {[type]} id   [description]
 * @param  {[type]} code [description]
 * @return {[type]}      [description]
 */
function showLOVIcon(id, code) {
    modal_lov_icon_show(id, code);
}

/**
 * [clearInputIcon called by beforeShowForm method to clean input of menu_icon]
 * @return {[type]} [description]
 */
function clearInputIcon() {
    $('#form_icon_id').val('');
    $('#form_icon_code').val('');
}

</script>
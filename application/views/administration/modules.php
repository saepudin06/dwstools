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
                    <a class="nav-link active" data-toggle="tab" href="javascript:;" role="tab" id="tab-1"><i class="fal fa-cog mr-1"></i> Modules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="javascript:;" role="tab" id="tab-2"><i class="fal fa-indent mr-1"></i> Menus</a>
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
                    <div class="col-md-12" id="grid-ui">
                        <table id="grid-table"></table>
                        <div id="grid-pager"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php $this->load->view('lov/lov_icon'); ?>

<script>
$("#tab-2").on("click", function(event) {

    event.stopPropagation();
    var grid = $('#grid-table');
    module_id = grid.jqGrid ('getGridParam', 'selrow');
    module_name = grid.jqGrid ('getCell', module_id, 'module_name');

    if(module_id == null) {
        swal.fire('Informasi','Silahkan pilih salah satu module','info');
        return false;
    }

    loadContentWithParams("administration.menus", {
        module_id: module_id,
        module_name : module_name
    });
});
</script>
<script>
    jQuery(function($) {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        jQuery("#grid-table").jqGrid({
            url: '<?php echo WS_JQGRID."administration.modules_controller/crud"; ?>',
            datatype: "json",
            mtype: "POST",
            colModel: [
                {label: 'ID', name: 'module_id', key: true, width: 5, sorttype: 'number', editable: true, hidden: true},
                {label: 'Nama Modul',name: 'module_name', width: 150, align: "left",editable: true,
                    editoptions: {
                        size: 30,
                        maxlength:32,
                    },
                    editrules: {required: true}
                },
                {label: 'Deskripsi',name: 'module_description', width: 200, align: "left",editable: true,
                    edittype:'textarea',
                    editoptions: {
                        rows: 2,
                        cols:50,
                        dataInit: function(elem) {
                            $(elem).width(210);  // set the width which you need
                        }
                    }
                },
                {label: 'Icon',
                    name: 'module_icon',
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
                                        '<button class="btn btn-success" style="margin-bottom: 2px; margin-left: 2px;" type="button" onclick="showLOVIcon(\'form_icon_id\',\'form_icon_code\')">'+
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
                                        var code_display = $("#"+gridId).jqGrid('getCell', selectedRowId, 'module_icon');
                                        $("#form_icon_code").val( code_display );
                                    }
                                },100);
                            }
                        }
                    }
                },
                {label: 'Title',name: 'module_title',width: 150, align: "left",editable: true,
                    editoptions: {
                        size: 30,
                        maxlength:5
                    },
                    editrules: {required: false}
                },
                {label: 'Class', name: 'module_class', width: 150, editable: true, edittype: 'select',
                    editoptions: {
                        value: "bg-success-200:bg-success-200;bg-warning-200:bg-warning-200;bg-danger-200:bg-danger-200;bg-info-200:bg-info-200;bg-primary-200:bg-primary-200;bg-fusion-200:bg-fusion-200",
                        dataInit: function(elem) {
                            $(elem).width(210);  // set the width which you need
                        }
                    },
                    editrules: {edithidden: true, required: false}
                },
                {label: 'Status Aktif',name: 'is_active',width: 120, align: "left",editable: true, edittype: 'select', hidden:true,
                    editrules: {edithidden: true, required: false},
                    editoptions: {
                    value: "Y:AKTIF;N:TIDAK AKTIF",
                    dataInit: function(elem) {
                        $(elem).width(210);  // set the width which you need
                    }
                }},
                {label: 'Status Aktif', name: 'status_active', width: 120, align: "left", editable: false}
            ],
            height: '100%',
            autowidth: true,
            viewrecords: true,
            rowNum: 5,
            rowList: [5, 10, 20],
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            altRows: true,
            shrinkToFit: true,
            multiboxonly: true,
            onSelectRow: function (rowid) {
                /*do something when selected*/
				var celValue = $('#grid-table').jqGrid('getCell', rowid, 'module_id');
                var celCode = $('#grid-table').jqGrid('getCell', rowid, 'module_name');

            },
            sortorder:'',
            pager: '#grid-pager' ,
            jsoncruder: {
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
            editurl: '<?php echo WS_JQGRID."administration.modules_controller/crud"; ?>',
            caption: ""

        });

        jQuery('#grid-table').jqGrid('navGrid', '#grid-pager',
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
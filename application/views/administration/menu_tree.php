<div class="ui-jqgrid">
    <div class="ui-jqgrid-view table-responsive">
        <div class="ui-jqgrid-titlebar ui-jqgrid-caption">
            <span class="ui-jqgrid-title">Daftar Menu</span>
        </div>
    </div>

    <div style="margin:5px">
        <input type="checkbox" name="all" id="all" value=""> All<br>
            <button class="btn btn-sm btn-primary" id="save">Save</button>
    </div>

    <div class="widget-body">
        <div style="margin:5px">
             <div id='jqxTree' style='visibility: hidden;'>

             </div>
             <div>
                <input type="hidden" name="group_id" id="group_id" value="<?= $group_id;?>">
             </div>
        </div>
    </div>
</div>
<!-- <div class="widget-box widget-color-blue">
    <div class="widget-header">
        <h4 class="ui-jqgrid-titlebar">Daftar Menu</h4>
    </div>


    <div style="margin-left:10px;">
        <input type="checkbox" name="all" id="all" value="">All<br>
            <button class="btn btn-sm btn-primary" id="save">Save</button></div>



    <div class="widget-body">
        <div class="widget-main padding-8">
             <div id='jqxTree' style='visibility: hidden;'>

             </div>
             <div>
                <input type="hidden" name="group_id" id="group_id" value="<?= $group_id;?>">
             </div>
        </div>
    </div>

</div> -->
<script type="text/javascript">
    $(document).ready(function () {

        $('#jqxTree').css('visibility', 'visible');

        $('#save').click(function () {
            var str = [];
            var uncheck_val = [];
            var items = $('#jqxTree').jqxTree('getCheckedItems');
            var itemsUn = $('#jqxTree').jqxTree('getUncheckedItems');

            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                str[i]= item.value ;
            }

            for (var u = 0; u < itemsUn.length; u++) {
                var uncheck = itemsUn[u];
                uncheck_val[u]= uncheck.value ;
            }
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('Admin/updateProfile');?>',
                data: { check_val:str,
                        uncheck_val:uncheck_val, 
                        group_id:<?= $group_id;?> 
                },
                timeout: 10000,
                success: function(data) {
                    $("#menutreAjax").html(data);
                    $('#jqxTree').jqxTree('refresh');
                }
            })
        });
        $('#selectall').click(function(event) {
            $('#jqxTree').jqxTree('checkAll');
        });
        $('#all').on('change', function (event) {
            if($(this).is(':checked')){
                $('#jqxTree').jqxTree('checkAll');
            }else{
                $('#jqxTree').jqxTree('uncheckAll');
            }

        });
       

    });
</script>
<script>
    $(document).ready(function () {
        // prepare the data
        var source =
        {
            datatype: "json",
            datafields: [
                { name: 'id' },
                { name: 'parentid' },
                { name: 'text' },
                { name: 'value' },
                { name: 'checked' },
                { name: 'app_menu_group_id' }
            ],
            id: 'id',
            url: '<?php echo site_url('Admin/getMenuTreeJson');?>/<?= $group_id;?>',
            async: false
//            localdata: data
        };
        // create data adapter.
        var dataAdapter = new $.jqx.dataAdapter(source);
        dataAdapter.dataBind();
        var records = dataAdapter.getRecordsHierarchy('id', 'parentid', 'items', [{ name: 'text', map: 'label'}]);
        $('#jqxTree').jqxTree({
            source: records,
            checkboxes: true,
            height: '300px'
        });


        $("#jqxTree").on('select', function (event) {
                var args = event.args;
                var item = $('#jqxTree').jqxTree('getItem', args.element);
                var app_menu_group_id;
                var id = args.element.id;
                var recursion = function (object) {
                    for (var i = 0; i < object.length; i++) {
                        if (id == object[i].id) {
                            app_menu_group_id = object[i].app_menu_group_id;
                            break;
                        } else if (object[i].items) {
                            recursion(object[i].items);
                        };
                    };
                };
                recursion(records);

        });


    });

</script>

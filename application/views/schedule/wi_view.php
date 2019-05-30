
<link rel="stylesheet" href="<?php echo base_url(); ?>bootstrap/css/bootstrap-treeview.css" />

<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-treeview.js"></script>

<script>
    var gridName = "gridGeneric";
    var dsBasicObject = new function () {
        // Private Variables;
        var thisObj = this;
        thisObj.gridName = undefined;

        // The starting function
        this.start = function (gridNamePar) {
            thisObj.tree_controller = {
                1: 'schedule/wi',
                2: 'schedule/wi_revision',
                3: 'schedule/wi_section',
                4: 'schedule/wi_section_workflow'
            };

            thisObj.wi_trunk = <?php echo $wi_trunk; ?>;

            if (thisObj.wi_trunk.length === 0) {
                thisObj.beforeEditWi({}, {level: 1, recid: -1});
            }
            thisObj.treeViewConf = {
                data: thisObj.wi_trunk,// data is not optional
                levels: 4,
                showTags: true,
                loadingIcon: "fa fa-hourglass",//懒加载过程中显示的沙漏字符图标
                lazyLoad: thisObj.load_branch,//load_branch为点击懒加载节点目录时，运行的函数名称，把后端的数据添加到这个节点下面
                onNodeSelected: thisObj.beforeEditWi,//add node click event
                // The naming convention for callback's is to prepend with `on`
                // and capitalize the first letter of the event name
                // e.g. nodeSelected -> onNodeSelected
                onRightClick: thisObj.popupOptions,//add node right click event
            };

            thisObj.wi_treeview = $('#wi_treeview').treeview(thisObj.treeViewConf);

            this.search = function (e) {
                var pattern = $('#input-search').val();
                var options = {
                    ignoreCase: $('#chk-ignore-case').is(':checked'),
                    exactMatch: $('#chk-exact-match').is(':checked'),
                    revealResults: true,
                };
                var results = thisObj.wi_treeview.treeview('search', [pattern, options]);
                if (results.length > 0) {
                    var scroll = results[results.length - 1].$el.offset().top - $('#wi_treeview').offset().top + $('#wi_treeview').scrollTop();
                    $('#wi_treeview').animate({scrollTop: scroll}, 300);
                }
            };

            thisObj.addListeners();

        };

        // Function to add listeners (events). Here is empty but it is part of the basic object structure
        this.addListeners = function () {
            $('#btn-search').on('click', thisObj.search);

            $('#btn-clear-search').on('click', function (e) {
                thisObj.wi_treeview.treeview('clearSearch');
                thisObj.wi_treeview.treeview('collapseAll');
                $('#input-search').val('');

            });
        };

        // It runs before close the screen (by choosing another option on the menu, for example). If you return false the system will not leave the screen
        this.beforeClose = function () {
            return true;
        }


        // Event that will be triggered when the object is being closed. Location to remove listeners, destroy grids, etc....
        this.close = function () {

            //introRemove();
            return true;
        }

        //add or update node in the tree
        this.addUpdateNode = function (level, pk, parent) {
            $.myCgbAjax({
                url: thisObj.tree_controller[level] + '/treeView/0/' + pk,
                message: javaMessages.updating,
                box: 'none',
                systemRequest: false,
                dataType: 'json',
                success: function (res, status) {
                    var _node = thisObj.findNodeById(level, pk);
                    if (_node) {
                        var newNode = _node;
                        $.each(_node, function (i, v) {
                            if (!(typeof (res[0][i]) == 'undefined')) {
                                newNode[i] = res[0][i];
                            }
                        });
                        thisObj.wi_treeview.treeview('updateNode', [_node, newNode]);
                    } else {
                        var parentNode = thisObj.findNodeById(level - 1, parent);
                        thisObj.wi_treeview.treeview('addNode', [res, parentNode]);
                    }
                }
            });
        };
        this.deleteNode = function (level, pk) {
            var nodeDel = thisObj.findNodeById(level, pk);
            thisObj.wi_treeview.treeview('removeNode', nodeDel);
        };

        this.findNodeById = function (level, pk) {
            var allNodes = thisObj.wi_treeview.treeview('getNodes');
            var _node = 0;
            $.each(allNodes, function (i, v) {
                if (v.level == level && v.recid == pk) {
                    _node = v;
                    return false;
                }
            });
            return _node;
        };

        this.load_branch = function(node, func, async) {
            $.myCgbAjax({
                url: thisObj.tree_controller[node.level+1] + '/treeView/' + node.recid,
                message: javaMessages.retrieveData,
                box: 'none',
                systemRequest: false,
                dataType: 'json', async: async,
                success: function (res, status) {
                    func(res);
                    //$("#wi_treeview").treeview("addNode", [res,node]);
                }

            });
        };

        this.beforeEditWi = function (event, data, upid) {
            if (typeof (dsMainObject) !== "undefined" && !dsMainObject.beforeClose()) {
                messageBoxYesNo(javaMessages.info_changed_close, function () {
                    thisObj.editWi(event, data, upid);
                });
            } else {
                thisObj.editWi(event, data, upid);
            }
        };

        this.editWi = function (event, data, upid) {//event parameter are defined by treeview, must exist and obey the sequence
            upid = upid ? '/' + upid : '';
            $.myCgbAjax({
                url: thisObj.tree_controller[data.level] + '/callWiForm/' + data.recid + upid,
                message: javaMessages.updating,
                box: 'none',
                systemRequest: false,
                dataType: 'json',
                success: function (res, status) {
                    $('#myGrid').html(res.html);

                }
            });

        };

        this.operationList = function (obj, oper) {
            var data = {
                'level': obj.parent().parent('ul').attr('level'),
                'recid': obj.parent().parent('ul').attr('pk'),
            };
            if (oper === 'add') {
                data.recid = -1;
            } else if (oper === 'duplicate') {
                var upid = -1;
            } else if (oper === 'addNext') {
                ++data.level;
                upid = data.recid;
                data.recid = -1;

            } else if (oper === 'expandNode') {
                var node = this.findNodeById(data.level, data.recid);
                thisObj.wi_treeview.treeview('expandNode', [node, {levels: 4}]);
                this.closeOptions();
                return;
            }
            this.beforeEditWi({}, data, upid);
            this.closeOptions();
        };

        this.popupOptions = function (event, data) {
            var optionArr = {
                '-1':'<li><a href="javascript:void(0);" onclick="dsBasicObject.operationList($(this),\'edit\')"><i class="fa fa-pencil-square-o contextMenuPluginIconFonts"></i><span>Edit</span></a></li>',
                '-2':'<li><a href="javascript:void(0);" onclick="dsBasicObject.operationList($(this),\'add\')"><i class="fa fa-plus contextMenuPluginIconFonts"></i><span>Add New</span></a></li>',
                '1':'<li><a href="javascript:void(0);" onclick="dsBasicObject.operationList($(this),\'expandNode\')"><i class="fa fa-expand contextMenuPluginIconFonts"></i><span>Expand All</span></a></li>',
                '2':'<li><a href="javascript:void(0);" onclick="dsBasicObject.operationList($(this),\'addNext\')"><i class="fa fa-chevron-circle-down contextMenuPluginIconFonts"></i><span>Add Next Level</span></a></li>',
                '3':'<li><a href="javascript:void(0);" onclick="dsBasicObject.operationList($(this),\'duplicate\')"><i class="fa fa-clone contextMenuPluginIconFonts"></i><span>Duplicate New</span></a></li>',

            };
            if (data.level === 1) {
                delete optionArr[3];

            } else if (data.level === 4) {
                delete optionArr[1];
                delete optionArr[2];
            }
            var clientX = event.data.clientX + 10;
            var menu = '<ul level='+data.level+' pk='+data.recid+' class="contextMenuPlugin" style="display: block; z-index: 1999; left: ' + clientX + 'px; top: ' + event.data.clientY + 'px;">\n' +
                '    <div class="gutterLine"></div>\n' +
                '    <li class="header">Wi Operations</li>';
            $.each(optionArr, function (i, v) {
                menu += v;
            });
            menu += '</ul><div id="popup_option" style="left: 0; top: 0; width: 100%; height: 100%; position: absolute; z-index: 1998;" oncontextmenu="return false;" onclick="dsBasicObject.closeOptions();"></div>';
            $('body').append(menu);

        }

        this.closeOptions = function () {
            $('.contextMenuPlugin').remove();
            $('#popup_option').remove();
        };

        //not used yet, for standby
        this.retrieveTrunk = function () {
            $.myCgbAjax({
                url: thisObj.tree_controller[1] + '/treeView/all',
                message: javaMessages.updating,
                box: 'none',
                systemRequest: false,
                dataType: 'json',
                success: function (res, status) {
                    thisObj.wi_treeview.treeview('remove');
                    thisObj.wi_trunk = res;
                    thisObj.treeViewConf.data = res;
                    thisObj.wi_treeview = $('#wi_treeview').treeview(thisObj.treeViewConf);

                }
            });
        };

    }
    dsBasicObject.start(gridName);
    $('#wi_treeview').css({'height':$('.main-sidebar').height()-150,'overflow':'auto'});

</script>

<div class="col-md-5 col-lg-3 col-sm-6 col-xs-12">
    <!-- <form> -->
    <div>
        <label for="input-search" class="sr-only">Search Tree:</label>
        <input style="display: inline; width:60%; height: 27px;" type="input" class="form-control" id="input-search" placeholder="Type to search...">
        <div style="float: right;">
        <button type="button" class="btn-linkedin" id="btn-search">Search</button>
        <button type="button" class="btn-linkedin" id="btn-clear-search">Clear</button>
        </div>
    </div>
    <div class="checkbox">
        <label>
            <input type="checkbox" class="checkbox" id="chk-ignore-case" value="true" checked>
            Ignore Case
        </label>&nbsp;&nbsp;&nbsp;
        <label>
            <input type="checkbox" class="checkbox" id="chk-exact-match" value="false">
            Exact Match
        </label>&nbsp;&nbsp;&nbsp;

    </div>

    <!-- </form> -->
    <div id="wi_treeview">treeView</div>
</div>

<div class="col-md-7 col-lg-9 col-sm-6 col-xs-12">
    <div id="myGrid"></div>
</div>






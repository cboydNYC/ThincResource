<!DOCTYPE html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <script src="dhtmlx/codebase/dhtmlx.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="dhtmlx/codebase/dhtmlx.css" type="text/css" media="screen" title="no title"
          charset="utf-8">

    <style type="text/css" media="screen">
        html, body {
            margin: 0px;
            padding: 0px;
            height: 100%;
            overflow: hidden;
        }
    </style>
</head>

<body>
<div id="grid_here" style="width: 600px; height: 400px;"></div>
<br/>
<input type="button" value='Add new row' onclick='add_row()'>
<input type="button" value='Delete selected' onclick='mygrid.deleteSelectedRows()'>

<script type="text/javascript" >
    function add_row() {
        var id = mygrid.uid();
        mygrid.addRow(id, ["2010-04-01", "2012-02-29", "New record"]);
        mygrid.selectRowById(id);
    }

    mygrid = new dhtmlXGridObject('grid_here');
    mygrid.setImagePath("./dhtmlx/grid/imgs/");
    mygrid.setHeader("Start date,End date,Text");
    mygrid.setInitWidths("150,150,*");
    mygrid.setColTypes("ro,ro,ed");
    mygrid.setSkin("dhx_skyblue");
    mygrid.init();
    mygrid.loadXML("./grid_data");

    var dp = new dataProcessor("./grid_data");
    dp.attachEvent("onAfterUpdate", function (sid, action, tid, xml) {
        if (action == "error") {
            mygrid.setCellTextStyle(sid, 2, "background:#eeaaaa");
            dhtmlx.message(xml.getAttribute("details"));
        }
    });
    dp.init(mygrid);
</script>
</body>
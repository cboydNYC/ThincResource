<!DOCTYPE html>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <script src="dhtmlxGantt/codebase/dhtmlxgantt.js"></script>
    <link rel="stylesheet" href="dhtmlxGantt/codebase/dhtmlxgantt.css">
</head>

<body>
<div id="gantt_here" style='width:100%; height:250px;'></div>
<script type="text/javascript">
    /* chart configuration and initialization */
    gantt.config.xml_date = "%Y-%m-%d %H:%i:%s";
    gantt.config.step = 1;
    gantt.config.scale_unit= "day";
    gantt.init("gantt_here", new Date(2010,7,1), new Date(2010,8,1));
    /* refers to the 'data' action that we will create in the next substep */
    gantt.load("./gantt_data", "xml");
    /* refers to the 'data' action as well */
    var dp = new gantt.dataProcessor("./gantt_data");
    dp.init(gantt);
</script>
</body>
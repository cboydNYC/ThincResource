@extends('layouts.app')

@section('content')

<script type="text/javascript" src="{{ asset('sximo/js/plugins/chartjs/Chart.min.js') }}"></script>
<style>
    span.black-block {
        background-color: #333;
        color: #FFF;
        font-size: 11px;
        font-weight: 500;
        padding: 3px 6px;
        margin: 2px;
        width:50px;
    }
</style>
<div class="page-content row">

	<div class="page-content-wrapper m-t">  
	
	
	@if(Auth::check() && Auth::user()->group_id == 1)
 
<section class="ribon-sximo"> 
	<div class="row m-l-none m-r-none m-t  white-bg shortcut ribon "  >
		<div class="col-sm-6 col-md-3  p-sm ribon-white">
			<span class="pull-left m-r-sm "><i class="fa fa-table"></i></span> 
			<a href="{{ URL::to('sximo/module') }}" class="clear">
				<span class="h3 block m-t-xs"><strong>  {{ Lang::get('core.dash_i_module') }}  </strong>
				</span> <small>  {{ Lang::get('core.dash_module') }}</small>
			</a>
		</div>
		<div class="col-sm-6 col-md-3   p-sm ribon-module">
			<span class="pull-left m-r-sm ">	<i class="icon-steam2"></i></span>
			<a href="{{ URL::to('sximo/config') }}" class="clear">
				<span class="h3 block m-t-xs"><strong> {{ Lang::get('core.dash_i_setting') }}</strong>
				</span> <small >   {{ Lang::get('core.dash_setting') }} </small> 
			</a>
		</div>
		<div class="col-sm-6 col-md-3   p-sm ribon-white">
			<span class="pull-left m-r-sm ">	<i class="icon-list"></i></span>
			<a href="{{ URL::to('sximo/menu') }}" class="clear">
			<span class="h3 block m-t-xs"><strong>  {{ Lang::get('core.dash_i_sitemenu') }} </strong></span>
			<small>  {{ Lang::get('core.dash_sitemenu') }}  </small> </a>
		</div>
		<div class="col-sm-6 col-md-3  p-sm ribon-setting">
			<span class="pull-left m-r-sm ">	<i class="icon-users"></i></span>
			<a href="{{ URL::to('core/users') }}" class="clear">
			<span class="h3 block m-t-xs"><strong> {{ Lang::get('core.dash_i_usergroup') }}</strong>
			</span> <small >  {{ Lang::get('core.dash_usergroup') }} </small> </a>
		</div>
	</div> 
</section>	

@endif


<div class="row m-t">  



            		<div class="col-lg-9">

            			<div class="row m-t">
                            <div class="col-lg-12">
                                <div class="sbox">
                                    <div class="sbox-content">
                                        <div class="sbox-title">
                                            Week:&nbsp;<select id="staff-utilization-select-week"><?php echo $current_week_options; ?></select>
                                        </div>

                                        <div>
                                            <div height="300" id="lineChart" style=" width: 100%; margin: 10px 0 ; height: 300px; background: #dadada;" >


                                            </div>
                                        </div>

                                        <div id="staff-utilization-updated" class="m-t-md">
                                            <small class="pull-right">
                                                <i class="fa fa-clock-o"> </i>
                                                Updated on <?php date_default_timezone_set('America/New_York'); echo date('m-d-Y h:i A'); ?>
                                            </small>
                                            <!--here-->
                                        </div>
                                    </div><!-- /sbox-content -->


                                </div><!-- /sbox -->
                            </div><!-- </div class="col-lg-12"> -->
                        </div><!-- </div class="row-mt"> -->


                        <div class="row">
                            <div class="col-lg-4">
                                <div class="sbox">

                                    <div class="sbox-content" id="company-resource-utilization" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto">

                                    </div>
                                    <div class="sbox-content" id="company-resource-utilization-table" style="display:none; min-width: 310px; height: auto; max-width: 600px; margin: 0 auto">
                                        <table style="width:100%">
                                            <tr><th>Group</th><th align='center'>Percent</th><th align='center'>Hours/Week</th></tr>
                                            <?php
                                            foreach ($groups as $grp) {
                                                $hpw = ($grp->hours_per_week/$grp->total_hours)*100;
                                                echo( "<tr><td style='color:".$grp->color."'><strong>".$grp->group_name."</strong></td><td align='center'>".number_format($hpw,2)."%</td><td align='center'>".$grp->hours_per_week."</td></tr>" );
                                            }
                                            ?>
                                        </table>
                                    </div>

                                </div><!-- /sbox -->
                            </div><!-- </div class="col-lg-4"> -->
                            <div class="col-lg-4">
                                <div class="sbox">

                                    <div class="sbox-content" id="project-percentages" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                    <div class="sbox-content" id="project-percentages-table" style="display:none; min-width: 310px; height: auto; max-width: 600px; margin: 0 auto">
                                        <table style="width:100%">
                                            <tr><th>Group</th><th align='center'>Percent</th><th align='center'>Hours/Week</th></tr>
                                            <?php
                                            foreach ($projects as $project) {
                                                $hpw = ($project->hours_per_week/$project->total_hours)*100;
                                                echo( "<tr><td><strong>".$project->short_name."</strong></td><td align='center'>".number_format($hpw,2)."%</td><td align='center'>".$project->hours_per_week."</td></tr>" );
                                            }
                                            ?>
                                        </table>
                                    </div>

                                 </div><!-- /sbox -->
                            </div><!-- </div class="col-lg-4"> -->
                            <div class="col-lg-4">
                                <div class="sbox">
                                    <!--
                                     <div class="sbox-title">
                                         <span class="label label-warning pull-right">Resource Type Percentages</span>
                                         <h5>Title</h5>
                                     </div>-->
                                     <div class="sbox-content" id="project-status-percentages" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto">

                                     </div>

                                 </div><!-- /sbox -->
                            </div><!-- </div class="col-lg-4"> -->
                        </div>

            		</div><!-- </div class="col-lg-9"> -->

                        <div class="col-lg-3" style="background-color:#ebebed;">

                            <div class="" style="min-height: 1196px;">
                          
                                    <h3>Upcoming Deadlines <span class="badge badge-info pull-right"><?php echo count($deadlines); ?></span></h3>

                                    <?php foreach ($deadlines AS $deadline) { ?>
                                        <div class="feed-activity-list">
                                            <div class="feed-element">
                                                <?php
                                                echo "<div style='margin-bottom:5px;'><span class='black-block'>". $deadline->name ."</span>";
                                                $members = \DB::select( \DB::raw("SELECT tb_staff.id, CONCAT(LEFT(tb_staff.first_name,1), LEFT(tb_staff.last_name,1)) AS initials, tb_staff_groups.color
                                                    FROM tb_project_milestone_staff
                                                    INNER JOIN tb_staff ON tb_project_milestone_staff.user_id=tb_staff.id
                                                    INNER JOIN tb_staff_groups ON tb_staff.group_id = tb_staff_groups.id
                                                    WHERE tb_project_milestone_staff.milestone_id=1") );
                                                foreach ($members AS $member) {
                                                    echo "<span class='' style='width:30px; border-radius: 50%; margin:3px; padding:3px;color: #FFF; font-size:12px; font-weight:700; background-color:".$member->color."'>".$member->initials."</span>";
                                                }
echo "</div>";
                                                ?>
                                                <div>
                                                    <small <?php if ($deadline->priority == 1) { echo "style='font-weight:bold;'"; } ?>><?php echo $deadline->title; ?></small><?php if ($deadline->priority == 1) { echo "<small> (high priority)</small>"; } ?>
                                                    <br/>
                                                    <small class="text-muted" <?php if ( ($deadline->start_date <= date('Y-m-d') && ($deadline->end_date=="0000:00:00")) || ($deadline->end_date <= date('Y-m-d')) ) { echo "style='color:red;'"; } ?>><?php echo $deadline->end_date?date("m/d", strtotime($deadline->start_date)):date("m/d/Y", strtotime($deadline->start_date)); ?> <?php echo $deadline->end_date?" - ".date("m/d/Y", strtotime($deadline->end_date)):""; ?></small><?php
                                                    if ( ($deadline->start_date <= date('Y-m-d') && ($deadline->end_date=="0000:00:00")) || ($deadline->end_date <= date('Y-m-d')) ) {
                                                        echo " <small style='color: red; font-weight:bold;'>&larr; OVERDUE</small>";
                                                    } elseif ( ($deadline->start_date <= date('Y-m-d')) && ($deadline->end_date > date('Y-m-d')) )  {
                                                        echo " <small style='font-weight:bold;'>&larr; DUE</small>";
                                                    } ?>
                                                </div>

                                            </div>
                                            <hr style="height:20px; margin:0px; padding:0px;" />
                                        </div><!-- </div class="feed-activity-list">  -->
                                    <?php } ?>

                                    <!--
                                    <div class="m-t-md">
                                        <h4>Title</h4>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.
                                        </p>
                                        <div class="row m-t-sm">
                                            <div class="col-md-6">
                                                <span class="bar" style="display: none;">5,3,9,6,5,9,7,3,5,2</span><svg class="peity" height="16" width="32"><rect fill="#1ab394" x="0" y="7.111111111111111" width="2.3" height="8.88888888888889"/><rect fill="#d7d7d7" x="3.3" y="10.666666666666668" width="2.3" height="5.333333333333333"/><rect fill="#1ab394" x="6.6" y="0" width="2.3" height="16"/><rect fill="#d7d7d7" x="9.899999999999999" y="5.333333333333334" width="2.3" height="10.666666666666666"/><rect fill="#1ab394" x="13.2" y="7.111111111111111" width="2.3" height="8.88888888888889"/><rect fill="#d7d7d7" x="16.5" y="0" width="2.3" height="16"/><rect fill="#1ab394" x="19.799999999999997" y="3.555555555555557" width="2.3" height="12.444444444444443"/><rect fill="#d7d7d7" x="23.099999999999998" y="10.666666666666668" width="2.3" height="5.333333333333333"/><rect fill="#1ab394" x="26.4" y="7.111111111111111" width="2.3" height="8.88888888888889"/><rect fill="#d7d7d7" x="29.7" y="12.444444444444445" width="2.3" height="3.5555555555555554"/></svg>
                                                <h5><strong>169</strong> Posts</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="line" style="display: none;">5,3,9,6,5,9,7,3,5,2</span><svg class="peity" height="16" width="32"><polygon fill="#1ab394" points="0 15 0 7.166666666666666 3.5555555555555554 10.5 7.111111111111111 0.5 10.666666666666666 5.5 14.222222222222221 7.166666666666666 17.77777777777778 0.5 21.333333333333332 3.833333333333332 24.888888888888886 10.5 28.444444444444443 7.166666666666666 32 12.166666666666666 32 15"/><polyline fill="transparent" points="0 7.166666666666666 3.5555555555555554 10.5 7.111111111111111 0.5 10.666666666666666 5.5 14.222222222222221 7.166666666666666 17.77777777777778 0.5 21.333333333333332 3.833333333333332 24.888888888888886 10.5 28.444444444444443 7.166666666666666 32 12.166666666666666" stroke="#169c81" stroke-width="1" stroke-linecap="square"/></svg>
                                                <h5><strong>28</strong> Orders</h5>
                                            </div>
                                        </div>
                                    </div>-->
                                    <!--
                                    <div class="m-t-md">
                                        <h4>Title</h4>
                                        <div>
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <span class="badge badge-primary">16</span>
                                                    General topic
                                                </li>
                                                <li class="list-group-item">
                                                    <span class="badge badge-info">12</span>
                                                    The generated Lorem
                                                </li>
                                                <li class="list-group-item">
                                                    <span class="badge badge-warning">7</span>
                                                    There are many variations
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    -->

                            </div><!-- </div style="height: 1196px;"> -->

                        </div><!-- </div class="col-lg-3" style="background-color:#ebebed;"> -->

                	</div>

	
</div>	
	
</div>

<script language="javascript">
    jQuery(document).ready(function($)	{

        var data_staff = <?php echo $json_staff; ?>;
        //alert(data_staff);
        $('#lineChart').highcharts({
            credits: {
                enabled: false
            },
            chart: {
                type: 'column'
            },
            title: {
                text: 'Staff Utilization Chart (<?php echo date("m/d", strtotime($week_range[0]))." - ".date("m/d/Y", strtotime($week_range[1])); ?>)'
            },
            xAxis: {
                categories: [<?php
                    foreach ($staff as $s) {
                        echo( "'" . $s->name . "'," );
                    }
                    ?>],
                labels: {
                    rotation: -45
                },
            },
            yAxis: {
                min: 0,

                title: {
                    text: 'Hours Alotted'
                },
                stackLabels: {
                    enabled: false,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -30,
                verticalAlign: 'top',
                y: 25,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: false,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black'
                        }
                    }
                }
            },
            series: [<?php

                foreach ($assignmentsgrouplist as $group) {
                    $group_data = "";

                    foreach($staff as $s) {
                        $ass_found = 0;
                        $staff_assignments = \DB::select( \DB::raw("SELECT tb_staff.id, IF(tb_staff_group_assignments.project_id,CONCAT('p',tb_staff_group_assignments.project_id),CONCAT('g',tb_staff_group_assignments.group_id)) AS `key`, tb_staff_group_assignments.hours_per_week
                        FROM tb_staff
                        LEFT JOIN tb_staff_group_assignments ON tb_staff_group_assignments.staff_id = tb_staff.id
                        WHERE tb_staff.id = ".$s->id." AND hours_per_week AND hours_per_week AND (tb_staff.isactive = 1) AND (tb_staff_group_assignments.hours_per_week > 0) AND ('".date('Y-m-d')."' >= tb_staff_group_assignments.start AND ('".date('Y-m-d')."' < tb_staff_group_assignments.end OR tb_staff_group_assignments.end = '0000-00-00' ))
                        ") );

                        foreach($staff_assignments AS $ass) {
                            if ($ass->key == $group->key) {
                                $ass_found = $ass->hours_per_week;
                            }
                        }

                        if ($ass_found) {
                            $group_data .= " ".$ass_found . ",";
                        } else {
                            $group_data .= " 0,";
                        }
                    }
                    $data = substr($group_data,0, strlen($group_data)-1 );

                    echo( "{
                    name: '".$group->short_name."',
                    data: [".$data."]
                }," );

                }
                ?>]
        });

        var data_groups = <?php echo $json_groups; ?>;
        $('#company-resource-utilization').highcharts({
            credits: {
                enabled: false
            },
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                text: 'Resource Utilization'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br />Hours/week: <b>{point.x}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return parseFloat(this.point.percentage).toFixed(1) + "%" ;
                        },
                        distance: -30,
                        style: {
                            fontWeight: 'bold',
                            color: 'white',
                            textShadow: '0px 1px 2px black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Percentage',
                innerSize: '50%',
                data: [
                    <?php
                    foreach ($groups as $grp) {
                        $hpw = ($grp->hours_per_week/$grp->total_hours)*100;
                        echo( "{name: '".$grp->group_name."', y: ".$hpw.", x: ".$grp->hours_per_week.", color: '".$grp->color."'}, " );
                    }
                    ?>
                ]
            }]

        });


        $('#project-percentages').highcharts({
            credits: {
                enabled: false
            },
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                text: 'Project Percentages'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br />Hours/week: <b>{point.x}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return parseFloat(this.point.percentage).toFixed(1) + "%" ;
                        },
                        distance: -30,
                        style: {
                            fontWeight: 'bold',
                            color: 'white',
                            textShadow: '0px 1px 2px black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Percentage',
                innerSize: '50%',
                data: [
                    <?php
                    foreach ($projects as $project) {
                        $hpw = ($project->hours_per_week/$project->total_hours)*100;
                        echo( "{name: '".$project->project."', y: ".$hpw.", x: ".$project->hours_per_week."}, " );
                    }
                    ?>
                ]
            }]

        });

        $('#project-status-percentages').highcharts({
            credits: {
                enabled: false
            },
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                text: 'Project Status Percentages'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br />Hours/week: <b>{point.x}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return parseFloat(this.point.percentage).toFixed(1) + "%" ;
                        },
                        distance: -30,
                        style: {
                            fontWeight: 'bold',
                            color: 'white',
                            textShadow: '0px 1px 2px black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Percentage',
                innerSize: '50%',
                data: [
                    <?php
                    $total_hours = \DB::select( \DB::raw("SELECT sum(tb_staff_group_assignments.hours_per_week) as 'tot' FROM tb_staff_group_assignments INNER JOIN tb_projects ON tb_staff_group_assignments.project_id = tb_projects.id WHERE tb_projects.isactive = 1 AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )") );
                    //echo( $total_hours->tot);
                    foreach ($projstatuses as $status) {

                        //get hours
                        $hours = \DB::select( \DB::raw("SELECT sum(tb_staff_group_assignments.hours_per_week) as 'hours_per_week' FROM tb_staff_group_assignments INNER JOIN tb_projects ON tb_staff_group_assignments.project_id = tb_projects.id WHERE tb_projects.isactive = 1 AND tb_projects.project_status = ".$status->id." AND (tb_staff_group_assignments.hours_per_week > 0) AND ( (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (STR_TO_DATE( concat( concat( date_format( CURDATE( ) , '%Y' ) , WEEKOFYEAR( CURDATE( ) ) ) , ' Monday' ) , '%X%V %W' ) >= tb_staff_group_assignments.start AND tb_staff_group_assignments.end = '0000-00-00') )") );

                        $hpw = ($hours[0]->hours_per_week/$total_hours[0]->tot)*100;
                        echo( "{name: '".$status->status."', y: ".$hpw.", x: ".$total_hours[0]->tot."}, " );
                    }
                    ?>
                ]
            }]

        });

        $( "#staff-utilization-select-week" ).change(function() {
            //alert( $("#staff-utilization-select-week").val() );
            var str = $("#staff-utilization-select-week option:selected").text();
            var data_staff = <?php echo $json_staff; ?>;


            //alert( str.substr(str.length - 4) );
            $.ajax({
                url: "/regenerateStaffUtilizationChart",
                dataType: 'json',
                data: 'week=' + $("#staff-utilization-select-week").val() + '&year=' + str.substr(str.length - 4) ,
                context: document.body
            }).done(function(data) {

                //alert(data.assgrouplist);
                //var ds = data.json_staff;

                alert( data.assgrouplist );
                //alert( data.assgrouplist.join(", ") );

                var d = new Date();
                var curr_date = d.getDate();
                var curr_m = d.getMonth();
                curr_m++;
                var curr_month = (curr_m < 10) ? ("0" + curr_m) : curr_m ;
                var curr_year = d.getFullYear();
                var h = d.getHours();
                var curr_h = (h < 10) ? ("0" + h) : h ;
                var curr_slot = "AM";
                if (curr_h > 12) {
                    curr_slot = "PM";
                    curr_h = (curr_h = parseInt(curr_h)-12);
                }
                var curr_hour = (curr_h < 10) ? ("0" + curr_h) : curr_h ;
                var m = d.getMinutes();
                var curr_min = (m < 10) ? ("0" + m) : m ;

                var str = "<small class='pull-right'><i class='fa fa-clock-o'> </i> Updated on " + curr_month + "-" + curr_date + "-" + curr_year + " " + curr_hour + ":" + curr_min + " " + curr_slot + "</small><!--here-->";

                $("#staff-utilization-updated").html(str);

                var chart = $('#lineChart').highcharts();
                chart.series[0].data.length = 0;

                chart.setTitle({
                    text: 'Staff Utilization Chart ('+data.week_range+')'
                });
                //chart.xAxis[0].setCategories( data.staff , false);
                //chart.xAxis[0].update({categories:[jQuery.parseJSON(data.json_staff).toString()]}, false);
                //chart.series[0].setData( data.assgrouplist, false);
                //chart.addSeries({
                //    name: "acx",
                //    data: [4,5,6,7,8]
                //}, false);

                chart.redraw();

            });
        });
    });
</script>


@stop
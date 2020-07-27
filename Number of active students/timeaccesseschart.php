<?php

require('../../config.php');
require('lib.php');
$course = required_param('id', PARAM_INT);
$days = required_param('days', PARAM_INT);
global $DB;
global $CFG;

$students = block_analytics_graphs_get_students($course);
$numberofstudents = count($students);
if ($numberofstudents == 0) {
    echo(get_string('no_students', 'block_analytics_graphs'));
    exit;
}

$logstorelife = block_analytics_graphs_get_logstore_loglife();
$coursedayssincestart = block_analytics_graphs_get_course_days_since_startdate($course);
if ($logstorelife === null || $logstorelife == 0) {
    // 0, false and NULL are threated as null in case logstore setting not found and 0 is "no removal" logs
    $maximumdays = $coursedayssincestart; // the chart should not break with value more than available
} else if ($logstorelife >= $coursedayssincestart) {
    $maximumdays = $coursedayssincestart;
} else {
    $maximumdays = $logstorelife;
}

if ($days > $maximumdays) { // sanitycheck
    $days = $maximumdays;
} else if ($days < 1) {
    $days = 1;
}

$daysaccess = block_analytics_graphs_get_accesses_last_days($course, $students, $days);
$daysaccess = json_encode($daysaccess);


?>

<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo get_string('timeaccesschart_title', 'block_analytics_graphs'); ?></title>

    <link rel="stylesheet" href="externalref/jquery-ui-1.12.1/jquery-ui.css">
    <script src="externalref/jquery-1.12.2.js"></script>
    <script src="externalref/jquery-ui-1.12.1/jquery-ui.js"></script>
    <script src="externalref/highstock.js"></script>
    <script src="externalref/no-data-to-display.js"></script>
    <script src="externalref/exporting.js"></script>
    <script src="externalref/export-csv-master/export-csv.js"></script>

</head>

<div style="width: 200px; min-width: 275px; height: 75px; left: 10px; top: 5px; border-radius: 10px; padding: 10px; border: 2px solid silver; text-align: center;">
    <?php echo get_string('timeaccesschart_daysforstatistics', 'block_analytics_graphs'); ?>
    <input style="width: 50px;" id = "days" type="number" name="days" min="1" max="<?php echo $maximumdays; ?>" value="<?php echo $days ?>">
    <br>
    <button style="width: 225px;" id="apply"><?php echo get_string('timeaccesschart_button_apply', 'block_analytics_graphs'); ?></button>
    <br>
    <?php echo get_string('timeaccesschart_maxdays', 'block_analytics_graphs') . "<b>" . $maximumdays . "</b>"; ?>
</div>

<div id="containerA" style="min-width: 300px; height: 600px; margin: 0 auto"></div>

<script type="text/javascript">
    var data = <?php echo $daysaccess; ?>;
    var houraccesses = [];
    var houractivities = [];

    for (var i = 0; i < 24; i++)
    {
        var hourbegin = i * 10000;
        var hourend = i * 10000 + 9999;
        var countedIds = [];
        var numActiveStudents = 0;
        var numActivitiesHour = 0;
        var maximumDays = <?php echo $maximumdays; ?>;

        for(var j in data)
        {
            if (data[j].timecreated >= hourbegin && data[j].timecreated <= hourend) {
                if (jQuery.inArray(data[j].userid, countedIds) == -1) {
                countedIds.push(data[j].userid);
                numActiveStudents++;
                }
                numActivitiesHour++;
            }
        }

        houraccesses[i] = numActiveStudents;
        houractivities[i] = numActivitiesHour;
    }

    $('#apply').click(function() {
        if (maximumDays < $('#days').val()) {
            window.location.href = '<?php echo $CFG->wwwroot . "/blocks/analytics_graphs/timeaccesseschart.php?id=" . $course . "&days="; ?>' + maximumDays;
        } else {
            window.location.href = '<?php echo $CFG->wwwroot . "/blocks/analytics_graphs/timeaccesseschart.php?id=" . $course . "&days="; ?>' + $('#days').val();
        }
        return false;
    });

    Highcharts.chart('containerA', {
        chart: {
            type: 'column',
            events: {
                load: function(){
                    this.mytooltip = new Highcharts.Tooltip(this, this.options.tooltip);
                }
            }
        },
        title: {
            text: '<?php echo get_string('timeaccesschart_title', 'block_analytics_graphs'); ?>'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '<?php echo get_string('timeaccesschart_tip', 'block_analytics_graphs'); ?>'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            enabled: false,
            useHTML: true,
            backgroundColor: "rgba(255, 255, 255, 1.0)",
            formatter: function(){
                var hour = this.point.name.replace(":00", "");
                var hourbegin = hour * 10000;
                var hourend = hour * 10000 + 9999;
                var countedIds = [];

                var tooltipStr = "<span style='font-size: 13px'><b>" +
                    this.point.name +
                    "</b></span>:<br>";

                for(var j in data)
                {
                    if (data[j].timecreated >= hourbegin && data[j].timecreated <= hourend) {
                        if (jQuery.inArray(data[j].userid, countedIds) == -1) {
                            countedIds.push(data[j].userid);
                            tooltipStr += data[j].firstname + " " + data[j].lastname + "<br>";
                        }
                    }
                }

                return "<div class='scrollableHighchartsTooltipAddition'>" + tooltipStr + "</div>";
            }
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series : {
                stickyTracking: false,
                events: {
                    click : function(evt){
                        this.chart.mytooltip.refresh(evt.point, evt);
                    },
                    mouseOut : function(){
                        this.chart.mytooltip.hide();
                    }
                }
            }
        },
        series: [{
            name: 'Time',
            data: [
                ['00:00', houraccesses[0]],
                ['01:00', houraccesses[1]],
                ['02:00', houraccesses[2]],
                ['03:00', houraccesses[3]],
                ['04:00', houraccesses[4]],
                ['05:00', houraccesses[5]],
                ['06:00', houraccesses[6]],
                ['07:00', houraccesses[7]],
                ['08:00', houraccesses[8]],
                ['09:00', houraccesses[9]],
                ['10:00', houraccesses[10]],
                ['11:00', houraccesses[11]],
                ['12:00', houraccesses[12]],
                ['13:00', houraccesses[13]],
                ['14:00', houraccesses[14]],
                ['15:00', houraccesses[15]],
                ['16:00', houraccesses[16]],
                ['17:00', houraccesses[17]],
                ['18:00', houraccesses[18]],
                ['19:00', houraccesses[19]],
                ['20:00', houraccesses[20]],
                ['21:00', houraccesses[21]],
                ['22:00', houraccesses[22]],
                ['23:00', houraccesses[23]]
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });

</script>

</html>

<?php
# +------------------------------------------------------------------+
# |             ____ _               _        __  __ _  __           |
# |            / ___| |__   ___  ___| | __   |  \/  | |/ /           |
# |           | |   | '_ \ / _ \/ __| |/ /   | |\/| | ' /            |
# |           | |___| | | |  __/ (__|   <    | |  | | . \            |
# |            \____|_| |_|\___|\___|_|\_\___|_|  |_|_|\_\           |
# |                                                                  |
# | Copyright Mathias Kettner 2014             mk@mathias-kettner.de |
# +------------------------------------------------------------------+
#
# This file is part of Check_MK.
# The official homepage is at http://mathias-kettner.de/check_mk.
#
# check_mk is free software;  you can redistribute it and/or modify it
# under the  terms of the  GNU General Public License  as published by
# the Free Software Foundation in version 2.  check_mk is  distributed
# in the hope that it will be useful, but WITHOUT ANY WARRANTY;  with-
# out even the implied warranty of  MERCHANTABILITY  or  FITNESS FOR A
# PARTICULAR PURPOSE. See the  GNU General Public License for more de-
# ails.  You should have  received  a copy of the  GNU  General Public
# License along with GNU Make; see the file  COPYING.  If  not,  write
# to the Free Software Foundation, Inc., 51 Franklin St,  Fifth Floor,
# Boston, MA 02110-1301 USA.

setlocale(LC_ALL, "POSIX");

# Use another color for each graph. After eight graphs colors wrap around.
$area_colors = array( "beff5f", "5fffef", "5faaff", "cc5fff", "ff5fe2", "ff5f6c", "ff975f", "ffec5f");
$line_colors = array( "5f7a2f", "2f8077", "2f5580", "662f80", "802f71", "802f36", "804b2f", "80762f");

// Make data sources available via names
$RRD = array();
foreach ($NAME as $i => $n) {
    $RRD[$n]     = "$RRDFILE[$i]:$DS[$i]:MAX";
    $RRD_MIN[$n] = "$RRDFILE[$i]:$DS[$i]:MIN";
    $RRD_AVG[$n] = "$RRDFILE[$i]:$DS[$i]:AVERAGE";
    $WARN[$n] = $WARN[$i];
    $CRIT[$n] = $CRIT[$i];
    $MIN[$n]  = $MIN[$i];
    $MAX[$n]  = $MAX[$i];
    $ACT[$n]  = $ACT[$i];
}

# RRDtool Options
#$servicedes=$NAGIOS_SERVICEDESC

$fsname = str_replace("_", "/", substr($servicedesc,11));
$fstitle = $fsname;

# Hack for windows: replace C// with C:\
if (strlen($fsname) == 3 && substr($fsname, 1, 2) == '//') {
    $fsname = $fsname[0] . "\:\\\\";
    $fstitle = $fsname[0] . ":\\";
}

$sizegb = sprintf("%.1f", $MAX[1] / 1024.0);
$maxgb = $MAX[1] / 1024.0;
$warngb = $WARN[1] / 1024.0;
$critgb = $CRIT[1] / 1024.0;
$warngbtxt = sprintf("%.1f", $warngb);
$critgbtxt = sprintf("%.1f", $critgb);

$opt[1] = "--vertical-label GB -l 0 -u $maxgb --title '$hostname: Filesystem $fstitle ($sizegb GB)' ";

# First graph show current filesystem usage
$def[1] = "DEF:mb=$RRDFILE[1]:$DS[1]:MAX ";
$def[1] .= "CDEF:var1=mb,1024,/ ";
$def[1] .= "AREA:var1#00ffc6:\"used space on $fsname\\n\" ";

# Optional uncommitted usage e.g. for esx hosts
if(isset($RRD['uncommitted'])) {
    $def[1] .= "DEF:uncommitted_mb=".$RRD['uncommitted']." ";
    $def[1] .= "CDEF:uncommitted_gb=uncommitted_mb,1024,/ ";
    $def[1] .= "CDEF:total_gb=uncommitted_gb,var1,+ ";
} else {
    $def[1] .= "CDEF:total_gb=var1 ";
}

$def[1] .= "HRULE:$maxgb#003300:\"Size ($sizegb GB) \" ";
$def[1] .= "HRULE:$warngb#ffff00:\"Warning at $warngbtxt GB \" ";
$def[1] .= "HRULE:$critgb#ff0000:\"Critical at $critgbtxt GB \\n\" ";
$def[1] .= "GPRINT:var1:LAST:\"current\: %6.2lf GB\" ";
$def[1] .= "GPRINT:var1:MAX:\"max\: %6.2lf GB \" ";
$def[1] .= "GPRINT:var1:AVERAGE:\"avg\: %6.2lf GB\\n\" ";

if(isset($RRD['uncommitted'])) {
    $def[1] .= "AREA:uncommitted_gb#eeccff:\"Uncommited\":STACK ";
    $def[1] .= "GPRINT:uncommitted_gb:MAX:\"%6.2lf GB\l\" ";
}

$def[1] .= "LINE1:total_gb#226600 ";

# Second graph is optional and shows trend. The MAX field
# of the third variable contains (size of the filesystem in MB
# / range in hours). From that we can compute the configured range.
if (isset($RRD['growth'])) {
    $size_mb_per_hours = floatval($MAX['trend']); // this is size_mb / range(hours)
    $size_mb = floatval($MAX[1]);
    $hours = 1.0 / ($size_mb_per_hours / $size_mb);
    $range = sprintf("%.0fh", $hours);

    // Current growth / shrinking. This value is give as MB / 24 hours.
    // Note: This has changed in 1.1.13i3. Prior it was MB / trend_range!
    $opt[2] = "--vertical-label '+/- MB / 24h' -l -1 -u 1 -X0 --title '$hostname: Growth of $fstitle' ";
    $def[2] = "DEF:growth_max=${RRD['growth']} ";
    $def[2] .= "DEF:growth_min=${RRD_MIN['growth']} ";
    $def[2] .= "DEF:trend=${RRD_AVG['trend']} ";
    $def[2] .= "CDEF:growth_pos=growth_max,0,MAX ";
    $def[2] .= "CDEF:growth_neg=growth_min,0,MIN ";
    $def[2] .= "CDEF:growth_minabs=0,growth_min,- ";
    $def[2] .= "CDEF:growth=growth_minabs,growth_max,MAX ";
    $def[2] .= "HRULE:0#c0c0c0 ";
    $def[2] .= "AREA:growth_pos#3060f0:\"Grow\" ";
    $def[2] .= "AREA:growth_neg#30f060:\"Shrink \" ";
    $def[2] .= "GPRINT:growth:LAST:\"Current\: %+9.2lfMB / 24h\" ";
    $def[2] .= "GPRINT:growth:MAX:\"Max\: %+9.2lfMB / 24h\\n\" ";

    // Trend
    $opt[3] = "--vertical-label '+/- MB / 24h' -l -1 -u 1 -X0 --title '$hostname: Trend for $fstitle' ";
    $def[3] = "DEF:trend=${RRD_AVG['trend']} ";
    $def[3] .= "HRULE:0#c0c0c0 ";
    $def[3] .= "LINE1:trend#000000:\"Trend\:\" ";
    $def[3] .= "GPRINT:trend:LAST:\"%+7.2lf MB/24h\" ";
    if ($WARN['trend']) {
        $warn_mb = sprintf("%.2fMB", $WARN['trend'] * $hours / 24.0);
        $def[3] .= "LINE1:${WARN['trend']}#ffff00:\"Warn\: $warn_mb / $range\" ";
    }
    if ($CRIT['trend']) {
        $crit_mb = sprintf("%.2fMB", $CRIT['trend'] * $hours / 24.0);
        $def[3] .= "LINE1:${CRIT['trend']}#ff0000:\"Crit\: $crit_mb / $range\" ";
    }
    $def[3] .= "COMMENT:\"\\n\" ";
}

if (isset($RRD['trend_hoursleft'])) {
    // Trend
    $opt[4] = "--vertical-label 'Days left' -l -1 -u 365 -X0 --title '$hostname: Days left for $fstitle' ";
    $def[4] = "DEF:hours_left=${RRD_AVG['trend_hoursleft']} ";
    $def[4] .= "DEF:hours_left_min=${RRD_MIN['trend_hoursleft']} ";
    // negative hours indicate no growth
    // the dataset hours_left_isneg stores this info for each point as True/False
    $def[4] .= "CDEF:hours_left_isneg=hours_left_min,-1,EQ ";
    $def[4] .= "CDEF:hours_left_unmon=hours_left_min,400,0,IF ";
    $def[4] .= "CDEF:days_left=hours_left,24,/ ";
    $def[4] .= "CDEF:days_left_cap=days_left,400,MIN ";
    // Convert negative points to 400 (y-axis cap)
    $def[4] .= "CDEF:days_left_cap_positive=hours_left_isneg,400,days_left_cap,IF ";
    // The AREA has a rendering problem. Points are too far to the right
    $def[4] .= "AREA:hours_left_unmon#AA2200: ";

    $def[4] .= "AREA:days_left_cap_positive#22AA44:\"Days left\:\" ";
    if ($ACT[4] == -1)
    {
        $def[4] .= "COMMENT:\"Not growing\" ";
    }
    else {
        $def[4] .= "GPRINT:days_left:LAST:\"%7.2lf days\" ";
    }
}

/*
Array (
    [1] => volume
    [2] => growth
    [3] => trend
    [4] => Compaction
    [5] => IO_read
    [6] => IO_write
    [7] => IO_total
    [8] => serviceTimeMS_read_max
    [9] => serviceTimeMS_write_max
    [10] => serviceTimeMS_total_max
)

$RRD = Array (
    [volume] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_volume.rrd:1:MAX
    [growth] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_growth.rrd:1:MAX
    [trend] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_trend.rrd:1:MAX
    [Compaction] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_Compaction.rrd:1:MAX
    [IO_read] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_IO_read.rrd:1:MAX
    [IO_write] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_IO_write.rrd:1:MAX
    [IO_total] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_IO_total.rrd:1:MAX
    [serviceTimeMS_read_max] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_serviceTimeMS_read_max.rrd:1:MAX
    [serviceTimeMS_write_max] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_serviceTimeMS_write_max.rrd:1:MAX
    [serviceTimeMS_total_max] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_serviceTimeMS_total_max.rrd:1:MAX
)

$RRDFILE = Array (
    [1] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_volume.rrd
    [2] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_growth.rrd
    [3] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_trend.rrd
    [4] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_Compaction.rrd
    [5] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_IO_read.rrd
    [6] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_IO_write.rrd
    [7] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_IO_total.rrd
    [8] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_serviceTimeMS_read_max.rrd
    [9] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_serviceTimeMS_write_max.rrd
    [10] => /omd/sites/nagios/var/pnp4nagios/perfdata/3Par1/Vol_Status_-_Placeholder_FC_ER_serviceTimeMS_total_max.rrd
)

*/

//throw new Kohana_exception(print_r($MAX, TRUE));

if ( isset($RRD['Compaction'])) {
    $defIdx = 5;
    $optIdx = 5;
    $i = 4;

    $ii = $i % 8;
    $name = $NAME[$i];
    $def[$defIdx] = "DEF:cnt=$RRDFILE[$i]:$DS[$i]:MAX ";
    $def[$defIdx] .= "AREA:cnt#$area_colors[$ii]:\"$name\" ";
    $def[$defIdx] .= "LINE1:cnt#$line_colors[$ii]: ";

    $upper = "";
    $lower = " -l 0";
    if ($WARN[$i] != "") {
    $def[$defIdx] .= "HRULE:$WARN[$i]#ffff00:\"Warning\" ";
    }
    if ($CRIT[$i] != "") {
    $def[$defIdx] .= "HRULE:$CRIT[$i]#ff0000:\"Critical\" ";
    }
    if ($MIN[$i] != "") {
    $lower = " -l " . $MIN[$i];
    $minimum = $MIN[$i];
    }
    if ($MAX[$i] != "") {
    $upper = " -u" . $MAX[$i];
    $def[$defIdx] .= "HRULE:$MAX[$i]#0000b0:\"Upper limit\" ";
    }

    $opt[$optIdx] = "$lower $upper --title '$hostname: $servicedesc - $name' ";
    $def[$defIdx] .= "GPRINT:cnt:LAST:\"current\: %6.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt:MAX:\"max\: %6.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt:AVERAGE:\"avg\: %6.2lf\" ";

}

if ( isset($RRD['IO_read']) && isset($RRD['IO_write']) && isset($RRD['IO_total']) ) {
    $defIdx = 6;
    $optIdx++;

    $i_pos = 5;
    $i_neg = 6;
    $i_tot = 7;
    
    $ii_pos = $i_pos % 8;
    $ii_neg = $i_neg % 8;
    $ii_tot = $i_tot % 8;

    $name_pos = $NAME[$i_pos];
    $name_neg = $NAME[$i_neg];
    $name_tot = $NAME[$i_tot];

    $opt[$optIdx] = " --title '$hostname: IO/s Read/Write' ";

    $def[$defIdx]  = "DEF:cnt_pos=$RRDFILE[$i_pos]:$DS[1]:MAX ";
    $def[$defIdx] .= "DEF:cnt_neg_real=$RRDFILE[$i_neg]:$DS[1]:MAX ";
    $def[$defIdx] .= "DEF:cnt_tot=$RRDFILE[$i_tot]:$DS[1]:MAX ";

    $def[$defIdx] .= sprintf( "COMMENT:\"%sMIN%sMAX%sAVG\l\" ", str_pad( " ", 36 ), str_pad( " ", 10 ), str_pad( " ", 9 ) );

    $def[$defIdx] .= sprintf( "AREA:cnt_pos#$area_colors[$ii_pos]:\"%s\" ", str_pad($name_pos, 25) );
    $def[$defIdx] .= "GPRINT:cnt_pos:LAST:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_pos:MAX:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_pos:AVERAGE:\"%10.2lf\l\" ";

    $def[$defIdx] .=  sprintf( "AREA:cnt_neg_real#$area_colors[$ii_neg]:\"%s\":STACK ", str_pad($name_neg, 25) );
    $def[$defIdx] .= "GPRINT:cnt_neg_real:LAST:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_neg_real:MAX:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_neg_real:AVERAGE:\"%10.2lf\l\" ";

    $def[$defIdx] .=  sprintf( "LINE1:cnt_tot#000:\"%s\" ", str_pad($name_tot, 25) );
    $def[$defIdx] .= "GPRINT:cnt_tot:LAST:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_tot:MAX:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_tot:AVERAGE:\"%10.2lf\l\" ";
}

if ( isset($RRD['serviceTimeMS_read_max']) && isset($RRD['serviceTimeMS_write_max']) ) {
    $defIdx = 7;
    $optIdx++;

    $i_pos = 8;
    $i_neg = 9;
    $i_tot = 10;
    
    $ii_pos = $i_pos % 8;
    $ii_neg = $i_neg % 8;
    $ii_tot = $i_tot % 8;

    $name_pos = $NAME[$i_pos];
    $name_neg = $NAME[$i_neg];
    $name_tot = $NAME[$i_tot];

    $opt[$optIdx] = " --title '$hostname: Service Time Read/Write' ";

    $def[$defIdx]  = "DEF:cnt_pos=$RRDFILE[$i_pos]:$DS[1]:MAX ";
    $def[$defIdx] .= "DEF:cnt_neg_real=$RRDFILE[$i_neg]:$DS[1]:MAX ";
    $def[$defIdx] .= "DEF:cnt_tot=$RRDFILE[$i_tot]:$DS[1]:MAX ";
    $def[$defIdx] .= "CDEF:cnt_neg=cnt_neg_real,-1,* ";

    $def[$defIdx] .= sprintf( "COMMENT:\"%sMIN%sMAX%sAVG\l\" ", str_pad( " ", 36 ), str_pad( " ", 10 ), str_pad( " ", 9 ) );

    $def[$defIdx] .= sprintf( "AREA:cnt_pos#$area_colors[$ii_pos]:\"%s\" ", str_pad($name_pos, 25) );
    $def[$defIdx] .= "LINE1:cnt_pos#$line_colors[$ii_pos]: ";
    $def[$defIdx] .= "GPRINT:cnt_pos:LAST:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_pos:MAX:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_pos:AVERAGE:\"%10.2lf\l\" ";

    $def[$defIdx] .=  sprintf( "AREA:cnt_neg#$area_colors[$ii_neg]:\"%s\" ", str_pad($name_neg, 25) );
    $def[$defIdx] .= "LINE1:cnt_neg#$line_colors[$ii_neg]: ";
    $def[$defIdx] .= "GPRINT:cnt_neg_real:LAST:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_neg_real:MAX:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_neg_real:AVERAGE:\"%10.2lf\l\" ";

    $def[$defIdx] .=  sprintf( "LINE1:cnt_tot#$line_colors[$ii_tot]:\"%s\" ", str_pad($name_tot, 25) );
    $def[$defIdx] .= "GPRINT:cnt_tot:LAST:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_tot:MAX:\"%10.2lf\" ";
    $def[$defIdx] .= "GPRINT:cnt_tot:AVERAGE:\"%10.2lf\l\" ";
}

?>

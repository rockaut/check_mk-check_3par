<?php

#throw new Kohana_exception(print_r($WARN,TRUE));

# Use another color for each graph. After eight graphs colors wrap around.
$area_colors = array( "90CAF9", "B39DDB", "E3F2FD", "cc5fff", "ff5fe2", "ff5f6c", "ff975f", "ffec5f");
$line_colors = array( "2196F3", "673AB7", "2f5580", "662f80", "802f71", "802f36", "804b2f", "80762f");


if( $servicedesc == "Status_CPU_Stats" ) {

  $nodesCount = count( $RRDFILE ) / 5;
  $rrdBaseDir = dirname( $RRDFILE[1] );

  #----------------------------------------------------------------------------------------------
  # Idle Times
  #----------------------------------------------------------------------------------------------
  
  $rrdFileFmt = $rrdBaseDir . "/Status_CPU_Stats_idle_n%s.rrd";
  $optCount   = 1;
  $defCount   = 1;
  $i          = 1;

  $ds_name[$optCount]     = "idle_nX";
  $opt[$optCount]  = "--title 'Idle Times' --upper-limit 105 --lower-limit -105 --rigid --height 180 --end=+10minutes";
  $def[$defCount]  = "";
  $def[$defCount] .= "HRULE:0#555 ";
  for( $nodeId = 0; $nodeId < $nodesCount; $nodeId++ ) {
    $i++;
    $ii = $i-1;

    $nameIdx = array_search("idle_n$nodeId", $NAME);
    #throw new Kohana_exception(print_r($WARN[$nameIdx],TRUE));
    
    $currentRrdFile = sprintf( $rrdFileFmt, $nodeId );

    if( $i % 2 == 1 ) {
      $def[$defCount] .= "DEF:node$nodeId=$currentRrdFile:$DS[1]:MAX ";
      $def[$defCount] .= "CDEF:neg_node$nodeId=node$nodeId,-1,* ";
      $def[$defCount] .= "CDEF:pred_node$nodeId=86400,-7,600,neg_node$nodeId,PREDICT ";
      $def[$defCount] .= "AREA:neg_node$nodeId#$area_colors[$ii]:\"node$nodeId\" ";
      $def[$defCount] .= "LINE1:pred_node$nodeId#$line_colors[$ii]55: ";
      $def[$defCount] .= "LINE2:neg_node$nodeId#$line_colors[$ii]: ";
      $def[$defCount] .= rrd::gprint( "node$nodeId", array( 'MIN', 'MAX', 'AVERAGE' ), '%6.0lf %%');
    } else {
      $def[$defCount] .= "DEF:node$nodeId=$currentRrdFile:$DS[1]:MAX ";
      $def[$defCount] .= "CDEF:pred_node$nodeId=86400,-7,600,node$nodeId,PREDICT ";
      $def[$defCount] .= "AREA:node$nodeId#$area_colors[$ii]:\"node$nodeId\" ";
      $def[$defCount] .= "LINE1:pred_node$nodeId#$line_colors[$ii]55: ";
      $def[$defCount] .= "LINE2:node$nodeId#$line_colors[$ii]: ";
      $def[$defCount] .= rrd::gprint( "node$nodeId", array( 'MIN', 'MAX', 'AVERAGE' ), '%6.0lf %%');
    }

  }

  #----------------------------------------------------------------------------------------------
  # System Tasks
  #----------------------------------------------------------------------------------------------
  
  $rrdFileFmt = $rrdBaseDir . "/Status_CPU_Stats_sys_n%s.rrd";
  $optCount   = 2;
  $defCount   = 2;
  $i          = 1;

  $ds_name[$optCount]     = "sys_nX";
  $opt[$optCount]  = "--title 'System Times' --upper-limit 105 --lower-limit -105 --rigid --height 180 --end=+10minutes";
  $def[$defCount]  = "";
  $def[$defCount] .= "HRULE:0#555 ";
  for( $nodeId = 0; $nodeId < $nodesCount; $nodeId++ ) {
    $i++;
    $ii = $i-1;

    $currentRrdFile = sprintf( $rrdFileFmt, $nodeId );

    if( $i % 2 == 1 ) {
      $def[$defCount] .= "DEF:node$nodeId=$currentRrdFile:$DS[1]:MAX ";
      $def[$defCount] .= "CDEF:neg_node$nodeId=node$nodeId,-1,* ";
      $def[$defCount] .= "CDEF:pred_node$nodeId=86400,-7,600,neg_node$nodeId,PREDICT ";
      $def[$defCount] .= "AREA:neg_node$nodeId#$area_colors[$ii]:\"node$nodeId\" ";
      $def[$defCount] .= "LINE1:pred_node$nodeId#$line_colors[$ii]55: ";
      $def[$defCount] .= "LINE2:neg_node$nodeId#$line_colors[$ii]: ";
      $def[$defCount] .= rrd::gprint( "node$nodeId", array( 'MIN', 'MAX', 'AVERAGE' ), '%6.2lf');
    } else {
      $def[$defCount] .= "DEF:node$nodeId=$currentRrdFile:$DS[1]:MAX ";
      $def[$defCount] .= "CDEF:pred_node$nodeId=86400,-7,600,node$nodeId,PREDICT ";
      $def[$defCount] .= "AREA:node$nodeId#$area_colors[$ii]:\"node$nodeId\" ";
      $def[$defCount] .= "LINE1:pred_node$nodeId#$line_colors[$ii]55: ";
      $def[$defCount] .= "LINE2:node$nodeId#$line_colors[$ii]: ";
      $def[$defCount] .= rrd::gprint( "node$nodeId", array( 'MIN', 'MAX', 'AVERAGE' ), '%6.2lf');
    }

  }

  #----------------------------------------------------------------------------------------------
  # User Tasks
  #----------------------------------------------------------------------------------------------
  
  $rrdFileFmt = $rrdBaseDir . "/Status_CPU_Stats_user_n%s.rrd";
  $optCount   = 3;
  $defCount   = 3;
  $i          = 1;

  $ds_name[$optCount]     = "user_nX";
  $opt[$optCount]  = "--title 'User Times' --upper-limit 105 --lower-limit -105 --rigid --height 180 --end=+10minutes";
  $def[$defCount]  = "";
  $def[$defCount] .= "HRULE:0#555 ";
  for( $nodeId = 0; $nodeId < $nodesCount; $nodeId++ ) {
    $i++;
    $ii = $i-1;

    $currentRrdFile = sprintf( $rrdFileFmt, $nodeId );

    if( $i % 2 == 1 ) {
      $def[$defCount] .= "DEF:node$nodeId=$currentRrdFile:$DS[1]:MAX ";
      $def[$defCount] .= "CDEF:neg_node$nodeId=node$nodeId,-1,* ";
      $def[$defCount] .= "CDEF:pred_node$nodeId=86400,-7,600,neg_node$nodeId,PREDICT ";
      $def[$defCount] .= "AREA:neg_node$nodeId#$area_colors[$ii]:\"node$nodeId\" ";
      $def[$defCount] .= "LINE1:pred_node$nodeId#$line_colors[$ii]55: ";
      $def[$defCount] .= "LINE2:neg_node$nodeId#$line_colors[$ii]: ";
      $def[$defCount] .= rrd::gprint( "node$nodeId", array( 'MIN', 'MAX', 'AVERAGE' ), '%6.2lf');
    } else {
      $def[$defCount] .= "DEF:node$nodeId=$currentRrdFile:$DS[1]:MAX ";
      $def[$defCount] .= "CDEF:pred_node$nodeId=86400,-7,600,node$nodeId,PREDICT ";
      $def[$defCount] .= "AREA:node$nodeId#$area_colors[$ii]:\"node$nodeId\" ";
      $def[$defCount] .= "LINE1:pred_node$nodeId#$line_colors[$ii]55: ";
      $def[$defCount] .= "LINE2:node$nodeId#$line_colors[$ii]: ";
      $def[$defCount] .= rrd::gprint( "node$nodeId", array( 'MIN', 'MAX', 'AVERAGE' ), '%6.2lf');
    }

  }

  #----------------------------------------------------------------------------------------------
  # Intr
  #----------------------------------------------------------------------------------------------
  
  $rrdFileFmt = $rrdBaseDir . "/Status_CPU_Stats_intr_n%s.rrd";
  $optCount   = 4;
  $defCount   = 4;
  $i          = 1;

  $ds_name[$optCount]     = "intr_nX";
  $opt[$optCount]  = "--title 'Interrupts' --height 180 --end=+10minutes";
  $def[$defCount]  = "";
  $def[$defCount] .= "HRULE:0#555 ";
  for( $nodeId = 0; $nodeId < $nodesCount; $nodeId++ ) {
    $i++;
    $ii = $i-1;

    $currentRrdFile = sprintf( $rrdFileFmt, $nodeId );

    if( $i % 2 == 1 ) {
      $def[$defCount] .= "DEF:node$nodeId=$currentRrdFile:$DS[1]:MAX ";
      $def[$defCount] .= "CDEF:neg_node$nodeId=node$nodeId,-1,* ";
      $def[$defCount] .= "CDEF:pred_node$nodeId=86400,-7,600,neg_node$nodeId,PREDICT ";
      $def[$defCount] .= "AREA:neg_node$nodeId#$area_colors[$ii]:\"node$nodeId\" ";
      $def[$defCount] .= "LINE1:pred_node$nodeId#$line_colors[$ii]55: ";
      $def[$defCount] .= "LINE2:neg_node$nodeId#$line_colors[$ii]: ";
      $def[$defCount] .= rrd::gprint( "node$nodeId", array( 'MIN', 'MAX', 'AVERAGE' ), '%6.2lf');
    } else {
      $def[$defCount] .= "DEF:node$nodeId=$currentRrdFile:$DS[1]:MAX ";
      $def[$defCount] .= "CDEF:pred_node$nodeId=86400,-7,600,node$nodeId,PREDICT ";
      $def[$defCount] .= "AREA:node$nodeId#$area_colors[$ii]:\"node$nodeId\" ";
      $def[$defCount] .= "LINE1:pred_node$nodeId#$line_colors[$ii]55: ";
      $def[$defCount] .= "LINE2:node$nodeId#$line_colors[$ii]: ";
      $def[$defCount] .= rrd::gprint( "node$nodeId", array( 'MIN', 'MAX', 'AVERAGE' ), '%6.2lf');
    }

  }


  #----------------------------------------------------------------------------------------------
  # Ctxt
  #----------------------------------------------------------------------------------------------
  
  $rrdFileFmt = $rrdBaseDir . "/Status_CPU_Stats_ctxt_n%s.rrd";
  $optCount   = 5;
  $defCount   = 5;
  $i          = 1;

  $ds_name[$optCount]     = "ctxt_nX";
  $opt[$optCount]  = "--title 'Context Switches' --height 180 --end=+10minutes";
  $def[$defCount]  = "";
  $def[$defCount] .= "HRULE:0#555 ";
  for( $nodeId = 0; $nodeId < $nodesCount; $nodeId++ ) {
    $i++;
    $ii = $i-1;

    $currentRrdFile = sprintf( $rrdFileFmt, $nodeId );

    if( $i % 2 == 1 ) {
      $def[$defCount] .= "DEF:node$nodeId=$currentRrdFile:$DS[1]:MAX ";
      $def[$defCount] .= "CDEF:neg_node$nodeId=node$nodeId,-1,* ";
      $def[$defCount] .= "CDEF:pred_node$nodeId=86400,-7,600,neg_node$nodeId,PREDICT ";
      $def[$defCount] .= "AREA:neg_node$nodeId#$area_colors[$ii]:\"node$nodeId\" ";
      $def[$defCount] .= "LINE1:pred_node$nodeId#$line_colors[$ii]55: ";
      $def[$defCount] .= "LINE2:neg_node$nodeId#$line_colors[$ii]: ";
      $def[$defCount] .= rrd::gprint( "node$nodeId", array( 'MIN', 'MAX', 'AVERAGE' ), '%6.2lf');
    } else {
      $def[$defCount] .= "DEF:node$nodeId=$currentRrdFile:$DS[1]:MAX ";
      $def[$defCount] .= "CDEF:pred_node$nodeId=86400,-7,600,node$nodeId,PREDICT ";
      $def[$defCount] .= "AREA:node$nodeId#$area_colors[$ii]:\"node$nodeId\" ";
      $def[$defCount] .= "LINE1:pred_node$nodeId#$line_colors[$ii]55: ";
      $def[$defCount] .= "LINE2:node$nodeId#$line_colors[$ii]: ";
      $def[$defCount] .= rrd::gprint( "node$nodeId", array( 'MIN', 'MAX', 'AVERAGE' ), '%6.2lf');
    }

  }

}
else {

  foreach ($RRDFILE as $i => $RRD) {
    $ii = $i % 8;
    $name = $NAME[$i];

    $def[$i] = "DEF:cnt=$RRDFILE[$i]:$DS[$i]:MAX ";
    $def[$i] .= "AREA:cnt#$area_colors[$ii]:\"$name\" ";
    $def[$i] .= "LINE1:cnt#$line_colors[$ii]: ";

    $upper = "";
    $lower = " -l 0";
    if ($WARN[$i] != "") {
      $def[$i] .= "HRULE:$WARN[$i]#ffff00:\"Warning\" ";
    }
    if ($CRIT[$i] != "") {
      $def[$i] .= "HRULE:$CRIT[$i]#ff0000:\"Critical\" ";
    }
    if ($MIN[$i] != "") {
      $lower = " -l " . $MIN[$i];
      $minimum = $MIN[$i];
    }
    if ($MAX[$i] != "") {
      $upper = " -u" . $MAX[$i];
      $def[$i] .= "HRULE:$MAX[$i]#0000b0:\"Upper limit\" ";
    }

    $opt[$i] = "$lower $upper --title '$hostname: $servicedesc - $name' ";
    $def[$i] .= "GPRINT:cnt:LAST:\"current\: %6.2lf\" ";
    $def[$i] .= "GPRINT:cnt:MAX:\"max\: %6.2lf\" ";
    $def[$i] .= "GPRINT:cnt:AVERAGE:\"avg\: %6.2lf\" ";
  }

}




?>

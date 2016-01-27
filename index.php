<?php

$BodyID = 'Undefined';
$PageTitle ='Simple System Monitor';
$PreloadJS = 'SysMon.js';
echo "<meta http-equiv='refresh' content='600'>";
require_once('Include/Header.php');
$stmt_Traps = $snpdo -> prepare($query_SNMP_Traps);

if(empty($_POST['Step'])) { $_POST['Step'] = 1; }

?>

<h4>SNMP Data</h4>
<div id='SNMPView'></div>

<p/>

<form id='StepForm' action='index.php' method='post'>
<strong>Interval: </strong>
<select name='Step' onChange='this.form.submit()'>
<option value=1>Select</option>
<option value=1>5 min</option>
<option value=3>15 min</option>
<option value=6>30 min</option>
<option value=12>1 hr</option>
<option value=36>3 hr</option>
</select>
</form>

<p/>

<div class='table'>
<div class='tr'>

<span class='td'>
<strong>Load Index</strong><br/>
<a href='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=Load' target='pChart'><img class='th_sm_med' src='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=Load' /></a>
</span>

<span class='td'>
<strong>CPU Use</strong><br/>
<a href='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=CPU' target='pChart'><img class='th_sm_med' src='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=CPU' /></a>
</span>

<span class='td'>
<strong>Temps</strong><br/>
<a href='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=Temp' target='pChart'><img class='th_sm_med' src='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=Temp' /></a>
</span>

</div>

<div class='tr'>

<span class='td'>
<strong>eth0</strong><br/>
<a href='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=eth0' target='pChart'><img class='th_sm_med' src='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=eth0' /></a>
</span>

<span class='td'>
<strong>Storage</strong><br/>
<a href='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=Storage' target='pChart'><img class='th_sm_med' src='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=Storage' /></a>
</span>

<span class='td'>
<strong>Memory</strong><br/>
<a href='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=Memory' target='pChart'><img class='th_sm_med' src='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=Memory' /></a>
</span>

</div>

<div class='tr'>

<span class='td'>
<strong>MySQL</strong><br/>
<a href='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=MySQL' target='pChart'><img class='th_sm_med' src='pChart/ch_Dynamic.php?Step=<?php echo $_POST['Step']; ?>&DynVar=MySQL' /></a>
</span>

</div>

</div>

<h4>Alarms (BETA)</h4>

<div class='table'>

<div class='tr'>
	<span class='td'><strong>trap_id</strong></span>
	<span class='td'><strong>date_time</strong></span>
	<span class='td'><strong>host</strong></span>
	<span class='td'><strong>auth</strong></span>
	<span class='td'><strong>type</strong></span>
	<span class='td'><strong>version</strong></span>
	<span class='td'><strong>request_id</strong></span>
	<span class='td'><strong>snmpTrapOID</strong></span>
	<span class='td'><strong>transport</strong></span>
	<span class='td'><strong>security_model</strong></span>
	<span class='td'><strong>v3msgid</strong></span>
	<span class='td'><strong>v3security_level</strong></span>
	<span class='td'><strong>v3context_name</strong></span>
	<span class='td'><strong>v3context_engine</strong></span>
	<span class='td'><strong>v3security_name</strong></span>
	<span class='td'><strong>v3security_engine</strong></span>
</div>

<?php

$stmt_Traps -> execute();

while ($row_Traps = $stmt_Traps -> fetch(PDO::FETCH_ASSOC)) {

echo "<div class='tr'>";
echo "<span class='td'>" . $row_Traps['trap_id'] . "</span>";
echo "<span class='td'>" . $row_Traps['date_time'] . "</span>";
echo "<span class='td'>" . $row_Traps['host'] . "</span>";
echo "<span class='td'>" . $row_Traps['auth'] . "</span>";
echo "<span class='td'>" . $row_Traps['type'] . "</span>";
echo "<span class='td'>" . $row_Traps['version'] . "</span>";
echo "<span class='td'>" . $row_Traps['request_id'] . "</span>";
echo "<span class='td'>" . $row_Traps['snmpTrapOID'] . "</span>";
echo "<span class='td'>" . $row_Traps['transport'] . "</span>";
echo "<span class='td'>" . $row_Traps['security_model'] . "</span>";
echo "<span class='td'>" . $row_Traps['v3msgid'] . "</span>";
echo "<span class='td'>" . $row_Traps['v3security_level'] . "</span>";
echo "<span class='td'>" . $row_Traps['v3context_name'] . "</span>";
echo "<span class='td'>" . $row_Traps['v3context_engine'] . "</span>";
echo "<span class='td'>" . $row_Traps['v3security_name'] . "</span>";
echo "<span class='td'>" . $row_Traps['v3security_engine'] . "</span>";
echo "</div>";

}

?>

</div>

<?php

include('Include/Footer.php');

?>

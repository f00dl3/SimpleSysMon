<?php

require_once('Header.php');

$CPULoads = array();
$CPUIdentifier = 196608;
$runningCPUs = 0;
$runningLoad = 0;

for($i=0; $i<8; $i++) {
	
	$thisCPUID = "HOST-RESOURCES-MIB::hrProcessorLoad." . $CPUIdentifier;
	$thisLoadCPU = snmp3_get('localhost', $snmpUser, 'authPriv', 'MD5', $snmpPass, 'DES', $snmpPass, $thisCPUID);
	$CPULoads[$i] = preg_replace('/INTEGER\: /','',$thisLoadCPU);
	$LoadIndex = snmp3_get('localhost', $snmpUser, 'authPriv', 'MD5', $snmpPass, 'DES', $snmpPass, "UCD-SNMP-MIB::laLoad.1");
	$LoadIndex = preg_replace('/STRING\: /','',$LoadIndex);
	$Uptime = snmp3_get('localhost', $snmpUser, 'authPriv', 'MD5', $snmpPass, 'DES', $snmpPass, "HOST-RESOURCES-MIB::hrSystemUptime.0");
	$Uptime = preg_replace('/Timeticks\: /','',$Uptime);
	$Uptime = preg_replace('/\([\s\S]+?\)/','',$Uptime);
	$runningCPUs++;
	$CPUIdentifier++;
	$runningLoad = ($runningLoad+$CPULoads[$i]);

}

$CPUAvgLoad = round($runningLoad/$runningCPUs,1);
$CPULoopNo = 1;

echo "Uptime: " . $Uptime . "<br/>";
echo "CPU Avg: " . $CPUAvgLoad . "% (Load: " . $LoadIndex . ")<p/>";

foreach($CPULoads as $thisCPULoad) {
	
	$thisColor =  AutoColorScale($thisCPULoad,100,-25,NULL);
	echo "<button class='UButton' style='background-color: " . $thisColor . ";'>CPU " . $CPULoopNo . "<br/>" . $thisCPULoad . "%</button>";
	$CPULoopNo++;
	
}

?>

<p/>

<?php

$CPUTemps = array();
$Sensor = 4;

for($i=0; $i<4; $i++) {
	
	$thisTempSensor = "LM-SENSORS-MIB::lmTempSensorsValue." . $Sensor;
	$thisTempSNMP = snmp3_get('localhost', $snmpUser, 'authPriv', 'MD5', $snmpPass, 'DES', $snmpPass, $thisTempSensor);
	$CPUTemps[$i] = preg_replace('/Gauge32\: /','',$thisTempSNMP);
	$Sensor++;

}

$CPUCoreNo = 1;

foreach($CPUTemps as $thisTemp) {
	$thisTemp = round(0.9 * Conv2TF($thisTemp/1000),0);
	$thisColor =  AutoColorScale($thisTemp,158,60,NULL);
	echo "<button class='UButton' style='background-color: " . $thisColor . ";'>Core " . $CPUCoreNo . "<br/>" . $thisTemp . " F</button>";
	$CPUCoreNo++;
	
}

?>

<p/>

<?php

$this_MemPhysSize = snmp3_get('localhost', $snmpUser, 'authPriv', 'MD5', $snmpPass, 'DES', $snmpPass, "HOST-RESOURCES-MIB::hrStorageSize.1");
$this_MemPhysUsed = snmp3_get('localhost', $snmpUser, 'authPriv', 'MD5', $snmpPass, 'DES', $snmpPass, "HOST-RESOURCES-MIB::hrStorageUsed.1");
$this_MemBuffUsed = snmp3_get('localhost', $snmpUser, 'authPriv', 'MD5', $snmpPass, 'DES', $snmpPass, "HOST-RESOURCES-MIB::hrStorageUsed.6");
$this_MemCachUsed = snmp3_get('localhost', $snmpUser, 'authPriv', 'MD5', $snmpPass, 'DES', $snmpPass, "HOST-RESOURCES-MIB::hrStorageUsed.7");
$this_MemPhysSize = preg_replace('/INTEGER\: /','',$this_MemPhysSize);
$this_MemPhysUsed = preg_replace('/INTEGER\: /','',$this_MemPhysUsed);
$this_MemBuffUsed = preg_replace('/INTEGER\: /','',$this_MemBuffUsed);
$this_MemCachUsed = preg_replace('/INTEGER\: /','',$this_MemCachUsed);
$UsedMemory = $this_MemPhysUsed - ($this_MemBuffUsed + $this_MemCachUsed);

$thisColor =  AutoColorScale($UsedMemory,($this_MemPhysSize*0.6),0,NULL);
echo "<button class='UButton' style='background-color: " . $thisColor . ";'>Mem Use<br/>" . round($UsedMemory/1024/1024,2) . " GB</button>";

if(isset($_SESSION['eth0LastIn'])) {

$this_eth0in = snmp3_get('localhost', $snmpUser, 'authPriv', 'MD5', $snmpPass, 'DES', $snmpPass, "IF-MIB::ifInOctets.2");
$this_eth0in = preg_replace('/Counter32\: /','',$this_eth0in);
$this_eth0in_diff = $this_eth0in - $_SESSION['eth0LastIn'];

$thisColor =  AutoColorScale($this_eth0in_diff,4096000,-10000,NULL);
echo "<button class='UButton' style='background-color: " . $thisColor . ";'>eth0 Rx<br/>" . round($this_eth0in_diff/1024/5,1) . " KB</button>";

$_SESSION['eth0LastIn'] = $this_eth0in;

} else $_SESSION['eth0LastIn'] = 0; 

if(isset($_SESSION['eth0LastOut'])) {

$this_eth0out = snmp3_get('localhost', $snmpUser, 'authPriv', 'MD5', $snmpPass, 'DES', $snmpPass, "IF-MIB::ifOutOctets.2");
$this_eth0out = preg_replace('/Counter32\: /','',$this_eth0out);
$this_eth0out_diff = $this_eth0out - $_SESSION['eth0LastOut'];

$thisColor =  AutoColorScale($this_eth0out_diff,4096000,-10000,NULL);
echo "<button class='UButton' style='background-color: " . $thisColor . ";'>eth0 Tx<br/>" . round($this_eth0out_diff/1024/5,1) . " KB</button>";

$_SESSION['eth0LastOut'] = $this_eth0out;

} else $_SESSION['eth0LastOut'] = 0;

?>

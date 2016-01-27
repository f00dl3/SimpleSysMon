<?php

$snmpUser; /* = Your SNMP v3 User Name */
$snmpPass; /* = Your SNMP v3 Password - set MD5 and DES to same password for this to work! */

$DB_User; /* = Your database web user account */
$DB_Pass; /* = Your database password */

try {
$snpdo = new PDO("mysql:host=localhost;dbname=net_snmp", $DB_User, $DB_Pass);
$snpdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) { echo 'MySQL NetSNMP DB - Error: ' . $e->getMessage(); }

$query_SNMP_Main = "(SELECT * FROM (
SELECT @row := @row+1 AS rownum, WalkTime, 
CPULoad1, CPULoad2, CPULoad3, CPULoad4, CPULoad5, CPULoad6, CPULoad7, CPULoad8,
LoadIndex1, LoadIndex5, LoadIndex15,
TempCPU, TempCase,
TempCore1, TempCore2, TempCore3, TempCore4,
OctetsIn, OctetsOut,
KMemPhys, KMemVirt, KMemBuff, KMemCached, KMemShared, KSwap, K4Root,
KMemPhysU, KMemVirtU, KMemBuffU, KMemCachedU, KMemSharedU, KSwapU, K4RootU,
MySQLUpdate, MySQLInsert, MySQLSelect, MySQLDelete, MySQLReplace
FROM ( SELECT @row :=0 ) r, Main ) ranked
WHERE :Test = 1 OR rownum % :Step = 1
ORDER BY WalkTime DESC
LIMIT 288) ORDER BY WalkTime ASC;";

$query_SNMP_Traps = "SELECT
trap_id, date_time, host, auth,
type, version, request_id, snmpTrapOID,
transport, security_model,
v3msgid, v3security_level, v3context_name, v3context_engine,
v3security_name, v3security_engine
from notifications
ORDER BY trap_id DESC
LIMIT 255;";

function AutoColorScale($thisData,$thisMax,$thisMin,$thisForcedAvg) { 
	if(!empty($thisForcedAvg)) { $Average = $thisForcedAvg; } else { $Average = ($thisMax + $thisMin)/2; }
	$Jump = ($thisMax - $thisMin)/12;
	switch(true) {
		case ($thisData >= $Average+(5*$Jump) && $thisData < $Average+(6*$Jump)): $thisColor = "#ff00cc"; break;
		case ($thisData >= $Average+(4*$Jump) && $thisData < $Average+(5*$Jump)): $thisColor = "#660000"; break;
		case ($thisData >= $Average+(3*$Jump) && $thisData < $Average+(4*$Jump)): $thisColor = "#ff0000"; break;
		case ($thisData >= $Average+(2*$Jump) && $thisData < $Average+(3*$Jump)): $thisColor = "#ff6600"; break;
		case ($thisData >= $Average+(1*$Jump) && $thisData < $Average+(2*$Jump)): $thisColor = "#ff9900"; break;
		case ($thisData >= $Average && $thisData < $Average+(1*$Jump)): $thisColor = "#ffff00"; break;
		case ($thisData < $Average && $thisData >= $Average-(1*$Jump)): $thisColor = "#00ff00"; break;
		case ($thisData <= $Average-(1*$Jump) && $thisData > $Average-(2*$Jump)): $thisColor = "#33ffff"; break;
		case ($thisData <= $Average-(2*$Jump) && $thisData > $Average-(3*$Jump)): $thisColor = "#3399ff"; break;
		case ($thisData <= $Average-(3*$Jump) && $thisData > $Average-(4*$Jump)): $thisColor = "#0000ff"; break;
		case ($thisData <= $Average-(4*$Jump) && $thisData > $Average-(5*$Jump)): $thisColor = "#000099"; break;
		case ($thisData <= $Average-(5*$Jump) && $thisData > $Average-(6*$Jump)): $thisColor = "#660099"; break;
		default: $thisColor = "#ffffff";
	}
	return $thisColor;
}

function CheckMobile() { return preg_match("/(android|sprint|iphone|mini|mobi|phone|tablet|up\.browser|up\.link)/i", $_SERVER['HTTP_USER_AGENT']); }
function Conv2TF($TCel) { if((isset($TCel)) && ($TCel < 150)) { return round(($TCel * 9/5 + 32),0); } else { return ''; }}

?>

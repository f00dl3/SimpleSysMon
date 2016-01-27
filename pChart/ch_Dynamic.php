<?php   

include("../Include/Backend.php");
include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");

$stmt_SysDB = $snpdo -> prepare($query_SNMP_Main);

if(isset($_GET['Step'])) {
if($_GET['Step'] == 1) { $Test = 1; $Step = 1; } else { $Test = 0; $Step = $_GET['Step']; }
}

$stmt_SysDB -> bindParam(':Test', $Test, PDO::PARAM_INT, 6);
$stmt_SysDB -> bindParam(':Step', $Step, PDO::PARAM_INT, 6);

$chWidth = 1920; $chHeight = 1080; $chFont = 16; $chWeight = 3;
if(CheckMobile()) { $chHeight = ($chHeight/2); $chWidth = ($chWidth/2); $chFont = ($chFont/2); $chWeight = ($chWeight/2); }

$serieSettingsD = array("R"=>16,"G"=>16,"B"=>16);
$serieSettingsS = array("R"=>49,"G"=>79,"B"=>79);

$ChartTitle = $_GET['DynVar'] . " Dynamic Graph";

switch($_GET['DynVar']) {

case "CPU": 
	$stmt_SysDB -> execute();
	while ($row_SysDB = $stmt_SysDB -> fetch(PDO::FETCH_ASSOC)) {
		ksort($row_SysDB);
		$OTime[] = $row_SysDB['WalkTime'];
		$thisCPUAverage = ($row_SysDB['CPULoad1'] + $row_SysDB['CPULoad2'] + $row_SysDB['CPULoad3'] + $row_SysDB['CPULoad4'] + $row_SysDB['CPULoad5'] + $row_SysDB['CPULoad6'] + $row_SysDB['CPULoad7'] + $row_SysDB['CPULoad8'])/8;
		$DynVar[] = $thisCPUAverage; $DynVarDef = "Average";
		$DynVar2[] = $row_SysDB['CPULoad1']; $DynVarDef2 = "Core 1"; $serieSettings2 = $serieSettingsS;
		$DynVar3[] = $row_SysDB['CPULoad2']; $DynVarDef3 = "Core 2"; $serieSettings3 = $serieSettingsS;
		$DynVar4[] = $row_SysDB['CPULoad3']; $DynVarDef4 = "Core 3"; $serieSettings4 = $serieSettingsS;
		$DynVar5[] = $row_SysDB['CPULoad4']; $DynVarDef5 = "Core 4"; $serieSettings5 = $serieSettingsS;
		$DynVar6[] = $row_SysDB['CPULoad5']; $DynVarDef6 = "Core 5"; $serieSettings6 = $serieSettingsS;
		$DynVar7[] = $row_SysDB['CPULoad6']; $DynVarDef7 = "Core 6"; $serieSettings7 = $serieSettingsS;
		$DynVar8[] = $row_SysDB['CPULoad7']; $DynVarDef8 = "Core 7"; $serieSettings8 = $serieSettingsS;
		$DynVar9[] = $row_SysDB['CPULoad8']; $DynVarDef9 = "Core 8"; $serieSettings9 = $serieSettingsS;
		
	} $DynVarUnits = "Percent"; break;

case "eth0": 
	$LastOctetsTotal = 0; $LastOctetsIn = 0; $LastOctetsOut = 0;
	$stmt_SysDB -> execute();
	while ($row_SysDB = $stmt_SysDB -> fetch(PDO::FETCH_ASSOC)) {
		ksort($row_SysDB);
		$OTime[] = $row_SysDB['WalkTime'];
		$ThisOctetsTotal = $row_SysDB['OctetsIn'] + $row_SysDB['OctetsOut'];
		if($LastOctetsTotal <= $ThisOctetsTotal && $LastOctetsTotal != 0) { $DynVar[] = round(($ThisOctetsTotal - $LastOctetsTotal)/1024/600,1); } $DynVarDef = "Total";
		if($LastOctetsIn <= $row_SysDB['OctetsIn'] && $LastOctetsIn != 0) { $DynVar2[] = round(($row_SysDB['OctetsIn'] - $LastOctetsIn)/1024/600,1); } $DynVarDef2 = "Rx"; $serieSettings2 = $serieSettingsS;
		if($LastOctetsOut <= $row_SysDB['OctetsOut'] && $LastOctetsOut != 0) { $DynVar3[] = round(($row_SysDB['OctetsOut'] - $LastOctetsOut)/1024/600,1); } $DynVarDef3 = "Tx"; $serieSettings3 = $serieSettingsS;
		$LastOctetsTotal = $ThisOctetsTotal;
		$LastOctetsIn = $row_SysDB['OctetsIn'];
		$LastOctetsOut = $row_SysDB['OctetsOut'];
	} $DynVarUnits = "KB/sec"; break;

case "Load": 
	$stmt_SysDB -> execute();
	while ($row_SysDB = $stmt_SysDB -> fetch(PDO::FETCH_ASSOC)) {
		ksort($row_SysDB);
		$OTime[] = $row_SysDB['WalkTime'];
		$DynVar[] = $row_SysDB['LoadIndex1']; $DynVarDef = "1 min";
		$DynVar2[] = $row_SysDB['LoadIndex5']; $DynVarDef2 = "5 min"; $serieSettings2 = $serieSettingsS;
		$DynVar3[] = $row_SysDB['LoadIndex15']; $DynVarDef3 = "15 min"; $serieSettings3 = $serieSettingsS;
	} $DynVarUnits = "Processors"; break;

case "Memory": 
	$stmt_SysDB -> execute();
	while ($row_SysDB = $stmt_SysDB -> fetch(PDO::FETCH_ASSOC)) {
		ksort($row_SysDB);
		$OTime[] = $row_SysDB['WalkTime'];
		$DynVar[] = round(($row_SysDB['KMemPhysU'] - ($row_SysDB['KMemBuffU'] + $row_SysDB['KMemCachedU']))/1024/1024,2); $DynVarDef = "Memory Use";
	} $DynVarUnits = "GB"; break;

case "MySQL": 
	$LastSelect = 0; $LastTotal = 0; $LastUpdate = 0; $LastInsert = 0; $LastDelete = 0; $LastReplace = 0;
	$stmt_SysDB -> execute();
	while ($row_SysDB = $stmt_SysDB -> fetch(PDO::FETCH_ASSOC)) {
		ksort($row_SysDB);
		$OTime[] = $row_SysDB['WalkTime'];
		$ThisTotal = ($row_SysDB['MySQLUpdate'] + $row_SysDB['MySQLInsert'] + $row_SysDB['MySQLSelect'] + $row_SysDB['MySQLDelete'] + $row_SysDB['MySQLReplace']);
		$QPSTotal = round(($ThisTotal - $LastTotal)/600,2);
		$QPSSelect = round(($row_SysDB['MySQLSelect'] - $LastSelect)/600,1); if ($QPSSelect > 25) { $QPSSelect = 0; }
		$QPSUpdate = round(($row_SysDB['MySQLUpdate'] - $LastUpdate)/600,1); if ($QPSUpdate > 25) { $QPSUpdate = 0; }
		$QPSInsert = round(($row_SysDB['MySQLInsert'] - $LastInsert)/600,1); if ($QPSInsert > 25) { $QPSInsert = 0; }
		$QPSDelete = round(($row_SysDB['MySQLDelete'] - $LastDelete)/600,1); if ($QPSDelete > 25) { $QPSDelete = 0; }
		$QPSReplace = round(($row_SysDB['MySQLReplace'] - $LastReplace)/600,1); if ($QPSReplace > 25) { $QPSReplace = 0; }
		if ($QPSTotal > 25) { $QPSTotal = 0; $QPSSelect = 0; $QPSUpdate = 0; $QPSInsert = 0; $QPSDelete = 0; $QPSReplace = 0; }
		if($LastTotal != 0 && $LastTotal < $ThisTotal) { $DynVar[] = $QPSTotal; $DynVarDef = "Total"; }
		if($LastSelect != 0 && $LastSelect < $row_SysDB['MySQLSelect']) { $DynVar2[] = $QPSSelect; $DynVarDef2 = "Select"; $serieSettings2 = $serieSettingsS; }
		if($LastUpdate != 0 && $LastUpdate < $row_SysDB['MySQLUpdate']) { $DynVar3[] = $QPSUpdate; $DynVarDef3 = "Update"; $serieSettings3 = $serieSettingsS; }
		if($LastInsert != 0 && $LastInsert < $row_SysDB['MySQLInsert']) { $DynVar4[] = $QPSInsert; $DynVarDef4 = "Insert"; $serieSettings4 = $serieSettingsS; }
		if($LastDelete != 0 && $LastDelete < $row_SysDB['MySQLDelete']) { $DynVar5[] = $QPSDelete; $DynVarDef5 = "Delete"; $serieSettings5 = $serieSettingsS; }
		if($LastReplace != 0 && $LastReplace < $row_SysDB['MySQLReplace']) { $DynVar6[] = $QPSReplace; $DynVarDef6 = "Replace"; $serieSettings6 = $serieSettingsS; }
		$LastTotal = $ThisTotal;
		$LastSelect = $row_SysDB['MySQLSelect'];
		$LastUpdate = $row_SysDB['MySQLUpdate'];
		$LastInsert = $row_SysDB['MySQLInsert'];
		$LastDelete = $row_SysDB['MySQLDelete'];
		$LastReplace = $row_SysDB['MySQLReplace'];
	} $DynVarUnits = "QPS"; break;

case "Storage": 
	$stmt_SysDB -> execute();
	while ($row_SysDB = $stmt_SysDB -> fetch(PDO::FETCH_ASSOC)) {
		ksort($row_SysDB);
		if(!isset($row_SysDB['K4RootU']) || $row_SysDB['K4RootU'] == 0 || $row_SysDB['K4Root'] == 0) { continue; }
		$OTime[] = $row_SysDB['WalkTime'];
		$DynVar[] = round(($row_SysDB['K4RootU']/$row_SysDB['K4Root'])*100,2); $DynVarDef = "Disk Use";
	} $DynVarUnits = "Percent"; break;

case "Temp": 
	$stmt_SysDB -> execute();
	while ($row_SysDB = $stmt_SysDB -> fetch(PDO::FETCH_ASSOC)) {
		ksort($row_SysDB);
		$OTime[] = $row_SysDB['WalkTime'];
		$DynVar[] = round(0.9 * Conv2TF($row_SysDB['TempCPU']/1000),0); $DynVarDef = "CPU";
		$DynVar2[] = round(0.9 * Conv2TF($row_SysDB['TempCase']/1000),0); $DynVarDef2 = "Case"; $serieSettings2 = $serieSettingsS;
		$DynVar3[] = round(0.9 * Conv2TF($row_SysDB['TempCore1']/1000),0); $DynVarDef3 = "Core 1"; $serieSettings3 = $serieSettingsS;
		$DynVar4[] = round(0.9 * Conv2TF($row_SysDB['TempCore2']/1000),0); $DynVarDef4 = "Core 2"; $serieSettings4 = $serieSettingsS;
		$DynVar5[] = round(0.9 * Conv2TF($row_SysDB['TempCore3']/1000),0); $DynVarDef5 = "Core 3"; $serieSettings5 = $serieSettingsS;
		$DynVar6[] = round(0.9 * Conv2TF($row_SysDB['TempCore4']/1000),0); $DynVarDef6 = "Core 4"; $serieSettings6 = $serieSettingsS;
	} $DynVarUnits = "degrees F"; break;

}

$MyData = new pData();
if(isset($DynVar2)) { $MyData->addPoints($DynVar2,$DynVarDef2); $MyData->setSerieWeight($DynVarDef2,($chWeight/3)); $MyData->setPalette($DynVarDef2,$serieSettings2); }
if(isset($DynVar3)) { $MyData->addPoints($DynVar3,$DynVarDef3); $MyData->setSerieWeight($DynVarDef3,($chWeight/3)); $MyData->setPalette($DynVarDef3,$serieSettings3); }
if(isset($DynVar4)) { $MyData->addPoints($DynVar4,$DynVarDef4); $MyData->setSerieWeight($DynVarDef4,($chWeight/3)); $MyData->setPalette($DynVarDef4,$serieSettings4); }
if(isset($DynVar5)) { $MyData->addPoints($DynVar5,$DynVarDef5); $MyData->setSerieWeight($DynVarDef5,($chWeight/3)); $MyData->setPalette($DynVarDef5,$serieSettings5); }
if(isset($DynVar6)) { $MyData->addPoints($DynVar6,$DynVarDef6); $MyData->setSerieWeight($DynVarDef6,($chWeight/3)); $MyData->setPalette($DynVarDef6,$serieSettings6); }
if(isset($DynVar7)) { $MyData->addPoints($DynVar7,$DynVarDef7); $MyData->setSerieWeight($DynVarDef7,($chWeight/3)); $MyData->setPalette($DynVarDef7,$serieSettings7); }
if(isset($DynVar8)) { $MyData->addPoints($DynVar8,$DynVarDef8); $MyData->setSerieWeight($DynVarDef8,($chWeight/3)); $MyData->setPalette($DynVarDef8,$serieSettings8); }
if(isset($DynVar9)) { $MyData->addPoints($DynVar9,$DynVarDef9); $MyData->setSerieWeight($DynVarDef9,($chWeight/3)); $MyData->setPalette($DynVarDef9,$serieSettings9); }
$MyData->addPoints($DynVar,$DynVarDef); $MyData->setSerieWeight($DynVarDef,$chWeight); $MyData->setPalette($DynVarDef,$serieSettingsD);
$MyData->setAxisName(0,$DynVarUnits);
$MyData->addPoints($OTime,"Labels");
$MyData->setSerieDescription("Labels","Time");
$MyData->setAbscissa("Labels");

$myPicture = new pImage($chWidth,$chHeight,$MyData);
$myPicture->Antialias = TRUE;
$Settings = array("R"=>90, "G"=>90, "B"=>90, "Dash"=>1, "DashR"=>120, "DashG"=>120, "DashB"=>120);
$myPicture->drawFilledRectangle(0,0,$chWidth,$chHeight,$Settings); 

$Settings = array("StartR"=>200, "StartG"=>200, "StartB"=>200, "EndR"=>50, "EndG"=>50, "EndB"=>50, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,$chWidth,$chHeight,DIRECTION_VERTICAL,$Settings); 
$myPicture->drawGradientArea(0,0,$chWidth,$chHeight,DIRECTION_HORIZONTAL,$Settings);  

$myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>$chFont));
$myPicture->drawText(150,35,$ChartTitle,array("FontSize"=>24,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

$myPicture->setFontProperties(array("FontName"=>"fonts/pf_arma_five.ttf","FontSize"=>($chFont-2)));
$myPicture->setGraphArea(60,40,($chWidth*0.98),($chHeight*0.95));
$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE, "LabelRotation"=>90);
$myPicture->drawScale($scaleSettings);

$myPicture->drawLineChart();

$myPicture->drawLegend(($chHeight/2),20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

$myPicture->autoOutput("temp/ch_Dynamic_" . $_GET['DynVar'] . ".png");

include("../Include/Footer.php");

?>

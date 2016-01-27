thisTimestamp=$(date +"%Y%m%d%H%M")
snmpwalk localhost . > /dev/shm/snmpwalk.txt
snmpwalk -m MYSQL-SERVER-MIB localhost enterprises.20267 >> /dev/shm/snmpwalk.txt

CPULoad1=$(sed -n '/.*hrProcessorLoad\.196608 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER: //')
CPULoad2=$(sed -n '/.*hrProcessorLoad\.196609 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER: //')
CPULoad3=$(sed -n '/.*hrProcessorLoad\.196610 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER: //')
CPULoad4=$(sed -n '/.*hrProcessorLoad\.196611 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER: //')
CPULoad5=$(sed -n '/.*hrProcessorLoad\.196612 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER: //')
CPULoad6=$(sed -n '/.*hrProcessorLoad\.196613 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER: //')
CPULoad7=$(sed -n '/.*hrProcessorLoad\.196614 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER: //')
CPULoad8=$(sed -n '/.*hrProcessorLoad\.196615 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER: //')

LoadIndex1=$(sed -n '/.*laLoad\.1 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*STRING\: //')
LoadIndex5=$(sed -n '/.*laLoad\.2 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*STRING\: //')
LoadIndex15=$(sed -n '/.*laLoad\.3 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*STRING\: //')

TempCPU=$(sed -n '/.*lmTempSensorsValue\.3 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Gauge32\: //')
TempCase=$(sed -n '/.*lmTempSensorsValue\.29 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Gauge32\: //')
TempCore1=$(sed -n '/.*lmTempSensorsValue\.4 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Gauge32\: //')
TempCore2=$(sed -n '/.*lmTempSensorsValue\.5 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Gauge32\: //')
TempCore3=$(sed -n '/.*lmTempSensorsValue\.6 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Gauge32\: //')
TempCore4=$(sed -n '/.*lmTempSensorsValue\.7 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Gauge32\: //')

OctetsIn=$(sed -n '/.*ifHCInOctets\.2 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Counter64\: //')
OctetsOut=$(sed -n '/.*ifHCOutOctets\.2 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Counter64\: //')

KMemPhys=$(sed -n '/.*hrStorageSize\.1 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
KMemVirt=$(sed -n '/.*hrStorageSize\.3 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
KMemBuff=$(sed -n '/.*hrStorageSize\.6 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
KMemCached=$(sed -n '/.*hrStorageSize\.7 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
KMemShared=$(sed -n '/.*hrStorageSize\.8 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
KSwap=$(sed -n '/.*hrStorageSize\.10 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
K4Root=$(sed -n '/.*hrStorageSize\.31 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')

KMemPhysU=$(sed -n '/.*hrStorageUsed\.1 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
KMemVirtU=$(sed -n '/.*hrStorageUsed\.3 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
KMemBuffU=$(sed -n '/.*hrStorageUsed\.6 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
KMemCachedU=$(sed -n '/.*hrStorageUsed\.7 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
KMemSharedU=$(sed -n '/.*hrStorageUsed\.8 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
KSwapU=$(sed -n '/.*hrStorageUsed\.10 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')
K4RootU=$(sed -n '/.*hrStorageUsed\.31 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*INTEGER\: //')

MySQLUpdate=$(sed -n '/.*myComUpdate\.0 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Counter32\: //')
MySQLInsert=$(sed -n '/.*myComInsert\.0 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Counter32\: //')
MySQLSelect=$(sed -n '/.*myComSelect\.0 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Counter32\: //')
MySQLDelete=$(sed -n '/.*myComDelete\.0 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Counter32\: //')
MySQLReplace=$(sed -n '/.*myComReplace\.0 \=/p' /dev/shm/snmpwalk.txt | sed 's/.*Counter32\: //')


mysql net_snmp << EOF
insert into Main
	(
	WalkTime,
	CPULoad1,CPULoad2,CPULoad3,CPULoad4,CPULoad5,CPULoad6,CPULoad7,CPULoad8,
	LoadIndex1,LoadIndex5,LoadIndex15,
	TempCPU,TempCase,
	TempCore1,TempCore2,TempCore3,TempCore4,
	OctetsIn,OctetsOut,
	KMemPhys, KMemVirt, KMemBuff, KMemCached, KMemShared, KSwap, K4Root,
	KMemPhysU, KMemVirtU, KMemBuffU, KMemCachedU, KMemSharedU, KSwapU, K4RootU,
	MySQLUpdate, MySQLInsert, MySQLSelect, MySQLDelete, MySQLReplace
	) values
	(
		'$thisTimestamp',
		'$CPULoad1','$CPULoad2','$CPULoad3','$CPULoad4','$CPULoad5','$CPULoad6','$CPULoad7','$CPULoad8',
		'$LoadIndex1','$LoadIndex5','$LoadIndex15',
		'$TempCPU','$TempCase',
		'$TempCore1','$TempCore2','$TempCore3','$TempCore4',
		'$OctetsIn','$OctetsOut',
		'$KMemPhys','$KMemVirt','$KMemBuff','$KMemCached','$KMemShared','$KSwap', '$K4Root',
		'$KMemPhysU','$KMemVirtU','$KMemBuffU','$KMemCachedU','$KMemSharedU','$KSwapU', '$K4RootU',
		'$MySQLUpdate','$MySQLInsert','$MySQLSelect','$MySQLDelete','$MySQLReplace'
	);
EOF

# SimpleSysMon
Simple SNMP-based "Monitorix-like" system monitor

This includes parts of several open source projects that I used to build this off of:

jQuery
  * The jQuery compressed is linked. Again, download if you wish. http://jquery.com/

pChart 
    * Please download pChart for full functionality. http://www.pchart.net/ -- I included classes but can not "type" the TTF fonts.
    
This project also REQUIRES:
  * Some modification to your environment. This is designed under Ubuntu Linux 15.10
  * MySQL-SNMP for MySQL database connectivity. http://www.masterzen.fr/software-contributions/mysql-snmp-monitor-mysql-with-snmp/
  * Several packages:
        sudo apt-get install snmp snmpd snmptrapd php5-snmp libperl-dev 
        More may be required depending on your installed packages!
  * CRON job that runs every X minutes to execute the shell script.

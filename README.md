# SimpleSysMon
![Screenshot](https://scontent-dfw1-1.xx.fbcdn.net/hphotos-xtp1/t31.0-8/12633641_10153755249295944_7509260030461482414_o.jpg "Screenie!")

Simple SNMP-based "Monitorix-like" system monitor. 

This includes parts of several open source projects that I used to build this off of:

jQuery
  * The jQuery compressed is linked. Again, download if you wish. http://jquery.com/

pChart 
    * Please download pChart for full functionality. http://www.pchart.net/ -- I included classes but can not "type" the TTF fonts.
    
This project also REQUIRES:
  * Some modification to your environment. This is designed under Ubuntu Linux 15.10
  * MySQL-SNMP for MySQL database connectivity. http://www.masterzen.fr/software-contributions/mysql-snmp-monitor-mysql-with-snmp/
  * Several packages:
        sudo apt-get install snmp snmpd snmptrapd snmp-mibs-downloader php5-snmp libperl-dev 
        More may be required depending on your installed packages!
  * CRON job that runs every X minutes to execute the shell script.

FUTURE ENHANCEMENTS:

Build custom MIBs.
Get "Alarms" to work. This will list SNMP Traps that are written to the database. (SNMPTrapD can be recompiled to write all traps to MySQL. I have not got this to work - YET)

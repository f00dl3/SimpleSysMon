#include <iostream>
#include <mysql.h>

// g++ mycon.cc -o dbtest -I/usr/include/mysql -lmysqlclient
using namespace std;

int main() {
	cout << "Testing database..." << endl;
	MYSQL *mysql;
	mysql = mysql_init(NULL);
	if(!mysql_real_connect(
		mysql,
		NULL, //host
		NULL, //user
		NULL, //passwd
		NULL,0,NULL,0)) {
		cout << "MySQL error: Failed to connect." << mysql_error(mysql) << endl;
	} else { mysql_close(mysql); cout << "Connection to DB established and closed." << endl; }
	return 0;
}

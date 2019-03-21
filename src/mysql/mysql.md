引入sql文件，将sql文件的数据插入数据库中：

	source 绝对路径

## Mac数据库报错的解决方法：

	错误：
		ERROR! The server quit without updating PID file (/usr/local/mysql/data/mayuliangdeMacBook-Pro.local.pid).
		
这个错误的原因是由于数据库中的日志文件写满了，清楚下日志文件就OK
	进入数据库：
		/usr/local/mysql/bin/mysql -u root -p
	清除日志：
		reset master;

数据库重新启动：	
	sudo /usr/local/mysql/support-files/mysql.server restart



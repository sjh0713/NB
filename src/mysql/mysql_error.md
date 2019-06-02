## mysql 常见报错：

1.23000 数据库字段唯一索引创建，再进行插入的时候会报错


## Mac数据库报错的解决方法：

	错误：
		ERROR! The server quit without updating PID file (/usr/local/mysql/data/mayuliangdeMacBook-Pro.local.pid).
		
- 这个错误的原因是由于数据库中的日志文件写满了，清楚下日志文件就OK
	
        进入数据库：
            /usr/local/mysql/bin/mysql -u root -p
        清除日志：
            reset master;

- 数据库重新启动：	
	
	    sudo /usr/local/mysql/support-files/mysql.server restart

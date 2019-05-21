# mysql语法
--
### 数据库导入导出命令：

* 命令行导出SQL

>只导出表结构：
mysqldump -h localhost -uroot -p -d database>database.sql	
>导出单个表的数据表的结构：
mysqldump -h localhost -uroot -p -d database table>table.sql

>导出整个数据库和数据：
mysqldump -u root -p --default-character-set=utf8 database> path/databaseName.sql

>导出单个数据表和数据：
mysqldump -u root -p --default-character-set=utf8 databasename table>database.sql	

>只导出表的数据：
mysqldump -u root -p -t database > database.sql

* 数据库导入
mysql -h localhost -u root -p law < database.sql

* 删除表中的数据：

		//Clear the data but cannot drop the index of the table
		delete from tablename where 1=1;  
		
		//Clear the table of data
		truncate table tablename ; 

修改表中的数据：
update table_name set column = value[, colunm = value...] [where condition];

mysql查询数据为空和不为空的时候：
	select * from table where column is null;


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


数据库导出字段和注释：
	SELECT	
		COLUMN_NAME 字段,	
		COLUMN_COMMENT 名称,	
		COLUMN_TYPE 类型,
		IF (	IS_NULLABLE = 'YES',	'是',	'否') 是否为空, 
		COLUMN_COMMENT 注释
	FROM	
		INFORMATION_SCHEMA. COLUMNS
	WHERE	
		table_schema = '数据库'AND table_name = '表名';


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






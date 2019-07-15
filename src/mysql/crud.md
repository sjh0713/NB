##数据库的增删改查
* 删除表中的数据：

		//Clear the data but cannot drop the index of the table
		delete from tablename where 1=1;  
		
		//Clear the table of data
		truncate table tablename ; 

* 修改表中的数据：
    
        update table_name set column = value[, colunm = value...] [where condition];

* mysql查询数据为空和不为空的时候：

	    select * from table where column is null;

* 修改数据表中的auto_increament
    
        查看：SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name="tableName";
        修改：ALTER TABLE tableName auto_increment=number ;

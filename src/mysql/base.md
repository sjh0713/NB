
- sql语句主要可以分为3个类别：

        DDL数据定义语言，这些语句定义了不同的数据段，数据库，表，列，索引等数据库对象，常用的关键字为：create，drop，alter等
        DML数据操控语句，用于添加，删除，更新和查询数据库记录，并检查数据的完整性，常用的关键字：insert，delete，update，select
        DCL数据控制语句，用于控制不同数据段直接的许可和访问级别的语句，这些语句定义了数据库，表，字段，用户访问权限和安全级别，主要的关键字grant,revoke

- mysql系统自动创建表的作用：
        
        information_schema主要存储了系统中的一些数据库对象信息，比如用户表信息，列信息，权限信息，字符集信息，分区信息
        cluster:存储了系统的集群信息
        mysql：存储了系统的用户权限信息

- 创建完表后，需要查看表的定义：desc tablename
- 查看完整的表的定义：show create table 表名 \G;（\G的含义使得记录能按照竖向排列）

- 数据表的基本操作

        修改表类型：ALTER table tablename MODIFY column(要修改的字段的属性);
        添加表字段：alter table tablename add column col_name col_type；
        删除表字段：alter table tablename drop column col_name;
        表字段改名：alter table tablename CHANGE old_col new_col col_type;
        注意：modify和chang都可以修改表的定义，change后边需要写2遍字段名，change可以修改字段的名称，但是modify不可以

        修改表名：alter table tablename rename new_tablename;

- 查询不重复的记录：用distinct关键字
        
        select distinct field from table_name;

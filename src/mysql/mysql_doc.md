- 数据库导出字段和注释：
        
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

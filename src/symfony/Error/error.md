## symfony常见错误
更新数据库报错的时候可以通过该命令进行查看执行哪些语句：
    
    php bin/console doctrine:schema:update --dump-sqll

创建时间转换

    $val->getCreateAt()->format('Y-m-d H:i:s')

查询出一条数据转一维数组：

    return empty($arr)?[]:$arr[0]

- 数据库神坑：
>两张数据表的数据合并的时候，如果有其他的表用到了user_id,慎用该方法合并，如果
>old_id=1修改为new_id=2 进行相应的修改其他表中的user_id,其他表user_id=2
>old_id=2修改为new_id=4,进行相应的修改其他表中的user_id,就会覆盖掉


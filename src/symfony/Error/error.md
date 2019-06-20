## symfony常见错误
更新数据库报错的时候可以通过该命令进行查看执行哪些语句：
    
    php bin/console doctrine:schema:update --dump-sqll

创建时间转换

    $val->getCreateAt()->format('Y-m-d H:i:s')

查询出一条数据转一维数组：

    return empty($arr)?[]:$arr[0]

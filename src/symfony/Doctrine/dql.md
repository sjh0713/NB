## doctrine自定义DQL函数：

- 要安装此库，请运行以下命令，您将获得最新版本：
  
        composer require beberlei/doctrineextensions

- 注册函数（app/config/config.yml）

        string_functions:
            DATE_FORMAT: DoctrineExtensions\Query\Mysql\DateFormat # only for DATE_FORMAT
            NOW: DoctrineExtensions\Query\Mysql\Now
            QUARTER: DoctrineExtensions\Query\Mysql\Quarter
            DATESUB: DoctrineExtensions\Query\Mysql\Datesub
            WEEK: DoctrineExtensions\Query\Mysql\Week
            FROM_UNIXTIME: DoctrineExtensions\Query\Mysql\FromUnixtime
            FIND_IN_SET: DoctrineExtensions\Query\Mysql\FindInSet

- 在doctrine中使用
    
        案例：
        if(!empty($specialtyId)){
            $qb->andWhere("FIND_IN_SET(:specialtyId, a.specialtyId)!=0")->setParameter('specialtyId', $specialtyId);
        }

## Console

- 安装文件资源

      php bin/console assets:install
      php bin/console assets:install web --symlink --relative

- 查看command列表
        
      php bin/console list

- 查看service列表

      php bin/console  debug:container

- 查看router列表
      
      php bin/console debug:router --help
      
- 根据配置创建数据库

      php bin/console doctrine:database:create
      
- 执行更新数据库操作前，打印SQL(*****)
      
      php bin/console doctrine:schema:update --dump-sql

- 更新数据库
      
      php bin/console doctrine:schema:update --force
      
- 更新列表
      
      php bin/console doctrine:fixture:load --append

- 创建数据库和数据表（生成entity和Repository）
    
      php bin/console doctrine:generate:entity  SchedulingBundle:Tablename

- 更新enity中的set和get
      
      php bin/console doctrine:generate:entities SchedulingBundle

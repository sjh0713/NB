## mac下安装redis

1.redis的安装

- 首先安装redis
    
        brew install redis
        
- 开机启动redis命令 

        ln -sfv /usr/local/opt/redis/*.plist ~/Library/LaunchAgents

- 测试redis server是否启动
    
        redis-cli ping
- 使用配置文件启动redis server

        redis-server /usr/local/etc/redis.conf

2.phpredis的安装

- 下载安装包develop.zip：
        
        wget -c https://github.com/phpredis/phpredis/archive/develop.zip

- 减压 
        
        unzip develop.zip
- 安装
        
        cd phpredis-develop
        which phpize  #查看phpize
        Which php-config //查找php-config存放地址
        
        记录下来php-config的位置，把记录下来php-config的位置写到 下面的=后面
        
        ./configure --with-php-config=usr/bin/php-onfig 
        
        make
        
        make install    【在make install时碰到了问题，见文章末尾总结】
        
- 配置：
        
        上面安装成功后，去配置php.ini文件
        
        extension_dir = "/usr/local/php/lib/php/extensions/no-debug-zts-20090626"  #这里的地址是你的php-reids安装好redis.so的位置。
        
        extension=redis.so

【问题汇总】

1.sudo make install时提示

Installing shared extensions:     /usr/lib/php/extensions/no-debug-non-zts-20131226/
cp: /usr/lib/php/extensions/no-debug-non-zts-20131226/#INST@12567#: Operation not permitted
make: *** [install-modules] Error 1
--------------------- 
解决方案：

https://blog.csdn.net/moliyiran/article/details/78816291

 

2.安装好后，配置php.ini文件

在文件中增加了  extension=redis.so   ,测试redis时，发现并没有出来，

解决方案：

extension_dir = "/usr/local/php/lib/php/extensions/no-debug-zts-20090626"  #地址中写 make install 返回的地址

extension=redis.so

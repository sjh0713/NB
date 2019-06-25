## 阿里云CentOS下LNMP环境搭建

记录下在阿里云CentOS下LNMP环境搭建过程，首先，需要安装C语言的编译环境，因为Nginx是C语言编写的。通常大多数Linux都会默认安装GCC，如果没有的话，可以如下安装。

1. 准备工作（阿里云下这些系统自动安装好的）

- 安装make：
    - 判断是否安装make
        
          rpm -qa|grep make 通常下Linux都会安装
    - 安装make
        
          yum -y install gcc automake autoconf libtool make

- 安装g++:
    - 判断是否安装安装g++
        
          rpm -qa|grep gcc 通常下Linux都会安装
    - 安装make
        
          yum -y install gcc gcc-c++

- PCRE库:
  
      Nginx需要PCRE（Perl Compatible Regular Expression），因为Nginx的Rewrite模块和Http核心模块都会使用到PCRE正则表达式语法。其下载地址为http://www.pcre.org/，我们也可以通过yum来安装。
  
      查看pcre安装情况:
          rpm -qa|grep pcre
        
      安装： yum install pcre pcre-devel

- zlib库:

      zlib库提供了压缩算法，Nginx很多地方都会用到gzip算法。其下载地址为http://www.zlib.net/，也可以通过yum安装。
      
      查看zlib安装情况:
      rpm -qa|grep zlib
      
      安装：yum install zlib zlib-devel
      
- OpenSSL:

      Nginx中如果服务器提供安全页面，就需要用到OpenSSL库。其下载地址为http://www.openssl.org/，也可以通过yum安装。

      查看openssl安装情况：
      rpm -qa|grep openssl
      
      安装：
           # yum -y install gcc gcc-c++ autoconf automake libtool make cmake
           # yum -y install
      zlib zlib-devel openssl openssl-devel pcre-devel

      安装：openssl-devel 阿里云没有
           yum -y install openssl-devel

2. 开始安装nginx

- 创建nginx用户组

      # groupadd nginx
      # useradd -g nginx -M nginx
      # vi /etc/passwd
      找到nginx，将后面的/bin/bash改为/sbin/nologin就不让ssh登录了）

- 编译安装Nginx
    - 进入nginx官网,找到最新版本的tar.gz包的链接,执行以下动作
    
            # wget https://nginx.org/download/nginx-1.10.1.tar.gz
            # tar zxf nginx-1.10.1.tar.gz
            # cd nginx-1.10.1/
    - 执行./configure,把软件安装到/usr/local目录,用conf、etc、sbin、run等进行归类管理。

            # ./configure --prefix=/usr/local/nginx \
            --pid-path=/usr/local/nginx/run/nginx.pid \
            --with-http_ssl_module \
            --user=nginx \
            --group=nginx \
            --with-pcre \
            --without-mail_pop3_module \
            --without-mail_imap_module \
            --without-mail_smtp_module
    - 最后三项为禁用nginx作为邮件代理服务器,建议想搭建邮件服务器的同志去阅读nginx搭建邮件服务器的教程。
    - 检查./configure结果,看看有没有报错,有没有缺少模块,如果一切OK，那么往下继续，如果感觉不对，可以用./configure –help认真阅读一下。
    - 编译并安装
            
            # make
            # make install

    - 运行nginx
            
            安装完了,但是nginx还没有运行
            
            # cd /usr/local/nginx
            # ls
            # sbin/nginx(必须是这样，进入sbin不能执行)
            运行Nginx：
            
            Nginx会默认安装在/usr/local/nginx目录，我们cd到/usr/local/nginx/sbin/目录，存在一个Nginx二进制可执行文件。打开浏览器访问此机器的IP，看到熟悉的nginx即为成功。

3.mysql

- 安装mysql
    - 直接使用yum命令下载mysql5.6来进行安装是不能成功的，安装过程会有问题，这里我们需要使用rpm命令来先进下载。
    - 下载路径为（5.6)：http://dev.mysql.com/get/mysql-community-release-el7-5.noarch.rpm
    - 下载路径为（5.7)：http://dev.mysql.com/get/mysql57-community-release-el7-8.noarch.rpm
    
    - 进行下载下载mysql源安装包：
            
            wget http://dev.mysql.com/get/mysql57-community-release-el7-8.noarch.rpm
    - 安装MySQL源：
    
            yum localinstall mysql57-community-release-el7-8.noarch.rpm
    - 检测是否安装完成:
    
            yum repolist enabled | grep "mysql.*-community.*"
    
    - 安装mysql:
            
            yum install mysql-community-server
            
            这样mysql就安装成功了
    - 启动mysql
    
            systemctl start mysqld
            
            启动完之后查看mysql状态
            systemctl status mysqld
    - 状态查看
            出现如下，说明已经成功的安装完毕了mysql
            Active: active (running) since 五 2019-06-21 09:57:05 CST; 4min 10s ago
    - 查看mysql初始密码
            
            grep 'A temporary password' /var/log/mysqld.log
            //案例：2019-06-21T01:57:02.047629Z 1 [Note] A temporary password is generated for root@localhost: =2&)o;heXJi>
            
            更改MySQL密码：
            mysqladmin -u root -p'=2&)o;heXJi>' password 'root'
    - 设置开机启动
    
            # systemctl enable mysqld 
            
            # systemctl daemon-reload
            将mysql启动之后，开始进行一些基本信息的设置。输入设置命令：
            
            # mysql_secure_installation
    - 设置mysql能够远程访问:
      
            登录进MySQL：mysql -uroot -p密码
            增加一个用户给予访问权限：grant all privileges on *.* to 'root'@'%%' identified by 'P@ssword1!' with grant option; //可将ip改为%%,表示开启所有的
    - 查看安装地址：
            
            show variables like "%char%";
            ///usr/share/mysql/charsets/
    - 设置成功后就OK了。
    - mysql5.6安装方法地址：https://www.linuxidc.com/Linux/2018-05/152462.htm
    
4.安装PHP

- 下载php7源码包

      #  wget -O php7.tar.gz http://cn2.php.net/get/php-7.0.1.tar.gz/from/this/mirror

- 解压源码包

      #  tar -xvf php7.tar.gz
- 进入目录

      # cd php-7.0.1
- 安装php 依赖包

      #  yum install libxml2 libxml2-devel openssl openssl-devel bzip2 bzip2-devel libcurl libcurl-devel libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel gmp gmp-devel libmcrypt libmcrypt-devel readline readline-devel libxslt libxslt-devel
- 接下来要进行编译前的配置,我们需要提供php-fpm相关参数

      # ./configure --prefix=/usr/local/php --with-config-file-path=/etc --enable-fpm --with-fpm-user=nginx  --with-fpm-group=nginx --enable-inline-optimization --disable-debug --disable-rpath --enable-shared  --enable-soap --with-libxml-dir --with-xmlrpc --with-openssl --with-mcrypt --with-mhash --with-pcre-regex --with-sqlite3 --with-zlib --enable-bcmath --with-iconv --with-bz2 --enable-calendar --with-curl --with-cdb --enable-dom --enable-exif --enable-fileinfo --enable-filter --with-pcre-dir --enable-ftp --with-gd --with-openssl-dir --with-jpeg-dir --with-png-dir --with-zlib-dir  --with-freetype-dir --enable-gd-native-ttf --enable-gd-jis-conv --with-gettext --with-gmp --with-mhash --enable-json --enable-mbstring --enable-mbregex --enable-mbregex-backtrack --with-libmbfl --with-onig --enable-pdo --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-zlib-dir --with-pdo-sqlite --with-readline --enable-session --enable-shmop --enable-simplexml --enable-sockets --enable-sysvmsg --enable-sysvsem --enable-sysvshm --enable-wddx --with-libxml-dir --with-xsl --enable-zip --enable-mysqlnd-compression-support --with-pear --enable-opcache
- 配置无误后执行:

      # make
      # make install
- 添加 PHP 命令到环境变量：

      # vim /etc/profile
- 在末尾加入：

      PATH=$PATH:/usr/local/php/bin
      export PATH
      
      要使改动立即生效执行：
      # source /etc/profile
- 查看环境变量：

      # echo $PATH
      可以看到php的bin目录已经在环境变量里面了
      
      查看php版本：
      # php -v

- 配置php-fpm：

        # cp php.ini-production /etc/php.ini
        # cp /usr/local/php/etc/php-fpm.conf.default /usr/local/php/etc/php-fpm.conf
        # cp /usr/local/php/etc/php-fpm.d/www.conf.default /usr/local/php/etc/php-fpm.d/www.conf
        # cp sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm
        # chmod +x /etc/init.d/php-fpm
        由于php-fpm走的是9000端口,外网是无法访问的,我们需要在nginx的配置文件中增加代理的规则，即可让用户在访问80端口，请求php的时候，交由后端的fpm去执行。
        启动php-fpm：
        # /etc/init.d/php-fpm start


- 配置nginx虚拟机：

        location ~ \.php$ {
                    root          html;
                    fastcgi_pass  127.0.0.1:9000;
                    fastcgi_index  index.php;
                    fastcgi_param  SCRIPT_FILENAME  /$document_root$fastcgi_script_name;
                    include        fastcgi_params;
                }
        将script 改为$document_root即可。

- 重启nginx：

        # service nginx reload(需要加nginx启动文件)
        
        或：
        cd /usr/local/nginx/sbin/
        ./nginx -s reload
        
        停止：./nginx -s stop
        启动：/usr/local/nginx/sbin/nginx 
        测试配置文件： ./nginx -t  
        
        
- 然后就OK了。

5.PHP和nginx相关联

    通过nginx配置文件进行相关联
    配置文件：
         location / {
                root   /usr/local/nginx/html;
                index  index.php index.html index.htm;
         }

         location ~ \.php$ {
             root           html;
             fastcgi_pass   127.0.0.1:9000;
             fastcgi_index  index.php;
             fastcgi_param  SCRIPT_FILENAME  /$document_root$fastcgi_script_name;
             include        fastcgi_params;
         }

6.报错
- 安装nginx遇到的错误：
    - CentOS安装Nginx 报错“configure: error: the HTTP rewrite module requires the PCRE library”解决办法

            ./configure: error: the HTTP rewrite module requires the PCRE library.
             
            安装pcre-devel与openssl-devel解决问题
             
            yum -y install pcre-devel openssl openssl-devel

    - [emerg]: getpwnam(“nginx”) failed
            
            没有安装nginx用户导致的无法启动

- 安装mysql报错
    - 查看mysql初始密码
    - 用该密码登录到服务端后，必须马上修改密码，不然会报如下错误：
      
          mysql> select user();
          ERROR 1820 (HY000): You must reset your password using ALTER USER statement before executing this statement.
          
          如果只是修改为一个简单的密码，会报以下错误：
          mysql>  ALTER USER USER() IDENTIFIED BY '12345678';
          ERROR 1819 (HY000): Your password does not satisfy the current policy requirements
          这个其实与validate_password_policy的值有关。
    
          登陆mysql并进行修改密码：
          MySQL 5.7之后，刚初始化的MySQL实例要求先修改密码。否则会报错：
            
          mysql> create database test;
          ERROR 1820 (HY000): You must reset your password using ALTER USER statement before executing this statement.
          
          所以修改下密码就OK：
          alter user root@localhost identified by 'P@ssword1!';
          flush privileges;          
- 安装PHP
    - 遇到的报错
        
          如果配置错误，需要安装需要的模块，直接yum一并安装依赖库
           
          # yum -y install libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel libxml2 libxml2-devel mysql pcre-devel    
    
7. 
注意这里需要再阿里云安全组中开放3306端口供公网访问
https://www.cnblogs.com/funnyboy0128/p/7966531.html






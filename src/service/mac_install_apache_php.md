
#php安装目录：

PHP安装命令：brew install php72

查看PHP版本：/usr/local/opt/php@7.2/bin/php -v

修改Apache2的配置文件：libphp7.so

libphp7.so的地方：
/usr/local/Cellar/php@7.2/7.2.15/lib/httpd/modules/libphp7.so

修改Apache2的配置文件

修改环境变量：
ln -s /usr/local/opt/php@7.2/bin/php  /usr/bin/php


#Mac系统如何完美安装PHP7

>PHP发布5.6版本后，一直在等，等到了跨越式的PHP7版本，那么问题来了，版本6到哪去了？根据官方的说法，现在的PHP7要比PHP5.6快一倍，有的朋友说快十倍，反正是更快了，本人习惯Mac系统，因此根本Mac系统详细讲解如何安装PHP7！ 
>一般有好几种方法来安装。 

>一，我们可以去官网上下源码去编译，我也尝试这种方法了，但是最后编译安装make test，这一步的时候，报错，于是就大胆尝试第二种方法吧！ 

>二，使用第三方包homebrew来安装，非常迅速有效！

### 安装教程：

1.首先我们需要安装Homebrew

	一条命令完美安装：http://brew.sh/index_zh-cn.html
	brew install php72

2.终端输入以下命令

	// 创建目录,如果你没有创建过该目录
	sudo mkdir /usr/local/var
	sudo chmod 777 /usr/local/var
---
	//修改成你自己的用户名和组,如果你没有创建过该目录
	sudo mkdir /usr/local/sbin/
	sudo chown -R <username>:<group> /usr/local/sbin
---
	//由于我本身一直在使用5.6版本，故上述步骤省略，下面进入正题
	//添加PHP库
	brew tap homebrew/dupes
	brew tap homebrew/versions
	brew tap homebrew/homebrew-php
---
	//关闭老版本的PHP56或55或更早版本 进程
	brew unlink php56
	//开始安装PHP7
	brew install php70
---
	//开启PHP70进程
	brew link php70
	//输入命令，查看是否成功
	php -v
---
	//成功后显示下面信息

	PHP 7.0.8 (cli) (built: Jul 13 2016 15:19:21) ( NTS )
	
	Copyright (c) 1997-2016 The PHP Group
	
	Zend Engine v3.0.0, Copyright (c) 1998-2016 Zend Technologies 



3.修改Apache配置文件

	sudo vim /etc/apache2/httpd.conf
---
	//找到大约168行，该语句，屏蔽后，根据自己的路径，添加php7的.so文件
	LoadModule php5_module libexec/apache2/libphp5.so
	LoadModule php7_module /usr/libexec/apache2/libphp7.so
	说明一下，我的libphp7.so文件目录是这个，好像是默认安装的结果
	LoadModule php7_module /usr/local/Cellar/php70/7.0.0-rc.4/libexec/apache2/libphp7.so

4.重启Apache

	sudo apachectl restart

5.如果发现php文件直接输出到浏览器了，那么你需要修改以下配置

	sudo vim /etc/apache2/httpd.conf

	找到 Include /private/etc/apache2/other/*.conf 这行 进入此文件 

	将文件内容，修改为以下代码：

	<IfModule php7_module>
	AddType application/x-httpd-php .php
	AddType application/x-httpd-php-source .phps
	
	<IfModule dir_module>
	DirectoryIndex index.html index.php
	</IfModule>
	</IfModule>

6.再次重启apache，重复第4步
	
	到你的Apache的默认目录/Library/WebServer/Documents下面去增加一个info.php的文件。

#注意：
	apache和PHP相关联是通过libphp7.so进行相关联的

有必要解释下apache和nginx解析PHP的原理。以前，我一直使用的是apache做web服务器,配置过apache的都应该知道，php是挂在apache下的一个模块,当http请求一个php文件的话，apache会调用php模块儿进行解析，然后返回html给apache。

而nginx又点不一样,它必须通过一个FastCGI的进程管理器来启动php解析,也就是是php-fpm,当nginx接受的php请求时，它会向它监听的fpm发送一个请求，当fpm接受到请求以后，在创建一个子进程来调用php进行解析。当解析完成后，回收线程，返回html给nginx，这里也引出了一个问题，那就是php鸡肋一般的单例，由于php的生命周期是伴随着一个请求的开始和结束，当这个请求完毕后，该线程就会自动回收了，所以php的静态对象，只能存在于一个请求当中，不想Java等其他语言，静态变量会一直存在。

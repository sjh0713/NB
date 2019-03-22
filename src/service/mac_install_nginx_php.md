坑：
由于brew安装的PHP，一直缺少GD库的freetype,导致验证码无法运用

mac缺少imagettftext()、freetype问题随笔

通过brew安装nginx：
	brew install nginx

安装PHP：
	curl -s http://php-osx.liip.ch/install.sh | bash -s 7.2
	PHP安装到了/usr/local/php5目录下，是一个单独的目录，所以，不会影响到原先的PHP，这2个版本是共存的。完全不会影响到目前的PHP版本。

PHP和nginx是通过php-fpm想关联的：
	php-fpm查看的端口，注意该端口是否被占用，如果占用，修改php-fpm的端口号
	修改nginx的配置文件，是通过php-fpm的IP：端口进行链接的
	修改后重启nginx

重启：nginx -s reload

启动：sudo nginx

停止：sudo nginx -s stop

lsof -i tcp:port 可以查看该端口被什么程序占用，并显示PID，方便KILL

发现文件所在路径：find / -name php-fpm.conf
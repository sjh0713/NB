设置硬链接：
	ln -s 要链接的路径 原来的路径

	cmd + enter : 全屏切换
	cmd + f : 查找，被查找内容会被自动复制
	cmd + d : 垂直分屏 cmd + shift + d : 水平分屏
	cmd + t : 新建标签页
	cmd + w : 关闭标签页
	cmd + ; : 自动不全历史
	cmd + shift + h : 剪切板历史
	ctrl + u : 清楚当前行
	ctrl + e : 行末
	ctrl + f : 向后移动光标
	ctrl + b : 向前移动光标
	ctrl + p : 上一条命令
	ctrl + d : 删除当前字符
	ctrl + h : 向前删除一个字符
	ctrl + w : 删除光标前的单词
	ctrl + k : 删除到行尾
	ctrl + t : 交换光标和前面的字符
	ctrl + l : 清楚屏幕
	cmd + r : 滚动新的一屏
	cmd + / : 找到光标的位置
	cmd + shift + s : 保存屏幕快照
	cmd + option + b : 屏幕回放

目录： /usr/local/Cellar用来存放brew安装的

有必要解释下apache和nginx解析PHP的原理。以前，我一直使用的是apache做web服务器,配置过apache的都应该知道，php是挂在apache下的一个模块,当http请求一个php文件的话，apache会调用php模块儿进行解析，然后返回html给apache。

而nginx又点不一样,它必须通过一个FastCGI的进程管理器来启动php解析,也就是是php-fpm,当nginx接受的php请求时，它会向它监听的fpm发送一个请求，当fpm接受到请求以后，在创建一个子进程来调用php进行解析。当解析完成后，回收线程，返回html给nginx，这里也引出了一个问题，那就是php鸡肋一般的单例，由于php的生命周期是伴随着一个请求的开始和结束，当这个请求完毕后，该线程就会自动回收了，所以php的静态对象，只能存在于一个请求当中，不想Java等其他语言，静态变量会一直存在。

重启：nginx -s reload

启动：sudo nginx

停止：sudo nginx -s stop

lsof -i tcp:port 可以查看该端口被什么程序占用，并显示PID，方便KILL

发现文件所在路径：c php-fpm --fpm-config /php-fpm.conf  --prefix /usr/local/varsudo find / -name php-fpm.conf

进程和线程

























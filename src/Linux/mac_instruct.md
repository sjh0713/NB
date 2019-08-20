设置硬链接：

	ln -s 要链接的路径 原来的路径

Mac常用命令：

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
	在访达中输入路径：command+shift+Gs

当前和root用户的切换：

    当前用户到root：sudo su
    root到当前用户：su - myl
目录： /usr/local/Cellar用来存放brew安装的

本地通过ssh连接服务器：
    
    ssh username@39.105.78.71


## 常用Mac操作
    command+shift+G 文件路径进入
    control+command+R 录屏截屏


## Mac快捷键：

    实现delete功能：fn+删除
    实现本行光标前的删除：command+删除
    快速截屏：shift+command+3/4
    切换到桌面：cd Desktop
    切换到root用户：sudo su -
	mac中切换到root用户的时候，可以设置权限
	
    apache:
    启动：sudo apachectl start
    关闭：sudo apachectl stop
    重启：sudo apachectl restart
    版本：sudo apachectl -v


## 指令：
    Linux启动apache：
    1. 启动apache
    service httpd start 
    2. 停止服务apache
    service httpd stop 
    3. 重新启动apache
    service httpd restart

## export:

    语　　法：export [-fnp][变量名称]=[变量设置值]
    补充说明：在shell中执行程序时，shell会提供一组环境变量。 export可新增，修改或删除环境变量，供后续执行的程序使用。export的效力仅及于该此登陆操作。
    参数：
    -f 　代表[变量名称]中为函数名称。 
    -n 　删除指定的变量。变量实际上并未删除，只是不会输出到后续指令的执行环境中。 
    -p 　列出所有的shell赋予程序的环境变量。

- Linux指令：

        touch 同时创建一个或者多个空文件
        open 打开文件
        给一个文件夹权限：chmod -R 777 thinkphp
        终端连接阿里云服务器：ssh -p 端口 用户名@IP
        创建一个文件夹：mkdir 名

- item2快捷键设置

        ⌘ + d: 垂直分屏，
        ⌘ + shift + d: 水平分屏。
        ⌘ + ]和⌘ + [在最近使用的分屏直接切换.
        ⌘ + opt + 方向键切换到指定位置的分屏。
        ⌘ + 数字: 切换标签页。 
        ⌘ + 方向键 按方向切换标签页。
        shift + ⌘ + s: 保存当前窗口快照。
        ⌘ + opt + b: 快照回放。

- 本地文件上传到阿里服务器上：

        scp -P 22 /Users/myl/Desktop/1651164_xiangfumijiu.mylzn.cn_nginx.zip root@39.105.78.71:/etc/nginx/
        scp -P 22 /Users/myl/Desktop/huijuquanqiu.zip root@39.105.78.71:/usr/local/nginx/html

- 连接到服务器：
	
	    ssh 用户名@IP
- 文件重新命名：

	    mv 原文件名 新文件名

- ssl

        ssl占用端口443
        ssl进行设置https：
        1.在ssl进行购买，审核
        2.将证书下载，上传到服务器
        3.在nginx配置文件进行设置

- Linux下ngix重启：

        进入到sbin下，nginx -s reload

- 在vi下展示行号：set nu
- 转换到文件末尾：G
- 删除第n行到末尾的内容：：n,.d
- 复制一个文件：cp -r 源文件绝对路径 复制后的绝对路径












































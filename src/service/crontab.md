Linux设置定时任务方法

查看Linux下有哪些定时任务：crontab -l

编辑Linux下的定时任务：crontab -e




cron机制

    cron可以让系统在指定的时间，去执行某个指定的工作，我们可以使用crontab指令来管理cron机制

crontab参数

    -u:这个参数可以让我们去编辑其他人的crontab，如果没有加上这个参数的话就会开启自己的crontab
    crontab -u 使用者名称

    -l:可以列出crontab的内容

    -r:可以移除crontab

    -e:可以使用系统预设的编辑器，开启crontab

    -i:可以移除crontab，会跳出系统信息让你再次确定是否移除crontab

范例：

    每五分钟执行  */5 * * * *

    每小时执行     0 * * * *

    每天执行        0 0 * * *

    每周执行       0 0 * * 0

    每月执行        0 0 1 * *

    每年执行       0 0 1 1 *



linux下定时执行任务的方法：
在LINUX中你应该先输入crontab -e，然后就会有个vi编辑界面，再输入0 3 * * 1 /clearigame2内容到里面 :wq 保存退出。
 
在LINUX中，周期执行的任务一般由cron这个守护进程来处理[ps -ef|grep cron]。cron读取一个或多个配置文件，这些配置文件中包含了命令行及其调用时间。
cron的配置文件称为“crontab”，是“cron table”的简写。
 
1、cron在3个地方查找配置文件
/var/spool/cron/ 这个目录下存放的是每个用户包括root的crontab任务，每个任务以创建者的名字命名。
/etc/crontab 这个文件负责安排由系统管理员制定的维护系统以及其他任务的crontab。
/etc/cron.d/ 这个目录用来存放任何要执行的crontab文件或脚本。
 
2、权限
crontab权限问题到/var/adm/cron/下一看，文件cron.allow和cron.deny是否存在
用法如下： 
1、如果两个文件都不存在，则只有root用户才能使用crontab命令。 
2、如果cron.allow存在但cron.deny不存在，则只有列在cron.allow文件里的用户才能使用crontab命令，如果root用户也不在里面，则root用户也不能使用crontab。 
3、如果cron.allow不存在, cron.deny存在，则只有列在cron.deny文件里面的用户不能使用crontab命令，其它用户都能使用。 
4、如果两个文件都存在，则列在cron.allow文件中而且没有列在cron.deny中的用户可以使用crontab，如果两个文件中都有同一个用户，
以cron.allow文件里面是否有该用户为准，如果cron.allow中有该用户，则可以使用crontab命令。
 
3、cron服务
cron是一个linux下 的定时执行工具，可以在无需人工干预的情况下运行作业。
　service crond start    //启动服务
　service crond stop     //关闭服务
　service crond restart  //重启服务
　service crond reload   //重新载入配置
　service crond status   //查看服务状态 
 
4、在crontab文件中如何输入需要执行的命令和时间
该文件中每行都包括六个域，其中前五个域是指定命令被执行的时间，最后一个域是要被执行的命令。
每个域之间使用空格或者制表符分隔。格式如下： 
minute hour day-of-month month-of-year day-of-week commands
分钟 小时 每个月的哪天 每年的哪月 每个星期的礼拜几 需执行的命令
合法值 00-59 00-23 01-31 01-12 0-6 (0 is sunday) commands（代表要执行的脚本）
除了数字还有几个个特殊的符号就是"*"、"/"和"-"、","，*代表所有的取值范围内的数字，"/"代表每的意思,"/5"表示每5个单位，"-"代表从某个数字到某个数字,","分开几个离散的数字。
 
几个例子： 
每五分钟执行一次： */5 * * * *
每小时执行一次 ：   0 * * * *
每天执行一次：       0 0 * * *
每周执行一次：       0 0 * * 0
每月执行一次：       0 0 1 * *
每年执行一次：       0 0 1 1 *














linux下定时执行任务的方法：
在LINUX中你应该先输入crontab -e，然后就会有个vi编辑界面，再输入0 3 * * 1 /clearigame2内容到里面 :wq 保存退出。
 
在LINUX中，周期执行的任务一般由cron这个守护进程来处理[ps -ef|grep cron]。cron读取一个或多个配置文件，这些配置文件中包含了命令行及其调用时间。
cron的配置文件称为“crontab”，是“cron table”的简写。
 
1、cron在3个地方查找配置文件
/var/spool/cron/ 这个目录下存放的是每个用户包括root的crontab任务，每个任务以创建者的名字命名。
/etc/crontab 这个文件负责安排由系统管理员制定的维护系统以及其他任务的crontab。
/etc/cron.d/ 这个目录用来存放任何要执行的crontab文件或脚本。
 
2、权限
crontab权限问题到/var/adm/cron/下一看，文件cron.allow和cron.deny是否存在
用法如下： 
1、如果两个文件都不存在，则只有root用户才能使用crontab命令。 
2、如果cron.allow存在但cron.deny不存在，则只有列在cron.allow文件里的用户才能使用crontab命令，如果root用户也不在里面，则root用户也不能使用crontab。 
3、如果cron.allow不存在, cron.deny存在，则只有列在cron.deny文件里面的用户不能使用crontab命令，其它用户都能使用。 
4、如果两个文件都存在，则列在cron.allow文件中而且没有列在cron.deny中的用户可以使用crontab，如果两个文件中都有同一个用户，
以cron.allow文件里面是否有该用户为准，如果cron.allow中有该用户，则可以使用crontab命令。
 
3、cron服务
cron是一个linux下 的定时执行工具，可以在无需人工干预的情况下运行作业。
　service crond start    //启动服务
　service crond stop     //关闭服务
　service crond restart  //重启服务
　service crond reload   //重新载入配置
　service crond status   //查看服务状态 
 
4、在crontab文件中如何输入需要执行的命令和时间
该文件中每行都包括六个域，其中前五个域是指定命令被执行的时间，最后一个域是要被执行的命令。
每个域之间使用空格或者制表符分隔。格式如下： 
minute hour day-of-month month-of-year day-of-week commands
分钟 小时 每个月的哪天 每年的哪月 每个星期的礼拜几 需执行的命令
合法值 00-59 00-23 01-31 01-12 0-6 (0 is sunday) commands（代表要执行的脚本）
除了数字还有几个个特殊的符号就是"*"、"/"和"-"、","，*代表所有的取值范围内的数字，"/"代表每的意思,"/5"表示每5个单位，"-"代表从某个数字到某个数字,","分开几个离散的数字。
 
几个例子： 
每五分钟执行一次： */5 * * * *
每小时执行一次 ：   0 * * * *
每天执行一次：       0 0 * * *
每周执行一次：       0 0 * * 0
每月执行一次：       0 0 1 * *
每年执行一次：       0 0 1 1 *
 
每天早上6点 
0 6 * * * echo "Good morning." >> /tmp/test.txt //注意单纯echo，从屏幕上看不到任何输出，因为cron把任何输出都email到root的信箱了。
 
每两个小时 
0 */2 * * * echo "Have a break now." >> /tmp/test.txt  
 
晚上11点到早上8点之间每两个小时和早上八点 
0 23-7/2，8 * * * echo "Have a good dream" >> /tmp/test.txt
 
每个月的4号和每个礼拜的礼拜一到礼拜三的早上11点  
0 11 4 * 1-3 command line
 
1月1日早上4点 
0 4 1 1 * command line SHELL=/bin/bash PATH=/sbin:/bin:/usr/sbin:/usr/bin MAILTO=root //如果出现错误，或者有数据输出，数据作为邮件发给这个帐号 HOME=/ 
 
每小时执行/etc/cron.hourly内的脚本
01 * * * * root run-parts /etc/cron.hourly
每天执行/etc/cron.daily内的脚本
02 4 * * * root run-parts /etc/cron.daily 
 
每星期执行/etc/cron.weekly内的脚本
22 4 * * 0 root run-parts /etc/cron.weekly 
 
每月去执行/etc/cron.monthly内的脚本 
42 4 1 * * root run-parts /etc/cron.monthly 
 
注意: "run-parts"这个参数了，如果去掉这个参数的话，后面就可以写要运行的某个脚本名，而不是文件夹名。 　 
 
每天的下午4点、5点、6点的5 min、15 min、25 min、35 min、45 min、55 min时执行命令。 
5，15，25，35，45，55 16，17，18 * * * command
 
每周一，三，五的下午3：00系统进入维护状态，重新启动系统。
00 15 * * 1，3，5 shutdown -r +5
 
每小时的10分，40分执行用户目录下的innd/bbslin这个指令： 
10，40 * * * * innd/bbslink 
 
每小时的1分执行用户目录下的bin/account这个指令： 
1 * * * * bin/account
 
每天早晨三点二十分执行用户目录下如下所示的两个指令（每个指令以;分隔）： 
20 3 * * * （/bin/rm -f expire.ls logins.bad;bin/expire$#@62;expire.1st）　　
 
每年的一月和四月，4号到9号的3点12分和3点55分执行/bin/rm -f expire.1st这个指令，并把结果添加在mm.txt这个文件之后（mm.txt文件位于用户自己的目录位置）。 
12,55 3 4-9 1,4 * /bin/rm -f expire.1st$#@62;$#@62;mm.txt 

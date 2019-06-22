Git 分布式版本控制系统（Distributed Version Control System - DVCS）

中央式 VCS 
分布式 VCS

什么是分布式版本控制系统（DVCS）
分布式 VCS （Distributed VCS / DVCS）和中央式的区别在于，分布式 VCS 除了中央仓库之外，还有本地仓库：团队中每一个成员的机器上都有一份本地仓库，这个仓库里包含了所有的版本历史，或者换句话说，每个人在自己的机器上就可以提交代码、查看历史，而无需联网和中央仓库交互——当然，取而代之的，你需要和本地仓库交互。
中央式 VCS 的中央仓库有两个主要功能：保存版本历史、同步团队代码。而在分布式 VCS 中，保存版本历史的工作转交到了每个团队成员的本地仓库中，中央仓库就只剩下了同步团队代码这一个主要任务。它的中央仓库依然也保存了历史版本，但这份历史版本更多的是作为团队间的同步中转站。

git: Git是目前世界上最先进的分布式版本控制系统

Git主要用来解决2个问题：
    
    版本回溯
    多人协同开发
Linus一直痛恨的CVS及SVN都是集中式的版本控制系统，而Git是分布式版本控制系统，集中式和分布式版本控制系统有什么区别呢？
先说集中式版本控制系统，版本库是集中存放在中央服务器的，而干活的时候，用的都是自己的电脑，所以要先从中央服务器取得最新的版本，然后开始干活，干完活了，再把自己的活推送给中央服务器。中央服务器就好比是一个图书馆，你要改一本书，必须先从图书馆借出来，然后回到家自己改，改完了，再放回图书馆。

##Linux系统下的安装：

源码安装

    安装依赖的包
    yum install curl-devel expat-devel gettext-devel openssl-devel zlib-devel gcc perl-ExtUtils-MakeMaker
    下载git源码并解压
    目前最新版本下载地址：https://github.com/git/git/releases/tag/v2.11.0
    解压 tar zxvf git-2.11.0.tar.gz
    cd git-2.11.0
    编译安装
    make prefix=/usr/local/git all
    make prefix=/usr/local/git install
    查看git
    whereis git
    git –version
    配置环境变量
    vim /etc/profile
    加入export PATH=$PATH:/usr/local/git/bin
    生效配置文件 source /etc/profile
    配置git
    1.设置用户名和email
    [root@zhuzhonghua2-fqawb util]# git config –global user.name “hiddenzzh”
    [root@zhuzhonghua2-fqawb util]# git config –global user.email “youremail@domain.com”
    此时$HOME目录下会新建一个.gitconfig文件
    2.为github账号添加SSH keys
    ssh-keygen -t ras -C “youremail@domain.com”
    系统会提示key的保存位置（一般是~/.ssh目录）和指定口令，保持默认，连续三次即可
    然后vim打开id_rsa.pub文件，粘贴到github账号管理的添加SSH KEY界面中
    vim ~/.ssh/id_rsa.pub
    然后将id_rsa.pub文件中的内容粘贴到gitub的“SSH and GPG keys”中。

git基本使用

    初始化一个目录：git init

    git log --pretty=oneline  简洁方式查看日志
    git reset --hard commit_id 回退到某个版本
    git reset --hard HEAS^       回退到上次的版本
    git reflog 用来记录每一次的命令
    git checkout -- readme.txt  修改了readme.txt后，在工作区如何返回
    git reset HEAD readme.txt 添加到暂存区，如何修改
    rm test.txt 删除工作区的文件
    git rm test.txt 删除版本库中的文件
    git commit -m 提交一下才能在版本库删除该文件
    git checkout -- test.ttxt 删除错了，但是版本库中还有，把误删除的文件还原
    
    继续阅读后续内容前，请自行注册GitHub账号。由于你的本地Git仓库和GitHub仓库之间的传输是通过SSH加密的，所以，需要一点设置：
    第1步：创建SSH Key。在用户主目录下，看看有没有.ssh目录，如果有，再看看这个目录下有没有id_rsa和id_rsa.pub这两个文件，如果已经有了，可直接跳到下一步。如果没有，打开Shell（Windows下打开Git Bash），创建SSH Key：
    $ ssh-keygen -t rsa -C "youremail@example.com"
    你需要把邮件地址换成你自己的邮件地址，然后一路回车，使用默认值即可，由于这个Key也不是用于军事目的，所以也无需设置密码。
    如果一切顺利的话，可以在用户主目录里找到.ssh目录，里面有id_rsa和id_rsa.pub两个文件，这两个就是SSH Key的秘钥对，id_rsa是私钥，不能泄露出去，id_rsa.pub是公钥，可以放心地告诉任何人。
    第2步：登陆GitHub，打开“Account settings”，“SSH Keys”页面：
    然后，点“Add SSH Key”，填上任意Title，在Key文本框里粘贴id_rsa.pub文件的内容：
    
    小结
    先有本地库，后有远程库的时候，如何关联远程库。
    先在本地初始化一下代码，git init ,然后将代码编写完整，git add .并git commit -m ''，
    要关联一个远程库，使用命令git remote add origin git@server-name:path/repo-name.git；
    关联后，使用命令git push -u origin master第一次推送master分支的所有内容；
    此后，每次本地提交后，只要有必要，就可以使用命令git push origin master推送最新修改；
    分布式版本系统的最大好处之一是在本地工作完全不需要考虑远程库的存在，也就是有没有联网都可以正常工作，而SVN在没有联网的时候是拒绝干活的！当有网络的时候，再把本地提交推送一下就完成了同步，真是太方便了！
    
    先创建远程库，然后，从远程库克隆
    $ git clone git@github.com:michaelliao/gitskills.git
    克隆一个仓库，首先必须知道仓库的地址，然后使用git clone命令克隆。
    Git支持多种协议，包括https，但通过ssh支持的原生git协议速度最快。
    
    git相关文档：
    https://www.liaoxuefeng.com/wiki/0013739516305929606dd18361248578c67b8067c8c017b000/0013752340242354807e192f02a44359908df8a5643103a000





















克隆一份代码：
	git clone 地址

在提交之前，切记更新代码：
	git pull

代码添加到暂存区：
	git add .

代码提交到缓存区：
	git commit -m '提交注释'

推到线上：
	git push

查看状态：
	git status

码云通过ssh上拉取代码：
	首先，将自己的电脑设置SSH公钥，命令是：ssh-keygen -t rsa -C "邮箱"；
	然后，查看公钥cat ~/.ssh/id_rsa.pub，将公钥复制到码云上；
	最后，在码云上复制ssh路径，git clone ssh路径 ，就复制好了

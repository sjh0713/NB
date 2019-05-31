## 韩顺平go语言

区块链：简称BT，是一种互联网数据库技术 ，让每个人参与到数据库中

Go语言主要作用：大并发

Go语言、Unix、C语言、be开发者是：肯.汤普森

程序：为了让计算机执行某些操作或解决某个问题而编写的一系列有序指令的集合 

静态类型的编译型语言，安全和性能，动态开发语言开发维护的高效

mac安装了ssh服务，默认情况下不会开机自启动：

    启动：sudo launchctl -w /System/Library/LaunchDaemons/ssh.plist
    停止：sudo launchctl upload -w /System/Library/LaunchDaemons/ssh.plist
    查看是否启动成功：sudo launchctl list|grep ssh

mac 下配置golong环境变量
        
    1.在/etc/profile文件下添加3条语句
        export GOROOT=/opt/go
        export PATH=$GOROOT/bin:$PATH
        export GOPATH=$HOME/goproject/
    2.查看环境变量
        echo $PATH
    3.当前环境变量
        cat ~/.bash_profile

Go程序开发注意事项：
    
    1.go 语言以.go结尾
    2.go语言的程序入口是main
    3.go语言严格区分大小写
    4.每个语句后面不需要加分号
    5.go是一行行进行编译的，不要把多条语句写入一行
    6.定义的变量或者import的包，必须使用，不使用就会报错 

Golang官方标准API文档

    https://golang.google.cn/
    中文文档：https://studygolong.com/pkgdoc 



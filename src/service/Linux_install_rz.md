##安装方法汇总:
1、安装方法(推荐)

        yum install lrzsz -y

2、在安装Linux系统时选中“DialupNetworking Support”组包

3、安装系统联网启动后执行yum直接安装组包

    yum groupinstall "Dialup Networking Support" -y


问题：linux系统中打rz命令后出现waiting to receive.**B0100000023be50？
        
    rz 与 sz 需要端支持。终端就是连接远程服务器的客户端，例如 XShell、SecureCRT、putty 等，linux默认终端是不支持的

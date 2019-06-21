## mac连接服务器报错

- Mac和Linux远程连接服务器异常修复（WARNING: REMOTE HOST IDENTIFICATION HAS CHANGED!）
    - 今天在使用SSH，连接远程服务器的时候，刚开始是没问题的。 后来阿里云主机重装了一下系统后，再也连不上了。一直报一个错。  

          ~ ⌚ 22:49:52
          $ ssh root@47.98.233.15
          @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          @    WARNING: REMOTE HOST IDENTIFICATION HAS CHANGED!     @
          @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          IT IS POSSIBLE THAT SOMEONE IS DOING SOMETHING NASTY!
          Someone could be eavesdropping on you right now (man-in-the-middle attack)!
          It is also possible that a host key has just been changed.
          The fingerprint for the ECDSA key sent by the remote host is
          SHA256:8hgJ8jpcBr1tm6HS72FpXwMrjba8MQqlqYJQLPB/Qf4.
          Please contact your system administrator.
          Add correct host key in /Users/wangdong/.ssh/known_hosts to get rid of this message.
          Offending ECDSA key in /Users/wangdong/.ssh/known_hosts:26
          ECDSA host key for 47.98.233.15 has changed and you have requested strict checking.
          Host key verification failed.
 
    - 解决方法，看错误日志中有一句
    
          Add correct host key in /Users/wangdong/.ssh/known_hosts to get rid of this message.
    
    - 以编辑器的方式进入这个文件
          
          vi /Users/wangdong/.ssh/known_hosts
          
    - 将红线框部分删除掉 
          
          带该服务器ip的部分删除

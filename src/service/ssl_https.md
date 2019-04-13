阿里云配置ssl
查找文档：https://blog.csdn.net/qq371959453/article/details/80508789

1.进入当前实例
2.实例后面的操作，点击更多，下面的网络和安全组，点击安全组配置
3.配置规则-》添加安全组规则
	1.端口范围：443/443
	2.授权对象：0.0.0.0/0
4.域名-〉云解析DNS-》解析设置-〉添加解析
	1.主机记录，对应的域名
	2.记录值：IP
5.购买ssl证书：
	通配符DV SSL -》Symantec->立即购买
	输入证书绑定的域名
6.下载证书
7.在ssl控制台中上传证书，将pem和key的内容复制到相对应的证书文件和证书私匙	
8.安装证书
如果是证书系统创建的CSR，还包含：证书私钥文件2147*********60078.key。
( 1 ) 在Nginx的安装目录下创建cert目录，并且将下载的全部文件拷贝到cert目录中。如果申请证书时是自己创建的CSR文件，请将对应的私钥文件放到cert目录下并且命名为2147*******60078.key；
( 2 ) 打开 Nginx 安装目录下 conf 目录中的 nginx.conf 文件，找到：
将其修改为 (以下属性中ssl开头的属性与证书配置有直接关系，其它属性请结合自己的实际情况复制或调整) :
server {
    listen 443;
    server_name localhost;
    ssl on;
    root html;
    index index.html index.htm;
    ssl_certificate   cert/2147******60078.pem;
    ssl_certificate_key  cert/2147******60078.key;
    ssl_session_timeout 5m;
    ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:ECDHE:ECDH:AES:HIGH:!NULL:!aNULL:!MD5:!ADH:!RC4;
    ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
    ssl_prefer_server_ciphers on;
    location / {
        root html;
        index index.html index.htm;
    }
}
保存退出。
( 3 )重启 Nginx。
( 4 ) 通过 https 方式访问您的站点，测试站点证书的安装配置。

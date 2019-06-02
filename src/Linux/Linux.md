## 服务器和本地之间的相互交互
1.从服务器复制文件到本地：

	scp root@×××.×××.×××.×××:/data/test.txt /home/myfile/

2.从服务器复制文件夹到本地：

	scp -r root@×××.×××.×××.×××:/data/ /home/myfile/
	只需在前面加-r即可，就可以拷贝整个文件夹。

3.从本地复制文件到服务器：

	scp /home/myfile/test.txt root@192.168.1.100:/data/

4.从本地复制文件夹到服务器：
	
	scp -r /home/myfile/ root@192.168.1.100:/data/


5.linux和本地之间文件传输：

	https://blog.51cto.com/oldboy/588592
	上传：[root@oldboy ~]# rz
	下载：sz oldboy.txt


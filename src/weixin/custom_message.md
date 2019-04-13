微信小程序-客服会话：

微信公众平台文档：
https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/customer-message/customer-message.html

第一步：后台接入消息服务
	按照文档进行配置，url服务器地址，token随便写（与之后的代码一致就好），EncodingAESKey随机生成，模式选择安全模式，数据格式选择json;(现在提交肯定是不成功的)
第二步：微信写button ，进入客服会话页面（便于调试）
第三步：根据你配置的URL服务器地址，进行复制代码
	代码码云地址：https://gitee.com/mylsunboy/message.git
	注意：如何你和我一样，是用thinkphp3框架的话，项目文件夹下，直接单独创建个文件
第四步：
	发送消息：https://developers.weixin.qq.com/miniprogram/dev/api/open-api/customer-message/sendCustomerMessage.html
	这个就按照微信API进行一步步调用即可，实现代码都在码云上

下载代码地址：https://gitee.com/mylsunboy/message.git
另一个小技巧：判断是否接收到数据，可以新建一个文件，如果Linux，需要设置0777，通过file_put_contents(),进行写入值来测试


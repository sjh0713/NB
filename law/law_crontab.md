## 相关命令文档
- 定时生成统计相关命令

		php bin/console scheduling:update:consultinglog
        php bin/console scheduling:update:signlog
        
- 定时生成排班相关命令（每天的23点执行）
       
    
    0 23 * * * php /home/wwwroot/law/bin/console scheduling:info:create
	
- 定时生成资金核发命令（每个季度/每个月）

    
    0 0 1 * * php /home/wwwroot/law/bin/console	scheduling:update:funds

- 根据实体平台数据生成用户（暂时不用）


    * * * * * php /home/wwwroot/law/bin/console scheduling:user:create

- 生成首页统计信息（每小时生成一次）


    0 * * * * php /home/wwwroot/law/bin/console scheduling:info:statistics

- 发送短信通知（每5分钟生成一次）

    
    */5 * * * * php /home/wwwroot/law/bin/console scheduling:sms:send

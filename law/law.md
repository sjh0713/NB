
生成表的命令：

	php bin/console kit:doctrine:generate:entity
生成repository的赋值和获取：

	php bin/console doctrine:schema:update --force
发短信的命令：

	php bin/console scheduling:sms:send

## 相关命令文档
- 定时生成统计相关命令

		php bin/console scheduling:update:consultinglog
        php bin/console scheduling:update:signlog
- 定时生成排班相关命令

		php bin/console scheduling:info:create
- 定时生成资金核发命令

		php bin/console scheduling:update:funds
- 根据实体平台数据生成用户

		php bin/console scheduling:user:create
- 生成首页统计信息

		php bin/console scheduling:info:statistics
- 修复地区问题

		php bin/console scheduling:user:fixed
- 修复律师,基层人员的地区问题
    		
      php bin/console scheduling:alter:law
      
- 修改法定五一节假日（可以传入时间，进行修改某个时间段的，将状态改为2）

      php bin/console scheduling:update:state
- 发送短信通知

        php bin/console scheduling:sms:send

### 需要清空的表
- scheduling 排班表
- work_consult 咨询数据表
- work_consult_number 咨询统计表
- sign_log 签到日志表
- consulting_info 咨询统计表
- consulting_log 咨询日志表
- abnormal 异常上报表
- attendance 考勤记录表
- funds 资金补助表
- duty_person 服务人员库
- add_task 任务下达表（省级）
- add_task_city 任务下达（市级）
- add_task_area 任务下达（县区级）

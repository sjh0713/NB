## 配置：
app/config/security.yml 安全系统

access_control 要求用户登录才能访问此URL

	IS_AUTHENTICATED_FULLY检查用户是否登录
	IS_AUTHENTICATED_ANONYMOUSLY所有用户（甚至是匿名用户）都有此功能

防火墙  admin_firewalls:

    pattern:   ^/admin
    anonymous: ~
    provider: database_admin_users   //数据库用户
    guard:
        authenticators:
            - auth.admin_form_login_authenticator
    form_login:
        login_path: auth_login_index  //登陆路由
        check_path: auth_login_check	//验证路由
        default_target_path: /admin     //登陆成功后的页面
        username_parameter: _username	//登陆用户
        password_parameter: _password	//登陆密码
        failure_path: auth_login_index	//登陆失败的页面
    logout:
        handlers: [rbac.logout_handler]
        path: auth_login_logout		//退出登陆执行的路由
        target: auth_login_index	//退出登陆跳转的页面

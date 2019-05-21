
# Basic 基础知识

- 获取登录用户信息
    - 控制器中
    
        $this->getUser();
        
        等价于：
            
            $this->get('security.token_storage')
                ->getToken()
                ->getUser();
            
        Twig中：
        
            {% if app.user %}{{ app.user.username }}{% else %}游客{% endif %}
        
	
- 用户密码加密

		/**
		*
		* @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoder
		*/
		$encoder = $this->get('security.password_encoder');

	校验密码:

		$encoder->isPasswordValid($user, $userForm->getPassword())；

	密码加密:

    	$password = $encoder->encodePassword($user, $userForm->getPlainPassword());
	
	
* 文件上传相关

		$file = $request->files->get('file'); //获取上传的文件
		if($file instanceof UploadedFile){
		  //不为空
			$filename = $this->get('kit.file_uploader')->upload($file, 'file');
		}else{
				//未上传
		}

* 获取根目录和host

		$this->getContainer()->get('kernel')->getRootDir()
		$root = $this->container->get('kernel')->getRootDir();
		$root = $this->get('kernel')->getRootDir();
		// xx/xxx/app
		//Symfony 3.3
		$root = $this->get('kernel')->getProjectDir()
		%kernel.project_dir%
		use %kernel.project_dir%/web/ instead of %kernel.root_dir%/../web/.

* get HOST

		$request()->getHost()

* 获取当前路由名称

		// controller
		$routeName = $request->get('_route');
		//twig
		app.request.get('_route')

* 获取头部信息

		$ua = $request->headers->get('User-Agent');

* 获取referer

		$this->getRequest()->headers->get('referer')；
		$request->server->get('HTTP_REFERER');














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



控制器中路由的跳转：$this->redirectToRoute('admin_homepage');

src/webBundle是一个空项目
URL：/
跳转到后台登陆，做了路由跳转
路由跳转：
	return $this->redirectToRoute('admin_homepage');
路由渲染：
	return $this->render('@Web/Default/index.html.twig',[])

src/adminBundle是一个后台首页






UUID就是一串全球唯一的(16进制)数字串，译为“通用唯一识别码”
$this->getUser();获取登陆的用户信息

Shortcuts methods in Controller

1. 获取服务

		$this->get($serviceId);

2. 重定向

		$this->redirectToRoute($routeName, $parameters, $status = 302);
		// add anchor增加锚点, symfony > 3.2
		$this->redirectToRoute($routeName, ['_fragment' => 'anchor2']);
		//$this->redirect($this->generateUrl($routeName, array('id' => $id)) . '#anchor7');

3. 生成路由的url

		$this->generateUrl($routeName, $parameters, $referenceType);

4. 返回一个json response

	 	$this->json($data, $status = 200, $headers = array(), $context = array());
	 	
5. 添加一个flash message
	
	 	$this->addFlash($type, $message);

6. 判断是否授权进入某个方法

		$this->isGranted('ROLE_ADMIN');
		
7. 判断用户是否授权，否，抛出异常

		$this->denyAccessUnlessGranted('ROLE_EDIT', $item, 'You cannot edit this item.');
		
8. 手动判断 CSRF token是否合法

		$this->isCsrfTokenValid('token_id', $token);

9. 把请求转发到其他控制器和方法

		$this->forward('GregwarCaptchaBundle:Captcha:generateCaptcha', ['key' => $key]);
 		//forward($controller, array $path = array(), array $query = array())
 
		PS： 第二个参数为route上占位符参数，第三个为其他额外的参数

10. 文件下载（symfony >= 3.2）

		$this->file();
		
		//示例代码：
		public function downloadAction(Request $request)
		{
		    $id = $request->query->getInt('id');
		    if(empty($id)){
		        return $this->msgResponse(2, '出错啦', '请求出错啦', 'kit_doc_homepage');
		    }
		    $doc = $this->getDoctrine()->getRepository('KitDocBundle:FileManagement')->find($id);
		    if(empty($doc)){
		        return $this->msgResponse(2, '出错啦', '文档不存在或已被删除', 'kit_doc_homepage');
		    }
		    $docPath = 'uploads' . $doc->getDocPath();
		    if(!file_exists($docPath)){
		        return $this->msgResponse(2, '出错啦', '文件不存在或已被删除', 'kit_doc_homepage');
		    }
		    return $this->file($docPath, $doc->getDocName()); // 如果是pdf之类，直接显示而不是下载，需要设置第三个参数ResponseHeaderBag::DISPOSITION_INLINE
			// 还可以直接接受一个File或者UploadedFile实例
			//$samplePdf = new File('/sample.pdf');
			//return $this->file($samplePdf);
		}

11. 读取配置参数
	
		$this->getParameter('kernel.root_dir')；
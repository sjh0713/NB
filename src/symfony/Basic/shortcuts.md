## Shortcuts methods in Controller
1. 获取服务

        $this->get($serviceId);
2. 重定向
    
         $this->redirectToRoute($routeName, $parameters, $status = 302);
         // add anchor增加锚点, symfony > 3.2
         $this->redirectToRoute($routeName, ['_fragment' => 'anchor2']);
         //$this->redirect($this->generateUrl($routeName, array('id' => $id)) . '#anchor7');
3. 生成路由的url
        
        $this->generateUrl($routeName, $parameters, $referenceType);
4. 文件下载
    
        $this->file()
    [文件下载简单案例](./../ServiceEg/download.md)
5. 读取配置参数
    
        $this->getParameter('kernel.root_dir')
6. 返回一个json response
        
         $this->json($data, $status = 200, $headers = array(), $context = array());
         $this->json(['code'=>200,'msg'=>'成功'])


# 一、请求方式
## 1. 请求类型
	$request->getMethod() == "POST"
	
	//判断表单是否提交：
	$request->request->get('submit')
	
	//判断ajax请求：
	$request->isXmlHttpRequest()

## 2. 接收数据
	POST请求接收数据：
	$request->request->get('projectVal');
	
	GET请求接收数据：
	$request->query->get('projectVal');

# 二、控制器中：

	获取容器管理：
	$em = $this->getDoctrine()->getManager();
	
	控制器中查询数据：
	$this->getDoctrine()
        ->getRepository('RbacBundle:User')
        ->findOneBy([]);
        ->find(id)
        ->findAll()
        ->findBy([array ],[array order],int limit,int offset)
        ->getClassName()
	
	控制器中数据的插入：        
	$em = $this->getDoctrine()->getManager();
	$device = new Device();
	$device->setName($name);
    $em->persist($device);
    $em->flush();
	
	数据库的修改：
	$em = $this->getDoctrine()->getManager();
	$valve_parameter = $em->getRepository('ValveBundle:ValveParameter')
        ->findOneBy(array(
            'deviceId' => $control_parameter_info['DeviceID']
        ));
	$valve_parameter->setExpectValue($expect_value);
    $em->flush();


	在命令中进行数据库的操作：
	//doctrine是服务标示符
	$test= $this->getContainer()->get('doctrine')
		->getRepository('RtuBundle:RateFlow');
	然后可以在Repository进行操作


### Repository对数据库的操作：（需要和entity对应）
	数据的查询：
	$this->createQueryBuilder('a')
		->getQuery() //进行分页操作
		->groupBy('a.typeId')
		->where('a.typeId = :typeId') //多个条件需要写在一起
        ->setParameter('typeId', $typeId)
        ->setParameters()
        ->orderBy('a.stage', 'ASC')  //排序
        ->orWhere()
        ->andWhere()
        ->setMaxResults(1)  //设置查询结果条数
        ->getArrayResult()  //数组
        ->getResult()   //对象
	链接查询：
	$conn = $this->getEntityManager()->getConnection();
	$sql = '';
	$stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();

	修改：
	$this->createQueryBuilder('a')
        ->update()
        ->set('a.rate', $rate)
        ->where('a.id = :id')
        ->getQuery()
        ->execute([
            'id' => $id
        ]);

分组查询
    $data = $this->createQueryBuilder('a')
        ->select('a.instituId,a.date')
        ->groupBy('a.instituId')
        ->addGroupBy('a.date')
        ->having('a.instituId is not null and a.date is not null')
        ->getQuery()
        ->getArrayResult();

getArrayResult和getResult的区别



获取单条数据
->getSingleResult();
            
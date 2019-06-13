## 表单较验
1. 使用entity的constraint校验表单的数据

         /**
          * @var \Symfony\Component\Validator\Validator\ValidatorInterface $validator
          */
         $validator = $this->get('validator');
         /**
          * @var \Symfony\Component\Validator\ConstraintViolationListInterface $errors
          */
         $errors = $validator->validate($egressApply);
         if (count($errors) == 0) {
         	
         }


2. 表单提交：
        
        -- 控制器
        
        /**
        *
        * @var \ApprovalBundle\Entity\PracticeChange $practiceChange
        */
        $practiceChange = $this->handleRequest($request, PracticeChange::class, $this->tableKey, $practiceChange);
        /**
        *
        * @var \Symfony\Component\Validator\Validator\ValidatorInterface $validator
        */
        $validator = $this->get('validator');
        
        //单独设置一些隐含的值
        $practiceChange->setAuthenastName($this->getAuthenastName(intval($practiceChange->getAuthenastId())));
        $practiceChange->setUuid($this->uuid);
        $practiceChange->setIp($request->getClientIp());
        
        /**
        *
        * @var \Symfony\Component\Validator\ConstraintViolationListInterface $errors
        */
        $errors = $validator->validate($practiceChange);
        

        -- BaseController(基类)
        
        protected function handleRequest(Request $request, $entity, $tableName, $entityObject = null)
        {
            if (is_null($entityObject)) {
                $entityObject = new $entity();
            }
            $post = $request->request->get($tableName);
            if(is_array($post)){
                foreach ($post as $key => $val) {
                    $method = 'set' . ucwords($key, '_');
                    if (method_exists($entity, $method)) {
                        $entityObject->$method($val);
                    }
                }
            }
            return $entityObject;
        }
        
        -- Entity(验证)
        
        /**
         * @UniqueEntity(fields="uuid", message="申请记录编码已存在")
        **/
        
        //字段的设置
        /**
         * @var string
         *
         * @ORM\Column(name="uuid", type="string", length=36, options={"comment":"申请记录编码"})
         * @Assert\NotBlank(message="申请记录编码不能为空")
         * @Assert\Length(
         *      max = 36,
         *      maxMessage = "申请记录编码最多36位"
         *      )
         */
        private $uuid;
        
        注意：类的传递 PracticeChange::class
             获取客户端的Ip：$request->getClientIp()
             表单进行验证，每个字段如果不为空的时候在验证的时候要加限制条件
             防止表单重复提交的方法：加load

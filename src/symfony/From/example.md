## 表单添加

- Controller

        public function newAction(Request $request)
        {
            $errors = [];
            $groupsUser = new GroupsUser();
            //GroupsUserType 进行from表单的创建
            $form = $this->createForm('ProcessBundle\Form\GroupsUserType', $groupsUser);
            $form->handleRequest($request);
    
            if ($form->isSubmitted()) {
                if($form->isValid()){
                    $em = $this->getDoctrine()->getManager();
                    $groupsUser->setValue($value);
                    $em->persist($groupsUser);
                    $em->flush();
                    return $this->msgResponse(0, '提示', '新增成功','process_groupsuser_index');
                }else{
                    $errors = $this->serializeFormErrors($form);
                }
            }
    
            return $this->render('@Process/GroupsUser/new.html.twig', array(
                'groupsUser' => $groupsUser,
                'form' => $form->createView(),
                'errors' => $errors
            ));
        }

- Entity Type(参见form build)

- Twig

      {{ form(form, {'attr': {'novalidate': 'novalidate', 'class': 'form-x', 'id': 'form'}}) }}

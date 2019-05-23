## Doctrine

- 批量插入 Bulk Inserts

        $batchSize = 20;
        for ($i = 1; $i <= 10000; ++$i) {
          $user = new CmsUser;
          $user->setStatus('user');
          $user->setUsername('user' . $i);
          $user->setName('Mr.Smith-' . $i);
          $em->persist($user);
          if (($i % $batchSize) === 0) {
            $em->flush();
            $em->clear(); // Detaches all objects from Doctrine!
          }
        }
        $em->flush(); //Persist objects that did not make up an entire batch
        $em->clear();

- Doctrine生命周期(用来设置时间)

        use Doctrine\ORM\Mapping as ORM;
        // Entity类前面
        @ORM\HasLifecycleCallbacks()
        // 类中的方法
        /**
        * @ORM\PrePersist()
        */
        public function prePersist()
        {
          if($this->getCreateAt() == null){
              $this->setCreateAt(new \DateTime());
          }
          $this->setUpdateAt(new \DateTime());
        }
        /**
        * @ORM\PreUpdate()
        */
        public function preUpdate()
        {
          $this->setUpdateAt(new \DateTime());
        }
   
    
    

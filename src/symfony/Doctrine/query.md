## Query Builder

>Doctrine 关于Query Builder的文档 https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/query-builder.html

## Repository
- order by multiple(多个字段排序)
    
        $qb->add('orderBy','first_name ASC, last_name ASC')
- count
    
        public function getUnreadCount($userId)
        {
            return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.userId = :uid AND u.isRead = 0')
            ->setParameter('uid', $userId)
            ->orderBy('u.publicId', 'DESC')
            ->getQuery()
            ->getSingleScalarResult();
        }


- query

        public function getList($userId, $page, $pagesize, $condition = [])
        {
            //分页
            $offset = ($page - 1) * $pagesize;
            $qb = $this->createQueryBuilder('o')->select("o.id,o.orderNo as order_no, o.number,o.subject,o.status,o.ammount,DATE_FORMAT(o.payAt,'%Y-%m-%d %H:%i') as pay_at,DATE_FORMAT(o.createAt,'%Y-%m-%d %H:%i') as create_at");
            $qb->where('o.clientId = :cid');
            $parameters = [
              'cid' => $userId
            ];
            if(isset($condition['subject']) && !empty($condition['subject'])){
              $qb->andWhere($qb->expr()->like('o.subject', ':subject'));
              $parameters['subject'] = '%'. $condition['subject']. '%';
            }
            if(isset($condition['start_time']) && !empty($condition['start_time'])){
              $qb->andWhere($qb->expr()->gte('o.createAt', ':start'));
              $parameters['start'] = $condition['start_time'];
            }
            
            if(isset($condition['end_time']) && !empty($condition['end_time'])){
              $qb->andWhere($qb->expr()->lte('o.createAt', $condition['end_time']));
              $parameters['end'] = $condition['end_time'];
            }
            $qb->setParameters($parameters);
            $qb->setMaxResults($pagesize);
            $qb->setFirstResult($offset);   
            return $qb->getQuery()->getArrayResult();
        }
        
- expr
    
        $qb = $this->createQueryBuilder();
        $expr = $qb->expr();
        $qb->select('DISTINCT itc.item_id')
            ->from('items_to_collections', 'itc')
            ->innerJoin('itc', 'statuses', 's', 's.id = itc.status_id')
            ->innerJoin('itc', 'tags_to_items', 'tti', 'tti.item_id = itc.item_id')
            ->where($expr->andX(
              $expr->eq('s.status_symbol', ':status_symbol'),
              $expr->eq('tti.tag_id', ':tag_id'),
              $expr->like('itc.path', ':path')
            ))
            ->setParameters([
              'status_symbol' => 'st_live',
              'tag_id' => $tag_id,
              'path' => $parent_path
            ])
            ->orderBy('itc.' . $sort_order, 'ASC');
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }
        if($offset > 0){
            $qb->setFirstResult($offset);   
        }
        if (false === empty($existing)) {
            $qb->andWhere($expr->notIn('itc.item_id', ':item_id'))
                ->setParameter('item_id', ((array) $existing), , \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);
        }
        $items_ids = $qb->execute()->fetchAll(\PDO::FETCH_COLUMN);
        
- LIKE
        
        $qb = $this->createQueryBuilder('u');
        $qb->where(
               $qb->expr()->like('u.username', ':user')
           )
           ->setParameter('user','%Andre%')
           ->getQuery()
           ->getResult();
           
- between

        $qb = $this->createQueryBuilder('u');
        $qb->where(
               $qb->expr()->between('t.price', $startPrice, $targetPrice)
           )
        ->getQuery()
        ->getResult();
        
- return array result(返回数组还是对象)

        $result = $this->getDoctrine()
            ->getRepository('MyBundle:MyEntity')
            ->createQueryBuilder('e')
            ->select('e')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
            //->getArrayResult();
        
        public function getHeaderNav($moduleId, $toArray = false, $ids = null)
        {
            $qb = $this->createQueryBuilder('r');
            $qb->where($qb->expr()
            ->andX($qb->expr()
            ->eq('r.status', ':status'), $qb->expr()
            ->eq('r.pid', ':pid'), $qb->expr()
            ->isNotNull('r.icon')))
            ->setParameters([
            'status' => 1,
            'pid' => $moduleId
            ]);
            if(!empty($ids)){
                $qb->andWhere('r.id IN(:ids)')->setParameter('ids', $ids);
            }
            if ($toArray) {
                return $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                // or 
                return $qb->getQuery()->getArrayResult();
            } else {
                return $qb->getQuery()->getResult();
            }
        }

- page

        $offset = ($page - 1) * $pagesize;
        $qb = $this->createQueryBuilder('a');
        $qb -> where("FIND_IN_SET($id,n.view)");
        $qb->setMaxResults($pagesize);
        $qb->setFirstResult($offset);
        return $qb->orderBy('a.createAt', 'desc')
          ->getQuery()
          ->getArrayResult();
- update

        $qb = $this->em->createQueryBuilder();
        $q = $qb->update('models\User', 'u')
            ->set('u.username', '?1')
            ->set('u.email', '?2')
            ->where('u.id = ?3')
            ->setParameter(1, $username)
            ->setParameter(2, $email)
            ->setParameter(3, $editId)
            ->getQuery();
        $p = $q->execute();
        
        public function updateStatus($orderNo, $origin, $new)
        {
            return $this->createQueryBuilder('o')
                ->update()
                ->set('o.status', $new)
                ->set('o.callbackAt', ':datetime')
                ->set('o.updateAt',  ':datetime')
                ->where('o.orderNo = :orderNo AND o.status = :origin')
                ->setParameters([
                  'orderNo' => $orderNo,
                  'origin' => $origin
                ])
                ->setParameter('datetime', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
                ->getQuery()
                ->execute();
        }
        
        public function setRead($mimeId, $toId)
        {
            return $this->createQueryBuilder('r')
                ->update()
                ->set('r.status', 1)
                ->where('r.mimeId = :mid AND r.toId = :tid')
                ->getQuery()
                ->execute([
                'mid' => $mimeId,
                'tid' => $toId
                ]);
        }
        
- delete

        public function deleteByUserId($userId, $contactId)
        {
            $this->createQueryBuilder('c')
                ->delete()
                ->where('c.userId = :uid AND c.contactId = :cid')
                ->setParameters([
                  'uid' => $userId,
                  'cid' => $contactId
                ])
                ->getQuery()->getResult();
        }

## Controller

- delete

        $item = $groupUserRepo->findOneBy(['code'=>$val]);
        $em->remove($item);
        $em->flush();

## redis基础知识

- 定义：
    
      Redis也是一个内存缓存服务器，也是将数据保存到内存
- redis和memcache区别：

      memcache服务器只支持字符串，如果存复合类型的数据，需要通过php的扩展序列化
      Redis支持字符串，除此之外还支持新的数据类型：队列、链表等结构的数据
      Redis支持部分数据的持久化存储

- 字符串：
      
      案例：
      $Redis=new Redis();

      $Redis->set("test","愿有你的每一天，都是那么幸福");
      echo $Redis->get("test")."</br>";

      //数组(存储需要序列化)
      $data = ['me','mother','father'];
      $Redis->set('data',serialize($data));
      echo $Redis->get('data')."</br>";
         
      说明：在redis中，如果需要存储数组到内存中，需要先序列化再存储，读取数据的时候，再反序列化
 
    - redis通用操作
        - set（key，value）
        - get（key）
        - incr（key，int），递增
        - decr（key，int），递减
                        
                案例：
                $Redis->set("num",2);
                echo $Redis->inc('num',3)."</br>";
                echo $Redis->dec('num',1)."</br>";
                echo $Redis->get('num')."</br>";

    - 删除某一项
        
          $redis->delete('num');
      
    - expire（key，length），设置有效的时间段
    - expireAt（key，timestamp），设置有效的时间点
    - ttl（key），获取数据的存活时间
    - flushAll，清空所有缓存的数据
    - keys(‘*’)，读取有哪些缓存项
    - exists(key)，判断缓存项是否存在

- 链表
    - 通常可以使用lpush、rpush、lpop、rpop模拟队列生成订单
    - lget，读取指定下标的元素值
    - lset，设置指定下标的元素值
    - llen，读取数组的长度
    - lRem, 删除
    
- hashes哈希结构数据 
    - hset
    - hget
    - hlen
    - hdel
    - hexists
- sets集合类型数据
    - flushAll()，清空内存中的所有数据
    - sAdd，添加元素
    - sMembers()，查询数组的成员
- ordered sets有序集合类型数据
    - 应用场景：用于粉丝数量、积分等字段，可以使用有序集合
    - zadd，添加元素，如果已存在则更新
    - zincBy，递增，如果数值为负数，表示递减
    - zScore，返回某一项的值，根据下标返回
    - zrange，返回集合成员，如果参数4位true，则返回成员的值
    - zRevRange，返回一个倒序的成员


- 案例：
            
        //实例化对象
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->flushAll();

        //字符串
        $redis->set("test","愿有你的每一天，都是那么幸福");
        echo $redis->get("test")."</br>";

        //数组(存储需要序列化)
        $data = ['me','mother','father'];
        $redis->set('data',serialize($data));
        echo $redis->get('data')."</br>";

        //增加和减少
        $redis->set("num",2);
        echo $redis->incrBy('num',3)."</br>";
        echo $redis->decrBy('num',1)."</br>";
        echo $redis->get('num')."</br>";
        $redis->del('num');

        //lists链表
        $redis->lpush('key1','order1');
        $redis->lpush('key1','order2');
        $redis->lpush('key1','order3');
        $redis->lpush('key1','order4');
        $redis->lpush('key1','order5');
        $result = $redis->lRange('key1',0,-1);
        var_dump($result);
        echo "</br>";

        //lists删除
        $redis->lPop('key1');
        $redis->rPop('key1');
        $res = $redis->lRange('key1',0,-1);
        var_dump($res);
        echo "</br>";

        //lists链表
        $redis->lSet('key1',2,'订单5');
        $res_one = $redis->lRange('key1',0,-1);
        dump($res_one);
        dump($redis->lLen('key1'));
        echo "</br>";

        $redis->lRem('key1','订单5',1);
        $res_one = $redis->lRange('key1',0,-1);
        dump($res_one);
        dump($redis->lLen('key1'));
        echo "</br>";

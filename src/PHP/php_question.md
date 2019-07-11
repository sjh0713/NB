## 常见问题总结：

1. php二维数组以某个字段进行排序
    
       $last_names = array_column($arr,'completeness');
       array_multisort($last_names,$sort_state,$arr);

2. php设置唯一编码：

        //设置编码
        $code = time(). str_pad($serverId, 5, '0', STR_PAD_LEFT);
        
        str_pad（）方法：
        用法：str_pad(string $input , int $pad_length [, string $pad_string = " " [, int $pad_type = STR_PAD_RIGHT ]] ) 
        作用：使用另一个字符串填充字符串为指定长度
        参数：pad_type： STR_PAD_RIGHT，STR_PAD_LEFT 或 STR_PAD_BOTH

3. php获取两个数组相同的元素（交集）以及比较两个数组中不同的元素（差集）

    - php获取两个数组相同元素
        
          array  array_intersect(array  $array1, array $array2, [, array $...])    
          array  array_intersect_assoc(array  $array1, array $array2, [, array $...])  
          
          这两个方法的功能基本一致，都是返回两个数组（也可以是多个数组）中都存在的元素，不同的是，前者只考虑数组中元素的 value 一致就认为两者相同，而后者需要 key 和 value 都一致才认为两者相同，
          例如：
            <?php
            $array1 = array('1', 'a' => 'aaaaaa', 'b' => 'bbbbbb', 'c');
            $array2 = array('a' => 'aaaaaa', 'c' => 'bbbbbb', 'c', '1');
            var_dump(array_intersect($array1,$array2));
            
          运行上面的代码会得到如下结果：
            array(4) {
              [0]=>
              string(1) "1"
              ["a"]=>
              string(6) "aaaaaa"
              ["b"]=>
              string(6) "bbbbbb"
              [1]=>
              string(1) "c"
            }
            
          而使用方法 array_intersect_assoc()将会得到如下结果：
            array(1) {
              ["a"]=>
              string(6) "aaaaaa"
            }
    - php比较两个数组中的不同元素
            
            array   array_diff(array  $array1, array $array2, [, array $...])
            array   array_diff_assoc(array  $array1, array $array2, [, array $...])
            类似的，这两个方法的基本功能也是一致的，返回第一个数组中有，但是其它数组没有的元素。 前者只比较值，后者同时比较 key 和 value。　
            
            <?php
            $array1 = array('1', 'a' => 'aaaaaa', 'b' => 'bbbbbb', 'c');
            $array2 = array('a' => 'aaaaaa', 'c' => 'bbbbbb', 'c', '1');
            var_dump(array_diff($array1,$array2));
            上面代码的运行结果为：
            array(0) {
            }
            
            而如果把最后一行更换为  var_dump(array_diff_assoc($array1, $array2));   
            将得到如下结果：
            array(3) {
              [0]=>
              string(1) "1"
              ["b"]=>
              string(6) "bbbbbb"
              [1]=>
              string(1) "c"
            }

4. 二维数组以某个字段转一维数组
    
        array_column($groups_user,'code')

5. PHP二维数组以某个字段进行排序

        $arr 为要排序的数组
        $last_names = array_column($arr,'field');
        array_multisort($last_names,'SORT_DESC',$arr);

6. jquery序列化后进行数据提交，PHP处理方法：

        $serilize = $request->request->get('serialize');
        $data = urldecode($serilize);
        parse_str($data,$query_arr); // 将字符串解析成多个变量

7. 身份证号安全性设置

        substr_replace($str,'*****',4,8)

8. 以某个字段进行去重复
        
        //二维数组以某个字段进行去重
        $result = array();
        foreach($data as $k=>$val){
            $code = false;
            foreach($result as $_val){
                if($_val['id'] == $val['id']){
                    $code = true;
                    break;
                }
            }
            if(!$code){
                $result[]=$val;
            }
        }

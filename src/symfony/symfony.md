# symfony笔记

对象取值：
$valve_token[0]->getId()


数据库查询：
$valve_token = $em->getRepository('ValveBundle:ValveToken')
    ->findBy(
        [],
        ['createAt'=>'asc'],
        ['limit'=>1]
    );


服务：

命令：

Repository：











##系统内置对象

1. Json对象
        
        //对数据中的特殊字符的处理
        $response = new JsonResponse($result);
        $response->setEncodingOptions(JSON_HEX_TAG | JSON_HEX_APOS);
        return $response;
    [参见文档] https://www.php.net/manual/zh/json.constants.php

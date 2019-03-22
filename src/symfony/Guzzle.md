通过面向对象的写法进行编写一个服务：

$client = $this->container->get('guzzle.client.api_valve');

//get请求
$response = $client->get($params['uri'], [
                'headers' => $headers,
                'query' => $params['data'],
            ]);

//post请求
$response = $client->post($params['uri'], [
            'headers' => [
	            'Authorization' => 'bearer ' . $token
	        ],
                'form_params' => $params['data'],
            ]);

请求成功：
$response->getStatusCode()==200

获取返回的数据：
$response->getBody();

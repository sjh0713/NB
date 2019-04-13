
#PHP错误异常
	catch (ClientException $e)
	catch (RequestException $e) {
	catch (\Exception $e)
	
	'data' => [
	    'exception_code' => $e->getCode(),
	    'exception_msg' => $e->getMessage(),
	    'request' => $e->getRequest(),
	    'response' => $e->getResponse()
	]
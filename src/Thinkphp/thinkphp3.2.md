##配置：

配置加载
* 惯例配置->应用配置->模式配置->调试配置->状态配置->模块配置->扩展配置->动态配置（优先顺序是从右到左）
* 惯例配置：惯例配置文件（位于 ThinkPHP/Conf/convention.php ），不需要修改惯例配置
* 应用配置：应用配置文件也就是调用所有模块之前都会首先加载的公共配置文件（默认位于 Application/Common/Conf/config.php ）。
* 调试配置：如果开启调试模式的话，则会自动加载框架的调试配置文件（位于 ThinkPHP/Conf/debug.php ）和应用调试配置文件（位于 Application/Common/Conf/debug.php ）
* 模块配置：每个模块会自动加载自己的配置文件（位于 Application/当前模块名/Conf/config.php ）。

读取配置

*     读取配置使用C方法，C(参数名称)
* 也可以读取二维配置：C('USER_CONFIG.USER_TYPE');
动态配置
* 设置格式：C('参数名称','新的参数值');
* 也可以支持二维数组的读取和设置

##架构

url模式

* ThinkPHP框架的URL是区分大小写
* 配置参数：'URL_CASE_INSENSITIVE' => true
* ThinkPHP支持的URL模式有四种：普通模式、PATHINFO、REWRITE和兼容模式，可以设置URL_MODEL参数改变URL模式
* 普通模式：传统的GET传参方式来指定当前访问的模块和操作，例如： http://localhost/?m=home&c=user&a=login&var=value
* PATHINFO模式：PATHINFO模式是系统的默认URL模式，提供了最好的SEO支持，系统内部已经做了环境的兼容处理，所以能够支持大多数的主机环境。
* REWRITE模式：
* 兼容模式：

CBD模式：

* 全新的CBD（核心Core+行为Behavior+驱动Driver）
* core核心：ThinkPHP的核心部分包括核心函数库、惯例配置、核心类库（包括基础类和内置驱动及核心行为），这些是ThinkPHP必不可少的部分。thinkphp目录下
* driver驱动：ThinkPHP/Library/Think/Cache/Driver // 缓存驱动类库 ThinkPHP/Library/Think/Db/Driver // 数据库驱动类库
* behavior行为：

##控制器

控制器定义

* A方法实例化的是默认控制器层（Controller），// 假设当前模块是Home模块 $User = A('User'); $Blog = A('Admin/Blog');
* 前置和后置操作：前置:_before_index();后置操作：_after_index()
* Action参数的绑定：
            （1）按照变量名绑定
* 'URL_PARAMS_BIND' => true, // URL变量绑定到操作方法作为参数
            （2）按照变量顺序绑定
* 'URL_PARAMS_BIND_TYPE' => 1, // 设置参数绑定按照变量顺序绑定
* URL生成：U('地址表达式',['参数'],['伪静态后缀'],['显示域名'])
ajax返回
* ajaxReturn();并且支持JSON、JSONP、XML和EVAL四种方式给客户端接受数据
跳转和重定向
* 类内置了两个跳转方法success和error，用于页面跳转提示，而且可以支持ajax提交
    1. // 操作完成3秒后跳转到 /Article/index $this->success('操作完成','/Article/index',3);
    2. // 操作失败5秒后跳转到 /Article/error $this->error('操作失败','/Article/error',5);
* redirect方法可以实现页面的重定向功能。
    1. //重定向到New模块的Category操作 $this->redirect('New/category', array('cate_id' => 2), 5, '页面跳转中...');

获取变量

* 不建议直接使用传统方式获取，因为没有统一的安全处理机制，后期如果调整的话，改起来会比较麻烦。所以，更好的方式是在框架中统一使用I函数进行变量获取和过滤。
* I方法是ThinkPHP用于更加方便和安全的获取系统输入变量，可以用于任何地方，用法格式如下：
* I('变量类型.变量名/修饰符',['默认值'],['过滤方法或正则'],['额外数据源'])

请求类型

* IS_GET、IS_POST、IS_PUT、IS_DELETE、IS_AJAX

空操作

* 空操作_empty()

##模型

D方法实例化：
* //实例化模型 $User = D('User');
* // 相当于 $User = new \Home\Model\UserModel();
* // 执行具体的数据操作 $User->select();

M方法实例化模型
1. D方法实例化模型类的时候通常是实例化某个具体的模型类，如果你仅仅是对数据表进行基本的CURD操作的话，使用M方法实例化的话，由于不需要加载具体的模型类，所以性能会更高。
D和M区别：我们在实例化的过程中，经常使用D方法和M方法，这两个方法的区别在于M方法实例化模型无需用户为每个数据表定义模型类，如果D方法没有找到定义的模型类，则会自动调用M方法。
实例化空模型类
* //实例化空模型 $Model = new Model();
* //或者使用M快捷方法是等效的 $Model = M();
* //进行原生的SQL查询 $Model->query('SELECT * FROM think_user WHERE status = 1')

字段的定义
* 如果需要显式获取当前数据表的字段信息，可以使用模型类的getDbFields方法来获取当前数据对象的全部字段信息，例如：
* $User = M('User'); $fields = $User->getDbFields();

连贯操作
* where条件（1）字符串条件（2）数组条件
* $Model->table('__USER__')->where('status>1')->select();会自动获取当前模型对应的数据表前缀来生成 think_user 数据表名称。
* data()方法
* field()获取指定字段
* order(‘ID desc’)排序
* fetchSql用于直接返回SQL而不是执行查询，适用于任何的CURD操作方法。 例如：
* create();数据创建
    


















    

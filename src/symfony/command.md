## 创建一个命令：


configure() 用来创建命令

    $this->setName('User:user:general') //命令的指令
    	//设置命令输入的指相关介绍
        ->addOption('email','u',InputOption::VALUE_OPTIONAL,'user email')  
        ->addOption('password','p',InputOption::VALUE_OPTIONAL,'user password')
        ->setDescription('generate admin account.') //指令的描述
        ->setHelp("This command generate admin account ..."); //查看帮助的描述
	
execute(）用来执行命令和逻辑操作


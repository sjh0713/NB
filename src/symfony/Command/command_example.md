## symfony常用命令：

	*运行PHP上：PHP bin/console server:run
	*自动加载：composer dump-autoload (新增bundle需执行)
	*数据库的创建：php bin/console doctrine:database:create 
	*创建bundle: php bin/console generate:bundle --namespace=AppBundle --formate=yml
	*创建entity和Repository: php bin/console doctrine:generate:entity AppBundle:User
	*生成get-set-repository: php bin/console doctrine:generate:entities AppBundle
	*创建数据表：php bin/console doctrine:schema:update --force
	*查看修改数据：php bin/console doctrine:schema:update --dump-sql


## 创建一个命令：

    configure() 用来创建命令
    execute(）用来执行命令和逻辑操作

#### 示例代码：

	namespace UserBundle\Command;
	
	use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Input\InputOption;
	use Symfony\Component\Console\Question\ConfirmationQuestion;
	use UserBundle\Entity\Admin;
	
	class UserCommand extends ContainerAwareCommand
	{
	    protected function configure()
	    {
	        $this->setName('app:create-u:general')
	            ->addOption('email','u',InputOption::VALUE_OPTIONAL,'admin email')
	            ->addOption('password','p',InputOption::VALUE_OPTIONAL,'admin password')
	            ->setDescription('generate admin account.')
	            ->setHelp("This command generate admin account ...");
	    }
	
	    protected function execute(InputInterface $input, OutputInterface $output)
	    {
	        //获取命令输入的参数,参数为-u *** -p ***
	        $email = $input->getOption('email');
	        $password = $input->getOption('password');
	
	        //没有执行默认
	        if(empty($email)){
	            $email = 'admin@qq.com';
	        }
	        if(empty($password)){
	            $password = 'admin';
	        }
	
	        $helper = $this->getHelper('question');
	
	        //询问是否添加数据
	        $question = new ConfirmationQuestion('generate account username:'.$email.', password:'.$password.'?(enter yes|no)', false);
	        if (!$helper->ask($input, $output, $question)) {
	            return;
	        }
	
	        //执行逻辑
	        $em = $this->getContainer()->get('doctrine')->getManager();
	        try {
	            $user = $em->getRepository('UserBundle:Admin')
	                ->findOneBy(array('email'=>$email));
	            if (empty($user)) {
	                $user = new Admin();
	                $user->setEmail($email);
	                $user->setPassword($password);
	                $em->persist($user);
	                $em->flush();
	            }
	        }catch (\Exception $exception){
	            $output->writeln([
	                'Exception',
	                'code:' . $exception->getCode(),
	                'msg:'. $exception->getMessage()
	            ]);
	            exit();
	        }
	
	        $output->writeln([
	            'generate success'
	        ]);
	    }
	}

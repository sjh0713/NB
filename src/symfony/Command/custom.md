## 自定义command

    <?php
    
    namespace SchedulingBundle\Command;
    
    use OrganBundle\Repository\AreaRepository;
    use SchedulingBundle\Entity\SignLog;
    use SchedulingBundle\Repository\AttendanceRepository;
    use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    
    class SignLogCommand extends ContainerAwareCommand
    {
        //签到日志 定时任务(每天执行一次)
        public function configure()
        {
            $this
                ->setName('scheduling:update:signlog')
                ->addOption('startTime', 'u', InputOption::VALUE_OPTIONAL, 'startTime 2019-03-23')
                ->setDescription('...')
                ->setHelp('...');
        }
    
        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $startTime = $input->getOption('startTime');
            if(empty($startTime)){
                $time = time();
            }else{
                $time = strtotime($startTime);
            }
            $output->writeln([
                '开始处理签到信息',
                '=--------------='
            ]);
            $this->news($output,$time);
            $output->writeln([
                '=--------------=',
                '完成签到信息处理'
            ]);
        }
    
        private function news(OutputInterface $output,$time)
        {
            $doctrine = $this->getContainer()->get('doctrine');
            $em = $doctrine->getManager();
            $date = date('Ymd',$time);
            /**
             * @var AreaRepository $area
             */
            $area = $em->getRepository('OrganBundle:Area')->getLevelAllData();
            for ($i = 0; $i < count($area); $i++) {
                //签到状态 0:未签到 1:正常 2:迟到 3:请假  4:异常上报（申报）5:异常上报（审核通过）6:异常上报（审核未通过）
                //签退状态 0:未签退 1:正常 2:早退 3:旷工 4:异常上报（申报）5:异常上报（审核通过）6:异常上报（审核未通过）
                $normal = 0; //正常出勤次数
                $late = 0; //异常次数(迟到,早退,异常审核通过)
                $absenteeism = 0; //旷工次数(没有值班的)
    
                $signLog = $em->getRepository('SchedulingBundle:SignLog')->findOneBy([
                    'date' => date('Y-m-d',$time),
                    'area' => $area[$i]['areacode'],
                    'city' => $area[$i]['parentid'],
                ]);
    
                //获取本天本地区的排班
                /**
                 * @var \SchedulingBundle\Repository\SchedulingRepository $schRepo;
                 */
                $schRepo = $em->getRepository('SchedulingBundle:Scheduling')->getSchedulingByDay($date,$area[$i]['areacode']);
                if(empty($schRepo)){
                    if(!empty($signLog)){
                        $signLog->setNormal($normal);
                        $signLog->setLate($late); //异常次数(迟到,早退,异常审核通过)
                        $signLog->setAbsenteeism($absenteeism); //旷工次数(旷工,审批未通过)
                        $sum = 0;
                        $signLog->setPersonNum($sum); //应出勤人数
                        $em->persist($signLog);
                        $em->flush();
                    }
                    $output->writeln([
                        '=--------------=',
                        $date.'暂无排班信息'
                    ]);
                    continue;
                }
    
                foreach ($schRepo as $key=>$val){
                    /**
                     * @var AttendanceRepository $attendance
                     */
                    $attendance = $em->getRepository('SchedulingBundle:Attendance')->getAttendanceByDate($date,$val['userId'],$val['institutionId']);
                    if(!empty($attendance)){
                        /**
                         * @var SignLog $signLog
                         */
                        foreach ($attendance as $k => $v) {
                            //当前时间大于下班时间
                            if (($v['signInstatus'] == 1 or $v['signInstatus']==5) and ($v['signOutstatus'] == 1 or $v['signOutstatus'] == 5)) {
                                $normal++; //正常出勤次数
                            }else{
                                $late++;
                            }
                        }
                    }else{
                        //矿工
                        $absenteeism += 1;
                    }
                }
    
                if (empty($signLog)) {
                    $signLog = new SignLog();
                    $signLog->setDate(date('Y-m-d',$time)); //时间
                    $signLog->setNormal($normal); //正常出勤次数
                    $signLog->setLate($late); //异常次数(迟到,早退,异常审核通过)
                    $signLog->setAbsenteeism($absenteeism); //旷工次数(旷工,审批未通过)
                    $sum = count($schRepo);
                    $signLog->setPersonNum($sum); //出勤人数
                    $signLog->setArea($area[$i]['areacode']);
                    $signLog->setCity($area[$i]['parentid']);
                    $em->persist($signLog);
                    $em->flush();
                    $output->writeln([
                        $area[$i]['areaname'] . '生成成功'
                    ]);
                } else {
                    $signLog->setNormal($normal);
                    $signLog->setLate($late); //异常次数(迟到,早退,异常审核通过)
                    $signLog->setAbsenteeism($absenteeism); //旷工次数(旷工,审批未通过)
                    $sum = count($schRepo);
                    $signLog->setPersonNum($sum); //应出勤人数
                    $em->persist($signLog);
                    $em->flush();
                    $output->writeln([
                        $area[$i]['areaname'] . '已更新数据'
                    ]);
                }
            }
    
        }
    
    }

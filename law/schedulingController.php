<?php

namespace SchedulingBundle\Controller;

use SchedulingBundle\Entity\Scheduling;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BaseBundle\Controller\BaseController;

/**
 *
 * @name 实体排班控制器
 *
 * @author yanfeng1012
 *
 */
class SchedulingController extends BaseController
{

    /**
     *
     * @name 实体排班列表
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        /**
         *
         * @var \RbacBundle\Entity\User $user
         */
        $user = $this->getUser();
        /**
         *
         * @var \ApplyBundle\Repository\EntityPlatformRepository $platformRepo
         */
        $platformRepo = $this->getDoctrine()->getRepository('ApplyBundle:EntityPlatform');
        $id = $request->query->getInt('id');
        switch ($user->getLEVEL()) {
            case 0:
            case 1:
            case 2:
                $platform = $platformRepo->find($id);
                break;
            case 3:
                $platform = $platformRepo->findOneBy([
                    'id' => $id,
                    'cITY' => $user->getCITY()
                ]);
                break;
            case 4:
                $platform = $platformRepo->findOneBy([
                    'id' => $id,
                    'aREA' => $user->getAREA()
                ]);
                break;
            default:
                return $this->msgResponse(2, '警告', '用户级别错误', 'duty_homepage');
                break;
        }
        if (empty($platform)) {
            return $this->msgResponse(2, '警告', '机构信息异常', 'duty_homepage');
        }
        // eg: 201902
        $month = $request->query->get('date', date('Y-m'));
        // 日历
        $calendar_array = $this->newCalendar($month);
        $calendar = $calendar_array['calendar'];

        //查询该月下的所有的节假日
        $first_day = date('Ymd', strtotime($calendar_array['first_day']['date']));
        $end_day = date('Ymd', strtotime($calendar_array['last_day']['date']));
        /**
         *
         * @var \SchedulingBundle\Repository\HolidayRepository $holidayRepo
         */
        $holidayRepo = $this->getDoctrine()->getRepository('SchedulingBundle:Holiday');
        $holiday = $holidayRepo->getHolidayBytime($first_day, $end_day);
        $work_day = $holiday_day = [];
        if (!empty($holiday)) {
            foreach ($holiday as $key => $val) {
                if ($val['dayType'] == 1) {
                    $work_day[] = $val['dayStr'];
                } else {
                    $holiday_day[] = $val['dayStr'];
                }
            }
        }

        $em = $this->getDoctrine()->getManager();

        $now_date = date('Y-m-j');

        //查询所有该月的节假日
        /**
         *
         * @var \SchedulingBundle\Repository\SchedulingRepository $sRepo
         */
        $sRepo = $em->getRepository('SchedulingBundle:Scheduling');
        foreach ($calendar as $key => &$val) {
            $calendar[$key][0]['num'] = 5;
            foreach ($val as $k => &$v) {
                //查询排班人信息
                $schedulings = $sRepo->getSchedulingPeopleInfo($id, date('Ymd', strtotime($v['date'])));
                $v['info'] = $schedulings;
                //设置隐藏域
                $boolOne = strtotime($v['date']) < strtotime(date('Y-m-d')) && date('w', strtotime($v['date'])) != 0;
                $boolTwo = strtotime($v['date']) < strtotime(date('Y-m-d')) && date('w', strtotime($v['date'])) != 6;
                if ($boolOne && $boolTwo) {
                    $calendar[$key][0]['num'] = $calendar[$key][0]['num'] - 1;
                }

                //查询该天是否是特殊日期
                if (in_array($v['date'], $work_day)) {
                    $v['special_day'] = 1;  //特殊上班日
                    $calendar[$key][0]['num'] = $calendar[$key][0]['num'] + 1;
                    if(strtotime($v['date']) < strtotime(date('Y-m-d'))){
                        $calendar[$key][0]['num'] = $calendar[$key][0]['num'] - 1;
                    }
                } elseif (in_array($v['date'], $holiday_day)) {
                    $v['special_day'] = 0; //特殊不上班日
                    $calendar[$key][0]['num'] = $calendar[$key][0]['num'] - 1;
                }

                //判断今天是否有签到信息，有不能编辑
                if($now_date==$v['date'] && !empty($schedulings)){
                    /**
                     *
                     * @var \SchedulingBundle\Repository\AttendanceRepository $attendRepo
                     */
                    $attendRepo = $em->getRepository('SchedulingBundle:Attendance');
                    $attend_data = $attendRepo->findBy(['instituId'=>$schedulings['institutionId'],'date'=>date('Ymd')]);
                    if(!empty($attend_data)){
                        $v['state']=1;
                    }
                }
            }
        }
        $prevMonth = date('Y-m', strtotime($month . ' -1 month'));
        $nextMonth = date('Y-m', strtotime($month . ' +1 month'));

        return $this->render('@Scheduling/Scheduling/index.html.twig', array(
            'calendar' => $calendar,
            'platform' => $platform,
            'default_id' => $id,
            'default_month' => $month,
            'prev_month' => $prevMonth,
            'next_month' => $nextMonth
        ));
    }

    /**
     *
     * @name 律师人员检查(新版)
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function checkAction(Request $request)
    {
        // 律师id（没有选择人员，直接检查通过）
        $uid = $request->request->getInt('uid');
        if(empty($uid)){
            return $this->json([
                'code' => 1,
                'msg' => '检查通过'
            ]);
        }
        //机构ID
        $entity_id = $request->request->getInt('eid');
        // 值班日期
        $date = $request->request->get('date');
        //选中时间段
        $start = $request->request->get('start');
        $end = $request->request->get('end');
        $new_start_time = strtotime($date .' '. $start);
        $new_end_time = strtotime($date .' '. $end);

        $sche_id = $request->request->getInt('sche_id');

        /**
         *
         * @var \ApplyBundle\Repository\EntityPlatformRepository $entityRepo
         */
        $entityRepo = $this->getDoctrine()->getRepository('ApplyBundle:EntityPlatform');
        $plat_form = $entityRepo->find($entity_id);
        if(empty($plat_form)){
            return $this->json(['code' => 0, 'msg' => '该机构不存在']);
        }

        /**
         *
         * @var \SchedulingBundle\Repository\SchedulingRepository $sRepo
         */
        $sRepo = $this->getDoctrine()->getRepository('SchedulingBundle:Scheduling');
        //该值班人员该天值班次数
        $info = $sRepo->getSchedulingByDate($uid, $date);
        if(empty($info)){
            return $this->json(['code' => 1, 'msg' => '检查通过']);
        }
        $num = count($info);
        //编辑
        if(!empty($sche_id)){
            if($num==1){return $this->json(['code' => 1, 'msg' => '检查通过']);}
            if($num==2){
                $info = $sRepo->getSchedulingByDate($uid, $date,$sche_id);
            }
        }

        if ($plat_form->getLevel() == 4) {
            if($num==2){
                if(empty($sche_id)) {
                    return $this->json(['code' => 0, 'msg' => '一天最多可以值班两次']);
                }
            }

            //先进行判断该时间点的两次排班是否重合
            //进行判断这天该人员是否在该时间段进行排班了
            foreach ($info as $k => $v) {
                //该天存在的时间进行和选中的时间比对
                $start_time = strtotime($date . $v['startTime']);
                $end_time = strtotime($date . $v['endTime']);

                $bool_one = ($start_time == $new_start_time and $end_time == $new_end_time);
                $bool_two = ($start_time < $new_end_time and $start_time > $new_start_time);
                $bool_three = ($end_time < $new_end_time and $end_time > $new_start_time);

                if ($bool_one) {
                    return $this->json([
                        'code' => 0,
                        'msg' => '该人员该时间段已排班'
                    ]);
                } elseif ($bool_two or $bool_three) {
                    return $this->json([
                        'code' => 0,
                        'msg' => '该人员该时间段已排班'
                    ]);
                } elseif($new_start_time>=$end_time){
                    if(intval($new_start_time-$end_time)>=7200){
                        return $this->json([
                            'code' => 1,
                            'msg' => '检查通过'
                        ]);
                    }else{
                        return $this->json([
                            'code' => 0,
                            'msg' => '一天两次排班间隔大于两小时'
                        ]);
                    }

                }elseif($start_time>=$new_end_time){
                    if(intval($start_time-$new_end_time)>=7200){
                        return $this->json([
                            'code' => 1,
                            'msg' => '检查通过'
                        ]);
                    }else{
                        return $this->json([
                            'code' => 0,
                            'msg' => '一天两次排班间隔大于两小时'
                        ]);
                    }
                }else{
                    return $this->json([
                        'code' => 0,
                        'msg' => '该人员该时间段已排班'
                    ]);
                }
            }
        }

        return $this->json([
            'code' => 1,
            'msg' => '检查通过'
        ]);
    }

    /**
     * @name 律师人员检查(旧版)
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function checkoldAction(Request $request)
    {
        // 律师id
        $uid = $request->request->getInt('uid');
        if(empty($uid)){
            return $this->json([
                'code' => 1,
                'msg' => '检查通过'
            ]);
        }
        //机构ID
        $entity_id = $request->request->getInt('eid');
        // 值班日期
        $dates = $request->request->get('date');
        $date = date('Ymd', strtotime($dates));
        //选中时间段
        $start = $request->request->get('start');
        $end = $request->request->get('end');
        $new_start_time = strtotime($date . $start);
        $new_end_time = strtotime($date . $end);

        //判断该天该机构是否存在排班（存在：编辑，不存在，添加）
        /**
         *
         * @var \SchedulingBundle\Repository\SchedulingRepository $sRepo
         */
        $sRepo = $this->getDoctrine()->getRepository('SchedulingBundle:Scheduling');
        //该值班人员该天值班次数
        $info = $sRepo->getSchedulingByDate($uid, $date);

        //如果存在排班，该用户这天排了
        if (!empty($info)) {
            //查询该机构的等级
            /**
             *
             * @var \ApplyBundle\Repository\EntityPlatformRepository $entityRepo
             */
            $entityRepo = $this->getDoctrine()->getRepository('ApplyBundle:EntityPlatform');
            $plat_form = $entityRepo->find($entity_id);
            if(empty($plat_form)){
                return $this->json([
                    'code' => 1,
                    'msg' => '该机构不存在'
                ]);
            }

            if ($plat_form->getLevel() == 4) {
                $num = count($info);
                if($num>=2){
                    return $this->json([
                        'code' => 0,
                        'msg' => '一天最多可以值班两次'
                    ]);
                }
                //判断该机构下该天是否存在排班
                $find_data = $sRepo->getSchedulingByEntity($uid, $date, $entity_id);
                if (!empty($find_data)) {
                    //编辑
                    return $this->json([
                        'code' => 1,
                        'msg' => '检查通过'
                    ]);
                }

                //先进行判断该时间点的两次排班是否重合
                //进行判断这天该人员是否在该时间段进行排班了
                foreach ($info as $k => $v) {
                    //该天存在的时间进行和选中的时间比对
                    $start_time = strtotime($date . $v['startTime']);
                    $end_time = strtotime($date . $v['endTime']);

                    $bool_one = ($start_time == $new_start_time and $end_time == $new_end_time);
                    $bool_two = ($start_time < $new_end_time and $start_time > $new_start_time);
                    $bool_three = ($end_time < $new_end_time and $end_time > $new_start_time);

                    if ($bool_one) {
                        return $this->json([
                            'code' => 0,
                            'msg' => '该人员该时间段已排班'
                        ]);
                    } elseif ($bool_two or $bool_three) {
                        return $this->json([
                            'code' => 0,
                            'msg' => '该人员该时间段已排班'
                        ]);
                    } elseif($new_start_time>=$end_time){
                        if(intval($new_start_time-$end_time)>=7200){
                            return $this->json([
                                'code' => 1,
                                'msg' => '检查通过'
                            ]);
                        }else{
                            return $this->json([
                                'code' => 0,
                                'msg' => '一天两次排班间隔大于2小时'
                            ]);
                        }

                    }elseif($start_time>=$new_end_time){
                        if(intval($start_time-$new_end_time)>=7200){
                            return $this->json([
                                'code' => 1,
                                'msg' => '检查通过4'
                            ]);
                        }else{
                            return $this->json([
                                'code' => 0,
                                'msg' => '一天两次排班间隔大于2小时'
                            ]);
                        }
                    }else{
                        return $this->json([
                            'code' => 0,
                            'msg' => '该人员本天已排班'
                        ]);
                    }
                }
            }else{
                //该用户该机构下
                if(!empty($info[0]['institutionId']) && $entity_id==$info[0]['institutionId']){
                    return $this->json([
                        'code' => 1,
                        'msg' => '检查通过'
                    ]);
                }
                //等级为3
                return $this->json([
                    'code' => 0,
                    'msg' => '该人员本天已排班'
                ]);
            }
        }

        return $this->json([
            'code' => 1,
            'msg' => '检查通过'
        ]);
    }

    /**
     *
     * @name 实体排班列表
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexsAction(Request $request)
    {
        /**
         *
         * @var \RbacBundle\Entity\User $user
         */
        $user = $this->getUser();
        $id = $user->getPLATID();
        /**
         *
         * @var \ApplyBundle\Repository\EntityPlatformRepository $platformRepo
         */
        $platformRepo = $this->getDoctrine()->getRepository('ApplyBundle:EntityPlatform');
        $platform = $platformRepo->findOneBy([
            'id' => $user->getPLATID(),
            'aREA' => $user->getAREA()
        ]);
        if (empty($platform)) {
            return $this->msgResponse(2, '警告', '机构信息异常', 'duty_homepage');
        }
        // eg: 201902
        $month = $request->query->get('date', date('Y-m'));
        // 日历
        $calendar_array = $this->newCalendar($month);
        $calendar = $calendar_array['calendar'];

        //查询该月下的所有的节假日
        $first_day = date('Ymd', strtotime($calendar_array['first_day']['date']));
        $end_day = date('Ymd', strtotime($calendar_array['last_day']['date']));
        /**
         *
         * @var \SchedulingBundle\Repository\HolidayRepository $holidayRepo
         */
        $holidayRepo = $this->getDoctrine()->getRepository('SchedulingBundle:Holiday');
        $holiday = $holidayRepo->getHolidayBytime($first_day, $end_day);
        $work_day = $holiday_day = [];
        if (!empty($holiday)) {
            foreach ($holiday as $key => $val) {
                if ($val['dayType'] == 1) {
                    $work_day[] = $val['dayStr'];
                } else {
                    $holiday_day[] = $val['dayStr'];
                }
            }
        }

        $em = $this->getDoctrine()->getManager();

        $now_date = date('Y-m-j');

        //查询所有该月的节假日
        /**
         *
         * @var \SchedulingBundle\Repository\SchedulingRepository $sRepo
         */
        $sRepo = $em->getRepository('SchedulingBundle:Scheduling');
        foreach ($calendar as $key => &$val) {
            $calendar[$key][0]['num'] = 5;
            foreach ($val as $k => &$v) {
                //查询排班人信息
                $schedulings = $sRepo->getSchedulingPeopleInfo($id, date('Ymd', strtotime($v['date'])));
                $v['info'] = $schedulings;

                //设置隐藏域
                $boolOne = strtotime($v['date']) < strtotime(date('Y-m-d')) && date('w', strtotime($v['date'])) != 0;
                $boolTwo = strtotime($v['date']) < strtotime(date('Y-m-d')) && date('w', strtotime($v['date'])) != 6;
                if ($boolOne && $boolTwo) {
                    $calendar[$key][0]['num'] = $calendar[$key][0]['num'] - 1;
                }

                //查询该天是否是特殊日期
                if (in_array($v['date'], $work_day)) {
                    $v['special_day'] = 1;  //特殊上班日
                    $calendar[$key][0]['num'] = $calendar[$key][0]['num'] + 1;
                    if(strtotime($v['date']) < strtotime(date('Y-m-d'))){
                        $calendar[$key][0]['num'] = $calendar[$key][0]['num'] - 1;
                    }
                } elseif (in_array($v['date'], $holiday_day)) {
                    $v['special_day'] = 0; //特殊不上班日
                    $calendar[$key][0]['num'] = $calendar[$key][0]['num'] - 1;
                }

                //判断今天是否有签到信息，有不能编辑
                if($now_date==$v['date'] && !empty($schedulings)){
                    /**
                     *
                     * @var \SchedulingBundle\Repository\AttendanceRepository $attendRepo
                     */
                    $attendRepo = $em->getRepository('SchedulingBundle:Attendance');
                    $attend_data = $attendRepo->findBy(['instituId'=>$schedulings['institutionId'],'date'=>date('Ymd')]);
                    if(!empty($attend_data)){
                        $v['state']=1;
                    }
                }
            }
        }
        $prevMonth = date('Y-m', strtotime($month . ' -1 month'));
        $nextMonth = date('Y-m', strtotime($month . ' +1 month'));

        return $this->render('@Scheduling/Scheduling/indexs.html.twig', array(
            'calendar' => $calendar,
            'platform' => $platform,
            'default_id' => $id,
            'default_month' => $month,
            'prev_month' => $prevMonth,
            'next_month' => $nextMonth
        ));
    }


    /**
     * 新版日历
     *
     * @param unknown $month
     */
    private function newCalendar($month)
    {
        $result = [];
        $firstDay = date('w', strtotime($month . '-01'));
        $totalDays = date('t', strtotime($month));
        $endDay = date('w', strtotime($month . '-' . $totalDays));
        $totalPrev = date('t', strtotime($month . ' -1 month'));
        $monthPrev = date('Y-m', strtotime($month . ' -1 month'));
        $monthNext = date('Y-m', strtotime($month . ' +1 month'));
        for ($i = $firstDay - 1; $i >= 0; $i--) {
            $result[] = [
                'day' => $totalPrev - $i,
                'date' => $monthPrev . '-' . ($totalPrev - $i)
            ];
        }
        for ($i = 1; $i <= $totalDays; $i++) {
            $result[] = [
                'day' => $i,
                'date' => $month . '-' . $i
            ];
        }
        for ($i = 1; $i < 7 - $endDay; $i++) {
            $result[] = [
                'day' => $i,
                'date' => $monthNext . '-' . $i
            ];
        }
        $count = count($result);
        $first_day = $result[0];
        $last_day = $result[$count - 1];

        return array(
            'calendar' => array_chunk($result, 7),
            'first_day' => $first_day,
            'last_day' => $last_day
        );
    }

    /**
     * 未排班数量统计
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function numberAction(Request $request)
    {
        /**
         *
         * @var \RbacBundle\Entity\User $user
         */
        $user = $this->getUser();

        //获取开始时间
        $weekStart = $this->get('scheduling.get_week_start')->getWeekTime();

        /**
         *
         * @var \SchedulingBundle\Repository\SchedulingInfoRepository $infoRepo
         */
        $infoRepo = $this->getDoctrine()->getRepository('SchedulingBundle:SchedulingInfo');
        switch ($user->getLEVEL()) {
            case 0:
            case 1:
            case 2:
                $city = $infoRepo->getNumberByCity($weekStart);
                $total = array_sum(array_column($city, 'num'));
                $data = array_merge([
                    [
                        'code' => 140000,
                        'num' => $total
                    ]
                ], $city, $infoRepo->getNumberByArea($weekStart));
                break;
            case 3:
                $data = array_merge($infoRepo->getNumberByCity($weekStart, $user->getCITY()), $infoRepo->getNumberByArea($weekStart, null, $user->getCITY()));
                break;
            case 4:
                $data = $infoRepo->getNumberByArea($weekStart, $user->getAREA());
                break;
            default:
                $data = [];
                break;
        }

        return $this->json([
            'code' => 1,
            'msg' => '获取成功',
            'data' => $data
        ]);
    }

    /**
     * 跨区域排班统计
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function crossAction(Request $request)
    {
        $month = $request->request->get('month');
        $startEnd = $this->getMonthStartAndEnd($month);

        //结束时间不变，开始时间进行月份的判断
        //当前月份,覆盖掉开始时间
        $month = date('m',time());
        $get_month = substr($month,-2);
        if($month==$get_month){
            $startEnd['start_day'] = date('Ymd');
        }

        /**
         *
         * @var \RbacBundle\Entity\User $user
         */
        $user = $this->getUser();
        $data = [];
        $areaData = [];
        if ($user->getLEVEL() == 3) {
            /**
             *
             * @var \SchedulingBundle\Repository\SchedulingRepository $infoRepo
             */
            $infoRepo = $this->getDoctrine()->getRepository('SchedulingBundle:Scheduling');
            $data = $infoRepo->getCrossByCity($user->getCITY(), $startEnd['start_day'], $startEnd['end_day']);
            foreach ($data as $val) {
                if (isset($areaData[$val['city']])) {
                    $areaData[$val['city']]['num'] += $val['num'];
                    $areaData[$val['city']]['code'] = $val['city'];
                } else {
                    $areaData[$val['city']]['num'] = $val['num'];
                    $areaData[$val['city']]['code'] = $val['city'];
                }
                if (isset($areaData[$val['area']])) {
                    $areaData[$val['area']]['num'] += $val['num'];
                    $areaData[$val['area']]['code'] = $val['area'];
                } else {
                    $areaData[$val['area']]['num'] = $val['num'];
                    $areaData[$val['area']]['code'] = $val['area'];
                }
            }
        }
        return $this->json([
            'code' => 1,
            'msg' => '获取成功',
            'data' => [
                'parent' => $areaData,
                'child' => $data
            ]
        ]);
    }

    /**
     * 人员选择弹出窗
     * @name
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function alertAction(Request $request)
    {
        /**
         *
         * @var \RbacBundle\Entity\User $user
         */
        $user = $this->getUser();
        $date = $request->query->get('date');
        // 主管机构id
        $id = $request->query->getInt('id');
        $uid = $request->query->getInt('id_u');
        $sch_id = $request->query->getInt('uid');

        /**
         *
         * @var \ApplyBundle\Repository\EntityPlatformRepository $aRepo
         */
        $aRepo = $this->getDoctrine()->getRepository('ApplyBundle:EntityPlatform');
        /**
         *
         * @var \OrganBundle\Entity\Authenast $authenast
         */
        $authenast = $aRepo->find($id);
        if (empty($authenast)) {
            return $this->errorMsgResponse('主管机关信息异常');
        }
        /**
         *
         * @var \OrganBundle\Repository\AreaRepository $areaRepo
         */
        $areaRepo = $this->getDoctrine()->getRepository('OrganBundle:Area');
        /**
         *
         * @var \SchedulingBundle\Repository\DutyPersonRepository $dutyRepo
         */
        $dutyRepo = $this->getDoctrine()->getRepository('SchedulingBundle:DutyPerson');
        /**
         * @var \SchedulingBundle\Entity\DutyPerson $duty
         */
        $duty = $dutyRepo->find($uid);
        $defaultArea = empty($duty) ? 0 : $duty->getAreaId();
        switch ($user->getLEVEL()) {
            case 0:
            case 1:
            case 2:
                // 超级管理员或者省级
                $areas = $areaRepo->getSelfAndChild(140000);
                $info = $dutyRepo->getListByCity(140000);
                break;
            case 3:
                // 市级
                $areas = $areaRepo->getSelfAndChild($user->getCITY());
                if (empty($defaultArea)) {
                    $info = $dutyRepo->getListByCity($user->getCITY());
                } else {
                    $info = $dutyRepo->getListByArea($defaultArea);
                }
                break;
            case 4:
                // 县区级
                $areas = [];
                $info = $dutyRepo->getListByArea($user->getAREA());
                break;
            default:
                return $this->errorMsgResponse('用户信息异常');
                break;
        }
        /**
         *
         * @var \SchedulingBundle\Repository\SetWorkRepository $sRepo
         */
        $sRepo = $this->getDoctrine()->getRepository('SchedulingBundle:SetWork');
        $setWork = $sRepo->findOneBy([
            'entityId' => $authenast->getId()
        ]);
        if (empty($setWork)) {
            $setWork = $sRepo->findOneBy([
                'entityId' => 0
            ]);
            if (empty($setWork)) {
                return $this->errorMsgResponse('无默认工作时间');
            }
        }

        return $this->render('@Scheduling/Scheduling/alert.html.twig', [
            'info' => $info,
            'setwork' => $setWork,
            'date' => $date,
            'areas' => $areas,
            'authenast' => $authenast,
            'default_area' => $defaultArea,
            'sch_id'=>$sch_id
        ]);
    }

    /**
     * ajax选择值班人员
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function selectAction(Request $request)
    {
        $areaId = $request->request->getInt('area_id');
        if (empty($areaId)) {
            return $this->json([
                'code' => 2,
                'msg' => '请求参数错误'
            ]);
        }
        /**
         *
         * @var \SchedulingBundle\Repository\DutyPersonRepository $dutyRepo
         */
        $dutyRepo = $this->getDoctrine()->getRepository('SchedulingBundle:DutyPerson');
        $list = $dutyRepo->getListByArea($areaId);
        if (empty($list)) {
            return $this->json([
                'code' => 3,
                'msg' => '暂无可选人员'
            ]);
        } else {
            return $this->json([
                'code' => 1,
                'msg' => '获取成功',
                'data' => $list
            ]);
        }
    }



    /**
     *
     * @name 实体排班新增
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        /**
         *
         * @var \RbacBundle\Entity\User $user
         */
        $user = $this->getUser();
        $arr = $request->request->get('Scheduling');
        $authenastId = $request->request->getInt('authenast_id');
        if (empty($arr)) {
            return $this->msgResponse(0, '提示', "排班信息为空", 'scheduling_scheduling_index', [
                'id' => $authenastId
            ]);
        }
        //数据整理
        $array = [];
        foreach ($arr as $key => $val) {
            foreach ($val as $k1 => $v1) {
                $array[$k1][$key] = $v1;
            }
        }
        $em = $this->getDoctrine()->getManager();
        /**
         *
         * @var \ApplyBundle\Repository\EntityPlatformRepository $platRepo
         */
        $platRepo = $em->getRepository('ApplyBundle:EntityPlatform');
        $plat_data = $platRepo->find($authenastId);
        if(!empty($plat_data)){
            $city = $plat_data->getCITY();
            $area = $plat_data->getAREA();
        }else{
            $city = null;
            $area = null;
        }
        /**
         *
         * @var \SchedulingBundle\Repository\DutyPersonRepository $uRepo
         */
        $uRepo = $em->getRepository('SchedulingBundle:DutyPerson');
        /**
         *
         * @var \SchedulingBundle\Repository\SchedulingRepository $sRepo
         */
        $sRepo = $em->getRepository('SchedulingBundle:Scheduling');

        /**
         *
         * @var \SchedulingBundle\Repository\SchedulingInfoRepository $siRepo
         */
        $siRepo = $em->getRepository('SchedulingBundle:SchedulingInfo');
        foreach ($array as $vo) {
            $times = [
                '09:00',
                '18:00'
            ];
            // 工作时间
            $workTime = $vo['work_time'];
            if (false !== strpos($workTime, '-')) {
                $times = explode('-', $workTime);
            }
            if (empty($vo['id'])) {
                //新增的
                $scheduling = new Scheduling();
                $typeId = ($vo['user_id'] == 0) ? 1 : 0;
                $state = ($vo['user_id'] == 0) ? 0 : 1;
                $sms_state = ($vo['user_id'] == 0) ? 1 : 0;
            } else {
                /**
                 * @var \SchedulingBundle\Entity\Scheduling $scheduling
                 */
                $scheduling = $sRepo->find($vo['id']);
                $state = null;
                $sms_state = null;
                //用户ID是否变化
                if (empty($scheduling)) {
                    $scheduling = new Scheduling();
                    $typeId = 0;
                } else {
                    //0正常 1跨区域
                    $typeId = ($vo['user_id'] == 0) ? 1 : 0;
                    //排班人员变动判断
                    if ($vo['user_id'] == $scheduling->getUserId()) {
                        if ($scheduling->getStartTime() != $times[0] || $scheduling->getEndTime() != $times[1]) {
                            $state = 3;
                            $sms_state = 0;
                        }
                    } else {
                        //排班人员变化修改旧记录的状态 //修改之前记录的状态改为state =2,sms_state=0
                        $scheduling->setState(2);
                        $em->persist($scheduling);
                        $em->flush();
                        $scheduling = new Scheduling();
                        //新增的时候判断是否是跨区域排班
                        //跨--需要判断用户等级等级为4,等级不为4的时候
                        if ($vo['user_id'] == 0) {
                            $state = ($user->getLEVEL() == 4) ? 0 : 1;
                            $sms_state = ($user->getLEVEL() == 4) ? 1 : 0;

                        } else {
                            $state = 1;
                            $sms_state = 0;
                        }
                    }
                }
            }

            // 值班人员身份信息
            if ($vo['user_id'] == 0) {
                $scheduling->setUserId(0);
                $scheduling->setUserName('');
                $scheduling->setAffilateName('');
                $scheduling->setAffiliateId(0);
                $scheduling->setStartTime('');
                $scheduling->setEndTime('');
            } else {
                $scheduling->setUserId($vo['user_id']);
                $scheduling->setUserName($vo['user_name']);
                /**
                 *
                 * @var \SchedulingBundle\Entity\DutyPerson $duty
                 */
                $duty = $uRepo->find($vo['user_id']);
                if (empty($duty)) {
                    return $this->msgResponse(0, '提示', "未找到值班人员信息", 'scheduling_scheduling_index', [
                        'id' => $authenastId
                    ]);
                }
                // 值班人员隶属机构信息
                /**
                 *
                 * @var \OrganBundle\Repository\AuthenastRepository $affiliateRepo
                 */
                $affiliateRepo = $em->getRepository('OrganBundle:Authenast');
                /**
                 *
                 * @var \OrganBundle\Entity\Authenast $affiliate
                 */
                $affiliate = $affiliateRepo->find($duty->getOrganizationId());

                if (empty($affiliate)) {
                    return $this->msgResponse(0, '提示', "未找到值班人员隶属信息", 'scheduling_scheduling_index', [
                        'id' => $authenastId
                    ]);
                }
                $scheduling->setAffiliateId($affiliate->getId());
                $scheduling->setAffilateName($affiliate->getName());
                $scheduling->setStartTime($times[0]);
                $scheduling->setEndTime($times[1]);
            }

            //状态
            $scheduling->setTypeId($typeId);
            if (!is_null($state)) {
                $scheduling->setState($state);
            }
            if (!is_null($sms_state)) {
                $scheduling->setSmsState($sms_state);
            }
            // 值班机构信息
            $scheduling->setInstitutionId(intval($vo['institution_id']));
            $scheduling->setInstitutionName($vo['institution_name']);
            $scheduling->setDutyCity($city);
            $scheduling->setDutyArea($area);
            // 值班日期
            $date = new \DateTime($vo['date']);
            $date_ymd = $date->format('Ymd');
            $scheduling->setDate($date_ymd);
            //修改scheduling_info信息
            $scheduling_data = $siRepo->updateState($date_ymd, $vo['institution_id']);
            if (!empty($scheduling_data)) {
                foreach ($scheduling_data as $key=>$val){
                    if ($val->getState() == 0) {
                        //设置为1
                        $val->setState(1);
                        $em->persist($val);
                        $em->flush();
                    }
                }
            }
            // 创建人信息
            $scheduling->setCreaterId($user->getId());
            $scheduling->setCreaterName($user->getXM());
            $em->persist($scheduling);
            $em->flush();
        }

        return $this->msgResponse(1, '提示', "操作成功", 'scheduling_scheduling_index', [
            'id' => $authenastId
        ]);
    }

    /**
     *
     * @name 实体排班详情
     * @param Scheduling $scheduling
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Scheduling $scheduling)
    {
        $deleteForm = $this->createDeleteForm($scheduling);

        return $this->render('@Scheduling/scheduling/show.html.twig', array(
            'scheduling' => $scheduling,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     *
     * @name 实体排班编辑
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $id = $request->query->getInt('uid');
        $sRepo = $this->getDoctrine()->getRepository('SchedulingBundle:Scheduling');
        $scheduling = $sRepo->find($id);
        /**
         *
         * @var \OrganBundle\Repository\AuthenastRepository $aRepo
         */
        $aRepo = $this->getDoctrine()->getRepository('OrganBundle:Authenast');
        $users = $aRepo->getLawerInfo($scheduling->getInstitutionId());
        return $this->render('@Scheduling/Scheduling/edit.html.twig', array(
            'info' => $scheduling,
            'users' => $users
        ));
    }

    /**
     *
     * @name 删除实体排班
     * @param Request $request
     * @param Scheduling $scheduling
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Scheduling $scheduling)
    {
        $form = $this->createDeleteForm($scheduling);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($scheduling);
            $em->flush();
        }

        return $this->redirectToRoute('scheduling_index');
    }

    /**
     * 排班首页数据统计
     */
    public function listAction(Request $request)
    {
        /**
         *
         * @var \RbacBundle\Entity\User $user
         */
        $user = $this->getUser();

        /**
         *
         * @var \OrganBundle\Repository\AreaRepository $aRepo
         */
        $aRepo = $this->getDoctrine()->getRepository('OrganBundle:Area');

        $id = $request->query->getInt('id');
        if (empty($id)) {
            switch ($user->getLEVEL()) {
                case 0:
                case 1:
                case 2:
                    $id = 1;
                    break;
                case 3:
                    $userInfo = $aRepo->findOneBy([
                        'areacode' => $user->getCITY(),
                    ]);
                    if (empty($userInfo)) {
                        return $this->msgResponse(1, '警告', '用户与机构不匹配');
                    }
                    $id = $userInfo->getId();

                    break;
                case 4:
                    $userInfo = $aRepo->findOneBy([
                        'areacode' => $user->getAREA(),
                    ]);
                    if (empty($userInfo)) {
                        return $this->msgResponse(0);
                    }
                    $id = $userInfo->getId();
                    break;
                case 5:
                    return $this->redirectToRoute('scheduling_index_indexs',['id'=>$user->getPLATID()]);
                default:
                    return $this->msgResponse(0, '警告', '用户级别错误');
                    break;

            }
        }

        //周的开始时间和结束时间
        $week_start = $this->get('scheduling.get_week_start')->getWeekTime();

        /**
         *
         * @var \SchedulingBundle\Repository\SchedulingInfoRepository $sRepo
         */
        $sRepo = $this->getDoctrine()->getRepository('SchedulingBundle:SchedulingInfo');

        $area_data = $aRepo->find($id);
        if (empty($area_data)) {
            return $this->msgResponse(2, '警告', '机构信息异常', 'duty_homepage');
        }
        $level = $area_data->getLevel();
        switch ($level) {
            case 1:
                // 未排班
                $not_scheduling_num = $sRepo->getNumberByState(0, $week_start);
                // 已经排班
                $scheduling_num = $sRepo->getNumberByState(1, $week_start);
                // 总数
                $total_scheduling_num = $not_scheduling_num + $scheduling_num;
                $city_data = $aRepo->findBy([
                    'level' => 2
                ]);
                foreach ($city_data as $area_key => $area_val) {
                    // 未排班的数据
                    $data[$area_val->getAreaname()]['not_scheduling_data'] = $sRepo->getDataByCity(0, $week_start, $area_val->getAreacode());
                    //已排班的数据
                    $data[$area_val->getAreaname()]['scheduling_data'] = $sRepo->getDataByCity(1, $week_start, $area_val->getAreacode());
                    // 排班数
                    $data[$area_val->getAreaname()]['scheduling_num'] = count($data[$area_val->getAreaname()]['scheduling_data']);
                    // 未排班的数
                    $data[$area_val->getAreaname()]['not_scheduling_num'] = count($data[$area_val->getAreaname()]['not_scheduling_data']);
                }
                break;
            case 2:
                // 未排班
                $not_scheduling_num = $sRepo->getNumberByState(0, $week_start, $area_data->getAreacode());
                // 已经排班
                $scheduling_num = $sRepo->getNumberByState(1, $week_start, $area_data->getAreacode());
                // 总数
                $total_scheduling_num = $not_scheduling_num + $scheduling_num;
                $city_data = $aRepo->findBy([
                    'level' => 3,
                    'parentid' => $area_data->getAreacode()
                ]);
                foreach ($city_data as $area_key => $area_val) {
                    // 未排班的数据
                    $data[$area_val->getAreaname()]['not_scheduling_data'] = $sRepo->getDataByCity(0, $week_start, $area_data->getAreacode(), $area_val->getAreacode());
                    // 未排班的数据
                    $data[$area_val->getAreaname()]['scheduling_data'] = $sRepo->getDataByCity(1, $week_start, $area_data->getAreacode(), $area_val->getAreacode());
                    // 区排班数
                    $data[$area_val->getAreaname()]['scheduling_num'] = count($data[$area_val->getAreaname()]['scheduling_data']);
                    // 未排班的
                    $data[$area_val->getAreaname()]['not_scheduling_num'] = count($data[$area_val->getAreaname()]['not_scheduling_data']);
                }
                break;
            case 3:
                // 未排班
                $not_scheduling_num = $sRepo->getNumberByState(0, $week_start, $area_data->getparentId(), $area_data->getAreacode());
                // 已经排班
                $scheduling_num = $sRepo->getNumberByState(1, $week_start, $area_data->getparentId(), $area_data->getAreacode());
                // 总数
                $total_scheduling_num = $not_scheduling_num + $scheduling_num;
                // 未排班的数据
                $data[$area_data->getAreaname()]['not_scheduling_data'] = $sRepo->getDataByCity(0, $week_start, $area_data->getparentId(), $area_data->getAreacode());
                // 排班的数据
                $data[$area_data->getAreaname()]['scheduling_data'] = $sRepo->getDataByCity(1, $week_start, $area_data->getparentId(), $area_data->getAreacode());
                // 区排班数
                $data[$area_data->getAreaname()]['scheduling_num'] = count($data[$area_data->getAreaname()]['scheduling_data']);
                //未排班的
                $data[$area_data->getAreaname()]['not_scheduling_num'] = count($data[$area_data->getAreaname()]['not_scheduling_data']);
                break;
            default:
                return $this->msgResponse(0, '警告', '级别错误');
                break;
        }

        return $this->render('@Scheduling/Scheduling/list.html.twig', array(
            'not_scheduling_num' => $not_scheduling_num,
            'scheduling_num' => $scheduling_num,
            'total_scheduling_num' => $total_scheduling_num,
            'data' => $data,
            'default_id' => $id,
            'default_month' => date('Y-m')
        ));
    }

    /**
     * 取消排班
     *
     * @param Request $request
     */
    public function cancleAction(Request $request)
    {
        $id = $request->request->getInt('id');
        /**
         *
         * @var \SchedulingBundle\Entity\Scheduling $schedule
         */
        $schedule = $this->getDoctrine()->getRepository('SchedulingBundle:Scheduling')->find($id);
        if (empty($schedule)) {
            return $this->json([
                'code' => 1,
                'msg' => '删除成功',
                'data' => 'no schedule'
            ]);
        }
        try {
            $schedule->setState(2);
            $schedule->setSmsState(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($schedule);
            $em->flush();
            return $this->json([
                'code' => 1,
                'msg' => '删除成功',
                'data' => 'update'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => 2,
                'msg' => '删除失败',
                'data' => [
                    'strace' => $e->getTraceAsString()
                ]
            ]);
        }
    }

    /**
     * 月份开始时间和结束时间
     *
     * @param $month
     * @return array
     */
    private function getMonthStartAndEnd($month)
    {
        $timestamp = strtotime($month . '-01');
        $totalDays = date('t', $timestamp);
        $firstDay = date('w', $timestamp);
        $endDay = date('w', strtotime($month . '-' . $totalDays));
        return [
            'start_day' => date('Ymd', strtotime($month . '-01 -' . $firstDay . ' days')),
            'end_day' => date('Ymd', strtotime($month . '-' . $totalDays . ' +' . (6 - $endDay) . ' days'))
        ];
    }

    /**
     *
     * @name 日历（废弃）
     * @return array
     */
    private function calendar($date)
    {
        $time = strtotime(substr($date, 0, 4) . '-' . substr($date, 4));
        $thisMonthLastDay = date('t', $time); // 本月最后一天
        $thisMonthFirstDayWeek = date('w', strtotime(date('Y-m', $time))); // 本月第一天的星期
        $lastMonthLastDay = date('t', strtotime('-1 month', $time)); // 上个月最后一天
        $lastMonthDay = 0;
        // 补齐本月第一天的星期
        if ($thisMonthFirstDayWeek == 0) {
            // 星期日 差6天
            $lastMonthDay = 6;
        } else {
            $lastMonthDay = $thisMonthFirstDayWeek + 1;
        }

        $riliArray = $lastRili = $nextRili = [];
        $newDate = substr($date, 0, 4) . '-' . substr($date, 4);
        for ($i = 1; $i <= $thisMonthLastDay; $i++) {
            if (date('Y-m', $time) == date('Y-m') && $i < date('d')) {
                $riliArray['this_month_' . $i]['day'] = $i;
                $riliArray['this_month_' . $i]['date'] = $newDate . '-' . $i;
            } elseif (date('Y-m', $time) == date('Y-m') && $i == date('d')) {
                $riliArray["this_month_" . $i . '_today']['day'] = $i;
                $riliArray["this_month_" . $i . '_today']['date'] = $newDate . '-' . $i;
            } else {
                $riliArray["this_month_" . $i]['day'] = $i;
                $riliArray['this_month_' . $i]['date'] = $newDate . '-' . $i;
            }
        }

        if (!empty($lastMonthDay)) {
            for ($j = 1; $j <= $lastMonthDay; $j++) {
                $lastRili['last_month_' . $j]['day'] = $lastMonthLastDay - $j + 1;
            }
        }

        $riliArray = array_merge(array_reverse($lastRili), $riliArray);

        $count = count($riliArray);
        // 共六个星期 补齐
        if ($count / 7 != 6) {
            for ($i = 1; $i <= 43 - $count; $i++) {
                $nextRili['next_month_' . $i]['day'] = $i;
            }
        }
        $riliArray = array_merge($riliArray, $nextRili);
        $arr = [];
        for ($k = 1; $k <= 6; $k++) {
            if ($k == 1) {
                $start = 1;
            } else {
                $start = (($k - 1) * 7) + 1;
            }
            $arr[] = array_slice($riliArray, $start, 7, true);
        }
        return array_values($arr);
    }
}

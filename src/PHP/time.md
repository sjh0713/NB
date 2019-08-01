本月开始时间和结束时间：

    $first = date('Y-m-01 0:0:0', strtotime(date("Y-m-d")));
    $last = date('Y-m-d 23:59:59', strtotime("$first +1 month -1 day"));

共有多少天

    date(t);

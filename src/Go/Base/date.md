- 时间和日期相关函数
    - 时间和日期相关函数，需要导入 time 包
    - time.Time 类型，用于表示时间
    
    - 时间相关操作：
            
            方式一：
            now := time.Now()表示当前时间
            now.Year()
            now.Month()
            now.Day()
            now.Hour()
            now.Minute()
            now.Second()
            
            方式二：
            now.Format("2006-01-02 15:00:23")
            
    - time的Unix和UnixNano的方法
            
            now.Unix()
            now.UnixNano()
            
            

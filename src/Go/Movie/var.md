## 变量

- 3中使用方式
    
        1. 指定变量类型，声明后若不赋值，使用默认值
           var name string 
        2. 根据自行判断变量类型
           var name = 10.11
        3. 省略var,注意：左侧的变量不应该是声明过的
           name := "tom" (var name string  name="tom")

- 整形

- 字符类型
    
        var c1 byte = 'a'
        fmt.println("c1=".c1)  //97
        fmt.printf("c1=%c",c1) //a

- 浮点型
        
- 布尔型
        
        占一个字节
        字节的长度：unsafe.sizeof()

- 字符串型
        
        go语言的字符串都是utf8 
        go中的字符串是不可变的

数据类型的转换
    
        var n2 int8 = int8(i) //前后类型必须一致才能转换
        


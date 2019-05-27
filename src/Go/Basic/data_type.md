## Data type

1. 整型

2. 浮点型

3. 复数
    
    - complex64
    - complex128
    
          获取实部：real(v)
          获取虚部：image(v)

4. 字符串
    - 字符串的常用操作
      
            len() 计算字符串的长度
            一个中文占3个字节数
            
            字符串的连接使用+
            
            字符串的遍历：
            for _,v:=range s{
                fmt.Printf("%c",v)
            }
            
            字符串的修改：
            []byte复制数据
            []rune修改数据

    - strings包
        
            strings.HasPrefix(str,'')前缀包含
            strings.HasSuffix(str,'')后缀包含
            strings.Contains(str,'')包含
    - strconv包
    
            作用：主要用于字符串和其他类型的转换
            strconv.Itoa() 将十进制数转换为字符串
            strconv.FormatFloat() 将64浮点型转为字符串
5. 布尔型

        go语言只有true和false两个值，不支持使用0和1表示真假

6. 数据类型的扩展
    
    - 强制类型的转换

            type_name(expression)
            
    

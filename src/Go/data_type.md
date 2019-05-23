## Data type

1. 整型

2. 浮点型

3. 复数
    
    - complex64
    - complex128
    
          获取实部：real(v)
          获取虚部：image(v)

4. 字符串
        
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

5. strings包
        
        strings.HasPrefix(str,'')前缀包含
        strings.HasSuffix(str,'')后缀包含
        strings.Contains(str,'')包含

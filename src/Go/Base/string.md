## 字符串相关知识

- [String](src/Go/Movie/var.md) 字符串转其他类型

- 字符串的遍历
        
        1) Golang 提供 for-range 的方式，可以方便遍历字符串和数组
        2) str 转成 []rune 切片
        
        案例：
        func main(){
        	//遍历0
        	var str string = "abc~ok"
        	for i:=0;i<len(str);i++{
        		fmt.Printf("%f \n",str[i]) 
        	}
        
        	//遍历1
        	var str string = "abc~ok上海"
        	for index,val := range str{
        		fmt.Printf("index=%d,val=%c \n",index,val)
        	}
        
        	//遍历2
        	var str string = "hello,world!北京"
        	str2 := []rune(str) //就是把str转成[]rune
        	for i:=0;i<len(str2);i++{
        		fmt.Printf("%c\n",str2[i])
        	}
        }


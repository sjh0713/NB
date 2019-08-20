- 闭包

    闭包就是一个函数和与其相关的引用环境组合的一个整体
    
        案例：
        package main
        import "fmt"
        
        func main(){
        	f:=AddUpper()
        	fmt.Println(f(1))
        	fmt.Println(f(2))
        	fmt.Println(f(3))
        }
        
        func AddUpper() func (int) int{
        	var n int = 10
        	return func (x int) int{
        		n = n+x
        		return n
        	}
        }
        对上面代码的说明和总结
        1) AddUpper 是一个函数，返回的数据类型是 fun (int) int 2) 
        闭包的说明：
        返回的是一个匿名函数, 但是这个匿名函数引用到函数外的 n ,因此这个匿名函数就和 n 形成一 个整体，构成闭包。

- 闭包的最佳实践

        经典案例：
        package main
        
        import (
        	"fmt"
        	"strings"
        )
        func makeSuffix(suffix string) func(string) string{
        	//这句话只调用了一次，其余执行的时候直接执行后面的方法
        	fmt.Println("文件名处理后",suffix)
        	return func(name string)string{
        		//该函数可以判断某个字符串是否有指定的后缀
        		if !strings.HasSuffix(name,suffix){
        			return name+suffix
        		}
        		return name
        	}
        }
        
        func main(){
        	f2 := makeSuffix(".jpg")
        	fmt.Println("文件名处理后",f2("winter"))
        	fmt.Println("文件名处理后",f2("bird.jpg"))
        }

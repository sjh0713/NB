- 切片

        args是slice 切片，通过args[index]可以访问到各个值
        
        案例：
            package main
            
            import "fmt"
            
            func main(){
            	res4 := sum(10,0,-1,39)
            	fmt.Println("res4=",res4)
            }
            
            func sum(n1 int,args...int) int{
            	sum:=n1
            	for i:=0;i<len(args);i++{
            		sum+=args[i]
            	}
            	return sum
            }

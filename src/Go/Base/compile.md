## go语言的编译和运行
- Go语言的基本命令及使用
    - go command [arguments]
    
          go build 目录  编译（会生成一个二进制文件）
          go run 
          指定生成编译后的文件名：go build -o mv.exe hello.go(window下） 
          build 编译源代码包和依赖
          run 先编译源码文件再运行
          clean 删除对象文件
          doc 显示go包或程序实体的文档
          env 打印输出go语言环境的变量信息
          install 编译并安装指定的包与依赖
- 或者使用 gofmt 来进行格式化
    
        gofmt main.go
        gofmt -w main.go //格式化或后写入文件中

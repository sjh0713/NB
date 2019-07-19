##composer报错
- 错误一

        错误：Deprecation warning: require.beberlei/DoctrineExtensions is invalid, it should not contain uppercase characters. Please use beberlei/doctrineextensions instead. Make sure you fix this as Composer 2.0 will error.
        
        解决：
            composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
    
- 错误二
    
        在命令行中进行git add . 操作时，控制台警告warning: CRLF will be replaced by LF in iris/js/list.js.这是由于跨平台合作或者开发时出现的编译器格式问题，Windows使用回车和换行两个字符来结束一行，而Mac和Linux只使用换行一个字符。
        Git可以在你提交时自动地把行结束符CRLF转换成LF，而在签出代码时把LF转换成CRLF。用core.autocrlf来打开此项功能， 如果是在Windows系统上，把它设置成true，这样当签出代码时，LF会被转换成CRLF：
        
        $ git config --global core.autocrlf true
        
        Linux或Mac系统使用LF作为行结束符，因此你不想Git在签出文件时进行自动的转换；当一个以CRLF为行结束符的文件不小心被引入时你肯定想进行修正， 把core.autocrlf设置成input来告诉Git在提交时把CRLF转换成LF，签出时不在Windows系统上的签出文件中保留CRLF，会在Mac和Linux系统上，包括仓库中保留LF。
        
        $ git config –global core.autocrlf input
        
        如果你使用Windows，且正在开发仅运行在Windows上的项目，可以设置false取消此功能，把回车符记录在库中：
        
        $ git config --global core.autocrlf false


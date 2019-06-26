## Linux查看用户和组

- 查看组：
        
        cat /etc/group

- 查看用户：
    
        cat /etc/passwd

- 添加用户
        
        adduser username
- 设置密码
        
        passwd username
        
- 把user用户加入到wheel用户组：(这么做的目的是为了让user用户拥有sudo权限)
        
        usermod -a -G wheel username

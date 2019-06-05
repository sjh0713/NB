nginx.pid的解决方案：

    问题：
    nginx重启后出现[error] open() “/usr/local/var/run/nginx/nginx.pid” failed
    简答：
    sudo nginx -c /usr/local/etc/nginx/nginx.conf

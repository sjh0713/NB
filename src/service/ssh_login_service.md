#!/usr/bin/expect

set timeout 3
spawn ssh root@39.105.78.71
expect "*password*"
send "your password\r"
send "cd /\r"
interacts

命名为：name.sh

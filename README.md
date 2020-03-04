# online-spliceboard
自建的在线剪贴板，每个月新建一个文件，目前只能显示每个月的剪贴板内容


### Problem
1. php 页面跳转
    ```php
    header('location: show_spliceboard.php');
    ```
2. php 文件连接 js、css 文件时，与html标签的前后顺序影响不影响渲染
3. 存在远程主机不能通过 http 方式创建文件，但是本地 Apache可以创建
    * 本地创建 save目录
    * chmod 777 save


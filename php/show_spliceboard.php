<?php
/*
    目前暂定显示这一个月的条目内容，没有number 限制

    1. strstr($val, 'char'), 返回第一次出现的位置，到结束的字符串
       strpos($val, 'char'), 返回第一次出现的位置
*/

echo '<h2>Welcome to spliceboard-online</h2>';
// $number = 30;           // 设置最大显示的条目数

// 获取当前时间
date_default_timezone_set('Asia/Shanghai');
$time_ry = date('Y');
$time_rm = date('m');

// 获取项目创建时间
$time;
if(file_exists('../save/spliceboard1.txt')){
    // echo 'spliceboard1.txt' . ' 存在<br />';
    $myfile1 = fopen('../save/spliceboard1.txt', 'r') or die('Unable to open file!');
    $time = fread($myfile1, 7);
    fclose($myfile1);
}else{
    echo 'spliceboard1.txt' . ' 不存在<br />';
    if(touch('../save/spliceboard1.txt')){
        echo 'spliceboard1.txt'. '   创建成功<br />';
        $time = date('Y') . '-' . date('m');
        $myfile1 = fopen('../save/spliceboard1.txt', 'w') or die('Unable to open file!');
        fwrite($myfile1, $time);
        fclose($myfile1);
    }else{
        exit('spliceboad1.txt' . '  创建失败<br />');
    }
}
$time_m = substr($time, 5);
$time_y = substr($time, 0, 4);

// 获取要查找的文件名
$id = strval(12*(intval($time_ry) - intval($time_y)) + (intval($time_rm) - intval($time_m)) + 1);
$filename = '../save/spliceboard' . $id . '.txt';
// echo '文件名: ' . substr($filename, 8) .'<br />';

// 检测要查找的文件是否存在
if(file_exists($filename)){
    // echo substr($filename, 8) . '   存在<br />';
}else{
    echo substr($filename, 8) . '   不存在<br />';
    if(touch($filename)){
        echo substr($filename, 8) . '   创建成功<br />';
    }else{
        exit(substr($filename, 8) . '   创建失败<br />');
    }
}

// 打开文件
$myfile = fopen($filename, 'r');

// 获取条目信息
echo '<table border="1"><tr><td>order</td><td>id</td><td>size</td><td>time</td><td>content</td><td>copy</td></tr/>';
$filesize = filesize($filename);
$len = 1;       // 先循环一次，然后赋值
for($i=0; $i < $len; $i ++){                    // 获取$number 个条目信息
    fseek($myfile, $filesize - 11);
    $pkey = fread($myfile, 11);                 // 获取每个条目的关键码
    $pid = substr($pkey, 0, 5);                 // 获取每个条目的 id
    $psize = intval(substr($pkey, 5));          // 获取每个条目的 size
    $filesize -= $psize;                        // 更新 $filesize
    fseek($myfile, $filesize);
    $content = fread($myfile, $psize - 11);     // 获取每个条目的 content
    // echo strpos($content, '{') . ' ' .strpos($content, '}') . '<br />';
    $time = substr($content, 0, 26);
    $content = substr($content, strpos($content, '{') + 1, strlen(strstr($content, '{')) - 3 );     // -3 也许是因为字符串结尾 '/0'
    $psize = substr($pkey, 5);                  // 重新获取 psize(有6位)
    $porder = $i + 1;                           // 获取序号
    $contentID = 'clip' .$porder;              // 获取每个条目content 的id
    echo "<tr><td>{$porder}</td>".
    "<td>{$pid}</td> ".
    "<td>{$psize}</td>".
    "<td>{$time}</td>".
    "<td id='{$contentID}'>{$content}</td>".
    "<td><font color='blue'><label class='clipboard' data-clipboard-action='copy' data-clipboard-target='#{$contentID}'><u>复制</u></label></font></td>".
    "</tr>";
    fseek($myfile, $filesize);                   // 更新 文件指针
    if($i == 0){                                 // 更新 $len
        $len = intval($pid);
    }
}
echo '</table>';

// 关闭文件
fclose($myfile);

// 引入 html
echo '<script type="text/javascript" src="../javascript/clipboard.min.js"></script>';
echo '<script type="text/javascript" src="../javascript/show_spliceboard.js"></script>';

?>
<?php
/*
    1. 文件是一个线性的结构，类似于数组，不能在文件头部追加信息，应该在文件尾部追加，读取数据时移动文件指针；
    2. date('Y/m/d'), date('Y.m.d'), date('Y-m-d');  date('h:i:sa'), date('H:i:s');
    3. touch() 函数创建文件时，文件所在目录的权限问题容易导致文件创建失败；
    4. php 文件数据类型转换中， intval(), floatval(), strval() 可以， int(), string(), 没有反应；
    5. php 整数补0，str_pad(1, 8, '0', STR_PAD_LEFT), str_pad(1, 8, '0', STR_PAD_RIGHT), str_pad(1, 8, '0', STR_PAD_BOTH)；
*/

/*
    文件存储，30天更新一个文件，然后每增加一个条目，增加到文件尾部；每条条目头部都增加提交时间；每条条目尾部都
    增加编号(5位), 此条目的字节数（包括编号、6位长度）（6位）;
*/

// 连接 css 文件
echo '<link rel="stylesheet" type="text/css" href="../css/spliceboard_php.css" />';

// 接受表单信息
$spliceboard = $_POST['spliceboard'];
if($spliceboard == ''){
    die('error, 内容为空');
}

// 获取提交时间
date_default_timezone_set('Asia/Shanghai');
$real_time = '<b>' .date('Y-m-d') .'</b> ' .date('H:i:s');
echo '提交时间： ' . $real_time . '<br />';
$spliceboard = $real_time . ' {' . $spliceboard . '} ';

// 获取要提交的文件信息
/*$myfile1 = fopen('../save/spliceboard1.txt', 'r') or die('Unable to open file!');
$time = fread($myfile1, 7);         // 获取项目开始年月
fclose($myfile1);*/
$flag = false;          // 标记是否是新文件
$time;
if(file_exists('../save/spliceboard1.txt')){        // 检测 spliceboard1.txt 是否存在
    echo 'spliceboard1.txt' . '    存在<br />';
    $myfile1 = fopen('../save/spliceboard1.txt', 'r') or die('Unable to open file!');
    $time = fread($myfile1, 7);
    fclose($myfile1);
}else{
    echo 'spliceboard1.txt' . '    不存在<br />';
    if(touch('../save/spliceboard1.txt')){
        echo 'spliceboard1.txt' . '    创建成功<br />';
        $flag = true;
        $time = date('Y') . '-' . date('m');
        $myfile1 = fopen('../save/spliceboard1.txt', 'w') or dir('Unable to open file!');
        fwrite($myfile1, $time);
        fclose($myfile1);
    }else{
        exit('spliceboard1.txt' . '    创建失败<br />');
    }
}
$time_m = substr($time, 5);         // 获取项目开始月
$time_y = substr($time, 0, 4);      // 获取项目开始年
$time_rm = date('m');                // 获取当前月
$time_ry = date('Y');                // 获取当前年    
// $number = strval(12*(intval($time_ry) - intval($time_y)) + (inval($time_rm) - intval($time_m)));  // 毫无征兆的错了
$number = strval(12*(intval($time_ry) - intval($time_y)) + (intval($time_rm) - intval($time_m)) + 1);   // 获取文件编号
echo '文件编号： ' . $number . '<br />';
$filename = '../save/spliceboard' . $number .'.txt';        // 获取文件名
echo '文件名： ' . substr($filename, 8) . '<br />';

// 检测文件是否存在
if(file_exists($filename)){
    echo substr($filename, 8) .'   存在' . '<br />';
}else{
    echo substr($filename, 8) .'   不存在' .'<br />';
    if(touch($filename)){       // 创建文件
        echo substr($filename, 8) .'   创建成功' .'<br />';
        $flag = true;       // 新文件
    }else{
        exit(substr($filename, 8) .'   创建失败' .'<br />');
    }
}

// 获取上一条目编号
$id;
if($flag){
    $id = '00001';
}else{
    $myfile2 = fopen($filename, 'r');
    $filesize = filesize($filename);
    fseek($myfile2, $filesize - 11);
    $id = fread($myfile2, 5);
    fclose($myfile2);
    // $id = strval(intval($id) + 1);
    $id = strval(str_pad(intval($id)+1, 5, '0', STR_PAD_LEFT));      // 前补0
}
echo '条目编号：  ' . $id . '<br />';
$spliceboard = $spliceboard . $id;

// 获取条目size
$len = str_pad(strlen($spliceboard)+6, 6, '0', STR_PAD_LEFT);   
$spliceboard = $spliceboard . $len;
echo 'content: &nbsp;&nbsp;' .$spliceboard .'<br />';

// 打开文件
$myfile = fopen($filename, 'a+') or die('Unable to open file!');

// 文件指针倒回到文件的开头
// $result = fseek($myfile, 0);

// echo '当前文件指针位置' .ftell($myfile) .'<br />';

// 读取文件，获取当前条目的个数
/*$numbers = fread($myfile, 6);       // 文件前6位记录当前文件条目个数
echo '当前文件条目个数： ' .$numbers .'<br />';
$numbers = strval(intval($numbers) + 1);
echo '添加条目后个数： ' . $numbers . '<br />';*/

// 写入文件
fwrite($myfile, $spliceboard);

// 写入文件成功
echo 'sucess: 提交成功！' .'<br />';

// 关闭文件
fclose($myfile);

// 2s 后跳转到 show_spliceboard 页面
// sleep(2);
header('location: show_spliceboard.php');
?> 

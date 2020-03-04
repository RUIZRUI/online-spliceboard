<?php
/*
    目前暂定显示这一个月的条目内容，没有number 限制

    1. strstr($val, 'char'), 返回第一次出现的位置，到结束的字符串
       strpos($val, 'char'), 返回第一次出现的位置
*/
error_reporting(0);
echo '<h2>Welcome to spliceboard-online</h2>';
// $number = 30;           // 设置最大显示的条目数

// 接收要显示文本的月份
$selectMonth = $_GET['month'];
if($selectMonth == null){
    $selectMonth = 0;       // 默认本月
}

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
$old_id = 12*(intval($time_ry) - intval($time_y)) + (intval($time_rm) - intval($time_m)) + 1;
$id = strval($old_id + intval($selectMonth));
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
$filesize = filesize($filename);
// $len = 1;       // 先循环一次，然后赋值，error
if($filesize == 0){
    $len = 0;
}else{
    echo '<table border="1"><tr><td>order</td><td>id</td><td>size</td><td>time</td><td>content</td><td>copy</td></tr/>';
    $len = 1;
}
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
    "<td><font color='#0593d3'><label class='clipboard' data-clipboard-action='copy' data-clipboard-target='#{$contentID}'><u>复制</u></label></font></td>".
    "</tr>";
    fseek($myfile, $filesize);                   // 更新 文件指针
    if($i == 0){                                 // 更新 $len
        $len = intval($pid);
    }
}
echo '</table>';

// 关闭文件
fclose($myfile);

// 选择显示的内容： 上上月、上一月、这月
echo '<div id="whichMonth">'. 
     '<label for="selectMonth"><span>选择月份：</span></label>'.
     '<select name="selectMonth" id="selectMonth">';

// echo '<option value="-2">上上月</option>'.
//      '<option value="-1">上一月</option>'.
//      '<option value="0">这月</option>'; 
for($i=0; $i < $old_id; $i++){
    $temp = -1 * $i;    
    $temp_year = intval($time_y) + intval((intval($time_m) + ($old_id - $i - 1) - 1) / 12);
    $temp_month = (intval($time_m) + ($old_id - $i - 1) - 1) % 12 + 1;
    if($temp_month < 10){
        $temp_month = '0'. $temp_month;
    }
    if($temp == intval($selectMonth)){ 
        echo "<option value='{$temp}' selected>{$temp_year}-{$temp_month}</option>";
    }else{
        echo "<option value='{$temp}'>{$temp_year}-{$temp_month}</option>";
    }
}

echo '</select>'. 
     '</div>';

// 引入 html
echo '<script type="text/javascript" src="../js/clipboard.min.js"></script>';
echo '<script type="text/javascript" src="../js/show_spliceboard.js"></script>';
echo '<link rel="stylesheet" type="text/css" href="../css/show_spliceboard.css" />';
?>

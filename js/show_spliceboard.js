/* eslint-disable no-undef */
/* eslint-disable no-unused-vars */

var flag = -1;          //  记录上一次记录的下标

// 处理 label数组点击复制事件
function click_copy(){          // 函数封装
    var label = document.getElementsByTagName('label');
    for(let i = 0; i<label.length; i ++){
        label[i].onclick = function(){
            if(flag != -1 && i == flag){        // 本次复制的和上次一样，可以不写 flag != -1
                return;
            }

            // alert('order ' + (i + 1) + ' 的条目');
            // alert('hello');
            // document.getElementsByTagName('tr')[i+1].getElementsByTagName('td')[4].innerText.select();       // 选择对象
            
            // alert('what');
            // document.execCommand('Copy');       // 执行浏览器命令
            // alert('即将复制好，可粘贴');

            // 复制成功后更改颜色
            document.getElementsByTagName('font')[i].color = '#fc6423';

            // 将上次复制的更改为原来的颜色
            if(flag != -1){
                document.getElementsByTagName('font')[flag].color = '#0593d3';
            }
            flag = i;
        };

        label[i].onmouseenter = function(){
            if(flag != -1 && i == flag){        // 跳过当前复制的，flag != -1 可以不写
                return;
            }
            document.getElementsByTagName('font')[i].color = '#fc6423';
            document.getElementsByTagName('font')[i].style.cursor = 'pointer';
        };

        label[i].onmouseleave = function(){
            if(flag != -1 && i == flag){
                return;
            }
            document.getElementsByTagName('font')[i].color = '#0593d3';
            document.getElementsByTagName('font')[i].style.cursor = 'default';
        };
    }
}

click_copy();

// 调用clipboard.min.js 处理复制事件
var clipboard = new Clipboard('.clipboard');

// 选择要显示文本的月份
var whichMonth = document.getElementById('whichMonth');
var selectMonth = document.getElementById('selectMonth');
selectMonth.onchange = function(){              // 下拉列表改变
    window.location.href = '/copy/php/show_spliceboard.php?month=' + selectMonth.value;
};

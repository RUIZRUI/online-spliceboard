/* eslint-disable no-undef */
/* eslint-disable no-unused-vars */

// 处理 label数组点击复制事件
function click_copy(){          // 函数封装
    var label = document.getElementsByTagName('label');
    for(let i = 0; i<label.length; i ++){
        label[i].onclick = function(){
            // alert('order ' + (i + 1) + ' 的条目');
            // alert('hello');
            // document.getElementsByTagName('tr')[i+1].getElementsByTagName('td')[4].innerText.select();       // 选择对象
            
            // alert('what');
            // document.execCommand('Copy');       // 执行浏览器命令
            alert('即将复制好，可粘贴');
        }
    }
}

click_copy();

// 调用clipboard.min.js 处理复制事件
var clipboard = new Clipboard('.clipboard');
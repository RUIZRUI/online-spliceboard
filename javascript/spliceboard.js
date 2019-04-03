var flag = false;           // 作为第一次点击 textarea 的标志

document.getElementsByTagName('textarea')[0].onclick = function(){
    if(!flag){
        document.getElementsByTagName('textarea')[0].value = '';
        flag = true;
    }
}  

document.getElementsByTagName('form')[0].onsubmit = function(){     // 不提交空内容
    if(document.getElementsByTagName('textarea')[0].value == ''){
        return false;
    }
    return true;
}
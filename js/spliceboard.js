var flag = false;           // 作为第一次点击 textarea 的标志

document.getElementsByTagName('textarea')[0].onclick = function(){
    if(!flag){
        document.getElementsByTagName('textarea')[0].value = '';
        flag = true;
    }
};

document.getElementsByTagName('form')[0].onsubmit = function(){     // 不提交空内容与默认值
    if(document.getElementsByTagName('textarea')[0].value == '' || document.getElementsByTagName('textarea')[0].value == '在此粘贴 ...'){
        return false;
    }
    flag = false;	// 处理跳转页面返回点击 textarea 
    return true;
};

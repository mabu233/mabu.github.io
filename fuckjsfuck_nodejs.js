var code = '(+(+!+[]+(!+[]+[])[!+[]+!+[]+!+[]]+[+!+[]]+[+[]]+[+[]])+[])[2]';

//用到了 浏览器端 对象, 解不出来, 可以....
//一些window 环境变量 nodejs 没有的,先定义
// var location = '"https://mlizu.com/newfile.php"';
var index = 1;

function parse(tmp = '', begin){
    if(begin){
        code = '[' + code + ']';
        tmp = '[';
    }
    var type = tmp == '[' ? ']' : ')';
    var is_eval = true;
    while(1){
        var current_ch = code[index++];
        if(current_ch == '[' || current_ch == '('){
            let res = parse(current_ch);
            tmp += res;
            if(res == '[]' || res == '[[]]' || res == '()'){
            }else {
                // is_eval = false;
            }
        }else if(current_ch == type){
            tmp += type;
            if(tmp == '[]' || tmp == '()')return tmp;
            if(tmp == '[[]]')
                return tmp;
            if(is_eval){
                if(begin){
                    begin;
                }
                if(/^\[.*\]$/.test(tmp)){
                    tmp = tmp.replace(/^\[(.*)\]$/,'$1');
                    try{
                        tmp = "['" +eval(tmp) +"']";
                    }catch(e){
                        tmp;
                    }
                    return tmp;
                }
                if(/^\(.*\)$/.test(tmp)){
                    //location 替换处理
                    // tmp = tmp.replace("([]+[]['sort']['constructor']('return location')())",`${location}`);
                    try{
                        if(Number.isInteger(Number((eval(tmp)))) && eval(tmp) !== '' && !/\.|\+|e/.test(eval(tmp))){
                            var res_tmp = "(" +eval(tmp) +")"; 
                        }else {
                            var res_tmp = "('" +eval(tmp) +"')";
                        }
                    }catch(e){
                        res_tmp = tmp;
                    }
                    return res_tmp;
                }
                var res_tmp = "'"+eval(tmp)+"'";
                return res_tmp;
            }else {
                return tmp;
            }
        }else {
            tmp += current_ch;
        }
    }
}

console.log(parse('', true));

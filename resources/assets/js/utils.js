//获取cookie、
export function getCookie(name) {
	var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
	if (arr = document.cookie.match(reg))
		return (arr[2]);
	else
		return null;
}
 
//设置cookie,增加到vue实例方便全局调用
export function setCookie (c_name, value, expiredays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays);
	document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
};
 
//删除cookie
export function delCookie (name) {
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval = getCookie(name);
	if (cval != null)
		document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
};


export function formatDate ( date , fmt ) {
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    let o = {
        'M+': date.getMonth() + 1 ,
        'd+': date.getDate(),
        'h+': date.getHours(),
        'm+': date.getMinutes(),
        's+': date.getSeconds()
    };
    for (let k in o) {
        if (new RegExp(`(${k})`).test(fmt)) {
            let str = o[k] + '';
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? str : padLeftZero(str));
        }
    }
    return fmt;
};

export function parseUrl (url) {
    var a = document.createElement('a');
    a.href = url;
    return {
        source : url,
        protocol : a.protocol.replace(':', ''),
        host : a.hostname,
        port : a.port,
        query : a.search,
        params : (function() {
            var ret = {}, seg = a.search.replace(/^\?/, '').split('&'), len = seg.length, i = 0, s;
            for (; i < len; i++) {
                if (!seg[i]) {
                    continue;
                }
                s = seg[i].split('=');
                ret[s[0]] = s[1];
            }
            return ret;
        })(),
        file : (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1],
        hash : a.hash.replace('#', ''),
        path : a.pathname.replace(/^([^\/])/, '/$1'),
        relative : (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1],
        segments : a.pathname.replace(/^\//, '').split('/')
    };
}  

export function isWeiXin() {
    let ua = window.navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == 'micromessenger') {
        return true;
    } else {
        return false;
    }
}



function padLeftZero (str) {
    return ('00' + str).substr(str.length);
}
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)}(function(e){function t(e){return e}function n(e){return decodeURIComponent(e.replace(r," "))}function i(e){0===e.indexOf('"')&&(e=e.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return a.json?JSON.parse(e):e}catch(t){}}var r=/\+/g,a=e.cookie=function(r,o,s){if(void 0!==o){if(s=e.extend({},a.defaults,s),"number"==typeof s.expires){var l=s.expires,c=s.expires=new Date;c.setDate(c.getDate()+l)}return o=a.json?JSON.stringify(o):String(o),document.cookie=[a.raw?r:encodeURIComponent(r),"=",a.raw?o:encodeURIComponent(o),s.expires?"; expires="+s.expires.toUTCString():"",s.path?"; path="+s.path:"",s.domain?"; domain="+s.domain:"",s.secure?"; secure":""].join("")}for(var u=a.raw?t:n,d=document.cookie.split("; "),h=r?void 0:{},f=0,p=d.length;p>f;f++){var m=d[f].split("="),g=u(m.shift()),v=u(m.join("="));if(r&&r===g){h=i(v);break}r||(h[g]=i(v))}return h};a.defaults={},e.removeCookie=function(t,n){return void 0!==e.cookie(t)?(e.cookie(t,"",e.extend(n,{expires:-1})),!0):!1}});
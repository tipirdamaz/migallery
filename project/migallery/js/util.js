/**
* return top-left corner position of html element
*  
* @param {object} el
* @return {object}
*/
function getOffset(el) {
  el = el.getBoundingClientRect();
  return {
    left: el.left + window.scrollX,
    top: el.top + window.scrollY
  }
}

/**
* return a formatted string
*
* @param {String} this
* @return {string}
*/
String.prototype.printf = String.prototype.printf ||
function () {
    "use strict";
    var str = this.toString();
    if (arguments.length) {
        var t = typeof arguments[0];
        var key;
        var args = ("string" === t || "number" === t) ?
            Array.prototype.slice.call(arguments)
            : arguments[0];

        for (key in args) {
            str = str.replace(new RegExp("\\{" + key + "\\}", "gi"), args[key]);
        }
    }

    return str;
};


/**
* 
* text, textarea char counter
* 
* @param {Object} params
* @param {number} params.maxlen
* @param {JQuery|HTMLElement} params.counter
* 
*/
(function($, lang) {
    $.fn.extend({
        charCount:
        function(params) {
            params = $.extend({maxlen:50, counter:''}, params);
            return this.each(function() {
                $(this).bind("keypress", function() {
				   	if ($(this)[0].value.length >= params.maxlen) return false;
                });
                $(this).bind("keyup", function() {
				   if ($(this)[0].value.length > params.maxlen) $(this)[0].value = $(this)[0].value.substring(0, params.maxlen);
				   $('#'+params.counter).html($(this)[0].value.length);
                });
                $(this).bind("change", function() {
				   if ($(this)[0].value.length > params.maxlen) $(this)[0].value = $(this)[0].value.substring(0, params.maxlen);
				   $('#'+params.counter).html($(this)[0].value.length);
                });
                $(this).bind('paste', null, function(e) {
                   setTimeout(function() {
				      if (e.target.value.length > params.maxlen) {
				         e.target.value = e.target.value.substring(0, params.maxlen);
				         alert(lang.charCount.exceedsMaxCharLength.printf(params.maxlen));
					  }
				      $('#'+params.counter).html(e.target.value.length);
                   }, 100);
                });
            });
        }
    });
} (jQuery, translateJS));


/**
* 
* textarea autogrow
* 
*/
(function ($) {
    'use strict';

    // Plugin interface
    $.fn.autoGrowTextarea = autoGrowTextArea;
    $.fn.autoGrowTextArea = autoGrowTextArea;

    // Shorthand alias
    if (!('autogrow' in $.fn)) {
        $.fn.autogrow = autoGrowTextArea;
    }

    /**
     * Initialization on each element
     */
    function autoGrowTextArea() {
        return this.each(init);
    }

    /**
     * Actual initialization
     */
    function init() {
        var $textarea, $origin, origin, hasOffset, innerHeight, height, offset = 0;

        $textarea = $(this).css({overflow: 'hidden', resize: 'none'});

        if ($textarea.data('autogrow-origin')) {
            return;
        }

        $origin = $textarea.clone().val('').appendTo(document.body);
        origin = $origin.get(0);

        height = $origin.height();
        origin.scrollHeight; // necessary for IE6-8. @see http://bit.ly/LRl3gf
        hasOffset = (origin.scrollHeight !== height);

        // `hasOffset` detects whether `.scrollHeight` includes padding.
        // This behavior differs between browsers.
        if (hasOffset) {
            innerHeight = $origin.innerHeight();
            offset = innerHeight - height;
        }

        $origin.hide();

        $textarea
            .data('autogrow-origin', $origin)
            .on('keyup change input paste autogrow', function () {
                grow($textarea, $origin, origin, height, offset);
            });

        grow($textarea, $origin, origin, height, offset);
    }

    /**
     * grow textarea height if its value changed
     */
    function grow($textarea, $origin, origin, initialHeight, offset) {
        var current, prev, scrollHeight, height;

        current = $textarea.val();
        prev = grow.prev;
        if (current === prev) return;

        grow.prev = current;

        $origin.val(current).show();
        origin.scrollHeight; // necessary for IE6-8. @see http://bit.ly/LRl3gf
        scrollHeight = origin.scrollHeight;
        height = scrollHeight - offset;
        $origin.hide();

        $textarea.height(height > initialHeight ? height : initialHeight);
    }
}(jQuery));

/**
* javascript MD5 function
* @param {string}
* @return {string}
*/
function rhex(i){for(str="",j=0;j<=3;j++)str+=hex_chr.charAt(i>>8*j+4&15)+hex_chr.charAt(i>>8*j&15);return str}function str2blks_MD5(x){for(nblk=1+(x.length+8>>6),blks=new Array(16*nblk),i=0;i<16*nblk;i++)blks[i]=0;for(i=0;i<x.length;i++)blks[i>>2]|=x.charCodeAt(i)<<i%4*8;return blks[i>>2]|=128<<i%4*8,blks[16*nblk-2]=8*x.length,blks}function add(i,x){var h=(65535&i)+(65535&x);return(i>>16)+(x>>16)+(h>>16)<<16|65535&h}function rol(i,x){return i<<x|i>>>32-x}function cmn(i,x,h,f,r,n){return add(rol(add(add(x,i),add(f,n)),r),h)}function ff(i,x,h,f,r,n,g){return cmn(x&h|~x&f,i,x,r,n,g)}function gg(i,x,h,f,r,n,g){return cmn(x&f|h&~f,i,x,r,n,g)}function hh(i,x,h,f,r,n,g){return cmn(x^h^f,i,x,r,n,g)}function ii(i,x,h,f,r,n,g){return cmn(h^(x|~f),i,x,r,n,g)}function MD5(h){x=str2blks_MD5(h);var f=1732584193,r=-271733879,n=-1732584194,g=271733878;for(i=0;i<x.length;i+=16){var t=f,e=r,c=n,d=g;r=ii(r=ii(r=ii(r=ii(r=hh(r=hh(r=hh(r=hh(r=gg(r=gg(r=gg(r=gg(r=ff(r=ff(r=ff(r=ff(r,n=ff(n,g=ff(g,f=ff(f,r,n,g,x[i+0],7,-680876936),r,n,x[i+1],12,-389564586),f,r,x[i+2],17,606105819),g,f,x[i+3],22,-1044525330),n=ff(n,g=ff(g,f=ff(f,r,n,g,x[i+4],7,-176418897),r,n,x[i+5],12,1200080426),f,r,x[i+6],17,-1473231341),g,f,x[i+7],22,-45705983),n=ff(n,g=ff(g,f=ff(f,r,n,g,x[i+8],7,1770035416),r,n,x[i+9],12,-1958414417),f,r,x[i+10],17,-42063),g,f,x[i+11],22,-1990404162),n=ff(n,g=ff(g,f=ff(f,r,n,g,x[i+12],7,1804603682),r,n,x[i+13],12,-40341101),f,r,x[i+14],17,-1502002290),g,f,x[i+15],22,1236535329),n=gg(n,g=gg(g,f=gg(f,r,n,g,x[i+1],5,-165796510),r,n,x[i+6],9,-1069501632),f,r,x[i+11],14,643717713),g,f,x[i+0],20,-373897302),n=gg(n,g=gg(g,f=gg(f,r,n,g,x[i+5],5,-701558691),r,n,x[i+10],9,38016083),f,r,x[i+15],14,-660478335),g,f,x[i+4],20,-405537848),n=gg(n,g=gg(g,f=gg(f,r,n,g,x[i+9],5,568446438),r,n,x[i+14],9,-1019803690),f,r,x[i+3],14,-187363961),g,f,x[i+8],20,1163531501),n=gg(n,g=gg(g,f=gg(f,r,n,g,x[i+13],5,-1444681467),r,n,x[i+2],9,-51403784),f,r,x[i+7],14,1735328473),g,f,x[i+12],20,-1926607734),n=hh(n,g=hh(g,f=hh(f,r,n,g,x[i+5],4,-378558),r,n,x[i+8],11,-2022574463),f,r,x[i+11],16,1839030562),g,f,x[i+14],23,-35309556),n=hh(n,g=hh(g,f=hh(f,r,n,g,x[i+1],4,-1530992060),r,n,x[i+4],11,1272893353),f,r,x[i+7],16,-155497632),g,f,x[i+10],23,-1094730640),n=hh(n,g=hh(g,f=hh(f,r,n,g,x[i+13],4,681279174),r,n,x[i+0],11,-358537222),f,r,x[i+3],16,-722521979),g,f,x[i+6],23,76029189),n=hh(n,g=hh(g,f=hh(f,r,n,g,x[i+9],4,-640364487),r,n,x[i+12],11,-421815835),f,r,x[i+15],16,530742520),g,f,x[i+2],23,-995338651),n=ii(n,g=ii(g,f=ii(f,r,n,g,x[i+0],6,-198630844),r,n,x[i+7],10,1126891415),f,r,x[i+14],15,-1416354905),g,f,x[i+5],21,-57434055),n=ii(n,g=ii(g,f=ii(f,r,n,g,x[i+12],6,1700485571),r,n,x[i+3],10,-1894986606),f,r,x[i+10],15,-1051523),g,f,x[i+1],21,-2054922799),n=ii(n,g=ii(g,f=ii(f,r,n,g,x[i+8],6,1873313359),r,n,x[i+15],10,-30611744),f,r,x[i+6],15,-1560198380),g,f,x[i+13],21,1309151649),n=ii(n,g=ii(g,f=ii(f,r,n,g,x[i+4],6,-145523070),r,n,x[i+11],10,-1120210379),f,r,x[i+2],15,718787259),g,f,x[i+9],21,-343485551),f=add(f,t),r=add(r,e),n=add(n,c),g=add(g,d)}return rhex(f)+rhex(r)+rhex(n)+rhex(g)}var hex_chr="0123456789abcdef";


/**
* 
* Check phone
* 
*/

window.isPhone = function() {
  let check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
};

/**
* Check phone or tablet
*/

window.isPhoneOrTablet = function() {
  let check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
};
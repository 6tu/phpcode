/*
 * Interfaces:
 * b64 = window.btoa(data);
 * data = window.atob(b64);
 */

if (typeof(btoa) == "undefined") {
    btoa = function() {
        var base64EncodeChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'.split('');
        return function(str) {
            var out, i, j, len, r, l, c;
            i = j = 0;
            len = str.length;
            r = len % 3;
            len = len - r;
            l = (len / 3) << 2;
            if (r > 0) {
                l += 4;
            }
            out = new Array(l);

            while (i < len) {
                c = str.charCodeAt(i++) << 16 |
                    str.charCodeAt(i++) << 8  |
                    str.charCodeAt(i++);
                out[j++] = base64EncodeChars[c >> 18]
                    + base64EncodeChars[c >> 12 & 0x3f]
                    + base64EncodeChars[c >> 6  & 0x3f]
                    + base64EncodeChars[c & 0x3f] ;
            }
            if (r == 1) {
                c = str.charCodeAt(i++);
                out[j++] = base64EncodeChars[c >> 2]
                    + base64EncodeChars[(c & 0x03) << 4]
                    + "==";
                }
            else if (r == 2) {
                c = str.charCodeAt(i++) << 8 |
                    str.charCodeAt(i++);
                out[j++] = base64EncodeChars[c >> 10]
                     + base64EncodeChars[c >> 4 & 0x3f]
                     + base64EncodeChars[(c & 0x0f) << 2]
                     + "=";
            }
            return out.join('');
        }
    }();
}

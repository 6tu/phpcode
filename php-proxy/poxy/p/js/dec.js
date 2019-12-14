// static class XXTEA
var XXTEA = new function() {
    // private static member delta
    var delta = 0x9E3779B9;

    // private static method longArrayToString
    function longArrayToString(data, includeLength) {
        var length = data.length;
        var n = (length - 1) << 2;
        if (includeLength) {
            var m = data[length - 1];
            if ((m < n - 3) || (m > n)) return null;
            n = m;
        }
        for (var i = 0; i < length; i++) {
            data[i] = String.fromCharCode(
                data[i] & 0xff,
                data[i] >>> 8 & 0xff,
                data[i] >>> 16 & 0xff, 
                data[i] >>> 24 & 0xff
            );
        }
        if (includeLength) {
            return data.join('').substring(0, n);
        }
        else {
            return data.join('');
        }
    }

    // private static method stringToLongArray
    function stringToLongArray(string, includeLength) {
        var length = string.length;
        var result = [];
        for (var i = 0; i < length; i += 4) {
            result[i >> 2] = string.charCodeAt(i) |
                string.charCodeAt(i + 1) << 8     |
                string.charCodeAt(i + 2) << 16    |
                string.charCodeAt(i + 3) << 24;
        }
        if (includeLength) {
            result[result.length] = length;
        }
        return result;
    }

    // public static method decrypt
    this.decrypt = function(string, key) {
        if (string == "") {
            return "";
        }
        var v = stringToLongArray(string, false);
        var k = stringToLongArray(key, false);
        if (k.length < 4) {
            k.length = 4;
        }
        var n = v.length - 1;

        var z = v[n - 1], y = v[0];
        var mx, e, p, q = Math.floor(6 + 52 / (n + 1)), sum = q * delta & 0xffffffff;
        while (sum != 0) {
            e = sum >>> 2 & 3;
            for (p = n; p > 0; p--) {
                z = v[p - 1];
                mx = (z >>> 5 ^ y << 2) + (y >>> 3 ^ z << 4) ^ (sum ^ y) + (k[p & 3 ^ e] ^ z);
                y = v[p] = v[p] - mx & 0xffffffff;
            }
            z = v[n];
            mx = (z >>> 5 ^ y << 2) + (y >>> 3 ^ z << 4) ^ (sum ^ y) + (k[p & 3 ^ e] ^ z);
            y = v[0] = v[0] - mx & 0xffffffff;
            sum = sum - delta & 0xffffffff;
        }

        return longArrayToString(v, true);
    }
}


String.prototype.toUTF16 = function() {
    var str = this;
    if ((str.match(/^[\x00-\x7f]*$/) != null) ||
        (str.match(/^[\x00-\xff]*$/) == null)) {
        return str.toString();
    }
    var out, i, j, len, c, c2, c3, c4, s;

    out = [];
    len = str.length;
    i = j = 0;
    while (i < len) {
        c = str.charCodeAt(i++);
        switch (c >> 4) { 
            case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
            // 0xxx xxxx
            out[j++] = str.charAt(i - 1);
            break;
            case 12: case 13:
            // 110x xxxx   10xx xxxx
            c2 = str.charCodeAt(i++);
            out[j++] = String.fromCharCode(((c  & 0x1f) << 6) |
                                            (c2 & 0x3f));
            break;
            case 14:
            // 1110 xxxx  10xx xxxx  10xx xxxx
            c2 = str.charCodeAt(i++);
            c3 = str.charCodeAt(i++);
            out[j++] = String.fromCharCode(((c  & 0x0f) << 12) |
                                           ((c2 & 0x3f) <<  6) |
                                            (c3 & 0x3f));
            break;
            case 15:
            switch (c & 0xf) {
                case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
                // 1111 0xxx  10xx xxxx  10xx xxxx  10xx xxxx
                c2 = str.charCodeAt(i++);
                c3 = str.charCodeAt(i++);
                c4 = str.charCodeAt(i++);
                s = ((c  & 0x07) << 18) |
                    ((c2 & 0x3f) << 12) |
                    ((c3 & 0x3f) <<  6) |
                     (c4 & 0x3f) - 0x10000;
                if (0 <= s && s <= 0xfffff) {
                    out[j++] = String.fromCharCode(((s >>> 10) & 0x03ff) | 0xd800,
                                                  (s         & 0x03ff) | 0xdc00);
                }
                else {
                    out[j++] = '?';
                }
                break;
                case 8: case 9: case 10: case 11:
                // 1111 10xx  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx
                i+=4;
                out[j++] = '?';
                break;
                case 12: case 13:
                // 1111 110x  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx  10xx xxxx
                i+=5;
                out[j++] = '?';
                break;
            }
        }
    }
    return out.join('');
}
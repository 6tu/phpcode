<?php
class urlcheck{
    var $regex = array(// 协议名(注意在这里必须写成小写) => 对应的正则表达式
        'ftp' => '$this->ftpurl',
        'file' => '$this->fileurl',
        'http' => '$this->httpurl',
        'https' => '$this->httpurl',
        'gopher' => '$this->gopherurl',
        'news' => '$this->newsurl',
        'nntp' => '$this->nntpurl',
        'telnet' => '$this->telneturl',
        'wais' => '$this->waisurl'
        );
    
    var $lowalpha;
    var $hialpha;
    var $alpha;
    var $digit;
    var $safe;
    var $extra;
    var $national;
    var $punctuation;
    var $reserved;
    var $hex;
    var $escape;
    var $unreserved;
    var $uchar;
    var $xchar;
    var $digits;
    
    var $urlpath;
    var $password;
    var $user;
    var $port;
    var $hostnumber;
    var $alphadigit;
    var $toplabel;
    var $domainlabel;
    var $hostname;
    var $host;
    var $hostport;
    var $login;
    
    // ftp
    var $ftptype;
    var $fsegment;
    var $fpath;
    var $ftpurl;
    
    // file
    var $fileurl;
    
    // http,https
    var $search;
    var $hsegment;
    var $hpath;
    var $httpurl;
    
    // gopher
    var $gopher_string;
    var $selector;
    var $gtype;
    var $gopherurl;
    
    // news
    var $article;
    var $group;
    var $grouppart;
    var $newsurl;
    
    // nntp
    var $nntpurl;
    
    // telnet
    var $telneturl;
    
    // wais
    var $wpath;
    var $wtype;
    var $database;
    var $waisdoc;
    var $waisindex;
    var $waisdatabase;
    var $waisurl;
    
    function check($url){
        $pos = @strpos($url, ':', 1);
        if($pos < 1) return false;
        $prot = substr($url, 0, $pos);
        if(!isset($this -> regex[$prot])) return false;
        eval('$regex = ' . $this -> regex[$prot] . ';');
        return ereg('^' . $regex . '$', $url);
        }
    
    function urlcheck(){
        $this -> lowalpha = '[a-z]';
        $this -> hialpha = '[A-Z]';
        $this -> alpha = '(' . $this -> lowalpha . '|' . $this -> hialpha . ')';
        $this -> digit = '[0-9]';
        $this -> safe = '[$.+_-]';
        $this -> extra = '[*()\'!,]';
        $this -> national = '([{}|\^~`]|\\[|\\])';
        $this -> punctuation = '[<>#%"]';
        $this -> reserved = '[?;/:@&=]';
        $this -> hex = '(' . $this -> digit . '|[a-fA-F])';
        $this -> escape = '(%' . $this -> hex . '{2})';
        $this -> unreserved = '(' . $this -> alpha . '|' . $this -> digit . '|' . $this -> safe . '|' . $this -> extra . ')';
        $this -> uchar = '(' . $this -> unreserved . '|' . $this -> escape . ')';
        $this -> xchar = '(' . $this -> unreserved . '|' . $this -> reserved . '|' . $this -> escape . ')';
        $this -> digits = '(' . $this -> digit . '+)';
        
        $this -> urlpath = '(' . $this -> xchar . '*)';
        $this -> password = '((' . $this -> uchar . '|[?;&=]' . ')*)';
        $this -> user = '((' . $this -> uchar . '|[?;&=]' . ')*)';
        $this -> port = $this -> digits;
        $this -> hostnumber = '(' . $this -> digits . '.' . $this -> digits . '.' . $this -> digits . '.' . $this -> digits . ')';
        $this -> alphadigit = '(' . $this -> alpha . '|' . $this -> digit . ')';
        $this -> toplabel = '(' . $this -> alpha . '|(' . $this -> alpha . '(' . $this -> alphadigit . '|-)*' . $this -> alphadigit . '))';
        $this -> domainlabel = '(' . $this -> alphadigit . '|(' . $this -> alphadigit . '(' . $this -> alphadigit . '|-)*' . $this -> alphadigit . '))';
        $this -> hostname = '((' . $this -> domainlabel . '\\.)*' . $this -> toplabel . ')';
        $this -> host = '(' . $this -> hostname . '|' . $this -> hostnumber . ')';
        $this -> hostport = '(' . $this -> host . '(:' . $this -> port . ')?)';
        $this -> login = '((' . $this -> user . '(:' . $this -> password . ')?@)?' . $this -> hostport . ')';
        
        $this -> ftptype = '[aidAID]';
        $this -> fsegment = '((' . $this -> uchar . '|[?:@&=])*)';
        $this -> fpath = '(' . $this -> fsegment . '(/' . $this -> fsegment . ')*)';
        $this -> ftpurl = '([fF][tT][pP]://' . $this -> login . '(/' . $this -> fpath . '(;[tT][yY][pP][eE]=' . $this -> ftptype . ')?)?)';
        
        $this -> fileurl = '([fF][iI][lL][eE]://(' . $this -> host . '|[lL][oO][cC][aA][lL][hH][oO][sS][tT])?/' . $this -> fpath . ')';
        
        $this -> search = '((' . $this -> uchar . '|[;:@&=])*)';
        $this -> hsegment = '((' . $this -> uchar . '|[;:@&=])*)';
        $this -> hpath = '(' . $this -> hsegment . '(/' . $this -> hsegment . ')*)';
        $this -> httpurl = '([hH][tT][tT][pP][sS]?://' . $this -> hostport . '(/' . $this -> hpath . '([?]' . $this -> search . ')?)?)';
        
        $this -> gopher_string = '(' . $this -> xchar . '*)';
        $this -> selector = '(' . $this -> xchar . '*)';
        $this -> gtype = $this -> xchar;
        $this -> gopherurl = '([gG][oO][pP][hH][eE][rR]://' . $this -> hostport . '(/(' . $this -> gtype . '(' . $this -> selector . '(%09' . $this -> search . '(%09' . $this -> gopher_string . ')?)?)?)?)?)';
        
        $this -> article = '((' . $this -> uchar . '|[;/?:&=])+@' . $this -> host . ')';
        $this -> group = '(' . $this -> alpha . '(' . $this -> alpha . '|' . $this -> digit . '|[-.+_])*)';
        $this -> grouppart = '([*]|' . $this -> group . '|' . $this -> article . ')';
        $this -> newsurl = '([nN][eE][wW][sS]:' . $this -> grouppart . ')';
        
        $this -> nntpurl = '([nN][nN][tT][pP]://' . $this -> hostport . '/' . $this -> group . '(/' . $this -> digits . ')?)';
        
        $this -> telneturl = '([tT][eE][lL][nN][eE][tT]://' . $this -> login . '/?)';
        
        $this -> wpath = '(' . $this -> uchar . '*)';
        $this -> wtype = '(' . $this -> uchar . '*)';
        $this -> database = '(' . $this -> uchar . '*)';
        $this -> waisdoc = '([wW][aA][iI][sS]://' . $this -> hostport . '/' . $this -> database . '/' . $this -> wtype . '/' . $this -> wpath . ')';
        $this -> waisindex = '([wW][aA][iI][sS]://' . $this -> hostport . '/' . $this -> database . '[?]' . $this -> search . ')';
        $this -> waisdatabase = '([wW][aA][iI][sS]://' . $this -> hostport . '/' . $this -> database . ')';
        $this -> waisurl = '(' . $this -> waisdatabase . '|' . $this -> waisindex . '|' . $this -> waisdoc . ')';
        }
    }
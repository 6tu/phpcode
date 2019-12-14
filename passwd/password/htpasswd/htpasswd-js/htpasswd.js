		//<![CDATA[
		<!--
		 
		var ALG_PLAIN = 0;          
		var ALG_CRYPT = 1;           
		var ALG_APMD5 = 2;           
		var ALG_APSHA = 3;           
		var AP_SHA1PW_ID = "{SHA}";
		var AP_MD5PW_ID  = "$apr1$";
		 

		var itoa64 = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";  /* 0 ... 63 => ASCII - 64 */
		function ap_to64(v, n) {
		  var s = '';
		  while (--n >= 0) {
			s += itoa64.charAt(v&0x3f);  
			v >>>= 6;                    
		  }
		  return s;
		}
		 

		function stringToArray(s) {
		  var a=[];
		  for (var i = 0; i < s.length; i++) a.push(s.charCodeAt(i));
		  return a;
		}
		 
		function htpasswd(user, pw, alg) {


		  var salt = ap_to64(Math.floor(Math.random()*16777215), 4)    
				   + ap_to64(Math.floor(Math.random()*16777215), 4);   
		 
		 
		  var plus127 = 0;
		  for (var i=0; i<user.length; i++) if (user.charCodeAt(i) > 127) plus127++;
		  if (plus127) alert("Apache doesn't like non-ascii characters in the user name.");
		 
		  var cpw  = '';         
		  switch (alg) {
			case ALG_APSHA:
			  cpw = AP_SHA1PW_ID + b64_sha1(pw);
			  break;
		 
			case ALG_APMD5:
			  var msg = pw;          
			  msg += AP_MD5PW_ID;    
			  msg += salt;           

			  var final_ = str_md5(pw + salt + pw);
			  for (var pl = pw.length; pl > 0; pl -= 16) msg += final_.substr(0, (pl > 16) ? 16 : pl);
		 

			  for (i = pw.length; i != 0; i >>= 1)
				if (i & 1) msg += String.fromCharCode(0);
				else msg += pw.charAt(0);
			  final_ = str_md5(msg);

			  var msg2;
			  for (i = 0; i < 1000; i++) {
				msg2 = '';
				if (i & 1) msg2 += pw; else msg2 += final_.substr(0, 16);
				if (i % 3) msg2 += salt;
				if (i % 7) msg2 += pw;
				if (i & 1) msg2 += final_.substr(0, 16); else msg2 += pw;
				final_ = str_md5(msg2);
			  }
			  final_ = stringToArray(final_);
		 

			  cpw = AP_MD5PW_ID + salt + '$';
			  cpw += ap_to64((final_[ 0]<<16) | (final_[ 6]<<8) | final_[12], 4);
			  cpw += ap_to64((final_[ 1]<<16) | (final_[ 7]<<8) | final_[13], 4);
			  cpw += ap_to64((final_[ 2]<<16) | (final_[ 8]<<8) | final_[14], 4);
			  cpw += ap_to64((final_[ 3]<<16) | (final_[ 9]<<8) | final_[15], 4);
			  cpw += ap_to64((final_[ 4]<<16) | (final_[10]<<8) | final_[ 5], 4);
			  cpw += ap_to64(                    final_[11]               , 2);
			  break;
		 
			case ALG_PLAIN:
			  cpw = pw;
			  break;
		 
			case ALG_CRYPT:
			default:
			  cpw = Javacrypt.displayPassword(pw, salt);
			  break;
		  }
		 

		  if (user.length + 1 + cpw.length > 255) alert('Your login and password are too long.');
		  else return user + ':' + cpw;
		}

		function pwgen(pwl) {
		  var source = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-#$@+_()[]{}=%*!?";
		  var pw = '';
		  for (var i = 1; i <= pwl; i++) {
			pw += source.substr(Math.floor(Math.random()*source.length),1);
		  }
		  return pw;
		}
		 
		function generation(f) {
		  var pw = pwgen(f.taille.options[f.taille.selectedIndex].text);
		  f.pwd1.value = pw;
		  f.pwd2.value = htpasswd(f.user.value, pw, f.alg.selectedIndex);
		}
		 
		//-->
		//]]>
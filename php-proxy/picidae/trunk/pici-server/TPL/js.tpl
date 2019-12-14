<script type="text/javascript" src="external/des.js" ></script>
<script type="text/javascript" src="external/base64.js" ></script>
<script type="text/javascript" src="external/urlencode.js" ></script>

<script type="text/javascript" src="S/validate.js" ></script>



<script type="text/javascript">

function scramble(m)
{
	var k = "{key}";

	// encode the string
	m = urlencode (m);
	
	// encrypt the string
	var r = des(k,m,1,0,null);
	var s = encodeBase64(r);
	
	return s;
}

</script>

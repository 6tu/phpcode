<?php
$str = file_get_contents('OperaMini.app.bak');
$hex = bin2hex ($str);
$cnkey =bin2hex ( 'jGDSpoEfhTZq8jGuQWgxsJQJthTpz6j96NhXfokmNuDgt6FR+WAbkwv1J+qKIr/m+19yUGvT6Bs7VdGJrxfjWy1+ph2Euk5izxwBeJ7bLD88APw8Ce4fyWJzZylHJ+Uq9MmQUW2Neq1OANarUM2MpjcF3wryQ+Zm2tKC1lFLZWeA4QjVkc94kg973uIe0UGaCAZVyirNrcTmTboBtazPcw==');

$sskey = bin2hex ('wd16t34slndG/hBoECbJIPhkgRMhvLi+a7+loD/aThbJyNs68oD3cDNm53jpPFXnFZqIUtKxOB5SGjN/IrFAbN30GjEUrstPS/554MWqK6iCT8mJy4vcv47FzvUXa/1AWfIpuRv6AlEmspX5xAnnX29kFe4JT9f139OVofQxZoxaCOiN6JHcTdONTpqpucANxgSgQo46paKMz6da8JkUew==');
$hex = str_replace($cnkey,$sskey,$hex);



$cnser = bin2hex('mini5cn.opera-mini.net:80');
$myser = bin2hex('yourshell.info:80/opm.php');
$hex = str_replace($cnser,$myser,$hex);
$bin = hex2bin($hex);

file_put_contents('OperaMini.app',hex2bin($hex));

function hex2bin($data){
$len = strlen($data);
return pack('H*',$data);
}
?>
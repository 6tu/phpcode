<?php if(!defined("FF"))die('FF');
/*******************************************************************************************************
*
* author:vking
* updatetime:2010-07-13 
* 此类前身为QQ-ZONE彩色点阵汉字，经原作者完善后发布。
* 调用说明：	
* 代码中FF为网站根目录路径 通过define("FF",str_replace("\\", "/",dirname(__FILE__))."/");设置
* $ps=new lattice();//实例化
* $ps->set($array);//通过关联数组设置类的成员变量，如$ps->set(array('line_num'=>4));//设置一行显示4个字。
* 					数组键值必须是类的成员变量名，否则设置不起作用
* $ps->change(\$valstr);//返回$valstr被点阵化并渲染后的一个串，直接显示此串就可以。$valstr也可通过set方式设置。
* 点阵字库目前有12,16,24(多种字体)3种，http://t.800shang.com/hzk/HZK16 下载请右击目标另存为(HZK12 HZK24H...)
* 07-16增加12 24 字库
* 转置回调函数
* function convert_reback() {
	$args=func_get_args(); 
	if(!$args) return;
	$do="\$a=array(";
	for($i=0;$i<count($args);$i++){
		$do.=$args[$i];
		$do.=(($i+1)<count($args))?",":");";
	}
	@eval($do);
	return ($a);
 }
*
********************************************************************************************************/
class lattice{
	var $ff;
	var $ff_w;
	var $ff_h;
	var $dot_string;
	var $line_num;
	var $style_type_1;//前景类型
	var $style_type_2;//背景类型
	var $style_color_1;//前景色才
	var $style_color_2;//背景色彩
	var $font_color_1;//前景文字
	var $font_color_2;//背景文字
	var $valstr;//转换字串
	function lattice(){
	
	}
	function set($arr){
		foreach($arr as $k=>$v){
			$this->$k=(($v==="0")?"_":$v);
		}
	}
	function init(){
		if(!$this->ff){
			$this->ff=FF."hzk/HZK16";
			$this->ff_w=$this->ff_h=16;		
		}
		//前景判断
		$this->style_color_1=$this->style_color_1?$this->style_color_1:$this->rand_color();
		//背景判断
		$this->style_color_2=$this->style_color_2?$this->style_color_2:$this->rand_color();
		
		$this->font_color_1=$this->font_color_1?$this->font_color_1:"〓";
		$this->font_color_2=$this->font_color_2?$this->font_color_2:"￣";
		$this->font_color_1="<font color=\"#FFFFFF\">{$this->font_color_1}</font>";
		$this->font_color_2="<font color=\"#FFFFFF\">{$this->font_color_2}</font>";
		
		$this->line_num=$this->line_num?$this->line_num:5;
	}
	function change($str=''){
		$str=$str?$str:$this->valstr;
		$str=iconv("UTF-8","GBK",$str);
		$this->init();
		$fp = fopen($this->ff, "rb");
		$font_size=$this->ff_w * $this->ff_h;
		$offset_size = $font_size / 8;
		for ($i = $strlen = 0; $i < strlen($str); $i ++,$strlen++){
			if (ord($str{$i}) > 160){
				// 先求区位码，然后再计算其在区位码二维表中的位置，进而得出此字符在文件中的偏移
				if($this->ff_w<24){
					$offset = ((ord($str{$i}) - 0xa1) * 94 + ord($str{$i + 1}) - 0xa1) * $offset_size;
				}else{//大字体位置计算不同于24以下的字体
					$offset = ((ord($str{$i}) - 176) * 94 + ord($str{$i + 1}) - 161) * $offset_size;
				}
				$i ++;	
			}else{
				$offset = (ord($str{$i}) + 156 - 1) * $offset_size;
			}
			// 读取其点阵数据
			fseek($fp, $offset, SEEK_SET);
			$bindot[$i] = fread($fp, $offset_size);
			for ($j = 0; $j < $offset_size; $j ++){
				// 将二进制点阵数据转化为字符串
				if($this->ff_w<24){
					$this->dot_string[$i][(int)($j/($this->ff_w/8))].=sprintf("%08b", ord($bindot[$i][$j]));
				}else{//24点以上字库需要转置数组，所以这里以数组方式存储
					$tar=str_split(sprintf("%08b", ord($bindot[$i][$j])),1);
					$this->dot_string[$i][(int)($j/($this->ff_w/8))]=$this->dot_string[$i][(int)($j/($this->ff_w/8))]?
					array_merge($this->dot_string[$i][(int)($j/($this->ff_w/8))],$tar):$tar;
				}
			}
		}
		fclose($fp);
		$this->convert_array();
		$this->bianxing();
		return $this->rendering();
	}
	function rendering(){//渲染
		foreach($this->dot_string as $index=>$font){
			foreach($font as $k=>$v){
				$v=is_array($v)?implode("",$v):$v;//大字体是数组，在这里转为字符串以显示
				if($this->line_num<2 & $this->ff_h==12){
					$v=substr($v,0,12);
				}
				$this->set_color();
				$str.=strtr($v,array(1=>$this->font_color_1,0=>$this->font_color_2))."<br />";
			}
		}
		return $str;
	}
	function bianxing(){
		if($this->line_num<2){return;}
		$this->dot_string=array_values($this->dot_string);
		$arr=array();
		foreach($this->dot_string as $index=>$font){
			$newkey=(int)(($index)/($this->line_num));
			foreach($font as $k=>$v){
				$v=is_array($v)?implode("",$v):$v;//大字体是数组，在这里转为字符串
				if($this->ff_h==12){
					$v=substr($v,0,12);
				}
				$arr[$newkey][$k].=$v;
			}
		}
		$this->dot_string=$arr;
	}
	function set_color(){
		//前景文字
		if($this->style_type_1==1){//纯色
			//$this->font_color_1=eregi_replace('#[^"]+',$this->style_color_1,$this->font_color_1);
			$this->font_color_1=preg_replace('/#[^"]+/i',$this->style_color_1,$this->font_color_1);
		}elseif($this->style_type_1==2){//随机
			//$this->font_color_1=eregi_replace('#[^"]+',$this->rand_color(),$this->font_color_1);
			$this->font_color_1=preg_replace('/#[^"]+/i',$this->rand_color(),$this->font_color_1);
		}else{//渐变
			$this->get_jb_color(1);
			//$this->font_color_1=eregi_replace('#[^"]+',$this->style_color_1,$this->font_color_1);
			$this->font_color_1=preg_replace('/#[^"]+/i',$this->style_color_1,$this->font_color_1);
		}
		//背景文字
		if($this->style_type_2==1){//纯色
			//$this->font_color_2=eregi_replace('#[^"]+',$this->style_color_2,$this->font_color_2);
			$this->font_color_2=preg_replace('/#[^"]+/i',$this->style_color_2,$this->font_color_2);
		}elseif($this->style_type_2==2){//随机
			//$this->font_color_2=eregi_replace('#[^"]+',$this->rand_color(),$this->font_color_2);
			$this->font_color_2=preg_replace('/#[^"]+/i',$this->rand_color(),$this->font_color_2);
		}else{//渐变
			$this->get_jb_color(2);
			//$this->font_color_2=eregi_replace('#[^"]+',$this->style_color_2,$this->font_color_2);
			$this->font_color_2=preg_replace('/#[^"]+/i',$this->style_color_2,$this->font_color_2);
		}
	}
	function get_jb_color($type){
		$style_color="style_color_{$type}";
		$c=str_replace("#","",$this->$style_color);
		$c=str_split($c,2);
		$c[0]=hexdec($c[0]);
		$c[1]=hexdec($c[1]);
		$c[2]=hexdec($c[2]);
		$num=abs(10-count($this->dot_string));
		if($type==1){
			if($c[2]-$num<0){
				if($c[1]-$num<0){
					$c[0]=abs($c[0]-$num);
				}else{
					$c[1]=$c[1]-$num;
				}	
			}else{
				$c[2]=$c[2]-$num;
			}
		}else{
			if($c[2]+$num>255){
				if($c[1]+$num>255){
					$c[0]=($c[0]+$num>255)?$c[0]:$c[0]+$num;
				}else{
					$c[1]=$c[1]+$num;
				}	
			}else{
				$c[2]=$c[2]+$num;
			}
		}
		$this->$style_color="#".sprintf("%02X",$c[0]).sprintf("%02X",$c[1]).sprintf("%02X",$c[2]);
	}
	function rand_color(){
		$r=rand(0,255)+1;
		$g=255-$r;
		$b=ceil((255+$r)/2);
		return "#".sprintf("%02X",$r).sprintf("%02X",$g).sprintf("%02X",$b);
	}
	//2010-7-15 大字体需要进行转置，不然显示出来的字是倒着的
	function convert_array(){
		if($this->ff_w<24) return;
		
		foreach($this->dot_string as $i=>$font){
			$do="\$b = array_map('convert_reback', ";//构造array_map的参数
			foreach($font as $k=>$v){
				$do.=var_export($v,true);
				$do.=(($k+1)<count($font))?",":");";
			}
			@eval($do);//执行这个命令，调用convert_reback进行数组转置
			$this->dot_string[$i]=$b;
		}
		
	}
	
	
}	
?>

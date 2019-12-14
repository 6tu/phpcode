<?php if(!defined("FF"))die('FF');
/*******************************************************************************************************
*
* author:vking
* updatetime:2010-07-13 
* ����ǰ��ΪQQ-ZONE��ɫ�����֣���ԭ�������ƺ󷢲���
* ����˵����	
* ������FFΪ��վ��Ŀ¼·�� ͨ��define("FF",str_replace("\\", "/",dirname(__FILE__))."/");����
* $ps=new lattice();//ʵ����
* $ps->set($array);//ͨ����������������ĳ�Ա��������$ps->set(array('line_num'=>4));//����һ����ʾ4���֡�
* 					�����ֵ��������ĳ�Ա���������������ò�������
* $ps->change(\$valstr);//����$valstr�����󻯲���Ⱦ���һ������ֱ����ʾ�˴��Ϳ��ԡ�$valstrҲ��ͨ��set��ʽ���á�
* �����ֿ�Ŀǰ��12,16,24(��������)3�֣�http://t.800shang.com/hzk/HZK16 �������һ�Ŀ�����Ϊ(HZK12 HZK24H...)
* 07-16����12 24 �ֿ�
* ת�ûص�����
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
	var $style_type_1;//ǰ������
	var $style_type_2;//��������
	var $style_color_1;//ǰ��ɫ��
	var $style_color_2;//����ɫ��
	var $font_color_1;//ǰ������
	var $font_color_2;//��������
	var $valstr;//ת���ִ�
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
		//ǰ���ж�
		$this->style_color_1=$this->style_color_1?$this->style_color_1:$this->rand_color();
		//�����ж�
		$this->style_color_2=$this->style_color_2?$this->style_color_2:$this->rand_color();
		
		$this->font_color_1=$this->font_color_1?$this->font_color_1:"��";
		$this->font_color_2=$this->font_color_2?$this->font_color_2:"��";
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
				// ������λ�룬Ȼ���ټ���������λ���ά���е�λ�ã������ó����ַ����ļ��е�ƫ��
				if($this->ff_w<24){
					$offset = ((ord($str{$i}) - 0xa1) * 94 + ord($str{$i + 1}) - 0xa1) * $offset_size;
				}else{//������λ�ü��㲻ͬ��24���µ�����
					$offset = ((ord($str{$i}) - 176) * 94 + ord($str{$i + 1}) - 161) * $offset_size;
				}
				$i ++;	
			}else{
				$offset = (ord($str{$i}) + 156 - 1) * $offset_size;
			}
			// ��ȡ���������
			fseek($fp, $offset, SEEK_SET);
			$bindot[$i] = fread($fp, $offset_size);
			for ($j = 0; $j < $offset_size; $j ++){
				// �������Ƶ�������ת��Ϊ�ַ���
				if($this->ff_w<24){
					$this->dot_string[$i][(int)($j/($this->ff_w/8))].=sprintf("%08b", ord($bindot[$i][$j]));
				}else{//24�������ֿ���Ҫת�����飬�������������鷽ʽ�洢
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
	function rendering(){//��Ⱦ
		foreach($this->dot_string as $index=>$font){
			foreach($font as $k=>$v){
				$v=is_array($v)?implode("",$v):$v;//�����������飬������תΪ�ַ�������ʾ
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
				$v=is_array($v)?implode("",$v):$v;//�����������飬������תΪ�ַ���
				if($this->ff_h==12){
					$v=substr($v,0,12);
				}
				$arr[$newkey][$k].=$v;
			}
		}
		$this->dot_string=$arr;
	}
	function set_color(){
		//ǰ������
		if($this->style_type_1==1){//��ɫ
			//$this->font_color_1=eregi_replace('#[^"]+',$this->style_color_1,$this->font_color_1);
			$this->font_color_1=preg_replace('/#[^"]+/i',$this->style_color_1,$this->font_color_1);
		}elseif($this->style_type_1==2){//���
			//$this->font_color_1=eregi_replace('#[^"]+',$this->rand_color(),$this->font_color_1);
			$this->font_color_1=preg_replace('/#[^"]+/i',$this->rand_color(),$this->font_color_1);
		}else{//����
			$this->get_jb_color(1);
			//$this->font_color_1=eregi_replace('#[^"]+',$this->style_color_1,$this->font_color_1);
			$this->font_color_1=preg_replace('/#[^"]+/i',$this->style_color_1,$this->font_color_1);
		}
		//��������
		if($this->style_type_2==1){//��ɫ
			//$this->font_color_2=eregi_replace('#[^"]+',$this->style_color_2,$this->font_color_2);
			$this->font_color_2=preg_replace('/#[^"]+/i',$this->style_color_2,$this->font_color_2);
		}elseif($this->style_type_2==2){//���
			//$this->font_color_2=eregi_replace('#[^"]+',$this->rand_color(),$this->font_color_2);
			$this->font_color_2=preg_replace('/#[^"]+/i',$this->rand_color(),$this->font_color_2);
		}else{//����
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
	//2010-7-15 ��������Ҫ����ת�ã���Ȼ��ʾ���������ǵ��ŵ�
	function convert_array(){
		if($this->ff_w<24) return;
		
		foreach($this->dot_string as $i=>$font){
			$do="\$b = array_map('convert_reback', ";//����array_map�Ĳ���
			foreach($font as $k=>$v){
				$do.=var_export($v,true);
				$do.=(($k+1)<count($font))?",":");";
			}
			@eval($do);//ִ������������convert_reback��������ת��
			$this->dot_string[$i]=$b;
		}
		
	}
	
	
}	
?>

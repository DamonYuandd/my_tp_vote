<?php
//ִ�к���
	set_time_limit(900000);
	@$url=$_GET["url"];
	if(@$_GET["url"])
	{ 
	   $type1=explode(".",$url);
	   $type=$type1[count($type1)-1];
	   $die="��Ч";
	 //  $type = fileext($url);
	   
	   switch($type)
	   {
		 case 'gif':$im= @imagecreatefromgif($url) or die($die);break;
		 case 'jpg':$im=@imagecreatefromjpeg($url) or die($die);break;
		 case 'png':$im=@imagecreatefrompng($url) or die($die);break;
		 default:  $im = @imagecreatefromjpeg($url);
		} 
	  $src_w=imagesx($im);	//��ȡԭͼ���
	  $src_h=imagesy($im);	//��ȡԭͼ�߶�
	  
	  if(!@$_GET['pw'] && !@$_GET['ph'])
	  {
		$_GET['pw'] = 400;
		$_GET['ph'] = 400;	
	  }
	  $width = $_GET['pw'];	//Ҫ����
	  $height = $_GET['ph'];	//Ҫ��߶�
	  $dst_h = $height;
	  $dst_w = $width;
	  $x = $y = 0;
	   /**
	     * ����ͼ������Դͼ�ߴ�
	     */

	    if($width> $src_w)	//ָ���ߴ��ȴ���ԭͼ
	    {
	        $dst_w = $width = $src_w;
	    }
	    if($height> $src_h)	//ָ���ߴ�߶ȴ���ԭͼ
	    {
	        $dst_h = $height = $src_h;
	    }		
		if($dst_w && $dst_h)	
	            {	
	                if($dst_w/$src_w> $dst_h/$src_h)	//Ҫ�����
	                {	
						
						if($_GET['pw'] >= $dst_w){	//ԭͼ����Ҫ��
							$dst_w = $src_w * ($dst_h / $src_h);
						//	$x = 0 - ($dst_w - $width) / 2;
							$x = ($_GET['pw'] - $dst_w)/2;
							
						}else{
							$dst_w = $src_w * ($dst_h / $src_h);
							$x = ($_GET['pw'] - $dst_w)/2;
							$y = ($_GET['ph'] - $dst_h)/2;
						}
						
						
						
	                }
	                else	//Ҫ������
	                {
						if($_GET['ph'] < $dst_h){	//ԭͼ����Ҫ��
							 $dst_h = $src_h * ($dst_w / $src_w);
							 $y = 0 - ($dst_h - $height) / 2;
							 
						}else{
							$dst_h = $src_h * ($dst_w / $src_w);
							$x = ($_GET['pw'] - $dst_w)/2;
							$y = ($_GET['ph'] - $dst_h)/2;
						}
	                   
	                }
	            }
			
	  //$newim = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
	  $newim = imagecreatetruecolor($_GET['pw'],$_GET['ph']);
	  $white = imagecolorallocate($newim, 255, 255, 255);
	  imagefill($newim, 0, 0, $white);
	  header("content-type:image/png");
	  imagecopyresampled($newim,$im, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h) or die("ͼƬ�����޷�����");
	  imagepng($newim);
	}else{
		return false;
	}
?>
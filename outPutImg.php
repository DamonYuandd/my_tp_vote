<?php
//执行函数
	set_time_limit(900000);
	@$url=$_GET["url"];
	if(@$_GET["url"])
	{ 
	   $type1=explode(".",$url);
	   $type=$type1[count($type1)-1];
	   $die="无效";
	 //  $type = fileext($url);
	   
	   switch($type)
	   {
		 case 'gif':$im= @imagecreatefromgif($url) or die($die);break;
		 case 'jpg':$im=@imagecreatefromjpeg($url) or die($die);break;
		 case 'png':$im=@imagecreatefrompng($url) or die($die);break;
		 default:  $im = @imagecreatefromjpeg($url);
		} 
	  $src_w=imagesx($im);	//获取原图宽度
	  $src_h=imagesy($im);	//获取原图高度
	  
	  if(!@$_GET['pw'] && !@$_GET['ph'])
	  {
		$_GET['pw'] = 400;
		$_GET['ph'] = 400;	
	  }
	  $width = $_GET['pw'];	//要求宽度
	  $height = $_GET['ph'];	//要求高度
	  $dst_h = $height;
	  $dst_w = $width;
	  $x = $y = 0;
	   /**
	     * 缩略图不超过源图尺寸
	     */

	    if($width> $src_w)	//指定尺寸宽度大于原图
	    {
	        $dst_w = $width = $src_w;
	    }
	    if($height> $src_h)	//指定尺寸高度大于原图
	    {
	        $dst_h = $height = $src_h;
	    }		
		if($dst_w && $dst_h)	
	            {	
	                if($dst_w/$src_w> $dst_h/$src_h)	//要求横向
	                {	
						
						if($_GET['pw'] >= $dst_w){	//原图大于要求
							$dst_w = $src_w * ($dst_h / $src_h);
						//	$x = 0 - ($dst_w - $width) / 2;
							$x = ($_GET['pw'] - $dst_w)/2;
							
						}else{
							$dst_w = $src_w * ($dst_h / $src_h);
							$x = ($_GET['pw'] - $dst_w)/2;
							$y = ($_GET['ph'] - $dst_h)/2;
						}
						
						
						
	                }
	                else	//要求竖向
	                {
						if($_GET['ph'] < $dst_h){	//原图大于要求
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
	  imagecopyresampled($newim,$im, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h) or die("图片区域无法复制");
	  imagepng($newim);
	}else{
		return false;
	}
?>
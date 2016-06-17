<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- layout::Home:Inc:script::0 -->
</head>
<body style="background:url(__HOME__/images/bg4.jpg) center top no-repeat;">
<!-- layout::Home:Inc:header::0 -->
<div id="banner_page_img"><img src="__HOME__/images/t8.png" width="1040" height="65" /></div>
<div id="banner_page_add">
 <?php echo Y('Category',array('style'=>'left','model'=>'Category','tplNum' => 'Home','userNum' => $USER_CNUM));?>
<div id="page_center">
  <div class="page_right">
  <?php echo Y('List',array('style'=>'goods_news','model'=>'Goods','rowpage'=>8,'tplNum' => 'Home','userNum' => $USER_CNUM));?>
  <div style="clear:both;"></div>
</div>
<!-- layout::Home:Inc:footer::0 -->
</body>
</html>
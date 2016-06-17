<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- layout::Home:Inc:script::0 -->
<script type="text/javascript" src="__HOME__/js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="__HOME__/js/lightbox/themes/default/jquery.lightbox.css" />
<!--[if IE 6]>
  <link rel="stylesheet" type="text/css" href="__HOME__/js/lightbox/themes/default/jquery.lightbox.ie6.css" />
  <![endif]-->
<script type="text/javascript" src="__HOME__/js/lightbox/jquery.lightbox.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($){
      $('.lightbox').lightbox();
    });
  </script>
</head>
<body style="background:url(__HOME__/images/bg4.jpg) center top no-repeat;">
<!-- layout::Home:Inc:header::0 -->
<div id="banner_page_img"><img src="__HOME__/images/t8.png" width="1040" height="65" /></div>
<div id="banner_page_add">
   <?php echo Y('Category',array('style'=>'left','model'=>'Category','tplNum' => 'Home','userNum' => $USER_CNUM));?>
<div id="page_center">
  <div class="page_right">
    <div class="page_right_concent">
    <?php echo Y('One',array('style'=>'product_show','model'=>'Goods','tplNum' => 'Home'));?>
      <div class="pro_r">
      <?php echo Y('List',array('style'=>'is_top_goods','model'=>'Goods','rowpage'=>20,'tplNum' => 'Home','userNum' => $USER_CNUM,'isTop'=>1));?>
        </div>
      <div style="clear:both;"></div>
    </div>
  </div>
  <div style="clear:both;"></div>
</div>
<!-- layout::Home:Inc:footer::0 -->
</body>
</html>
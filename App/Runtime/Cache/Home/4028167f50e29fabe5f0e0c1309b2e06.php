<?php if (!defined('THINK_PATH')) exit();?><?php 
	$goodsImgs = selectGoodsPhoto();	//产品图片
    $num = count($goodsImgs);
?>
      <div class="pro_l">
        <p align="center" style="font-size:20px; font-weight:bold; color:#FF9900; line-height:40px;"><?php echo ($obj["title"]); ?> </p>
        <p>
        <div class="pro_imgss">
          <!--DEMO start-->
          <p style="width:760px; height:500px; margin:0 auto;">
          <?php if(is_array($goodsImgs)): $i = 0; $__LIST__ = $goodsImgs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="__PUBLIC__/images/product/m_<?php echo ($vo["image"]); ?>" class="lightbox" rel="group1" title="<?php echo ($obj["title"]); ?>" <?php if(($i)  !=  "1"): ?>style="visibility:hidden;"<?php endif; ?>>
          <img src="__ROOT__/outPutImg.php?url=http://<?php echo $_SERVER['HTTP_HOST']; ?>/__PUBLIC__/images/product/m_<?php echo ($vo["image"]); ?>&pw=760&ph=476" width="760" height="476" alt=""/></a><?php endforeach; endif; else: echo "" ;endif; ?>
          </p>
          <!--DEMO end-->
        </div>
        </p>
         <?php echo (htmlspecialchars_decode($obj["content"])); ?>
      </div>
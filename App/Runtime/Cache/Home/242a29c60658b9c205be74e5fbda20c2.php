<?php if (!defined('THINK_PATH')) exit();?> <div class="page_right_concent">
     <?php if(is_array($dataList)): $i = 0; $__LIST__ = $dataList;if( count($__LIST__)==0 ) : echo "没有相关信息" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="pro_box">
        <div class="pro_box_img"><a href="__APP__/<?php echo MODULE_NAME; ?>/show/cid/<?php echo ($vo["category_id"]); ?>/id/<?php echo ($vo["id"]); ?>.html" target="_blank" title="<?php echo ($vo["title"]); ?>">
		<img src="__ROOT__/outPutImg.php?url=http://<?php echo $_SERVER['HTTP_HOST']; ?>/__PUBLIC__/images/product/s_<?php echo ($vo["image"]); ?>&pw=220&ph=170" width="500" height="376" />
		</a></div>
        <div class="pro_box_d"><a href="__APP__/<?php echo MODULE_NAME; ?>/show/cid/<?php echo ($vo["category_id"]); ?>/id/<?php echo ($vo["id"]); ?>.html" target="_blank" title="<?php echo ($vo["title"]); ?>"><?php echo (str_cut($vo["title"],0,12)); ?></a></div>
      </div><?php endforeach; endif; else: echo "没有相关信息" ;endif; ?>
     

      <div style="clear:both;"></div>
    </div>
    <div class="news_next">
         <?php echo ($pageBar); ?>
      </div>
  </div>
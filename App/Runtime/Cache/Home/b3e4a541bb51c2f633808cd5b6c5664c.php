<?php if (!defined('THINK_PATH')) exit();?><?php if(is_array($dataList)): $i = 0; $__LIST__ = $dataList;if( count($__LIST__)==0 ) : echo "没有相关信息" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="pro_box">
          <div class="pro_box_img"><a href="__APP__/<?php echo MODULE_NAME; ?>/show/cid/<?php echo ($vo["category_id"]); ?>/id/<?php echo ($vo["id"]); ?>.html" >
          <img src="__PUBLIC__/images/product/m_<?php echo ($vo["image"]); ?>" width="500" height="376" /></a>
          </div>
          <div class="pro_box_d"><a href="__APP__/<?php echo MODULE_NAME; ?>/show/cid/<?php echo ($vo["category_id"]); ?>/id/<?php echo ($vo["id"]); ?>.html" ><?php echo (str_cut($vo["title"],0,20)); ?></a></div>
        </div><?php endforeach; endif; else: echo "没有相关信息" ;endif; ?>
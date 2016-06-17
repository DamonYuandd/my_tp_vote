<?php if (!defined('THINK_PATH')) exit();?><div class="weibo">
    <!-- JiaThis Button BEGIN -->
    <div class="jiathis_style"> <a class="jiathis_button_qzone"></a> <a class="jiathis_button_tsina"></a> <a class="jiathis_button_tqq"></a> <a class="jiathis_button_renren"></a> <a class="jiathis_button_kaixin001"></a> <a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a> <a class="jiathis_counter_style"></a> </div>
    <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js?uid=1374890429635147" charset="utf-8"></script>
    <!-- JiaThis Button END -->
  </div>
  您当前所在的位置：<a href="__APP__">首页</a> > <a href="__APP__/<?php echo ($location[1]['alias']); ?>"><?php echo ($location[1]['title']); ?></a> > <?php echo ($location[2]['title']); ?></div>
<div id="nav_line">
  <ul>
   <?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><?php $url = __APP__.'/'.MODULE_NAME.'/index/cid/'.$vo['id'].'.html';?>
     <?php if(($_GET['cid'])  ==  $vo['id']): ?><li><a href="<?php echo ($url); ?>"  class="cur"><?php echo (str_cut($vo["title"],0,6)); ?></a></li>
     <?php else: ?>
     	 <li><a href="<?php echo ($url); ?>" ><?php echo (str_cut($vo["title"],0,6)); ?></a></li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
  </ul>
</div>
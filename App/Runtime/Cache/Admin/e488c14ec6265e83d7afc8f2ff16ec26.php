<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>默认左侧菜单</title>

<link rel="stylesheet" href="__ADMIN__/Public/css/base.css" type="text/css" />
<script src="__ADMIN__/Public/js/jquery-1.7.1.min.js"></script>

<style type="text/css">
body{background-color:#FFFFFF;}
#sidemenu{background-color:#feffff;}
#sidemenu ul {font-size: 12px;line-height: 20px;}
#sidemenu li {position: relative;border-bottom: 1px solid #DCE7F0;}
#sidemenu a {display: block;color: #596677;padding: 9px 26px 9px 15px;border-top: 1px solid #F6F7F9;border-bottom: 1px solid #F6F7F9;text-decoration: none;}
#sidemenu a img {margin-bottom: -4px;margin-right: 9px;}
#sidemenu a:hover,.checked-action {text-decoration: none;background:#e4f1ff;color: #3F4C59;border-top: 1px solid #DCE7F0;border-bottom: 1px solid #DCE7F0;}
#sidemenu .submenu li{border-bottom: none;}
#sidemenu .submenu a {padding: 5px 12px 5px 30px;}
#sidemenu .submenu .submenu a {padding: 5px 12px 5px 60px;}
.submenu {padding: 0px;padding-bottom: 6px;display: none;}
.subtitle .action .arrow {position: absolute;right: 10px;top: 18px;}
.fixed{background-color:#ffffff;}
.fixed a{font-weight:bold;}
</style>

<script>
$(document).ready(function () {

	$("#sidemenu li.subtitle a.action").toggle(
	  function () {
		  $(this).addClass('checked-action');
		  $(this).siblings("ul").show();
	  }, 
	  function () {
		  $(this).removeClass('checked-action');
		  $(this).siblings("ul").hide();
	  }
	);

	$('.openMain').click(function(){
		parent.toURLIframe($(this).attr('href'));
		return false;
	});

	if('<?php echo $_GET["cid"];?>'=='lang') {
		parent.closeLayout('west');
	}
	
});
</script>

</head>
<body>
<div id="sidemenu">
  <ul>
  	<?php if(($module)  !=  "Guestbook"): ?><?php if(is_array($dataList)): $i = 0; $__LIST__ = $dataList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$one): $mod = ($i % 2 );++$i;?><?php if(empty($one['_child'])): ?><li><a href="<?php getSideMenuUrl($module,$one['id'],$one['burl']);?>/lang/<?php echo ($one["lang"]); ?>" class="openMain"><?php getLangTextSidemenu($_SESSION['hardware'],$one['lang']);?><?php echo ($one["title"]); ?></a></li>
  		<?php else: ?>
  			<li class="subtitle">
            <?php if(($module)  !=  "Market"): ?><a class="action openMain" href="<?php getSideMenuUrl($module,$one['_child'][0]['id'],$one['_child'][0]['burl']);?>/lang/<?php echo ($one["lang"]); ?>"><?php getLangTextSidemenu($_SESSION['hardware'],$one['lang']);?><?php echo ($one["title"]); ?><img src="__ADMIN__/Public/imgs/arrow-down.png" width="7" height="4" alt="arrow" class="arrow"> </a>
                <?php else: ?><!-- 如果是Market特殊处理 -->
                 <?php if($_SESSION['hardware'] == 'pc'): ?><a class="action openMain" href="<?php getSideMenuUrl($module,$one['_child'][0]['id'],$one['_child'][0]['burl']);?>/lang/<?php echo ($one["lang"]); ?>"><?php getLangTextSidemenu($_SESSION['hardware'],$one['lang']);?><?php echo ($one["title"]); ?><img src="__ADMIN__/Public/imgs/arrow-down.png" width="7" height="4" alt="arrow" class="arrow"> </a>
               		  <?php else: ?>
                   <a class="action openMain" href="__APP__/Admin/Market/edit/cid/<?php echo ($one['_child'][0]['id']); ?>/lang/<?php echo ($one["lang"]); ?>"><?php getLangTextSidemenu($_SESSION['hardware'],$one['lang']);?><?php echo ($one["title"]); ?><img src="__ADMIN__/Public/imgs/arrow-down.png" width="7" height="4" alt="arrow" class="arrow"> </a><?php endif; ?><?php endif; ?>
		  		<?php if(is_array($one['_child'])): $i = 0; $__LIST__ = $one['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$two): $mod = ($i % 2 );++$i;?><ul class="submenu" style="display:none;">
		  			<?php if(empty($two['_child'])): ?><?php if(($module)  !=  "Market"): ?><li><a href="<?php getSideMenuUrl($module,$two['id'],$two['burl']);?>/lang/<?php echo ($one["lang"]); ?>" class="openMain"><?php echo ($two["title"]); ?></a></li>
                        <?php else: ?><!-- 如果是Market特殊处理 -->
                        <?php if($_SESSION['hardware'] == 'pc'): ?><li><a href="<?php getSideMenuUrl($module,$two['id'],$two['burl']);?>/lang/<?php echo ($one["lang"]); ?>" class="openMain"><?php echo ($two["title"]); ?></a></li>						<?php else: ?>
                       		<li><a href="__APP__/Admin/Market/edit/cid/<?php echo ($two['id']); ?>/lang/<?php echo ($one["lang"]); ?>" class="openMain"><?php echo ($two["title"]); ?></a></li><?php endif; ?><?php endif; ?>
		  			<?php else: ?>
						<li class="subtitle">
				        	<a class="action openMain" href="<?php getSideMenuUrl($module,$two['id'],$two['burl']);?>/lang/<?php echo ($one["lang"]); ?>" class="openMain"><?php echo ($two["title"]); ?><img src="__ADMIN__/Public/imgs/arrow-down.png" width="7" height="4" alt="arrow" class="arrow"> </a>
				        	<ul class="submenu" style="display:none;">
				        		<?php if(is_array($two['_child'])): $i = 0; $__LIST__ = $two['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$three): $mod = ($i % 2 );++$i;?><li><a href="<?php getSideMenuUrl($module,$three['id'],$three['burl']);?>/lang/<?php echo ($one["lang"]); ?>" class="openMain"><?php echo ($three["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
				        	</ul>
				        </li><?php endif; ?>
		  		</ul><?php endforeach; endif; else: echo "" ;endif; ?>
	  		</li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?><?php endif; ?>
	<?php if(($module)  ==  "Goods"): ?><?php if(($alias)  ==  "Goods"): ?><!-- <li class="fixed"><a href="__APP__/Admin/Goods/inquire/cid/<?php echo $_GET["cid"];?>/lang/<?php echo $_GET["lang"];?>" class="openMain"> 产品询价</a></li> --><?php endif; ?>
		<li class="fixed"><a href="__APP__/Admin/Goods/category/cid/<?php echo $_GET["cid"];?>/lang/<?php echo $_GET["lang"];?>" class="openMain"> 分类管理</a></li>
		<li class="subtitle fixed">
			<a class="action openMain checked-action" href="__APP__/Admin/Goods/isHomeList"> 推荐管理<img src="__ADMIN__/Public/imgs/arrow-down.png" width="7" height="4" alt="arrow" class="arrow"> </a>
	  		<ul class="submenu" style="display: none; ">
	  			<li><a href="__APP__/Admin/Goods/isHomeList/cid/<?php echo $_GET["cid"];?>/lang/<?php echo $_GET["lang"];?>" class="openMain">首页</a></li>
	  			<li><a href="__APP__/Admin/Goods/isTopList/cid/<?php echo $_GET["cid"];?>/lang/<?php echo $_GET["lang"];?>" class="openMain">置顶</a></li>
	  			<li><a href="__APP__/Admin/Goods/isPublish1List/cid/<?php echo $_GET["cid"];?>/lang/<?php echo $_GET["lang"];?>" class="openMain">已发布</a></li>
	  			<li><a href="__APP__/Admin/Goods/isPublish0List/cid/<?php echo $_GET["cid"];?>/lang/<?php echo $_GET["lang"];?>" class="openMain">未发布</a></li>
	  		</ul>
		</li><?php endif; ?>
	<?php if(($module)  ==  "News"): ?><li class="subtitle fixed">
			<a class="action openMain checked-action" href="__APP__/Admin/Goods/isHomeList"> 推荐管理<img src="__ADMIN__/Public/imgs/arrow-down.png" width="7" height="4" alt="arrow" class="arrow"> </a>
	  		<ul class="submenu" style="display: none; ">
	  			<li><a href="__APP__/Admin/News/isHomeList/cid/<?php echo $_GET["cid"];?>/lang/<?php echo $_GET["lang"];?>" class="openMain">首页</a></li>
	  			<li><a href="__APP__/Admin/News/isTopList/cid/<?php echo $_GET["cid"];?>/lang/<?php echo $_GET["lang"];?>" class="openMain">置顶</a></li>
	  			<li><a href="__APP__/Admin/News/isPublish1List/cid/<?php echo $_GET["cid"];?>/lang/<?php echo $_GET["lang"];?>" class="openMain">已发布</a></li>
	  			<li><a href="__APP__/Admin/News/isPublish0List/cid/<?php echo $_GET["cid"];?>/lang/<?php echo $_GET["lang"];?>" class="openMain">未发布</a></li>
	  		</ul>
		</li><?php endif; ?>
	<?php if(($module)  ==  "Market"): ?><li class="fixed"><a href="__APP__/Admin/Market/category" class="openMain"> 区域管理</a></li><?php endif; ?>
	<?php if(($module)  ==  "System"): ?><li><a href="__APP__/Admin/System/index/cid/base" class="openMain">网站基本信息</a></li>
		<?php
		if( $_SESSION['hardware']=='pc' ) {
			echo '<li><a href="__APP__/Admin/System/index/cid/contact" class="openMain">快捷联系方式</a></li>';
		} else {
			echo '<li><a href="__APP__/Admin/MobileContact/edit/cid/1" class="openMain">快捷联系方式</a></li>';
		}
		?>
		<li><a href="__APP__/Admin/System/index/cid/seo" class="openMain">网站SEO设置</a></li>
		
		<li class="fixed"><a href="__APP__/Admin/System/index/cid/user" class="openMain">网站管理员</a></li><?php endif; ?>
	<?php if(($module)  ==  "Guestbook"): ?><li class="fixed"><a href="__APP__/Admin/Guestbook/index/c_root/21/cid/220/lang/zh-cn" class="openMain"> 留言类型</a></li><?php endif; ?>
	
	
  </ul>
</div>
</body>
</html>
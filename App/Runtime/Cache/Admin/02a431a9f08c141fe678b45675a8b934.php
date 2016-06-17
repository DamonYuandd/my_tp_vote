<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>分类管理</title>
<link rel="stylesheet" type="text/css" href="__ADMIN__/Public/css/base.css" />
<script src="__ADMIN__/Public/js/jquery-1.7.1.min.js"></script>
<script src="__ADMIN__/Public/js/base.js"></script>

<script>
function Data(){
	this._APP_ = "__APP__";
	this.c_root = "<?php echo $_SESSION['c_root']; ?>";
	this.get_cid = "<?php echo $_GET['cid']; ?>"
	this.actionName = "Category";
}
</script>
<script src="__ADMIN__/Public/js/index_category.js"></script>

</head>
<body>
<div class="nav-site"><?php getNavSite($nav_site,2);?> &gt; 分类管理</div>

<form action="" method="post" id="form_list">
<input type="hidden" name="cid" value="<?php echo $_GET["cid"];?>" />
<table class="grid-function" border="0" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th width="600">
				<div class="qw-fl grid-add-data">
					<input type="button" value="添加分类" onclick="goToUrl('__APP__/Admin/Category/editAdd/pid/<?php echo ($_SESSION['c_root']); ?>');" class="button-img-add" />
				</div>
				<div class="qw-fl grid-batch-operate">
					<a href="#" id="on_ordernum" title="数字排序"><img src="__ADMIN__/Public/imgs/sort.png" align="top" /></a>&nbsp;&nbsp;
					<a href="#" id="on_move" title="移动数据"><img src="__ADMIN__/Public/imgs/move.png" align="top" /></a>&nbsp;&nbsp;
					<a href="#" id="on_copy" title="复制一份"><img src="__ADMIN__/Public/imgs/copy.png" align="top" /></a>&nbsp;&nbsp;
				</div>
				<div class="qw-fr">
				</div>
			</th>
		</tr>
	</thead>
	<!-- 移动和复制操作分类选择 -->
	<tbody id="category_box" style="display:none;">
		<tr>
			<td align="left">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="category_lang" id="category_lang">
					<option value="">请选择语言</option>
					<?php echoLangsOption();?>
				</select>
				选择分类
				<select id="one_category_id" name="one_category_id" style="width:150px;" onchange="changeCategory(this,'two_category_id')">
	              <option value="-1" selected="">当前顶级分类</option>
	            </select>
	            <select id="two_category_id" name="two_category_id" style="width:150px;display:none;" onchange="changeCategory(this,'three_category_id')">
	              <option value="" selected="">请选择</option>
	            </select>
	            <select id="three_category_id" name="three_category_id" style="width:150px;display:none;" onchange="changeCategory(this,'')">
	              <option value="" selected="">请选择</option>
	            </select>
	            <input type="submit" value="" class="button button-green" id="category_button" />
			</td>
		</tr>
	</tbody>
</table>
<table class="grid-table" border="1" cellpadding="0" cellspacing="0"> 
	<thead> 
		<tr>
			<th width="15"><input type="checkbox" id="chk_all"></th>
		    <th>分类名称</th>
		    <!--th width="35">数量</th-->
		    <th width="35">排序</th>
		    <th width="25">发布</th>
		    <th width="180">操作</th>
		</tr> 
	</thead> 
	<tbody><?php $num = 0; ?>
		<?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><?php $num++;?>
			<tr>
		      <td><input type="checkbox" name="ids[]" id="ids<?php echo ($vo["id"]); ?>" value="<?php echo ($num); ?>,<?php echo ($vo["id"]); ?>"></td>
		      <td><?php getLangTextSidemenu($_SESSION['hardware'],$vo['lang']);?>&nbsp;<strong><?php echo ($vo["title"]); ?></strong>
			  <?php if(!empty($vo["image"])): ?><img alt="有封面图片" src="__ADMIN__/Public/imgs/gtk-image.png"><?php endif; ?>
			  </td>
		      <!--td><?php echo ($vo["list_count"]); ?></td-->
		      <td><input style="width:35px" name="ordernums[]" id="ordernum<?php echo ($vo["id"]); ?>" value="<?php echo ($vo["ordernum"]); ?>"></td>
		      <td align="center"><?php getCheckboxState($vo['id'],'is_publish',$vo['is_publish']);?></td>
		      <td align="left"><a href="__APP__/Admin/Category/editAdd/id/<?php echo ($vo["id"]); ?>" style="visibility:hidden;">[添加子分类]</a>&nbsp;&nbsp;[<a href="__APP__/Admin/Category/edit/pid/<?php echo ($vo["pid"]); ?>/c_root/<?php echo ($_SESSION['c_root']); ?>/id/<?php echo ($vo["id"]); ?>">编辑</a>]&nbsp;&nbsp;
		      <?php 
		          if($_SESSION['category_action']!='Market') {
		      ?>
		      [<a href="#" onclick="deleteData('__APP__/Admin/Category/deleteCategory/id/<?php echo ($vo["id"]); ?>');">删除</a>]</td>
		      <?php 
		      }
		      ?>
		    </tr>
		    <?php if(is_array($vo['_child'])): $i = 0; $__LIST__ = $vo['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child2): $mod = ($i % 2 );++$i;?><?php $num++;?>
				<tr>
			      <td><input type="checkbox" name="ids[]" id="ids<?php echo ($child2["id"]); ?>" value="<?php echo ($num); ?>,<?php echo ($child2["id"]); ?>"></td>
			      <td><strong><span style="color:#ddd">|--</strong></span><?php getLangTextTitle($child2['lang']);?>&nbsp;<?php echo ($child2["title"]); ?>
				  <?php if(!empty($child2["image"])): ?><img alt="有封面图片" src="__ADMIN__/Public/imgs/gtk-image.png"><?php endif; ?>
				  </td>
			      <!--td><?php echo ($child2["list_count"]); ?></td-->
			      <td><input style="width:35px" name="ordernums[]" id="ordernum<?php echo ($child2["id"]); ?>" value="<?php echo ($child2["ordernum"]); ?>"></td>
			      <td align="center"><?php getCheckboxState($child2['id'],'is_publish',$child2['is_publish']);?></td>
			      <td align="left">
			       <?php 
                      if($_SESSION['category_action']!='Market') {
                  ?>
			      [<a href="__APP__/Admin/Category/editAdd/id/<?php echo ($child2["id"]); ?>">添加子分类</a>]
			      <?php 
                  }
                  ?>
			      &nbsp;&nbsp;[<a href="__APP__/Admin/Category/edit/pid/<?php echo ($child2["pid"]); ?>/c_root/<?php echo ($_SESSION['c_root']); ?>/id/<?php echo ($child2["id"]); ?>">编辑</a>]&nbsp;&nbsp;
			      [<a href="#" onclick="deleteData('__APP__/Admin/Category/deleteCategory/id/<?php echo ($child2["id"]); ?>');">删除</a>]</td>
			    </tr>
			    <?php if(is_array($child2['_child'])): $i = 0; $__LIST__ = $child2['_child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child3): $mod = ($i % 2 );++$i;?><?php $num++;?>
					<tr>
				      <td><input type="checkbox" name="ids[]" id="ids<?php echo ($child3["id"]); ?>" value="<?php echo ($num); ?>,<?php echo ($child3["id"]); ?>"></td>
				      <td><strong><span style="color:#ddd">|--|--</strong></span><?php getLangTextTitle($child3['lang']);?>&nbsp;<?php echo ($child3["title"]); ?>
					  <?php if(!empty($child3["image"])): ?><img alt="有封面图片" src="__ADMIN__/Public/imgs/gtk-image.png"><?php endif; ?>
					  </td>
				      <!--td><?php echo ($child3["list_count"]); ?></td-->
				      <td><input style="width:35px" name="ordernums[]" id="ordernum<?php echo ($child3["id"]); ?>" value="<?php echo ($child3["ordernum"]); ?>"></td>
				      <td align="center"><?php getCheckboxState($child3['id'],'is_publish',$child3['is_publish']);?></td>
				      <td align="left" style="padding-left:88px">[<a href="__APP__/Admin/Category/edit/pid/<?php echo ($child3["pid"]); ?>/c_root/<?php echo ($_SESSION['c_root']); ?>/id/<?php echo ($child3["id"]); ?>">编辑</a>]&nbsp;&nbsp;[<a href="#" onclick="deleteData('__APP__/Admin/Category/deleteCategory/id/<?php echo ($child3["id"]); ?>');">删除</a>]</td>
				    </tr><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
</table>
</form>


</body>
</html>
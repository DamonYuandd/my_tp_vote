<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>默认数据表格</title>
<link rel="stylesheet" type="text/css" href="__ADMIN__/Public/css/base.css" />
<script src="__ADMIN__/Public/js/jquery-1.7.1.min.js"></script>
<script src="__ADMIN__/Public/js/base.js"></script>
<script src="__ADMIN__/Public/js/97Date/WdatePicker.js"></script>
<link rel="stylesheet" type="text/css" href="__ADMIN__/Public/js/97Date/skin/WdatePicker.css" />

<script>
function Data(){
	this._APP_ = "__APP__";
	this.c_root = "<?php echo $_SESSION['c_root']; ?>";
	this.get_cid = "<?php echo $_GET['cid']; ?>";
	this.get_lang = "<?php echo $_GET['lang']; ?>";
	this.def_lang = "<?php echo $custom['def_lang']; ?>"
	this.rowpage = "<?php echo $rowpage; ?>";
	this.actionName = "<?php echo $actionName;?>";
}
</script>
<script src="__ADMIN__/Public/js/index.js"></script>

</head>
<body>

<div class="nav-site"><?php getNavSite($nav_site,$_GET['cid']);?>网站后台 > 系统管理 > 预约管理</div>

<form action="__APP__/Admin/Reservation/index" method="get" id="form_list">
<table class="grid-function" border="0" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th width="600">
				<div class="qw-fl grid-add-data">
					时间段：
					<input type="text" id="start_time" name="start_time" onClick="WdatePicker()" value="<?php echo (!isset($obj['start_time'])) ? date('Y-m-d') : date('Y-m-d', $obj['start_time']);?>" class="type-text">
					---
					<input type="text" id="end_time" name="end_time" onClick="WdatePicker()" value="<?php echo (!isset($obj['end_time'])) ? date('Y-m-d') : date('Y-m-d', $obj['end_time']);?>" class="type-text">
					<span>预约手机号码：</span>
					<input name="searchKey" id="searchKey" value="<?php echo ($searchKey); ?>"  /> 
					<input type="submit" value="搜索" class="button"  />
				</div>
				<div></div>
				
			</th>
		</tr>
	</thead>
	<!-- 移动和复制操作分类选择 -->
	<!-- layout::Inc:category_lang::0 -->
</table>
<table class="grid-table" border="1" cellpadding="0" cellspacing="0"> 
	<thead> 
		<tr>
			<th width="20">编号</th>
		    <th>预约时间</th>
		    <th width="40">联系人</th>
		    <th width="80">联系号码</th>
		    <th width="75">查看详情</th>
		</tr> 
	</thead>
	<?php if(!empty($dataList)): ?><tbody>
		<?php if(is_array($dataList)): $i = 0; $__LIST__ = $dataList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr> 
			<td><?php echo ($vo["id"]); ?></td>
			<td><?php echo ($vo["reservation_date"]); ?>&nbsp;&nbsp;&nbsp;<?php echo aop($vo['am_or_pm']); ?></td>
		    <td><?php echo ($vo["contact_name"]); ?></td>
		    <td><?php echo ($vo["phone"]); ?></td>
		    <td>
		    <a href="__APP__/Admin/Reservation/edit/id/<?php echo ($vo["id"]); ?>"><img src="__ADMIN__/Public/imgs/edit.png" /></a></td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
	<?php else: ?>
	<tbody>
		<tr>
			<td colspan="11" align="center" style="color:red;">没有找到数据！！！</td>
		</tr>
	</tbody><?php endif; ?>
	<tfoot>
		<tr>
			<td colspan="11">
				<div class="qw-fl" style="height:20px;">
					
				</div>
				<div class="qw-fr">
					<div class="grid-pagination">
					<?php echo ($pageBar); ?>
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
</form>
</body>
</html>
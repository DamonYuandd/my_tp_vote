<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/base.css" />
<script src="__PUBLIC__/js/jquery-1.7.1.min.js"></script>
<script>
function Data(){
	this._APP_ = "__APP__";
	this.rowpage = "<?php echo $rowpage; ?>";
	this.actionName = "<?php echo MODULE_NAME;?>";
}
</script>
<script src="__PUBLIC__/js/index.js"></script>
<title>main</title>
</head>
<body>
<br>
<form action="__APP__/Template" method="post" id="form_list">
<table class="grid-function" border="0" cellpadding="0" cellspacing="0" style="margin-top: 10px">
	<thead>
		<tr>
			<th width="600">
				<div class="qw-fl grid-add-data">
					<input type="button" value="添加模板" onclick="javascript:location.href='__APP__/Template/edit'" class="button-img-add" />
				</div>
				<div class="qw-fl grid-batch-operate">
					<a href="#" id="on_ordernum" title="数字排序"><img src="__PUBLIC__/imgs/sort.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="__APP__/Template/index/hardware/pc" style="text-decoration: underline;color: #03F;">电脑模板</a>&nbsp;&nbsp;
					<a href="__APP__/Template/index/hardware/mobile" style="text-decoration: underline;color: #03F;">手机模板</a>&nbsp;&nbsp;
				</div>
			</th>
		</tr>
	</thead>
</table>

<table class="grid-table" border="1" cellpadding="0" cellspacing="0" width="100%"> 
	<thead> 
		<tr>
			<th width="15"><input type="checkbox" id="chk_all"></th>
			<th width="35">排序</th>
			<th width="50">缩略图</th>
			<th width="60">模板编号</th>
		    <th width="50">属于单位</th>
		    <th>模板类别</th>
		    <th width="40">设计师</th>
		    <th width="40">已使用</th>
			<th width="25">发布</th>
			<th width="50">操作</th>
		</tr> 
	</thead>
	<tbody>
		<?php if(is_array($templateList)): $i = 0; $__LIST__ = $templateList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
			<td align="center"><input type="checkbox" name="ids[]" id="ids<?php echo ($vo["id"]); ?>" value="<?php echo ($i); ?>,<?php echo ($vo["id"]); ?>"></td>
			<td align="center"><input style="width:35px" name="ordernums[]" id="ordernum<?php echo ($vo["id"]); ?>" value="<?php echo ($vo["ordernum"]); ?>"></td>
			<td align="center"><img src="http://img.huyionline.cn/tplimg/<?php echo ($vo["image"]); ?>" width="80" style="margin:5px"></td>
			<td><?php echo ($vo["number"]); ?></td>
			<td align="center"><?php echoUnitTitle($vo['unit']);?></td>
			<td><?php echoCategoryTitle($vo['category_id']);?></td>
			<td><?php echoStylist($vo['number']);?></td>
			<td align="center" style="color: #FF5200;"><?php echoTplUseCount($vo['number'],$hardware);?></td>
			<td align="center"><?php getCheckboxState($vo['id'],'is_publish',$vo['is_publish']);?></td>
		    <td>
				<a href="__APP__/Template/edit/id/<?php echo ($vo["id"]); ?>"><img src="__PUBLIC__/imgs/edit.png" /></a>&nbsp;&nbsp;&nbsp;
				<a href="javascript:;" name="template_delete" id="<?php echo ($vo["id"]); ?>"><img src="__PUBLIC__/imgs/cross.png" /></a>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="11">
				<div class="grid-pagination">
				<?php echo ($pageBar); ?>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
</form>

<script>
$(function(){

	$('a[name=template_delete]').click(function(){
		if (confirm('确定要删除此模板吗？删除后无法恢复！')) {
			var a_elem = $(this);
			var id = a_elem.attr('id');
			window.location.href = '__APP__/Template/delete/id/'+id;
		}
	});
	
});
</script>

</body>
</html>
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/base.css" />
<script src="__PUBLIC__/js/jquery-1.7.1.min.js"></script>
<title>main</title>
</head>
<body>
<br>
<table class="grid-table" border="1" cellpadding="0" cellspacing="0" width="100%"> 
	<thead> 
		<tr>
		    <th><a href="javascript:;" onclick="parent.goToLeftFrame('__APP__/Account/edit/id/<?php echo ($vo["id"]); ?>')">修改网站信息</a></th>
		</tr> 
	</thead>
	<tbody style="display:none;">
		<?php if(is_array($accountList)): $i = 0; $__LIST__ = $accountList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr> 
			<td style="word-break: break-all;"><?php echo ($vo["website_name"]); ?></td>
			<td>
			<?php
				$urls = explode(',',$vo['url']);
				foreach($urls as $val) {
			?>
			<a href="http://<?php echo $val; ?>" target="_blank"><?php echo $val; ?></a><br>
			<?php } ?>
			</td>
		    <td>
		    	<?php echo ($vo["contract_number"]); ?>
			</td>
			<td><?php echo ($vo["yy_price"]); ?></td>
			<td><?php echo ($vo["service_price"]); ?></td>
			<td>
				<?php 
		    	if( date('Y-m-d',time())==date('Y-m-d',$vo['begin_time']) ) {
				?>
				<span style="color:red"><?php echo date('Y-m-d',$vo['begin_time']); ?></span>
				<?php } else { ?>
				<?php echo date('Y-m-d',$vo['begin_time']); ?>
				<?php } ?>
			</td>
		    <td><a href="__APP__/Account/downloadConfig/id/<?php echo ($vo["id"]); ?>" title="导出配置文件">生成</a>&nbsp;&nbsp;&nbsp;<a href="__APP__/Account/syncWebsiteName/id/<?php echo ($vo["id"]); ?>" title="同步网站名称到系统设置">同步</a>&nbsp;&nbsp;&nbsp;<a href="javascript:;" onclick="parent.goToLeftFrame('__APP__/Account/edit/id/<?php echo ($vo["id"]); ?>')"><img src="__PUBLIC__/imgs/edit.png" /></a>&nbsp;&nbsp;&nbsp;<a href="javascript:;" name="account_delete" id="<?php echo ($vo["id"]); ?>"><img src="__PUBLIC__/imgs/cross.png" /></a>
			
			<!--&nbsp;&nbsp;&nbsp;<a href="__APP__/Account/upgradeSite/id/<?php echo ($vo["id"]); ?>">升级</a>--></td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
</table>

<script>
$(function(){
	jQuery('a[name=account_delete]').click(function(){
		if ( confirm('是否要删除此帐号！') ) {
			var a_elem = jQuery(this);
			var id = a_elem.attr('id');
			jQuery.get('__APP__/Account/delete', {
				'id': id
			}, function(result){
				if (result == 'success_delete') {
					a_elem.parent().parent().remove();
				}
				else {
					alert('删除帐号失败');
				}
			});
		}
	});
});
</script>

</body>
</html>
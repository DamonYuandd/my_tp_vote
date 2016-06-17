<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>编辑内容</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/base.css" />
<script src="__PUBLIC__/js/jquery-1.7.1.min.js"></script>
</head>
<body>

<form action="__APP__/Template/save" method="post" enctype="multipart/form-data" class="form">  
<input type="hidden" id="id" name="id" value="<?php echo ($obj["id"]); ?>">
<input type="hidden" name="imgwidth" value="300">
<input type="hidden" name="imgheight" value="300">
   <fieldset style="margin-top: 25px">
       <ul class="align-list">
       	   <li>
               <label>选择终端</label>
			   <input type="radio" name="hardware" value="pc" checked="checked"> 电脑版&nbsp;&nbsp;&nbsp;
			   <input type="radio" name="hardware" value="mobile"> 手机版
           </li>
           <li>
               <label>属于单位</label>
               <input type="radio" name="unit" value="enterprise" checked="checked"> 企业&nbsp;&nbsp;&nbsp;
               <input type="radio" name="unit" value="government"> 政府&nbsp;&nbsp;&nbsp;
               <input type="radio" name="unit" value="school"> 学校
           </li>
		   <li>
			   <label>模板分类 </label>
			   <select name="category_id" id="category_id">
			   	<?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			   </select>
		   </li>
		   <li>
               <label>模板编号</label>
               <input type="text" id="number" name="number" value="<?php echo ($obj["number"]); ?>" class="type-text">
           </li>
           <li>
               <label>设计师</label>
               <select name="stylist" id="stylist">
               	<option value="KEN">KEN</option>
               	<option value="白雪">白雪</option>
               	<option value="梁惠贤">梁惠贤</option>
               	<option value="吴斯斯">吴斯斯</option>
               	<option value="林贵欢">林贵欢</option>
               	<option value="陈濠国">陈濠国</option>
               </select>
           </li>
           <li>
               <label>模板封面</label>
               <?php if( !empty($obj['image']) ) { ?>
               <span id="span_image">
               <img alt="" align="middle" vspace="5" src="http://img.huyionline.cn/tplimg/<?php echo ($obj["image"]); ?>">
               <a href="javascript:void(0)" id="delete_image" style="color:red;text-decoration:underline;">删除封面</a>&nbsp;&nbsp;&nbsp;&nbsp;
               </span>
               <?php } ?>
                <input type="file" name="image">
		   </li>
           <li>
               <label>现在发布<a href="#" class="issue" title="在网站前台显示">?</a></label>
               <input type="checkbox" id="is_publish" name="is_publish" value="1">
           </li>
           <li>
               <label></label>
               <input type="submit" value="确定并保存" name="save" class="button button-green" style="padding: 10px 30px;font-size: 14px;font-weight: bold;" />
            </li>
        </ul>
    </fieldset>
</form>

<script>
$(function(){
	var id = "<?php echo $obj['id']; ?>";
	if(id!='') {
		$('input[name="modules[]"]').attr('checked', false);
		$("input[name=is_publish][value=<?php echo $obj['is_publish']; ?>]").attr('checked',true);
        $("input[name=hardware][value=<?php echo $obj['hardware']; ?>]").attr('checked',true);
        $("input[name=unit][value=<?php echo $obj['unit']; ?>]").attr('checked',true);
        $("#category_id").val("<?php echo $obj['category_id']; ?>");
        $("#stylist").val("<?php echo $obj['stylist']; ?>");
	} else {
		$("input[name=is_publish]").attr('checked',true);
	}

	$('#delete_image').click(function(){
		if( confirm('确定要删除封面吗？') ) {
			$.get('__APP__/Template/deleteImage',{'id':id},function(bool){
				if( bool==1 ) {
					$('#span_image').css('display','none');
				}
			});
		}
	});
	
	$('#delete_file').click(function(){
		if( confirm('确定要删除模板文件吗？') ) {
			$.get('__APP__/Template/deleteFile',{'id':id},function(bool){
				if( bool==1 ) {
					$('#span_tpl_file').css('display','none');
				}
			});
		}
	});
	
});
</script>

</body>
</html>
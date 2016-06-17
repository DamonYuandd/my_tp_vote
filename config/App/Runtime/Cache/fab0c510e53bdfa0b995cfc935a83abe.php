<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/base.css" />
<script src="__PUBLIC__/js/jquery-1.7.1.min.js"></script>
<script src="__PUBLIC__/js/jquery.form.js"></script>
<script src="__PUBLIC__/js/base.js"></script>
<style>
			#navList ol{
				float:left;
			}
			#navList ol li{
				float:left;
				padding:0 7px;
				border:1px #CCCCCC solid;
				margin:0 7px 0 0;
			}
			#navList ol li a{
				color:#555555;
			}
			#selectRadio{
				margin: -24px 0 0 138px; 
			}
			#navList a{
				padding:0 7px;
				color:#555555;
				border:1px #CCCCCC solid;
				margin:7px;
				line-height:25px;
			}
			#navList .null{
				background:#ED6E6E;
				color:#FFF;
				border:1px #ED6E6E solid;
			}
</style>
<title>edit</title>
</head>
<body>
<h3>自定义栏目</h3>
<form class="form">
<fieldset>
<ul class="align-list">
	 <li>
            	<label>语言选择</label>
				<?php if(is_array($langList)): $i = 0; $__LIST__ = $langList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><input type="radio" name="lang" value="<?php echo ($vo["alias"]); ?>" lang="<?php echo ($vo["alias"]); ?>"> <?php echo ($vo["title"]); ?>&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
     </li>
	<li>
    	<label>当前栏目效果：</label><br/>
        <span id="navList">
              <?php if(is_array($part)): $i = 0; $__LIST__ = $part;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><?php if($vo["is_publish"] == 1): ?><label></label><a href="__APP__/Category/addPart/id/<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a>
                                    <?php else: ?>
                                    	<label></label><a href="__APP__/Category/addPart/id/<?php echo ($vo["id"]); ?>" class="null"><?php echo ($vo["title"]); ?></a><?php endif; ?>
                                    <?php if(!empty($vo["next"])): ?>&gt;&gt;
                                    	<?php if(is_array($vo["next"])): $i = 0; $__LIST__ = $vo["next"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tt): $mod = ($i % 2 );++$i;?><?php if($tt["is_publish"] == 1): ?><a class="nextPart" href="__APP__/Category/addPart/id/<?php echo ($tt["id"]); ?>"><?php echo ($tt["title"]); ?></a>
                                             <?php else: ?>
                                             	<a class="null" href="__APP__/Category/addPart/id/<?php echo ($tt["id"]); ?>"><?php echo ($tt["title"]); ?></a><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?><?php endif; ?>
                                    <br/><?php endforeach; endif; else: echo "" ;endif; ?>
                        </span>
    </li>
</ul>
</fieldset>
</form>
<script type="text/javascript">
	$(document).ready(function(){
		$("input[value=zh-cn]").attr("checked","checked");
		$("input[name=lang]").change(function(){
				$lang = $(this).val();
				getPartList($lang);
				})
	})
	//获得当前栏目函数
			function getPartList(){
				$lang = $("input[name=lang]:checked").val();
							$("#navList").html('');
							$.ajax({
								type:'POST',
								url:'__APP__/Category/getPartList',
								dataType:'json',
								data:{
									'lang': $lang
									 },
								success: function(data){
									if(data != 0){
										
										for(i=0;i<data.length;i++){
										if(data[i].is_publish == 0){
											$("#navList").append('<label></label><a class="null" href="__APP__/Category/addPart/id/'+ data[i].id+'"  va="'+data[i].id+'">'+data[i].title+'</a>');
										}else{
											$("#navList").append('<label></label><a href="__APP__/Category/addPart/id/'+ data[i].id+'"  va="'+data[i].id+'">'+data[i].title+'</a>');
										}
										if(data[i].next){
										$("#navList").append('&gt;&gt;');
										for(ii=0;ii<data[i].next.length;ii++){
												if(data[i].next[ii].is_publish == 0){
												$("#navList").append('<a class="null" href="__APP__/Category/addPart/id/'+ data[i].next[ii].id +'" va="'+data[i].next[ii].id+'">'+data[i].next[ii].title+'</a>');
											}
											else{
												$("#navList").append('<a class="nextPart" href="__APP__/Category/addPart/id/'+ data[i].next[ii].id +'" va="'+data[i].next[ii].id+'">'+data[i].next[ii].title+'</a>');
											}
												}
											}
												$("#navList").append('<br/>');
										}	
											}else{
												$("#navList").html('暂无信息');
										}
									}
								});
			}
</script>
</body>
</html>
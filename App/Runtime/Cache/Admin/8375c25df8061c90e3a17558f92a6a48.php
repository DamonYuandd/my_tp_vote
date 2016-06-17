<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>编辑内容</title>
<!-- layout::Inc:edit_page::0 -->
<!-- layout::Inc:ueditor::0 -->

</head>
<body>
<div class="nav-site"><?php getNavSite($nav_site,$_GET['cid']);?> 网站后台 > 系统管理 > 预约管理</div>
<form action="__APP__/Admin/News/<?php echo $obj==null?'add':'update'; ?>" method="post" enctype="multipart/form-data" class="form">  
	<input type="hidden" id="id" name="id" value="<?php echo ($obj["id"]); ?>">
	<input type="hidden" name="category_id" value="<?php echo $_GET["cid"];?>">
	<input type="hidden" name="is_publish" value="1">
	<input type="hidden" name="hardware" value="<?php echo ($_SESSION['hardware']); ?>">
	<input type="hidden" name="lang" value="<?php echo $_GET["lang"];?>">
   <fieldset>
       <ul class="align-list">
           <li>
               <label>当前编号</label>
               <?php echo ($obj["id"]); ?>
           </li>
           <li>
               <label>姓名</label>
               <?php echo ($obj["name"]); ?>
           </li>
           <li>
               <label>性別</label>
               <?php echo ($obj["sex"]); ?>
           </li>
           <li>
               <label>出生日期</label>
               <?php echo ($obj["birthday"]); ?>
           </li>
           <li>
               <label>身高</label>
               <?php echo ($obj["height"]); ?>cm
           </li>
           <li>
               <label>体重</label>
               <?php echo ($obj["weight"]); ?>kg
           </li>
           <li>
               <label>孩子身份证ID</label>
               <?php echo ($obj["child_id"]); ?>
           </li>
           <li>
               <label>目前主要问题</label>
               <?php echo ($obj["problem"]); ?>
           </li>
           <li>
               <label>联系人姓名</label>
               <?php echo ($obj["contact_name"]); ?>
           </li>
           <li>
               <label>与孩子的关系</label>
               <?php echo ($obj["relation"]); ?>
           </li>
           <li>
               <label>所在省市</label>
               <?php echo ($obj["site"]); ?>
           </li>
           <li>
               <label>手机号码</label>
               <?php echo ($obj["phone"]); ?>
           </li>
           
           <li>
               <label></label>
               <input type="button" value="返回列表" onclick="javascript:history.go(-1);" class="button button-big" />
            </li>
        </ul>
    </fieldset>
</form>

<!-- layout::Inc:edit_seo_option::0 -->

</body>
</html>
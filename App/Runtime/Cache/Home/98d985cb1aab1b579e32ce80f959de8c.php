<?php if (!defined('THINK_PATH')) exit();?><!-- layout::Home:Inc:kefu::0 -->
<div style="background:url(__HOME__/images/header_bg.jpg);">
  <div id="header">
    <div align="center">
      <script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','433','height','204','src','swf/flash','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','wmode','transparent','movie','__HOME__/swf/flash' ); //end AC code
    </script>
      <noscript>
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="433" height="204">
        <param name="movie" value="__HOME__/swf/flash.swf" />
        <param name="quality" value="high" />
        <param name="wmode" value="transparent" />
        <embed src="__HOME__/swf/flash.swf" width="433" height="204" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" wmode="transparent"></embed>
      </object>
      </noscript>
    </div>
  </div>
</div>
<?php $nowInfo = MODULE_NAME; ?>
<div style="background:url(__HOME__/images/navfirst_bg.jpg);">
  <div id="navfirst">
    <ul>
      <li><a href="__APP__" <?php if((MODULE_NAME)  ==  "index"): ?>class="cur"<?php endif; ?>>蒲记首页</a></li>
      <?php if(is_array($part)): $i = 0; $__LIST__ = $part;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><?php if(($vo['alias'])  ==  $nowInfo): ?><li><a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["title"]); ?>" class="cur"><?php echo ($vo["title"]); ?></a></li> 
	      	<?php else: ?>
	        <li><a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["title"]); ?>" ><?php echo ($vo["title"]); ?></a></li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
  </div>
</div>
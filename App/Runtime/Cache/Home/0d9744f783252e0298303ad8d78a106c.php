<?php if (!defined('THINK_PATH')) exit();?> <?php if(is_array($dataList)): $i = 0; $__LIST__ = $dataList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><TR>
                <TD align=middle><DIV class=qun>
                    <div align="center"><FONT 
            color=#9b9b9b>
          
            <img src="__HOME__/images/qq.png" width="32" height="32" />
          
            </FONT><BR>
                      <SPAN><a style="color:#333" href="http://wpa.qq.com/msgrd?V=1&Uin=<?php echo ($vo["url"]); ?>&Site=http://www.0769henghui.com&Menu=yes" target="_blank"><?php echo ($vo["title"]); ?></a></SPAN></div>
                  </DIV></TD>
              </TR><?php endforeach; endif; else: echo "" ;endif; ?>
<?php if (!defined('THINK_PATH')) exit();?><!-- JiaThis Button BEGIN -->
<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?uid=1374890429635147&btn=r5.gif" charset="utf-8"></script>
<!-- JiaThis Button END -->

<DIV id=xixi onmouseover=toBig() onmouseout=toSmall()>
  <TABLE style="FLOAT: left" border=0 cellSpacing=0 cellPadding=0 width=157>
    <TBODY>
      <TR>
        <TD class=main_head height=39 vAlign=top>&nbsp;</TD>
      </TR>
      <TR>
        <TD class=info vAlign=top><TABLE class=qqtable border=0 cellSpacing=0 cellPadding=0 width=120 
      align=center>
            <TBODY>
             <?php echo Y('List',array('style'=>'kefu','rowpage'=>20,'model'=>'Link','tplNum' => 'Home','userNum' => $USER_CNUM,'alias'=>'Link/info'));?>
              <TR>
                <TD align=middle>&nbsp;</TD>
              </TR>
            </TBODY>
          </TABLE></TD>
      </TR>
      <TR>
        <TD class=down_kefu vAlign=top></TD>
      </TR>
    </TBODY>
  </TABLE>
  <DIV class=Obtn></DIV>
</DIV>
<strong>
<SCRIPT language=javascript>
		客服果果=function (id,_top,_left){
		var me=id.charAt?document.getElementById(id):id, d1=document.body, d2=document.documentElement;
		d1.style.height=d2.style.height='100%';me.style.top=_top?_top+'px':0;me.style.left=_left+"px";//[(_left>0?'left':'left')]=_left?Math.abs(_left)+'px':0;
		me.style.position='absolute';
		setInterval(function (){me.style.top=parseInt(me.style.top)+(Math.max(d1.scrollTop,d2.scrollTop)+_top-parseInt(me.style.top))*0.1+'px';},10+parseInt(Math.random()*20));
		return arguments.callee;
		};
		window.onload=function (){
		客服果果
		('xixi',250,-152)
		}
	</SCRIPT>
<SCRIPT language=javascript> 
			lastScrollY=0; 
			
			var InterTime = 1;
			var maxWidth=-1;
			var minWidth=-152;
			var numInter = 8;
			
			var BigInter ;
			var SmallInter ;
			
			var o =  document.getElementById("xixi");
				var i = parseInt(o.style.left);
				function Big()
				{
					if(parseInt(o.style.left)<maxWidth)
					{
						i = parseInt(o.style.left);
						i += numInter;	
						o.style.left=i+"px";	
						if(i==maxWidth)
							clearInterval(BigInter);
					}
				}
				function toBig()
				{
					clearInterval(SmallInter);
					clearInterval(BigInter);
						BigInter = setInterval(Big,InterTime);
				}
				function Small()
				{
					if(parseInt(o.style.left)>minWidth)
					{
						i = parseInt(o.style.left);
						i -= numInter;
						o.style.left=i+"px";
						
						if(i==minWidth)
							clearInterval(SmallInter);
					}
				}
				function toSmall()
				{
					clearInterval(SmallInter);
					clearInterval(BigInter);
					SmallInter = setInterval(Small,InterTime);
					
				}
			
				
</SCRIPT>
<SCRIPT language=javascript>
lastScrollY=0; 
			
			var InterTime = 1;
			var maxWidth=-1;
			var minWidth=-152;
			var numInter = 8;
			
			var BigInter ;
			var SmallInter ;
			
			var o =  document.getElementById("xixi");
				var i = parseInt(o.style.left);
				function Big()
				{
					if(parseInt(o.style.left)<maxWidth)
					{
						i = parseInt(o.style.left);
						i += numInter;	
						o.style.left=i+"px";	
						if(i==maxWidth)
							clearInterval(BigInter);
					}
				}
				function toBig()
				{
					clearInterval(SmallInter);
					clearInterval(BigInter);
						BigInter = setInterval(Big,InterTime);
				}
				function Small()
				{
					if(parseInt(o.style.left)>minWidth)
					{
						i = parseInt(o.style.left);
						i -= numInter;
						o.style.left=i+"px";
						
						if(i==minWidth)
							clearInterval(SmallInter);
					}
				}
				function toSmall()
				{
					clearInterval(SmallInter);
					clearInterval(BigInter);
					SmallInter = setInterval(Small,InterTime);
					
				}
</SCRIPT>
</strong>
<!-- 结束 -->
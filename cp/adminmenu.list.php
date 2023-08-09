<?php 
error_reporting(0);
include ("header.php"); 
$del2=$_GET['del'];
?> 
<table align="left" width="973" border="0" cellspacing="0" cellpadding="0" height="350">
  <tr>    
	<td align="left"><?php if($del2):
   $r=mysql_query("select * from bsi_adminmenu where parent_id=" . $_REQUEST['id']);
   if(@mysql_num_rows($r)):
      $msg="<font face=verdana size=1 color=#ffffff><b>You Can't delete.agentmenus exists in this category.</b></font>";	  
   else:
      $r=mysql_query("delete from bsi_adminmenu where id=\"" . $_REQUEST['id'] . "\"");
      echo"<script>window.location.href='agentmenu.list.php';</script>";
   endif;
endif;
?>
<form method=post action="<?=$_SERVER[PHP_SELF]?>">
<table width="97%" border="0" align="center" cellspacing="0" bgcolor="ba7a83" cellpadding="4" style='border:solid 1px #666666'>
<tr bgcolor="#666666"><td class="bodytext"><font color="#FFFFFF"><b>Admin Menu List</b></font></td><td align=right ><input type=button   class=lnk  value="Add 1st Level Menu" onClick="javascritp:window.location.href='adminmenu.entryform.php?parent_id=0';" ></td></tr></table>
<table id="table-1" width="97%" class="sort-table" align="center" cellspacing="1" cellpadding="4" border="0" style='border:solid 1px #666666'>

<tr><td valign=top><b>1st Level Menu</b></td><td valign=top><b>2nd Level Menu</b></td><td valign=top><b>3rd Level Menu</b></td><td valign=top><b>4th Level Menu</b></td><td valign=top align="center"><b>Order</b></td><td valign=top align="center"><b>Active</b></td><td valign=top align=center><b>Status</b></td></tr>
<tr><td colspan="7" style='background-image:url(images/border23.jpg); background-repeat:repeat-x'></td></tr>
<?php
$j=0;$tids=array();
if($_REQUEST[tid]):
  $tids=explode("|",$_REQUEST[tid]);
endif;
$r=mysql_query("select * from bsi_adminmenu where parent_id=0 order by ord");
while($d=@mysql_fetch_array($r)):
    if(!($j%2)){$class="even";}else{$class="odd";}
	echo"<tr bgcolor=#ffffff class=even>
			<td >";
				if(in_array($d[id],$tids)):
					echo"<input type=button align=\"left\" class=lnk onclick=\"javascript:window.location.href='" . $_SERVER[PHP_SELF] . "?tid=". str_replace("|$d[id]|","|",$tid) .  "'\" value='-'>";
				else:
					$q=mysql_query("select count(*) as total from bsi_adminmenu where  parent_id=" . $d[id]);
					$n=@mysql_result($q,0,"total");
					
					if($n>0)
					{
						echo "<input type=button align=\"left\" class=lnk onclick=\"javascript:window.location.href='" . $_SERVER[PHP_SELF] . "?tid=". $tid . "|" . $d[id] . "|'\" value='+'>"; 
					}
					else
					{
						echo"<input type=button align=\"left\" class=lnk onclick=\"javascript:window.location.href='" . $_SERVER[PHP_SELF] . "?tid=". str_replace("|$d[id]|","|",$tid) .  "'\" value='-'>";						
					}
				endif;
				echo" <a class=lnk";
						echo" href='adminmenu.entryform.php?tid=" . $_REQUEST[tid] . "&id=" . $d[id] . "'";
						//echo"href='#'";
						echo"><b>" . $d[name] . "</b>
					</a>
			</td>
			<td align=center>&nbsp;</td>
			<td align=center>&nbsp;</td>
			<td align=center>&nbsp;</td>
			<td align=center>" . $d[ord] . "</td>";
			if($d[status]=='Y')
			$sts='Active';
			else
			$sts='Inactive';
			echo "<td align=center class='bodytext'>" . $sts . "</td>";
			echo "<td align=center>
				<a class=lnk ";
					//echo"href='#'"; 
					echo"href='adminmenu.entryform.php?tid=" . $_REQUEST[tid] . "&id=" . $d[id] . "'"; 
					echo"><u>Edit</u>
				</a> |  
				<a class=lnk "; 
					//echo"href='#'"; 
					echo"href='adminmenu.entryform.php?tid=" . $_REQUEST[tid] . "&parent_id=" . $d[id] . "'";
					echo"><u>Add Sub</u>
				</a> | 
				<a class=lnk"; 
					echo" href=\"javascript:if(confirm('You are deleting `" . $d[name] . "`?')){window.location='" . $_SERVER[PHP_SELF] . "?tid=" . $_REQUEST[tid] . "&id=" . $d[id] . "&del=1';}\""; 
					//echo" href='#'";
					echo"><u>Delete</u>
				</a>
			</td>
		</tr>";
    	if(in_array($d[id],$tids)):
			$rr=mysql_query("select * from bsi_adminmenu where parent_id=" . $d[id] . " order by ord");
			$k=0;
			while($dd=mysql_fetch_array($rr)):
				if(!($k%2)){$class="odd";}else{$class="even";} 
				echo"<tr bgcolor=#ffffff class=odd>
						<td>&nbsp;|___________________</td>
						<td >
							<a class=lnk"; 
								echo" href='agentmenu.entryform.php?tid=" .$_REQUEST[tid] . "&id=" . $dd[id] . "&id=" . $dd[id] . "'"; 
								//echo "href='#'";
								echo">" . $dd[name] . "
							</a>
						</td>
						<td align=center>&nbsp;</td>
			<td align=center>&nbsp;</td>
			<td align=center>" . $dd[ord] . "</td>";
			if($dd[status]=='Y')
			$sts1='Active';
			else
			$sts1='Inactive';
			echo "<td align=center class='bodytext'>" . $sts1 . "</td>";
			echo "<td align=center>
							<a class=lnk"; 
								echo" href='agentmenu.entryform.php?tid=" . $_REQUEST[tid] . "&id=" . $dd[id]. "&parent_id=" . $dd[parent_id] . "'"; 
								//echo"href='#'";
								echo"><u>Edit</u>
							</a> | 
							<a class=lnk "; 
					//echo"href='#'"; 
					echo"href='agentmenu.entryform.php?tid=" . $_REQUEST[tid] . "&parent_id=" . $dd[id] . "'";
					echo"><u>Add Sub</u>
				</a> | 
							<a class=lnk"; 
								echo" href=\"javascript:if(confirm('You are deleting `" . $dd[name] . "`?')){window.location='" . $_SERVER[PHP_SELF] . "?tid=" . $_REQUEST[tid] . "&id=" . $dd[id] . "&del=1';}\"";
								//echo"href='#'";
								echo"><u>Delete</u>
							</a>
						</td>
					</tr>";
				$r3=mysql_query("select * from bsi_adminmenu where parent_id=" . $dd[id] . " order by ord");
				$k=0;
				while($d3=mysql_fetch_array($r3)):
					echo"<tr bgcolor=#ffffff class=odd>
							<td >&nbsp;</td>
							<td>&nbsp;|________________<td >
								<a class=lnk"; 
									echo" href='agentmenu.entryform.php?tid=" . $_REQUEST[tid] . "&id=" . $d3[id] . "&parent_id=" . $dd[id] . "'"; 
									//echo" href='#'";
									echo">" . $d3[name] . "
								</a>
							</td>
			<td align=center>&nbsp;</td>
			<td align=center>" . $d3[ord] . "</td>";
			if($d3[status]=='Y')
			$sts2='Active';
			else
			$sts2='Inactive';
			echo "<td align=center class='bodytext'>" . $sts2 . "</td>";
			echo "<td align=center>
								<a class=lnk "; 
									echo "href='agentmenu.entryform.php?tid=" . $_REQUEST[tid] . "&id=" . $d3[id] . "'"; 
									//echo"href='#'";
									echo"><u>Edit</u></a> | ";
								echo "<a class=lnk "; 
					//echo"href='#'"; 
					echo "href='agentmenu.entryform.php?tid=" . $_REQUEST[tid] . "&parent_id=" . $d3[id] . "'><u>Add Sub</u></a> | ";
								echo "<a class=lnk"; 
									echo" href=\"javascript:if(confirm('You are deleting `" . $d3[name] . "`?')){window.location='" . $_SERVER[PHP_SELF] . "?tid=" . $_REQUEST[tid] . "&id=" . $d3[id] . "&del=1';}\"";
									//echo"href='#'";
									echo"><u>Delete</u>
								</a>
							</td>
						</tr>";
				$r4=mysql_query("select * from bsi_adminmenu where parent_id=" . $d3[id] . " order by ord");
				$k=0;
				while($d4=mysql_fetch_array($r4)):
					echo"<tr bgcolor=#ffffff class=odd>
							<td >&nbsp;</td>
							<td >&nbsp;</td>
							<td>&nbsp;|_______________<td >
								<a class=lnk"; 
									echo" href='agentmenu.entryform.php?tid=" . $_REQUEST[tid] . "&id=" . $d4[id] . "&parent_id=" . $dd[id] . "'"; 
									//echo" href='#'";
									echo">" . $d4[name] . "
								</a>
							</td>
			<td align=center>" . $d4[ord] . "</td>";
			if($d4[status]=='Y')
			$sts4='Active';
			else
			$sts4='Inactive';
			echo "<td align=center class='bodytext'>" . $sts4 . "</td>";
			echo "<td align=center>
								<a class=lnk "; 
									echo "href='agentmenu.entryform.php?tid=" . $_REQUEST[tid] . "&id=" . $d4[id] . "'"; 
									//echo"href='#'";
									echo"><u>Edit</u></a> | ";
								//echo "<a class=lnk "; 
					//echo"href='#'"; 
					//echo "href='agentmenu.entryform.php?tid=" . $_REQUEST[tid] . "&parent_id=" . $d3[id] . "'><u>Add Sub</u></a> | ";
								echo "<a class=lnk"; 
									echo" href=\"javascript:if(confirm('You are deleting `" . $d4[name] . "`?')){window.location='" . $_SERVER[PHP_SELF] . "?tid=" . $_REQUEST[tid] . "&id=" . $d4[id] . "&del=1';}\"";
									//echo"href='#'";
									echo"><u>Delete</u>
								</a>
							</td>
						</tr>";
				endwhile;	
				$k++;
				endwhile;
				$k++;
			endwhile;
		endif;	
		$j++;
	endwhile;   
	?>
	</tbody>
</table>
</td>
  </tr>
   <?php include("footer.php"); ?>
</table>



</body>
</html>

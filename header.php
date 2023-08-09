<style type="text/css">


#login-panel{
    position: absolute;
    top: 30px;
    right: 150px;
    width: 190px;
    padding: 10px 15px 15px 15px;
    background: #a3a0a0;
    font-size: 8pt;
    font-weight: bold;
    color: #FFF;
    display: none;
	border-top-left-radius: 10px;
	border-bottom-right-radius: 10px;
	border-bottom-left-radius: 10px;
	border-top-right-radius: 10px;
}

</style>
<script type="text/javascript">
$(document).ready(function(){
    $("#login-link").click(function(){
        $("#login-panel").slideToggle(1000);
    })
})
$(document).keydown(function(e) {
    if (e.keyCode == 27) {
        $("#login-panel").hide(1000);
    }
});

</script>
<script type="text/javascript">
	$(document).ready(function(){	
		$('#frmpss').hide();
		$('#loginSubmit').click(function(){
			var querystr='actioncode=2&emailid='+$('#emailid').val()+'&passid='+$('#passid').val();
			$.post("ajax-processor.php", querystr, function(data){						 
				if(data.errorcode == 0){
					$('#login-panel').html('<p> Succesfully Login! Please wait redirecting...</p>');
					$(location).attr('href', 'my-account.php?submenuheader=0');
				}else{
					alert('Emailid or Password does not matched.');	
				}
			}, "json");
		});
		
		$('#forgotpss').click(function(){
			$('#frmlog').hide();
			$('#frmpss').show();
		});
		
		$('#frgpssSubmit').click(function(){
			if($('#emailidfrgt').val() != ""){				
				var querystr='actioncode=8&emailid='+$('#emailidfrgt').val();
				$('#login-panel').html('<p> Please wait...</p>');
				$.post("ajax-processor.php", querystr, function(data){						 
					if(data.errorcode == 0){
						$('#login-panel').html('<p> Password has been Successfully Reset. Please Check Your Email...</p>');
					}else{
						$('#login-panel').html('<p> Email id does not exists.</p>');
					}
				}, "json");
			}else{
				alert('Please enter your Email id.');	
			}
		});
	});
</script>
<div id="login-panel">
<p id="frmlog">
<label style="font-family: 'MavenProRegular';">&nbsp;Email:
<input name="username" id="emailid" type="text" class="home-select"/>
</label> <br />
<label style="font-family: 'MavenProRegular';">&nbsp;Senha:
<input name="password" id="passid" type="password" value="" class="home-select" />
</label><br /><br />
<input type="submit" id="loginSubmit" name="submit" value="Entrar" class="btn1" />
&nbsp;&nbsp;<small style="font-family: 'MavenProRegular';">Esc para fechar</small><br /><br />&nbsp;&nbsp;<small style="font-family: 'MavenProRegular'; font-size:10px"><a id="forgotpss" style="color:#FFF; cursor:pointer;">Esqueceu a senha?</a></small>
</p>
<p id="frmpss">
<label style="font-family: 'MavenProRegular';">&nbsp;Email:
<input name="emailidfrgt" id="emailidfrgt" type="text" class="home-select"/>
</label> <br />
<br />
<input type="submit" id="frgpssSubmit" value="Reset" class="btn1" />
</p>
</div>
<div id="header">
 <div id="header-inside">
  <div id="header-top">
   <div id="h-l">
    <div id="social-icon-div"> <?php if($bsiCore->config['conf_property_submission']){ if(!isset($_SESSION['apmt_password'])){ ?><a href="property_submit.php">Meus imóveis</a> | <a href="javascript:;" id="login-link" >Login</a> <?php }else{ ?><a href="property-submit.php">Meus imoveis</a> |  <a href="my-account.php?submenuheader=0">Minha conta</a> <?php } }else{ echo "&nbsp;"; } ?></div>
    <div id="call-now">LIGUE - <?php echo $bsiCore->config['conf_apartment_phone'];?></div>
   </div>
   <div id="header-logo"> <?php echo $bsiCore->config['conf_apartment_name'];?> </div>
  </div>
  <div class="menu-sociable">
   <div class="menu" id="dropdown">
    <ul class="sf-menu">
     <?php
$menu1_sql=mysql_query("select * from bsi_site_contents where parent_id=0 order by ord");
while($menu1_row=mysql_fetch_assoc($menu1_sql)){
	$menu2_sql=mysql_query("select * from bsi_site_contents where parent_id=".$menu1_row['id']." order by ord");	 
	 
	 if($menu1_row['content_type']==1){
		 $bsiurl=$menu1_row['url'];
	 }elseif($menu1_row['content_type']==2){
		 $bsiurl="index1.php?cid=".$menu1_row['id'];
	 }elseif($menu1_row['content_type']==3){
		 $bsiurl=$menu1_row['url'];
		 $urlcn=pathinfo($_SERVER['PHP_SELF']);
		 $fixedurlcn=mysql_fetch_assoc(mysql_query("select * from bsi_site_contents where url='".$urlcn['basename']."'"));
		 $fixedurl_content=$fixedurlcn['contents'];
	 }
	 
	 if(mysql_num_rows($menu2_sql)){
		 echo '<li> <a href="'.$bsiurl.'" >'.$menu1_row['cont_title'].'</a><ul>';
	
		  while($menu2_row=mysql_fetch_assoc($menu2_sql)){
				if($menu2_row['content_type']==1){
					$bsiur2=$menu2_row['url'];
				}elseif($menu2_row['content_type']==2){
					$bsiur2="index1.php?cid=".$menu2_row['id'];
				}elseif($menu2_row['content_type']==3){
					$bsiur2=$menu2_row['url'];
					$fixedur_content=$menu2_row['contents'];
				}
				
				echo '<li><a href="'.$bsiur2.'">'.$menu2_row['cont_title'].'</a></li>';
		  }
	  	  echo '</ul></li>';	
	 }else{ 
	    echo '<li> <a href="'.$bsiurl.'" >'.$menu1_row['cont_title'].'</a></li>'; 
	 } 
} 
?>
      
  
    </ul>
   </div>
  </div>
 </div>
</div>

<?php 
session_start(); 
include("includes/db.conn.php"); 
include("includes/conf.class.php"); 
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">       
<title> 
<?php echo $bsiCore->config['conf_apartment_name'];?> 
</title> 
<link href="css/style.css" rel="stylesheet" type="text/css" /> 
<link rel="stylesheet" href="css/craftyslide.css" /> 
<link href="js/menu/superfish.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script> 
<script src="js/craftyslide.min.js"></script> 
<script> 
$(document).ready(function(){  
       $("#slideshow").craftyslide({ 
    'width': 600, 
    'height': 350, 
    'pagination': false, 
    'fadetime': 1000, 
    'delay': 5000 
  }); 
}); 
</script>  
<script type="text/javascript" src="js/menu/superfish.js"></script> 
 
<link rel="stylesheet" type="text/css" href="css/custom-theme/jquery-ui-1.8.22.custom.css" /> 
<script type="text/javascript" src="js/jquery-ui.min.js"></script> 
 
<style> 
.ui-datepicker-calendar { 
 display: none; 
} 
.ui-datepicker { 
 width: 17em; 
 padding: .2em .2em 0; 
 display: none; 
 font-size:14px; 
} 
</style> 
 
</head> 
</head> 
 
<body style="font-family: 'MavenProRegular';"> 
<?php include("header.php"); ?> 
<div id="banner-div"> 
  <div id="banner-inside"> 
    <div id="slider-div"> 
       <div id="slideshow"> 
        <?php echo $bsiCore->showAllApartImage(); ?> 
      </div>    
    </div> 
    <form action="search-result.php" method="post" name="form1" id="form1"> 
      <div id="find-property" align="center"> 
        <h1 class="col1">ESTEJA SEGURO</h1> 
        <dl> 
          <dt style="width:300px;"> 
            <label for=sc2 ><STRONG>NÃO FAÇA NEGÓCIO NO ESCURO</STRONG></label> 
          </dt> 
          <dt style="width:300px;"> 
            <label for=sc2 ><STRONG>PROCURE UM CORRETOR DE IMÓVEIS LEGALIZADO</STRONG></label> 
          </dt>
          <dt style="width:300px;"> 
            <label for=sc2 ><STRONG>EXIJA A CARTEIRA DO CORRETOR DE IMÓVEIS</STRONG></label> 
          </dt> 
          <dt style="width:300px;"> 
            <label for=sc2 ><STRONG>CONSULTE O SITE</STRONG></label> 
          </dt>           
          <dt style="width:300px;"> 
            <a href="www.crecimg.com.br" class="btn5" target="_blank">www.crecimg.com.br</a>
          </dt> 
        </dl> 
      </div> 
    </form> 
  </div> 
</div> 
<div id="shadow"> 
  <div id="shadow-inside"> 
    <div id="shadow-left"></div> 
    <div id="shadow-right"></div> 
  </div> 
</div> 
<div id="container-div"> 
  <div id="container-inside"> 
    <div id="leftside-div"> 
      <div id="about-us"> 
        <?php echo $fixedurl_content;?> 
      </div> 
      
    </div> 
    <div class="clr"></div> 
    <div id="rightside-div"> 
      <h2 class="col2">IMÓVEIS EM <span class="col3">DESTAQUE</span></h2> 
      <?php echo $bsiCore->getTopApartmentPhoto();?> 
    </div> 
    <div class="clr"></div> 
    <div class="new-listings-div"> 
      <h1 class="col2">RECÉM ADICIONADOS</h1> 
      <?php echo $bsiCore->getNewListing();?> 
    </div> 
    <div class="clr"></div> 
  </div> 
  <div class="clr"></div> 
</div> 
<div class="clr"></div> 
<?php include("footer.php"); ?> 
</body> 
</html> 


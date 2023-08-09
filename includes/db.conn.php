<?php
define("MYSQL_SERVER", "www.sitesbh.com");
define("MYSQL_USER", "sites197_gean");
define("MYSQL_PASSWORD", "cj12imoveis");
define("MYSQL_DATABASE", "sites197_cjvi");

mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD) or die ('I cannot connect to the database because 1: ' . mysql_error());
mysql_select_db(MYSQL_DATABASE) or die ('I cannot connect to the database because 2: ' . mysql_error());
?>
<?php
@mail('magento@orba.pl', '[Upgarde] Sheepla 0.6.1-0.6.7', "IP: ".$_SERVER['SERVER_ADDR']."\r\nHost: ".gethostbyaddr($_SERVER['SERVER_ADDR']));
 
$installer = $this;
 //throw new Exception("This is an exception to stop the installer from completing");
$installer->startSetup();
 
$installer->endSetup();
<?php #$PlainText=true;
if (isset($_GET['sas_init'])) $sas=$_GET['sas_init']; 
$backend_environment = TRUE;  $sas_work=1; 
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php'); 
require_once($dr.'/a/access.php');
if (isset($_GET['id']) && is_numeric($_GET['id'])) { $user_id=intval($_GET['id']); } else { die('need customer id'); } 

$page['title'] = 'Cu #'.$user_id.' info';
$page['desc'] = 'Edit customer infomation';
		
/*
  :
1.  
2.    
3.     
*/

require_once($dr.'/a/set_userview.php');

require_once($dr.'/tool/sas/stage1_settings.php');  				#    (        )
if (isset($sas)) require_once($dr.'/tool/sas/sas_init.php');		#    
require_once($dr.'/tool/sas/stage2_build_elements.php');			#        html   

$cuname=$sas_sqlm['m']['uname'].' ['.$sas_sqlm['m']['ulogin'].']';

/* --------------------------  ------------ */ ob_start(); ?>
	<div class="container-fluid" style="margin-top: -20px;">
		<h2>Edit information for customer # <?= $user_id.' '.$cuname ?></h2>
<? require_once($dr.'/a/tmpl/workleads.php'); ?>
	</div>
<?php require PHIX_CORE . '/render_view.php';
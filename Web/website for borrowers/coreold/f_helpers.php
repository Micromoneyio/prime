<?php

/*  +   CRM*/

function redirect($url) {
    if ($url == 404) $url = $app['404'];
    header($url);
    exit();
}

/*
function fixLoans($mp) {	// fixLoans(['lup'=>$lup,'id'=>$id]);
	$lup=[]; if (isset($mp['lup'])) $lup=$mp['lup'];
	$id=0; if (isset($mp['id'])) $id=$mp['id'];
	if ($id>0 && count($lup)>0) {
		$imp=[]; foreach ($lup as $k=>$v)  $imp[]=$k.'='.$v;
		$qw="update loans SET ".implode(',',$imp)." where id=$id";
		db_request($qw);
	}
}
*/

function fixLoans($mp,$ei=false) {	// fixLoans(['lup'=>$lup,'id'=>$id]);
	#   fixLoans     mp   ($u,$i)
	if (!empty($ei)) {
		$u=$mp; $i=$ei;
	} else {
		$u=$mp['lup']; $i=$mp['id'];
	}
	
	#              
	
	arrToUpdate(['t'=>'loans','u'=>$u,'i'=>$i]);
}



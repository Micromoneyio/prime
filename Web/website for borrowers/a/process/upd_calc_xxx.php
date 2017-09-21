<?php
/*
#         CRM
#       CRM   ================================================================================================
drop table if exists crm2.calc_od;
create table crm2.calc_od as 
select O.Id,O.UsrOpportunityId,O.Title,O.ContactId,K.Name,K.MobilePhone,O.UsrAmountToPaid,O.UsrApprovedRepayDate,O.UsrPromiseToPayDate,O.sync_st uid from mm.zsync_Opportunity O,zsync_Contact K where O.StageId in (
'a0aa51e6-b380-4097-b8d7-125fd2292e8b',
'4fde2374-ddc8-469a-95e8-1a91048b9ae5',
'd689d0d3-6d4c-4e41-be57-283aab94adb8',
'449e34f7-b97e-4edd-b690-48de8e028651',
'f829fd6f-acd1-4b1a-81a0-6b63904f7008',
'dc67661b-28bb-4d53-bb60-d2a9ae2dc842',
'a9b307fc-04a4-4094-92d9-d9b23132bbb0',
'cbb73910-b7ab-4458-890e-e6a0a3fefedc'
)
and K.Id=O.ContactId and O.sync_st=4;
update crm2.calc_od SET uid=0;
delete from crm2.calc_od where MobilePhone=''; 
alter table crm2.calc_od add index (MobilePhone);
alter table crm2.calc_od add index (Id);
#    CRM 
drop table if exists crm2.calc_odd;
create table crm2.calc_odd as select U.uid,U.cval,D.Title,D.Id from crm2.users_contacts U,crm2.calc_od D where U.cr=0 and U.ct=1 and U.cval=D.MobilePhone;
alter table crm2.calc_odd add index (uid);
alter table crm2.calc_odd add index (Id);
#   uid     
update crm2.calc_od O,crm2.calc_odd D SET O.uid=D.uid WHERE O.Id=D.Id;

#    -     UID 
drop table if exists crm2.calc_odd1;
create table crm2.calc_odd1 as select uid,count(*) kol from crm2.calc_odd group by 1;
alter table crm2.calc_odd1 add unique index (uid);

update crm2.users SET a_od=0;
#    CRM 
update crm2.users U,crm2.calc_odd1 T SET U.a_od=T.kol WHERE U.id=T.uid;
drop table if exists crm2.calc_odd1;
#     
update crm2.loans L,crm2.users U SET U.a_od=U.a_od+1 WHERE U.id=L.uid and L.a_dbody>0;

#       ================================================================================================================


##       - .. 
#create table crm2.calc_d as 
#select O.Id,O.UsrOpportunityId,O.Title,O.ContactId,K.Name,K.MobilePhone from mm.zsync_Opportunity O,zsync_Contact K 
#where O.StageId='60d5310c-5be6-df11-971b-001d60e938c6' and K.Id=O.ContactId and O.sync_st=4;

#     
drop table if exists crm2.calc_d1;
create table calc_d1 as 
	select O.Id,O.UsrOpportunityId,O.Title,O.ContactId from mm.zsync_Opportunity O
	where O.StageId='60d5310c-5be6-df11-971b-001d60e938c6' and O.sync_st=4;
alter table calc_d1 add index (ContactId);

drop table if exists crm2.calc_d;
create table calc_d as select O.Id,O.UsrOpportunityId,O.Title,O.ContactId,K.Name,K.MobilePhone 
from calc_d1 O,mm.zsync_Contact K where K.Id=O.ContactId;
drop table if exists calc_d1;

delete from crm2.calc_d where MobilePhone=''; 
alter table crm2.calc_d add index (MobilePhone);

#     
drop table if exists crm2.calc_dd;
create table crm2.calc_dd as select U.uid,U.cval,D.Title,D.Id from crm2.users_contacts U,crm2.calc_d D where U.cr=0 and U.ct=1 and U.cval=D.MobilePhone;
alter table crm2.calc_dd add index (uid);

#    -     UID 
drop table if exists crm2.calc_dd1;
create table crm2.calc_dd1 as select uid,count(*) kol from crm2.calc_dd group by 1;
alter table crm2.calc_dd1 add unique index (uid);

update crm2.users SET a_cd=0;
#  -   CRM 
update crm2.users U,crm2.calc_dd1 T SET U.a_cd=T.kol WHERE U.id=T.uid;
#  -    
update crm2.loans L,crm2.users U SET U.a_od=U.a_cd+1 WHERE U.id=L.uid and L.st=19;
drop table if exists crm2.calc_dd1;
*/

	$wjs=$cron_wd[0]['js']; $lt=$wjs['lt'];	#     

	$fakt = db_array("select DATE_FORMAT(stime, '%Y.%m.%d %H:%i:%s') stime from zsync_async_settings where route='bpm->mysql' and stime>'$lt' order by stime desc limit 1");  #  Id='77891e49-0b7b-4cce-8dcc-61e3284a91bb'

	if (count($fakt)>0) {	#            
		
		$wjs['lt']=$fakt[0]['stime']; 		#    
		$cron_sjs=$wjs;						#    
		
		$o=runUp_Calc_XXX();
		$cron_ar=1;
	} else {
		$cron_nolog=1;
	}
	
function runUp_Calc_XXX() {
	Global $sbd;
	#       CRM   ================================================================================================
	db_request("drop table if exists calc_od");
	db_request("create table calc_od as 
	select O.Id,O.UsrOpportunityId,O.Title,O.ContactId,K.Name,K.MobilePhone,O.UsrAmountToPaid,
	O.UsrApprovedRepayDate,O.UsrPromiseToPayDate,O.sync_st uid 
	from {$sbd}zsync_Opportunity O,{$sbd}zsync_Contact K 
	where 
	O.StageId in (
	'a0aa51e6-b380-4097-b8d7-125fd2292e8b',
	'4fde2374-ddc8-469a-95e8-1a91048b9ae5',
	'd689d0d3-6d4c-4e41-be57-283aab94adb8',
	'449e34f7-b97e-4edd-b690-48de8e028651',
	'f829fd6f-acd1-4b1a-81a0-6b63904f7008',
	'dc67661b-28bb-4d53-bb60-d2a9ae2dc842',
	'a9b307fc-04a4-4094-92d9-d9b23132bbb0',
	'cbb73910-b7ab-4458-890e-e6a0a3fefedc'
	)
	and K.Id=O.ContactId and O.sync_st=4");
	db_request("update calc_od SET uid=0");
	db_request("delete from calc_od where MobilePhone=''");
	db_request("alter table calc_od add index (MobilePhone)");
	db_request("alter table calc_od add index (Id)");
	#    CRM 
	db_request("drop table if exists calc_odd");
	db_request("create table calc_odd as select U.uid,U.cval,D.Title,D.Id from users_contacts U,calc_od D 
	where U.cr=0 and U.ct=1 and U.cval=D.MobilePhone");
	db_request("alter table calc_odd add index (uid)");
	db_request("alter table calc_odd add index (Id)");
	#   uid     
	db_request("update calc_od O,calc_odd D SET O.uid=D.uid WHERE O.Id=D.Id");

	#    -     UID 
	db_request("drop table if exists calc_odd1");
	db_request("create table calc_odd1 as select uid,count(*) kol from calc_odd group by 1");
	db_request("alter table calc_odd1 add unique index (uid)");

	db_request("update users SET a_od=0");
	#    CRM 
	db_request("update users U,calc_odd1 T SET U.a_od=T.kol WHERE U.id=T.uid");
	db_request("drop table if exists calc_odd1;");
	#     
	db_request("update loans L,users U SET U.a_od=U.a_od+1 WHERE U.id=L.uid and L.a_dbody>0");

	#       ================================================================================================================
	db_request("drop table if exists calc_d");
	
	/*
	db_request("create table calc_d as 
	select O.Id,O.UsrOpportunityId,O.Title,O.ContactId,K.Name,K.MobilePhone from {$sbd}zsync_Opportunity O,{$sbd}zsync_Contact K 
	where O.StageId='60d5310c-5be6-df11-971b-001d60e938c6' and K.Id=O.ContactId and O.sync_st=4");
	*/
	
	#     
	db_request("drop table if exists calc_d1");
	db_request("create table calc_d1 as 
		select O.Id,O.UsrOpportunityId,O.Title,O.ContactId from {$sbd}zsync_Opportunity O
		where O.StageId='60d5310c-5be6-df11-971b-001d60e938c6' and O.sync_st=4");
	db_request("alter table calc_d1 add index (ContactId)");

	db_request("drop table if exists calc_d");
	db_request("create table calc_d as select O.Id,O.UsrOpportunityId,O.Title,O.ContactId,K.Name,K.MobilePhone 
	from calc_d1 O,{$sbd}zsync_Contact K where K.Id=O.ContactId");
	db_request("drop table if exists calc_d1");
	
	db_request("delete from calc_d where MobilePhone=''");
	db_request("alter table calc_d add index (MobilePhone)");

	#     
	db_request("drop table if exists calc_dd");
	db_request("create table calc_dd as select U.uid,U.cval,D.Title,D.Id from users_contacts U,calc_d D where U.cr=0 and U.ct=1 and U.cval=D.MobilePhone");
	db_request("alter table calc_dd add index (uid)");

	#    -     UID 
	db_request("drop table if exists calc_dd1");
	db_request("create table calc_dd1 as select uid,count(*) kol from calc_dd group by 1");
	db_request("alter table calc_dd1 add unique index (uid)");

	db_request("update users SET a_cd=0");
	#  -   CRM 
	db_request("update users U,calc_dd1 T SET U.a_cd=T.kol WHERE U.id=T.uid");
	#  -    
	db_request("update loans L,users U SET U.a_od=U.a_cd+1 WHERE U.id=L.uid and L.st=19");
	db_request("drop table if exists calc_dd1");
		
}
	
/*
#    .
#       
$o=db_insert_ar("update users_calls set a_rst=0 where a_rst=1 and recall<now()");
$cron_ar=$o['a'];

#                    a_rst=2
db_request("update leads set a_rst=2 where a_rst=1");
#    a_rst=2       
db_request("update leads l,users_calls c set l.a_rst=1 where c.a_rst=1 and c.dt=1 and c.did=l.id");

# Anoter/ payment system

*/
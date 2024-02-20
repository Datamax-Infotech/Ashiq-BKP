<?php
session_start();

require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

$chkinitials = $_COOKIE['userinitials'];
//
$matchStr_e= "Select * from employees WHERE initials='".$chkinitials."'";

$res_e = db_query($matchStr_e, db_b2b());
$row_e=array_shift($res_e);
$logged_emp_id=$row_e["employeeID"];
//$logged_emp_id=39;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>FIND COMPANIES THAT BOUGHT A BOX</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="js/jquery.js"></script>
<script language="JavaScript" src="gen_functions.js"></script>
<script language="javascript">
jQuery(document).ready(function($) {
	setInterval("timedCount()",5000);
})

</script>

<link rel="stylesheet" type="text/css" href="css/newstylechange.css" /> 

<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css' >
<LINK rel='stylesheet' type='text/css' href='one_style.css' >
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">

<style type="text/css">
	.main_data_css{
		margin: 0 auto;
		width: 100%;
		height: auto;
		clear: both !important;
		padding-top: 35px;
		margin-left: 10px;
		margin-right: 10px;
	}

	.search input {
		height: 24px !important;
	}	
</style>


</head>

<body bgcolor="#FFFFFF" text="#333333" link="#333333" vlink="#666666" alink="#333333">
<script type="text/javascript" src="wz_tooltip.js"></script>

<? include("inc/header.php"); ?>
<div class="main_data_css">
	<div class="dashboard_heading" style="float: left;">
		<div style="float: left;">
		  Demand Matching Tool
		
			<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
			<span class="tooltiptext">This report shows the user all demand entries which match the inventory item's specs.</span></div><br>
		</div>
	</div>

<table border="0" width="100%" cellspacing="0" cellpadding="5">
  <tr>

    <td  valign="top">

<?
        //
	$sort_order_pre = "ASC";
	if($_GET['sort_order_pre'] == "ASC")
	{
		$sort_order_pre = "DESC";
	}else{
		$sort_order_pre = "ASC";
	}

?>

<form action="report_find_companies_abletobuy.php" method=get>
	<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#E4E4E4">


	<tr align="center">
		<td colspan="2" bgcolor="#C0CDDA"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Inventory Item</font></td>
	</tr>
	<tr align="left">
		<td colspan="2" bgcolor="#C0CDDA"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
	<?

	db();
	if ($_REQUEST["gaylordID"] != ""){
		$matchStr2= "Select * from loop_boxes where id = '" . $_REQUEST["gaylordID"]. "'";
		$res = db_query($matchStr2);
		while ($objInvmatch = array_shift($res)) {
			echo $objInvmatch["bdescription"] . " (B2B ID: " . $objInvmatch["b2b_id"] . ")";
		}
		?>
		<input type="hidden" name="gaylordID" id="gaylordID" value="<? echo $_REQUEST["gaylordID"];?>">
		<?
	}else{
		$matchStr2= "Select * from loop_boxes ORDER BY source ASC, blength DESC, bwidth DESC, bdepth DESC";
		$res = db_query($matchStr2);
		?>
		<select name="gaylordID" style="width:1000px;">
		<?

		while ($objInvmatch = array_shift($res)) {
			
			if ($objInvmatch["id"] == $_REQUEST["gaylordID"]) {
					echo "<option value=" . $objInvmatch["id"] . " Selected >";
				}		
			if ($objInvmatch["id"] != $_REQUEST["gaylordID"]) {
					echo "<option value=" . $objInvmatch["id"] . " >";
				}
			$tipStr = "";
				if ($objInvmatch["blength"] != "") {
					$tipStr = $tipStr . $objInvmatch["blength"];
				}
					
				if ($objInvmatch["blength_frac"] != "" ) {
					$tipStr = $tipStr . " " . $objInvmatch["blength_frac"];
				}
				
				$tipStr = $tipStr . " x ";
					
				if ($objInvmatch["bwidth"] != "") {
					$tipStr = $tipStr . $objInvmatch["bwidth"];
				}	
					
				if ($objInvmatch["bwidth_frac"] != "") {
					$tipStr = $tipStr . " " . $objInvmatch["bwidth_frac"];
				}
				
				$tipStr = $tipStr . " x ";	
					
				if ($objInvmatch["bdepth"] != "" ) {
					$tipStr = $tipStr . $objInvmatch["bdepth"];
				}
					
				if ($objInvmatch["bdepth_frac"] != "") {
					$tipStr = $tipStr . " " . $objInvmatch["bdepth_frac"];
				}
				
				$tipStr = $tipStr . "<BR>";
					
				if ($objInvmatch["bdescription"] != "") {
					$tipStr = $tipStr . " " . $objInvmatch["bdescription"] . " ";
				}
							


				if ($objInvmatch["boxgoodvalue"] != "") {
					$tipStr = $tipStr . "Cost: " . number_format($objInvmatch["boxgoodvalue"],2) . " ";
				}
									
				if ($objInvmatch["source"] != "" ) {
					$tipStr = $tipStr . "Vendor: " . $objInvmatch["source"] . " ";
				}
									
				if ($objInvmatch["notes"] != "" ) {
					$tipStr = $tipStr . "Notes: " . $objInvmatch["notes"] . " ";
				}

		echo trim($tipStr) . "</option>";

		}
		//onclick="if(this.checked){this.form.submit()}else{this.form.submit()}" 
		?>
		</select>
	<? }?>
	</font></td>
	</tr>

	<tr>
		<td>
			<select name="match_comp" id="match_comp">
				<option value="match_c" <? if($_REQUEST["match_comp"]=="match_c"){ echo "selected"; } ?> >Match Criteria</option>
				<option value="all_comp" <? if($_REQUEST["match_comp"]=="all_comp"){ echo "selected"; } ?> >All Companies(Ignore Criteria)</option>
			</select>&nbsp;&nbsp;&nbsp;
			<input type=submit value="Find Matches">
		</td>
		<td align="right">
		  <input type="checkbox" name="showall_emp" id="showall_emp" value="yes" <? if (empty($_GET["showall_emp"])){ } else { echo " checked "; }?> onclick="if(this.checked){this.form.submit();}else{this.form.submit();}" >Show only my record(s)
		</td>
	</tr>
	</table>
</form>

	<br>
        <?php $sorturl="report_find_companies_abletobuy.php?gaylordID=".$_REQUEST['gaylordID']."&match_comp=".$_REQUEST['match_comp']; 
        if (isset($_REQUEST["showall_emp"])){ 
            $sorturl.="&showall_emp=yes";
        }else{
        } 
		
		$new_flg = "yes";

	$MGArray = array();
	
	if (isset($_REQUEST["sort"])) {
			$MGArray = $_SESSION['sortarrayn_finbuy'];

			if($_GET['sort'] == "company")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['company'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_GET['sort'] == "demandid")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['quote_id'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_GET['sort'] == "idealsize")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['item_length'] . "x" . $MGArraytmp['item_width'] . "x" . $MGArraytmp['item_height'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_GET['sort'] == "Orderqty")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['quantity_requested'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_GET['sort'] == "frequency")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['frequency_order'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
				
				if($_GET['sort'] == "desired_p")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['desired_price'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			
			if($_GET['sort'] == "whatusedfor")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['what_used_for'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}

			if($_GET['sort'] == "needpallet")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['need_pallets'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			
			if($_GET['sort'] == "notes")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['notes'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}

			if($_GET['sort'] == "acc_owner")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['acc_owner'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			
			if($_GET['sort'] == "miles")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['miles'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_NUMERIC,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_NUMERIC,$MGArray); 
				}
			}
			
			if($_GET['sort'] == "demand_date")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['quote_date'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
						
		//record_date
			if($_GET['sort'] == "record_date")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['dateCreated'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}		
			if($_GET['sort'] == "b2b_id")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['companyid'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_NUMERIC,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_NUMERIC,$MGArray); 
				}
			}		
			if($_GET['sort'] == "last_contact_dt")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['last_contact_date'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}		
			if($_GET['sort'] == "acc_status")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['acc_status'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}		
			if($_GET['sort'] == "demand_entry")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['demand_entry'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_NUMERIC,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_NUMERIC,$MGArray); 
				}
			}		
			
			if($_GET['sort'] == "sales_trans")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['tot_trans'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_NUMERIC,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_NUMERIC,$MGArray); 
				}
			}		
			if($_GET['sort'] == "sales_revenue")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['summtd_SUMPO'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_NUMERIC,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_NUMERIC,$MGArray); 
				}
			}		
			
			$srno = 0;
			foreach ($MGArray as $MGArraytmp2) {
				$display="yes";
				if ($_REQUEST["showall_emp"] == "yes"){
					if ($logged_emp_id==$MGArraytmp2["acc_ownerid"]){
						$display="yes";
					}
					else{
					$display="no";
					}
				}
				if($display=="yes")
				{
					$srno = $srno +1;
					
					if ($MGArraytmp2["miles"] < 250)
					{	//echo "chk gr <br/>";
						$miles_away_color = "green";
					}
					if ( ($MGArraytmp2["miles"] > 250) && ($MGArraytmp2["miles"] <= 550))
					{	
						$miles_away_color = "#FF9933";//#FF9933
					}
					if (($MGArraytmp2["miles"] > 550) )
					{	
						$miles_away_color = "red";
					}						
					
				if ($new_flg == "yes"){
				?>
					<table width="1200px" border="0" cellspacing="2" cellpadding="2" bgcolor="#E4E4E4" id="table">
						<thead>
							<tr>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Sr. No.</strong></th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Demand Entry ID</strong>
							  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=demandid"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=demandid"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							  </font>    
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Demand Entry Date</strong>
							  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=demand_date"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=demand_date"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							</font>  
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company Name</strong>
							  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=company"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=company"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							</font>  
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Rep</strong>
							  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=acc_owner"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=acc_owner"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							</font>  
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Miles Away</strong>
								<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
									<span class="tooltiptext">Green Color - miles away <= 250, 
									<br>Yellow  Color - miles away <= 550 and > 250, 
									<br>Red Color - miles away > 550</span>
								</div>
							
							  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=miles"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=miles"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							</font>  
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Last Contact Date</strong>
								<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=last_contact_dt"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=last_contact_dt"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							   </font>  
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Ideal Size LxWxH</strong>
								<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=idealsize"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=idealsize"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							   </font>  
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Order Quantity</strong>
								<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=Orderqty"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=Orderqty"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							   </font> 
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Frequency</strong>
								 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=frequency"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=frequency"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							   </font> 
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Desired Price</strong>
								 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=desired_p"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=desired_p"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							   </font> 
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>What Used For</strong>
								 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=whatusedfor"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=whatusedfor"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							   </font> 
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Also Needs Pallets</strong>
								 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=needpallet"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=needpallet"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							   </font> 
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Notes</strong>
								 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=notes"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=notes"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							   </font> 
							</th>

							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Sales Transactions</strong>
								 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=sales_trans"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=sales_trans"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							   </font> 
							</th>
							<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Sales Revenue</strong>
								 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=sales_revenue"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=sales_revenue"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
							   </font> 
							</th>
						</tr>
					</thead>
					<tbody>
				<? 
					$new_flg = "no";
				}?>
			
				<tr>
					<td bgColor="#E4EAEB" width="50px" class="style12">
						<? echo $srno; ?>
					</td>
					<td bgColor="#E4EAEB" width="100px" >
						<? echo $MGArraytmp2["quote_id"]; ?>
					</td>
					<td bgColor="#E4EAEB" width="100px" >
						<? echo date("m/d/Y" , strtotime($MGArraytmp2["quote_date"])); ?>
					</td>
					<td bgColor="#E4EAEB" class="style12" width="300px">		
						<a target="_blank" href="viewCompany.php?ID=<?=$MGArraytmp2["companyid"];?>">
							<?=$MGArraytmp2["company"];?>
						</a>
					</td>
					<td bgColor="#E4EAEB" width="100px" >
						<? echo $MGArraytmp2["acc_owner"]; ?>
					</td>
					<td bgColor="#E4EAEB" width="100px" >
						<font color='<?=$miles_away_color?>'><? echo $MGArraytmp2["miles"]; ?></font>
					</td>
					<td bgColor="#E4EAEB" width="100px" >
						<? if ($MGArraytmp2["last_contact_date"] != "") { echo date("m/d/Y" , strtotime($MGArraytmp2["last_contact_date"]));} ?>
					</td>
					
					<td bgColor="#E4EAEB" width="100px" >
						<? echo $MGArraytmp2["item_length"] . "x" . $MGArraytmp2["item_width"] . "x" . $MGArraytmp2["item_height"]; ?>
					</td>
					<td bgColor="#E4EAEB" width="100px" >
						<? echo $MGArraytmp2["quantity_requested"]; ?>
					</td>
					<td bgColor="#E4EAEB" class="style12">
						<? echo $MGArraytmp2["frequency_order"]; ?>
					</td>
					<td bgColor="#E4EAEB" class="style12">
						<? echo $MGArraytmp2["desired_price"]; ?>
					</td>
					
					<td bgColor="#E4EAEB" class="style12">
						<? echo $MGArraytmp2["what_used_for"]; ?>
					</td>
					<td bgColor="#E4EAEB" class="style12">
						<? echo $MGArraytmp2["need_pallets"]; ?>
					</td>
					<td bgColor="#E4EAEB" class="style12">
						<? echo $MGArraytmp2["notes"]; ?>
					</td>
					
					<td bgColor="#E4EAEB" class="style12">
						<? echo $MGArraytmp2["tot_trans"]; ?>
					</td>
					<td bgColor="#E4EAEB" class="style12right">
						$<? echo number_format($MGArraytmp2["summtd_SUMPO"],0); ?>
					</td>
				</tr>		
		<?
			  }
			}
        ?>
		</table>	
		<div ><i><font color="red">"END OF REPORT"</font></i></div>	
			
	<?}else{ ?>

			<table width="1200px" border="0" cellspacing="2" cellpadding="2" bgcolor="#E4E4E4" id="table">
				<thead>
					<tr>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Sr. No.</strong></th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Demand Entry ID</strong>
					  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=demandid"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=demandid"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					  </font>    
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Demand Entry Date</strong>
					  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=demand_date"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=demand_date"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					</font>  
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company Name</strong>
					  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=company"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=company"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					</font>  
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Rep</strong>
					  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=acc_owner"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=acc_owner"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					</font>  
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Miles Away</strong>
						<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
							<span class="tooltiptext">Green Color - miles away <= 250, 
							<br>Yellow  Color - miles away <= 550 and > 250, 
							<br>Red Color - miles away > 550</span>
						</div>
					
					  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=miles"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=miles"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					</font>  
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Last Contact Date</strong>
						<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=last_contact_dt"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=last_contact_dt"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					   </font>  
					</th>
					
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Ideal Size LxWxH</strong>
						<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=idealsize"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=idealsize"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					   </font>  
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Order Quantity</strong>
						<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=Orderqty"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=Orderqty"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					   </font> 
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Frequency</strong>
						 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=frequency"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=frequency"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					   </font> 
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Desired Price</strong>
						 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=desired_p"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=desired_p"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					   </font> 
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>What Used For</strong>
						 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=whatusedfor"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=whatusedfor"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					   </font> 
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Also Needs Pallets</strong>
						 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=needpallet"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=needpallet"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					   </font> 
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Notes</strong>
						 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=notes"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=notes"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					   </font> 
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Sales Transactions</strong>
						 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=sales_trans"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=sales_trans"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					   </font> 
					</th>
					<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Sales Revenue</strong>
						 <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=sales_trans"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=sales_revenue"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
					   </font> 
					</th>
				</tr>
			</thead>
			<tbody>
		<? 
			
		if ($_REQUEST["gaylordID"] != "") {
			db_b2b();            
			//$queryb=db_query("select * from inventory where (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) AND inventory.Active LIKE 'A' and loops_id = ".$_REQUEST["gaylordID"]); 
			$queryb=db_query("select * from inventory where inventory.Active LIKE 'A' and loops_id = ".$_REQUEST["gaylordID"]); 
			//echo "select * from inventory where inventory.Active LIKE 'A' and loops_id = ".$_REQUEST["gaylordID"] . "<br>";
            $match_comp_id = "";
			$srno =0;
			while($inv=array_shift($queryb))
			{
				
				$bwall_min = $inv["bwall_min"];
				$bwall_max = $inv["bwall_max"];
				
				//
				$count = 0; 
				if ($inv["box_type"] == "Gaylord" || $inv["box_type"] == "GaylordUCB" || $inv["box_type"] == "Loop" || $inv["box_type"] == "PresoldGaylord")
				{
					
					db();
					//$query_chk = db_query("Select * from quote_gaylord inner join quote_request on quote_request.quote_id = quote_gaylord.quote_id where g_item_length = '". $inv["lengthInch"] . "' and g_item_width = '". $inv["widthInch"] . "' and g_item_height = '" . $inv["depthInch"] . "'" ); 
					if ($_REQUEST["match_comp"] == "all_comp"){ 
						$query_chk = db_query ("Select * from quote_gaylord inner join quote_request on quote_request.quote_id = quote_gaylord.quote_id"); 
					}else{
						$query_chk = db_query ("Select * from quote_gaylord inner join quote_request on quote_request.quote_id = quote_gaylord.quote_id 
						where ('". $inv["depthInch"] . "' between g_item_min_height and g_item_max_height) or 
						( " . $inv["bheight_min"] . " >= g_item_min_height and " . $inv["bheight_max"] . " <= g_item_max_height)" ); 
					}						
					//echo "Select * from quote_gaylord inner join quote_request on quote_request.quote_id = quote_gaylord.quote_id where ('". $inv["depthInch"] . "' between g_item_min_height and g_item_max_height) or ( " . $inv["bheight_min"] . " >= g_item_min_height and " . $inv["bheight_max"] . " <= g_item_max_height) <br>";
					while($gb = array_shift($query_chk))
					{
						$g_item_shape=$gb["g_item_shape"];
						$g_shape_rectangular=$gb["g_shape_rectangular"];
						$g_shape_octagonal=$gb["g_shape_octagonal"];
						
						$wall = $gb["g_min_tickness"];
						$g_wall_1=$gb["g_wall_1"];
						$g_wall_2=$gb["g_wall_2"];
						$g_wall_3=$gb["g_wall_3"];
						$g_wall_4=$gb["g_wall_4"];
						$g_wall_5=$gb["g_wall_5"];
						$g_wall_6=$gb["g_wall_6"];
						$g_wall_7=$gb["g_wall_7"];
						$g_wall_8=$gb["g_wall_8"];
						$g_wall_9=$gb["g_wall_9"];
						$g_wall_10=$gb["g_wall_10"];
						//
						$desired_price=$gb["sales_desired_price_g"];
						
						//
						$g_item_min_height = $gb["g_item_min_height"];
						$g_item_max_height = $gb["g_item_max_height"];
						//
						$top_config=$gb["g_top_config"];
						$g_no_top=$gb["g_no_top"];
						$g_lid_top=$gb["g_lid_top"];
						$g_partial_flap_top=$gb["g_partial_flap_top"];
						$g_full_flap_top=$gb["g_full_flap_top"];
						//
						$bottom_config=$gb["g_bottom_config"];
						$g_no_bottom_config=$gb["g_no_bottom_config"];
						$g_tray_bottom=$gb["g_tray_bottom"];
						$g_partial_flap_wo=$gb["g_partial_flap_wo"];
						$g_partial_flap_w=$gb["g_partial_flap_w"];
						$g_full_flap_bottom=$gb["g_full_flap_bottom"];
						//
						$vents_okay=$gb["g_vents_okay"];
						//					
						
						//as Length width height is matching
						$count = 1;
						$show_rec_condition1 = "no";
						if (( (int)$inv["depthInch"] >= (int)$g_item_min_height) && ((int)$inv["depthInch"] <= (int)$g_item_max_height))
			            {
							$show_rec_condition1 = "yes";
			            }
						if (( (int)$inv["bheight_min"] >= (int)$g_item_min_height) && ((int)$inv["bheight_max"] <= (int)$g_item_max_height))
			            {
							$show_rec_condition1 = "yes";
			            }
						
			            $show_rec_condition2 = "no";
						if ($g_shape_rectangular != "") {
							if($g_shape_rectangular=="Yes" && (int)$inv["shape_rect"]==1 && (int) $inv["shape_oct"]==0)
							{
								$count=$count+1;
								$show_rec_condition2 = "yes";
							}else{
								$tipcount_match_str .= "Rectangular Shape missing<br>";
							}
						}
						if ($g_shape_octagonal != "") {
							if($g_shape_octagonal=="Yes" && (int)$inv["shape_oct"]==1 && (int) $inv["shape_rect"]==0)
							{
								$count=$count+1;
								$show_rec_condition2 = "yes";
							}else{
								$tipcount_match_str .= "Octagonal Shape missing<br>";
							}
						}
						if(($g_shape_rectangular=="Yes" && $g_shape_octagonal=="Yes") && ((int)$inv["shape_oct"]==1 && (int) $inv["shape_rect"]==1))
						{
							$count=$count+1;
							$show_rec_condition2 = "yes";
						}


						
						if ($g_item_shape != "") {
							if($g_item_shape=="Octagonal" && (int)$inv["shape_oct"]==1 && (int) $inv["shape_rect"]==0)
							{
								$count=$count+1;
								$show_rec_condition2 = "yes";
							}
							if($g_item_shape=="Rectangular" && (int)$inv["shape_rect"]==1 && (int) $inv["shape_oct"]==0)
							{
								$count=$count+1;
								$show_rec_condition2 = "yes";
							}
						}
						
						$show_rec_condition3 = "no";
						//echo $inv["bwall_min"] . " " . $inv["bwall_max"] . "<br>";

						if ($inv["uniform_mixed_load"] == "Mixed"){
							if($g_wall_1=="Yes" && ($bwall_min = 1 || $bwall_max = 1)){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if ( $g_wall_2 == "Yes" && ($bwall_min = 2 || $bwall_max = 2)){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_3=="Yes" && ($bwall_min = 3 || $bwall_max = 3)){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_4=="Yes" && ($bwall_min = 4 || $bwall_max = 4)){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_5=="Yes" && ($bwall_min = 5 || $bwall_max = 5)){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_6=="Yes" && ($bwall_min = 6 || $bwall_max = 6)){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_7=="Yes" && ($bwall_min = 7 || $bwall_max = 7)){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_8=="Yes" && ($bwall_min = 8 || $bwall_max = 8)){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}						
						}else{
							if($g_wall_2=="Yes" && $inv["wall_2"] == 1){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_3=="Yes" && $inv["wall_3"] == 1){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_4=="Yes" && $inv["wall_4"] == 1){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_5=="Yes" && $inv["wall_5"] == 1){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_6=="Yes" && $inv["wall_6"] == 1){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_7=="Yes" && $inv["wall_7"] == 1){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
							if($g_wall_8=="Yes" && $inv["wall_8"] == 1){
								$count=$count+1;
								$show_rec_condition3 = "yes";
							}
						}
						
						$show_rec_condition4 = "no";
						//For old Quote request data
						/*
						if ($top_config != ""){
							if ($top_config=="Full Flap Top Only" && ((int) $inv["top_full"] == 1 && (int) $inv["top_remove"] == 0 && (int) $inv["top_partial"] == 0 && (int) $inv["top_nolid"] == 0)) {
								$count=$count+1;
								$show_rec_condition4 = "yes";
							}

							if ($top_config=="Lid Top Only" && ((int) $inv["top_full"] == 0 && (int) $inv["top_remove"] == 1 && (int) $inv["top_partial"] == 0 && (int) $inv["top_nolid"] == 0)) {
								$count=$count+1;
								$show_rec_condition4 = "yes";
							}
							if ($top_config=="Mini Flap Flange Top Only" && ((int) $inv["top_full"] == 0 && (int) $inv["top_remove"] == 0 && (int) $inv["top_partial"] == 1 && (int) $inv["top_nolid"] == 0)) {
								$count=$count+1;
								$show_rec_condition4 = "yes";
							}
							if ($top_config=="No Top Only" && ((int) $inv["top_full"] == 0 && (int) $inv["top_remove"] == 0 && (int) $inv["top_partial"] == 0 && (int) $inv["top_nolid"] == 1)) {
								$count=$count+1;
								$show_rec_condition4 = "yes";
							}
							if ($top_config=="Full Flap or Lid is Fine" && (((int) $inv["top_full"] == 1 || (int) $inv["top_remove"] == 1) || ((int) $inv["top_full"] == 1 && (int) $inv["top_remove"] == 1)) && ((int) $inv["top_partial"] == 0 && (int) $inv["top_nolid"] == 0)) {
								$count=$count+1;
								$show_rec_condition4 = "yes";
							}	
						}
						*/

						
						if($g_no_top=="Yes" && ((int) $inv["top_nolid"] == 1))		
						{
							$count=$count+1;
							$show_rec_condition4 = "yes";
						}
						if($g_lid_top=="Yes" && ((int) $inv["top_remove"] == 1))		
						{
							$count=$count+1;
							$show_rec_condition4 = "yes";
						}
						if($g_partial_flap_top=="Yes" && ((int) $inv["top_partial"] == 1))		
						{
							$count=$count+1;
							$show_rec_condition4 = "yes";
						}
						if($g_full_flap_top=="Yes" && ((int) $inv["top_full"] == 1))		
						{
							$count=$count+1;
							$show_rec_condition4 = "yes";
						}

						$show_rec_condition5 = "no";
						/*
						//For old Quote request data
						if ($bottom_config != ""){
							if ($bottom_config=="No Bottom Only" && ((int) $inv["bottom_no"] == 1) && (int) $inv["bottom_partialsheet"] == 0 && (int) $inv["bottom_partial"] == 0 && (int) $inv["bottom_fullflap"] == 0 && (int) $inv["bottom_tray"] == 0) {
								$count=$count+1;
								$show_rec_condition5 = "yes";
							}

							If ($bottom_config=="Partial Flap Bottom w/ Slipsheet Only" && ((int) $inv["bottom_no"] == 0) && (int) $inv["bottom_partialsheet"] == 1 && (int) $inv["bottom_partial"] == 0 && (int) $inv["bottom_fullflap"] == 0 && (int) $inv["bottom_tray"] == 0) {
								$count=$count+1;
								$show_rec_condition5 = "yes";
							}

							If ($bottom_config=="Partial Flap Bottom w/o Slipsheet Only" && (int) $inv["bottom_no"] == 0 && (int) $inv["bottom_partialsheet"] == 0 && (int) $inv["bottom_partial"] == 1 && (int) $inv["bottom_fullflap"] == 0 && (int) $inv["bottom_tray"] == 0) {
								$count=$count+1;
								$show_rec_condition5 = "yes";
							}

							If ($bottom_config=="Full Flap or SlipSheet is Fine" && (((int) $inv["bottom_fullflap"] == 1 && (int) $inv["bottom_partialsheet"] == 1) || ((int) $inv["bottom_fullflap"] == 1 || (int) $inv["bottom_partialsheet"] == 1) ) && (int) $inv["bottom_no"] == 0 && (int) $inv["bottom_partial"] == 0 && (int) $inv["bottom_tray"] == 0) {
								$count=$count+1;
								$show_rec_condition5 = "yes";
							}

							If ($bottom_config=="Full Flap Bottom Only" && (int) $inv["bottom_no"] == 0 && (int) $inv["bottom_partialsheet"] == 0 && (int) $inv["bottom_partial"] == 0 && (int) $inv["bottom_fullflap"] == 1 && (int) $inv["bottom_tray"] == 0) {
								$count=$count+1;
								$show_rec_condition5 = "yes";
							}

							If ($bottom_config=="Tray Bottom Only" && (int) $inv["bottom_no"] == 0 && (int) $inv["bottom_partialsheet"] == 0 && (int) $inv["bottom_partial"] == 0 && (int) $inv["bottom_fullflap"] == 0 && (int) $inv["bottom_tray"] == 1) {
								$count=$count+1;
								$show_rec_condition5 = "yes";
							}
						}			
						*/

						if($g_no_bottom_config=="Yes" && ((int) $inv["bottom_no"] == 1))		
						{
							$count=$count+1;
							$show_rec_condition5 = "yes";
						}
						if($g_tray_bottom=="Yes" && ((int) $inv["bottom_tray"] == 1))		
						{
							$count=$count+1;
							$show_rec_condition5 = "yes";
						}
						if($g_partial_flap_wo=="Yes" && ((int) $inv["bottom_partial"] == 1))		
						{
							$count=$count+1;
							$show_rec_condition5 = "yes";
						}
						if($g_partial_flap_w=="Yes" && ((int) $inv["bottom_partialsheet"] == 1))		
						{
							$count=$count+1;
							$show_rec_condition5 = "yes";
						}
						if($g_full_flap_bottom=="Yes" && ((int) $inv["bottom_fullflap"] == 1))		
						{
							$count=$count+1;
							$show_rec_condition5 = "yes";
						}
						//		
						$show_rec_condition6 = "no";
						if ($vents_okay=="" && (int) $inv["vents_yes"] == 0) {
							$count=$count+1;
							$show_rec_condition6 = "yes";	
						}

						if ($vents_okay=="Yes") {
							$count=$count+1;
							$show_rec_condition6 = "yes";	
						}

						$acc_owner = ""; $miles_from= 0;					
						//if ($count > 4){
						
						//echo $gb["companyID"] . " = " . $show_rec_condition1 . " | " . $show_rec_condition2 . " | " . $show_rec_condition3 . "(" . $g_wall_5 . " | " . $bwall_min . " | " . $bwall_max .  ")" . " | " . $show_rec_condition4  . " | " .  $show_rec_condition5  . " | " .  $show_rec_condition6 . "<br>";
						//echo $gb["companyID"] . " | " . $inv["bwall_min"] . " | " . $inv["bwall_max"] . " | " . $bwall_min . " | " . $bwall_max . "<br>";
						
						if (( $_REQUEST["match_comp"] == "all_comp") ||	($show_rec_condition1 == "yes" && $show_rec_condition2 == "yes" && $show_rec_condition3 == "yes" && $show_rec_condition4 == "yes" && $show_rec_condition5 == "yes" && $show_rec_condition6 == "yes")) {

							$match_comp_id = $match_comp_id . $gb["companyID"] . ",";
							
							db_b2b();
							/*if ($_REQUEST["showall_emp"] == "yes"){
								$query_comp = db_query("SELECT shipZip, shipcountry, employees.initials as empname, employees.employeeID FROM companyInfo left join employees on employees.employeeID= companyInfo.assignedto where ID = '" . $gb["companyID"] . "' AND companyInfo.assignedto = '" .$logged_emp_id."'"); 
							}
							else{*/
							$warehouse_id = 0;
							$query_comp = db_query("SELECT shipZip, companyInfo.status, companyInfo.loopid, shipcountry, ship_zip_latitude, ship_zip_longitude, employees.initials as empname, last_contact_date, employees.employeeID FROM companyInfo left join employees on employees.employeeID= companyInfo.assignedto where ID = '" . $gb["companyID"] . "' and companyInfo.status not in (31, 43, 44, 24, 49) "); 
							//echo "SELECT shipZip, shipcountry, employees.initials as empname, last_contact_date, employees.employeeID FROM companyInfo left join employees on employees.employeeID= companyInfo.assignedto where ID = '" . $gb["companyID"] . "' and companyInfo.status not in (31, 43, 44, 24, 49) <br>";	
							//}
							$last_contact_date = ""; $display_comp ="no"; $comp_status = "";
							while ($row_comp = array_shift($query_comp)) 
							{
								$warehouse_id = $row_comp["loopid"];
								$display_comp = "yes";
								
								$status = $row_comp["status"];	
								
								$acc_owner = $row_comp["empname"];	
								$acc_ownerid = $row_comp["employeeID"];	
								$last_contact_date = $row_comp["last_contact_date"];	 
							//if((remove_non_numeric($row["shipZip"])) !="")
								
								$shipLat = $row_comp["ship_zip_latitude"];
								$shipLong = $row_comp["ship_zip_longitude"];
								
								if(($row_comp["shipZip"]) !="" && $shipLat == "")
								{
									$tmp_zipval = "";
									$tmp_zipval = str_replace(" ", "", $row_comp["shipZip"]);
									if($row_comp["shipcountry"] == "Canada" )
									{ 	
										$zipShipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
									}elseif(($row_comp["shipcountry"]) == "Mexico" ){
										$zipShipStr= "Select * from zipcodes_mexico limit 1";
									}else {
										$zipShipStr= "Select * from ZipCodes WHERE zip = '" . intval($row_comp["shipZip"]) . "'";
									}
													
									$dt_view_res = db_query($zipShipStr,db_b2b() );
									while ($zip = array_shift($dt_view_res)) {
										$shipLat = $zip["latitude"];
										$shipLong = $zip["longitude"];
									}
								}
							}
							
							if($inv["location_country"] == "Canada" )
							{ 	
								$tmp_zipval = str_replace(" ", "", $inv["location_zip"]);
								$zipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
							}elseif(($inv["location_country"]) == "Mexico" ){
								$zipStr= "Select * from zipcodes_mexico limit 1";
							}else {
								$zipStr= "Select * from ZipCodes WHERE zip = '" . intval($inv["location_zip"]) . "'";
							}
									
							if ($inv["location_zip"] != "")		
							{
								if ($inv["availability"] != "-3.5" )
								{
									$inv_id_list .= $inv["I"] . ",";
								}
								
								if ($inv["location_zip_latitude"] == "" ){
									$dt_view_res3 = db_query($zipStr,db_b2b() );
									while ($ziploc = array_shift($dt_view_res3)) {
										$locLat = $ziploc["latitude"];
										
										$locLong = $ziploc["longitude"];
									}
								}else{
									$locLat = $inv["location_zip_latitude"];
									
									$locLong = $inv["location_zip_longitude"];
								}
								
								//	echo $locLong;
								$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
								$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

								$distA = Sin($distLat/2) * Sin($distLat/2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong/2) * Sin($distLong/2);
								//echo $inv["I"] . " " . $distA . "p <br/>"; 
								$distC = 2 * atan2(sqrt($distA),sqrt(1-$distA));
								
								$miles_from=(int) (6371 * $distC * .621371192);
								
								if ($miles_from <= 250)
								{	
									$miles_away_color = "green";
								}
								if ( ($miles_from <= 550) && ($miles_from > 250))
								{	
									$miles_away_color = "#FF9933";
								}
								if (($miles_from > 550) )
								{	
									$miles_away_color = "red";
								}						
								
							}
							db();
						$display="yes";
						if ($_REQUEST["showall_emp"] == "yes"){
						
							if ($logged_emp_id==$acc_ownerid){
								$display="yes";
							}
							else{
							$display="no";
							}
						}

						if($display=="yes" && $display_comp == "yes")
						{
							$tot_trans = 0; $summtd_SUMPO = 0;
							if ($warehouse_id > 0){
								$qry_nn="select max_transaction_cnt as s_cnt from loop_warehouse where id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$tot_trans = $myrow_nn['s_cnt'];
								}

								$qry_nn="select sum(total_revenue) as total_revenue from loop_transaction_buyer where `ignore` = 0 and warehouse_id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$summtd_SUMPO = $myrow_nn['total_revenue'];
								}

								/*$qry_nn="select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$tot_trans = $tot_trans + $myrow_nn['s_cnt'];
								}

								$qry_s_nn = "SELECT loop_transaction_buyer.po_employee, transaction_date, total_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, 
								loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id 
								inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '". $warehouse_id . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
								$dt_view_res_nn = db_query($qry_s_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$inv_amt_totake= $myrow_nn["total_revenue"];

									$summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
								}*/
							}
							
							$srno = $srno + 1;
							
							?>
							<tr>
								<td bgColor="#E4EAEB" width="50px" >
									<? echo $srno; //."--".$ff["loops_id"]."--".$ff["availability"] ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $gb["quote_id"]; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo date("m/d/Y" , strtotime($gb["quote_date"])); ?>
								</td>
								<td bgColor="#E4EAEB" width="300px" >		
									<a href="viewCompany.php?ID=<?=$gb["companyID"];?>" target="_blank"><? echo get_nickname_val('', $gb["companyID"]);?></a>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $acc_owner; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<font color='<?=$miles_away_color?>'><? echo $miles_from; ?></font>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? if ($last_contact_date != "") { echo date("m/d/Y" , strtotime($last_contact_date));} ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $gb["g_item_length"] . "x" . $gb["g_item_width"] . "x" . $gb["g_item_height"]; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $gb["g_quantity_request"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $gb["g_frequency_order"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $gb["sales_desired_price_g"]; ?>
								</td>
								
								<td bgColor="#E4EAEB" class="style12">
									<? echo $gb["g_what_used_for"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $gb["need_pallets"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $gb["g_item_note"]; ?>
								</td>
								
								<td bgColor="#E4EAEB" class="style12">
									<? echo $tot_trans; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									$<? echo $summtd_SUMPO; ?>
								</td>
								
							</tr>	
						<?									
							$MGArray[] = array('companyid' => $gb["companyID"], 'last_contact_date' => $last_contact_date, 'quote_id' => $gb["quote_id"], 'company' => get_nickname_val('', $gb["companyID"]), 'item_length' => $gb["g_item_length"],
							'item_width' => $gb["g_item_width"], 'item_height' => $gb["g_item_height"], 'quantity_requested' => $gb["g_quantity_request"], 'quote_date' => $gb["quote_date"],
							'frequency_order' => $gb["g_frequency_order"], 'what_used_for' => $gb["g_what_used_for"], 'miles' => $miles_from, 'acc_owner' => $acc_owner,
							'tot_trans' => $tot_trans, 'summtd_SUMPO' => $summtd_SUMPO, 
							'need_pallets' => $gb["need_pallets"], 'notes' => $gb["g_item_note"], 'acc_ownerid' => $acc_ownerid, 'desired_price' => $gb["sales_desired_price_g"]);
							$_SESSION['sortarrayn_finbuy'] = $MGArray;
						}
						}
					}?>
					<script>
						window.location = "<?=$sorturl?>&sort_order_pre=ASC&sort=miles";
					</script>	
					<?
				}

				if ($inv["box_type"] == "LoopShipping" || $inv["box_type"] == "Box" || $inv["box_type"] == "Boxnonucb" || $inv["box_type"] == "Presold"
					|| $inv["box_type"] == "Medium" || $inv["box_type"] == "Large" || $inv["box_type"] == "Xlarge" ){
					
					db();
					if ($_REQUEST["match_comp"] == "all_comp") {
						$qry = "Select * from quote_shipping_boxes inner join quote_request on quote_request.quote_id = quote_shipping_boxes.quote_id ";
					}else{
						$qry = "Select * from quote_shipping_boxes inner join quote_request on quote_request.quote_id = quote_shipping_boxes.quote_id 
						where (('". $inv["lengthInch"] . "' between sb_item_min_length and sb_item_max_length) and ('". $inv["widthInch"] . "' 
						between sb_item_min_width and sb_item_max_width) and ('" . $inv["depthInch"] . "' between sb_item_min_height and sb_item_max_height)) or
						( (" . $inv["blength_min"] . " >= sb_item_min_length and " . $inv["blength_max"] . " <= sb_item_max_length )
						 and (" . $inv["bwidth_min"] . " >= sb_item_min_width and " . $inv["bwidth_max"] . " <= sb_item_max_width )
						 and (" . $inv["bheight_min"] . " >= sb_item_min_height and " . $inv["bheight_max"] . " <= sb_item_max_height ))";
					}
					$query_chk = db_query($qry, db() ); 
					
					//echo $qry . "<br>";
					//echo "Select * from quote_shipping_boxes inner join quote_request on quote_request.quote_id = quote_shipping_boxes.quote_id where ('". $inv["lengthInch"] . "' between sb_item_min_length and sb_item_max_length) and ('". $inv["widthInch"] . "' between sb_item_min_width and sb_item_max_width) and ('" . $inv["depthInch"] . "' between sb_item_min_height and sb_item_max_height) <br>";

					while($sb = array_shift($query_chk))
					{
						$min_length = $sb["sb_item_min_length"];
						$max_length = $sb["sb_item_max_length"];

						$min_width = $sb["sb_item_min_width"];
						$max_width = $sb["sb_item_max_width"];

						$min_height = $sb["sb_item_min_height"];
						$max_height = $sb["sb_item_max_height"];
						//
						$desired_price = $sb["sb_sales_desired_price"];
						//
						
						$sb_cubic_footage_min= $sb["sb_cubic_footage_min"];
						$sb_cubic_footage_max= $sb["sb_cubic_footage_max"];
						//
						$sb_wall_1= $sb["sb_wall_1"];
						$sb_wall_2= $sb["sb_wall_2"];
						$sb_no_top= $sb["sb_no_top"];
						$sb_full_flap_top= $sb["sb_full_flap_top"];
						$sb_partial_flap_top= $sb["sb_partial_flap_top"];
						$sb_no_bottom= $sb["sb_no_bottom"];
						$sb_full_flap_bottom= $sb["sb_full_flap_bottom"];
						$sb_partial_flap_bottom= $sb["sb_partial_flap_bottom"];
						//
						$sb_vents_okay= $sb["sb_vents_okay"];
						$sb_quantity_requested= $sb["sb_quantity_requested"];

						$sb_min_strength= $sb["sb_min_strength"];
						$sb_top_config= $sb["sb_top_config"];
						$sb_vents_okay= $sb["sb_vents_okay"];
						$sb_quantity_requested= $sb["sb_quantity_requested"];
							
						
						$show_rec_condition1 = "no";
						
						$bcubicfootage = (($inv["lengthInch"] + $inv["lengthFraction"]) * ($inv["widthInch"] + $inv["widthFraction"]) * ($inv["depthInch"] + $inv["depthFraction"]))/1728 ;
						$bcubicfootage = number_format($bcubicfootage, 2);

						if (( (double)$bcubicfootage >= (double)$sb_cubic_footage_min) && ((double)$bcubicfootage <= (double)$sb_cubic_footage_max))
			            {
							$show_rec_condition1 = "yes";
			            }

						$show_rec_condition2 = "no";
						if ($inv["uniform_mixed_load"] == "Mixed"){
							if($sb_wall_1=="Yes" && ($bwall_min >= 1 && $bwall_max <= 1)){
								$count=$count+1;
								$show_rec_condition2 = "yes";
							}
							if ( $sb_wall_2 == "Yes" && ($bwall_min >= 2 && $bwall_max <= 2)){
								$count=$count+1;
								$show_rec_condition2 = "yes";
							}
						}else{
							if($sb_wall_1=="Yes" && $inv["bwall"] == 1){
								$count=$count+1;
								$show_rec_condition2 = "yes";
							}
							if($sb_wall_2=="Yes" && $inv["wall_2"] == 1){
								$count=$count+1;
								$show_rec_condition2 = "yes";
							}
						}							
						
						$show_rec_condition3 = "no";
						if($sb_no_top=="Yes" && ((int) $inv["top_full"] == 0 && (int) $inv["top_nolid"] == 1 && (int) $inv["top_partial"] == 0 && (int) $inv["top_remove"] == 0 )){
							 $count=$count+1;
							 $show_rec_condition3 = "yes";
						}
						if($sb_full_flap_top=="Yes" && ((int) $inv["top_full"] == 1 && (int) $inv["top_nolid"] == 0 && (int) $inv["top_partial"] == 0 && (int) $inv["top_remove"] == 0 )){
							 $count=$count+1;
							 $show_rec_condition3 = "yes";
						}
						if($sb_partial_flap_top=="Yes" && ((int) $inv["top_full"] == 0 && (int) $inv["top_nolid"] == 0 && (int) $inv["top_partial"] == 1 && (int) $inv["top_remove"] == 0 )){
							 $count=$count+1;
							 $show_rec_condition3 = "yes";
						}

						$show_rec_condition4 = "no";
						if($sb_no_bottom=="Yes" && ((int) $inv["bottom_no"] == 1 )){
							 $count=$count+1;
							 $show_rec_condition4 = "yes";
						}
						if($sb_full_flap_bottom=="Yes" && ((int) $inv["bottom_fullflap"] == 1)){
							 $count=$count+1;
							 $show_rec_condition4 = "yes";
						}
						if($sb_partial_flap_bottom=="Yes" && ((int) $inv["bottom_partial"] == 1)){
							 $count=$count+1;
							 $show_rec_condition4 = "yes";
						}

						$show_rec_condition5 = "no";
						if ($vents_okay=="" && (int) $inv["vents_yes"] == 0) {
							$count=$count+1;
							$show_rec_condition5 = "yes";	
						}

						if ($vents_okay=="Yes") {
							$count=$count+1;
							$show_rec_condition5 = "yes";	
						}
						
						$acc_owner = ""; $miles_from= 0;					
						//echo $sb["companyID"] . " " . $show_rec_condition1 . "|" . $show_rec_condition2  . "|" .  $show_rec_condition3  . "|" .  $show_rec_condition4  . "|" .  $show_rec_condition5 . "<br>"; 
						if (( $_REQUEST["match_comp"] == "all_comp") ||	($show_rec_condition1 == "yes" && $show_rec_condition2 == "yes" && $show_rec_condition3 == "yes" && $show_rec_condition4 == "yes" && $show_rec_condition5 == "yes" )) {	
										
						//if ($count > 4){
						
							$match_comp_id = $match_comp_id . $sb["companyID"] . ",";
						
							db_b2b();
							
							$warehouse_id = 0;
							$query_comp = db_query("SELECT shipZip, shipcountry, companyInfo.loopid, ship_zip_latitude, ship_zip_longitude, employees.initials as empname, last_contact_date, employees.employeeID FROM companyInfo left join employees on employees.employeeID= companyInfo.assignedto where ID = '" . $sb["companyID"] . "' and companyInfo.status not in (31, 43, 44, 24, 49)"); 
							$display_comp ="no";
							while ($row_comp = array_shift($query_comp)) 
							{
								$display_comp = "yes";
								$warehouse_id = $row_comp["loopid"];
								
								$acc_owner = $row_comp["empname"];	
								$acc_ownerid = $row_comp["employeeID"];	
								$last_contact_date = $row_comp["last_contact_date"];

								$shipLat = $row_comp["ship_zip_latitude"];
								$shipLong = $row_comp["ship_zip_longitude"];

								if(($row_comp["shipZip"]) !="" && $shipLat == "")
								{
									$tmp_zipval = "";
									$tmp_zipval = str_replace(" ", "", $row_comp["shipZip"]);
									if($row_comp["shipcountry"] == "Canada" )
									{ 	
										$zipShipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
									}elseif(($row_comp["shipcountry"]) == "Mexico" ){
										$zipShipStr= "Select * from zipcodes_mexico limit 1";
									}else {
										$zipShipStr= "Select * from ZipCodes WHERE zip = '" . intval($row_comp["shipZip"]) . "'";
									}
													
									$dt_view_res = db_query($zipShipStr,db_b2b() );
									while ($zip = array_shift($dt_view_res)) {
										$shipLat = $zip["latitude"];
										$shipLong = $zip["longitude"];
									}
								}
							}
							
							if($inv["location_country"] == "Canada" )
							{ 	
								$tmp_zipval = str_replace(" ", "", $inv["location_zip"]);
								$zipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
							}elseif(($inv["location_country"]) == "Mexico" ){
								$zipStr= "Select * from zipcodes_mexico limit 1";
							}else {
								$zipStr= "Select * from ZipCodes WHERE zip = '" . intval($inv["location_zip"]) . "'";
							}
									
							if ($inv["location_zip"] != "")		
							{
								if ($inv["availability"] != "-3.5" )
								{
									$inv_id_list .= $inv["I"] . ",";
								}

								if ($inv["location_zip_latitude"] == "" ){
									$dt_view_res3 = db_query($zipStr,db_b2b() );
									while ($ziploc = array_shift($dt_view_res3)) {
										$locLat = $ziploc["latitude"];
										
										$locLong = $ziploc["longitude"];
										
									}
								}else{
									$locLat = $inv["location_zip_latitude"];
									$locLong = $inv["location_zip_longitude"];
								}									

								//	echo $locLong;
								$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
								$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

								$distA = Sin($distLat/2) * Sin($distLat/2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong/2) * Sin($distLong/2);
								//echo $inv["I"] . " " . $distA . "p <br/>"; 
								$distC = 2 * atan2(sqrt($distA),sqrt(1-$distA));
								
								$miles_from=(int) (6371 * $distC * .621371192);
								
								if ($miles_from <= 250)
								{	//echo "chk gr <br/>";
									$miles_away_color = "green";
								}
								if ( ($miles_from <= 550) && ($miles_from > 250))
								{	
									$miles_away_color = "#FF9933";//#FF9933
								}
								if (($miles_from > 550) )
								{	
									$miles_away_color = "red";
								}						
								
							}
							db();						
							$display="yes";
							if ($_REQUEST["showall_emp"] == "yes"){
								if ($logged_emp_id==$acc_ownerid){
									$display="yes";
								}
								else{
								$display="no";
								}
							}
						
						if($display=="yes" && $display_comp == "yes")
						{
							$srno = $srno + 1;
						
							$tot_trans = 0; $summtd_SUMPO = 0;
							if ($warehouse_id > 0){
								$qry_nn="select max_transaction_cnt as s_cnt from loop_warehouse where id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$tot_trans = $myrow_nn['s_cnt'];
								}

								$qry_nn="select sum(total_revenue) as total_revenue from loop_transaction_buyer where `ignore` = 0 and warehouse_id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$summtd_SUMPO = $myrow_nn['total_revenue'];
								}

								/*$qry_nn="select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$tot_trans = $tot_trans + $myrow_nn['s_cnt'];
								}

								$qry_s_nn = "SELECT loop_transaction_buyer.po_employee, transaction_date, total_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, 
								loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id 
								inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '". $warehouse_id . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
								$dt_view_res_nn = db_query($qry_s_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$inv_amt_totake= $myrow_nn["total_revenue"];

									$summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
								}*/
							}
						
							?>
							<tr>
								<td bgColor="#E4EAEB" width="50px" >
									<? echo $srno; //."--".$ff["loops_id"]."--".$ff["availability"] ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $sb["quote_id"]; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo date("m/d/Y" , strtotime($sb["quote_date"])); ?>
								</td>
								<td bgColor="#E4EAEB" width="300px" >		
									<a href="viewCompany.php?ID=<?=$sb["companyID"];?>" target="_blank"><? echo get_nickname_val('', $sb["companyID"]);?></a>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $acc_owner; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<font color='<?=$miles_away_color?>'><? echo $miles_from; ?></font>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? if ($last_contact_date != "") { echo date("m/d/Y" , strtotime($last_contact_date));} ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $sb["sb_item_length"] . "x" . $sb["sb_item_width"] . "x" . $sb["sb_item_height"]; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $sb["sb_quantity_requested"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_frequency_order"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_sales_desired_price"]; ?>
								</td>

								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_what_used_for"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_need_pallets"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_notes"]; ?>
								</td>

								<td bgColor="#E4EAEB" class="style12">
									<? echo $tot_trans; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									$<? echo $summtd_SUMPO; ?>
								</td>
								
							</tr>	
							<?						
							
							$MGArray[] = array('companyid' => $sb["companyID"], 'last_contact_date' => $last_contact_date, 'quote_id' => $sb["quote_id"], 'company' => get_nickname_val('', $sb["companyID"]), 'item_length' => $sb["sb_item_length"],
							'item_width' => $sb["sb_item_width"], 'item_height' => $sb["sb_item_height"], 'quantity_requested' => $sb["sb_quantity_requested"], 'quote_date' => $sb["quote_date"],
							'frequency_order' => $sb["sb_frequency_order"], 'what_used_for' => $sb["sb_what_used_for"], 'miles' => $miles_from, 'acc_owner' => $acc_owner,
							'tot_trans' => $tot_trans, 'summtd_SUMPO' => $summtd_SUMPO, 
							'need_pallets' => $sb["sb_need_pallets"], 'notes' => $sb["sb_notes"], 'acc_ownerid' => $acc_ownerid, 'desired_price' => $sb["sb_sales_desired_price"]);
							$_SESSION['sortarrayn_finbuy'] = $MGArray;
						}
						}
					}?>
					
					<script>
						window.location = "<?=$sorturl?>&sort_order_pre=ASC&sort=miles";
					</script>	
					<?
				}

				//pallets
				if ($inv["box_type"] == "PalletsUCB" || $inv["box_type"] == "PalletsnonUCB" ){
					
					db();
					$query_chk = db_query("Select * from quote_pallets inner join quote_request on quote_request.quote_id = quote_pallets.quote_id " ); 
					
					while($sb = array_shift($query_chk))
					{
						//$count = 5;
						$acc_owner = ""; $miles_from= 0;
						$pal_grade_a = $sb['pal_grade_a'];
						$pal_grade_b = $sb['pal_grade_b'];
						$pal_grade_c = $sb['pal_grade_c'];
						$pal_material_wooden = $sb['pal_material_wooden'];
						$pal_material_plastic = $sb['pal_material_plastic'];
						$pal_material_corrugate = $sb['pal_material_corrugate'];
						$pal_entry_2way = $sb['pal_entry_2way'];
						$pal_entry_4way = $sb['pal_entry_4way'];
						$pal_structure_stringer = $sb['pal_structure_stringer'];
						$pal_structure_block = $sb['pal_structure_block'];
						$pal_heat_treated = $sb['pal_heat_treated'];
						
						$show_rec_condition1 = "no";
						if($inv["grade"] == "" && $pal_grade_a == "" && $pal_grade_b == "" && $pal_grade_c == ""){
							$show_rec_condition1 = "yes";
						}else	if(($pal_grade_a == "Yes" && $inv["grade"] == "A") || ($pal_grade_b == "Yes" && $inv["grade"] == "B") || ($pal_grade_c == "Yes" && $inv["grade"] == "C") ) {
							$show_rec_condition1 = "yes";
						}

						$show_rec_condition2 = "no";
						if($inv["material"] == "" && $pal_material_wooden == "" && $pal_material_plastic == "" && $pal_material_corrugate == ""){
							$show_rec_condition2 = "yes";
						}else	if(($pal_material_wooden == "Yes" && $inv["material"] == "Wooden") || ($pal_material_plastic == "Yes" && $inv["material"] == "Plastic") || ($pal_material_corrugate == "Yes" && $inv["material"] == "Corrugate")) {
							$show_rec_condition2 = "yes";
						}

						$show_rec_condition3 = "no";
						if($inv["entry"] == "" && $pal_entry_2way == "" && $pal_entry_4way == ""){
							$show_rec_condition3 = "yes";
						}else	if(($pal_entry_2way == "Yes" && $inv["entry"] == "2-way") || ($pal_entry_4way == "Yes" && $inv["entry"] == "4-way")) {
							$show_rec_condition3 = "yes";
						}

						$show_rec_condition4 = "no";
						if($inv["structure"] == "" && $pal_structure_stringer == "" && $pal_structure_block == "" ){
							$show_rec_condition4 = "yes";
						}else	if(($pal_structure_stringer == "Yes" && $inv["structure"] == "Stringer") || ($pal_structure_block == "Yes" && $inv["structure"] == "Block")) {
							$show_rec_condition4 = "yes";
						}

						$show_rec_condition5 = "no";
						if($inv["heat_treated"] == ""){
							$show_rec_condition5 = "yes";
						}else	if($pal_heat_treated == $inv["heat_treated"]) {
							$show_rec_condition5 = "yes";
						}


						//echo $sb["companyID"] . " = " . $sb["quote_id"] . " = " . $show_rec_condition1 . " | " . $show_rec_condition2 . " | " . $show_rec_condition3 . " | " . $show_rec_condition4  . " | " .  $show_rec_condition5 . "<br>";
						//echo $gb["companyID"] . " | " . $inv["bwall_min"] . " | " . $inv["bwall_max"] . " | " . $bwall_min . " | " . $bwall_max . "<br>";

						if (( $_REQUEST["match_comp"] == "all_comp") || ( $show_rec_condition1 == 'yes' && $show_rec_condition2 == 'yes' && $show_rec_condition3 == 'yes' && $show_rec_condition4 == 'yes' && $show_rec_condition5 == 'yes')) {

						//if ($count > 4){
							$match_comp_id = $match_comp_id . $sb["companyID"] . ",";
						
							db_b2b();
							
							$warehouse_id = 0;
							$query_comp = db_query("SELECT shipZip, shipcountry, companyInfo.loopid, ship_zip_latitude, ship_zip_longitude, employees.initials as empname, last_contact_date, employees.employeeID FROM companyInfo left join employees on employees.employeeID= companyInfo.assignedto where ID = '" . $sb["companyID"] . "' and companyInfo.status not in (31, 43, 44, 24, 49)"); 
							$display_comp ="no";
							while ($row_comp = array_shift($query_comp)) 
							{
								$display_comp = "yes";	
								$warehouse_id = $row_comp["loopid"];
								$acc_owner = $row_comp["empname"];	
								$acc_ownerid = $row_comp["employeeID"];	
								$last_contact_date = $row_comp["last_contact_date"];

								$shipLat = $row_comp["ship_zip_latitude"];
								$shipLong = $row_comp["ship_zip_longitude"];

							//if((remove_non_numeric($row["shipZip"])) !="")
								if(($row_comp["shipZip"]) !="" && $shipLat == "")
								{
									$tmp_zipval = "";
									$tmp_zipval = str_replace(" ", "", $row_comp["shipZip"]);
									if($row_comp["shipcountry"] == "Canada" )
									{ 	
										$zipShipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
									}elseif(($row_comp["shipcountry"]) == "Mexico" ){
										$zipShipStr= "Select * from zipcodes_mexico limit 1";
									}else {
										$zipShipStr= "Select * from ZipCodes WHERE zip = '" . intval($row_comp["shipZip"]) . "'";
									}
													
									$dt_view_res = db_query($zipShipStr,db_b2b() );
									while ($zip = array_shift($dt_view_res)) {
										$shipLat = $zip["latitude"];
										$shipLong = $zip["longitude"];
									}
								}
							}
							
							if($inv["location_country"] == "Canada" )
							{ 	
								$tmp_zipval = str_replace(" ", "", $inv["location_zip"]);
								$zipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
							}elseif(($inv["location_country"]) == "Mexico" ){
								$zipStr= "Select * from zipcodes_mexico limit 1";
							}else {
								$zipStr= "Select * from ZipCodes WHERE zip = '" . intval($inv["location_zip"]) . "'";
							}
									
							if ($inv["location_zip"] != "")		
							{
								if ($inv["availability"] != "-3.5" )
								{
									$inv_id_list .= $inv["I"] . ",";
								}

								if ($inv["location_zip_latitude"] == "" ){
									$dt_view_res3 = db_query($zipStr,db_b2b() );
									while ($ziploc = array_shift($dt_view_res3)) {
										$locLat = $ziploc["latitude"];
										
										$locLong = $ziploc["longitude"];
										
									}
								}else{
									$locLat = $inv["location_zip_latitude"];
									
									$locLong = $inv["location_zip_longitude"];
								}

								//	echo $locLong;
								$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
								$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

								$distA = Sin($distLat/2) * Sin($distLat/2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong/2) * Sin($distLong/2);
								//echo $inv["I"] . " " . $distA . "p <br/>"; 
								$distC = 2 * atan2(sqrt($distA),sqrt(1-$distA));
								
								$miles_from=(int) (6371 * $distC * .621371192);
								
								if ($miles_from <= 250)
								{	//echo "chk gr <br/>";
									$miles_away_color = "green";
								}
								if ( ($miles_from <= 550) && ($miles_from > 250))
								{	
									$miles_away_color = "#FF9933";//#FF9933
								}
								if (($miles_from > 550) )
								{	
									$miles_away_color = "red";
								}						
								
							}
							db();						
						$display="yes";
						if ($_REQUEST["showall_emp"] == "yes"){
							if ($logged_emp_id==$acc_ownerid){
								$display="yes";
							}
							else{
							$display="no";
							}
						}
						
						//echo $sb["companyID"] . " = " . $display . " " . $display_comp . "<br>";
						if($display=="yes" && $display_comp == "yes")
						{
							$srno = $srno + 1;
							$tot_trans = 0; $summtd_SUMPO = 0;
							if ($warehouse_id > 0){
								$qry_nn="select max_transaction_cnt as s_cnt from loop_warehouse where id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$tot_trans = $myrow_nn['s_cnt'];
								}

								$qry_nn="select sum(total_revenue) as total_revenue from loop_transaction_buyer where `ignore` = 0 and warehouse_id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$summtd_SUMPO = $myrow_nn['total_revenue'];
								}
								
								/*$qry_nn="select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$tot_trans = $tot_trans + $myrow_nn['s_cnt'];
								}

								$qry_s_nn = "SELECT loop_transaction_buyer.po_employee, transaction_date, total_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, 
								loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id 
								inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '". $warehouse_id . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
								$dt_view_res_nn = db_query($qry_s_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$inv_amt_totake= $myrow_nn["total_revenue"];

									$summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
								}*/
							}
							
							?>
							<tr>
								<td bgColor="#E4EAEB" width="50px" >
									<? echo $srno; //."--".$ff["loops_id"]."--".$ff["availability"] ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $sb["quote_id"]; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo date("m/d/Y" , strtotime($sb["quote_date"])); ?>
								</td>
								<td bgColor="#E4EAEB" width="300px" >		
									<a href="viewCompany.php?ID=<?=$sb["companyID"];?>" target="_blank"><? echo get_nickname_val('', $sb["companyID"]);?></a>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $acc_owner; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<font color='<?=$miles_away_color?>'><? echo $miles_from; ?></font>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? if ($last_contact_date != "") { echo date("m/d/Y" , strtotime($last_contact_date));} ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $sb["sb_item_length"] . "x" . $sb["sb_item_width"] . "x" . $sb["sb_item_height"]; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $sb["sb_quantity_requested"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_frequency_order"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_sales_desired_price"]; ?>
								</td>

								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_what_used_for"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_need_pallets"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_notes"]; ?>
								</td>
								
								<td bgColor="#E4EAEB" class="style12">
									<? echo $tot_trans; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									$<? echo $summtd_SUMPO; ?>
								</td>
							</tr>	
							<?						
							
							$MGArray[] = array('companyid' => $sb["companyID"], 'last_contact_date' => $last_contact_date, 'quote_id' => $sb["quote_id"], 'company' => get_nickname_val('', $sb["companyID"]), 'item_length' => $sb["pal_item_length"],
							'item_width' => $sb["pal_item_width"], 'quantity_requested' => $sb["pal_quantity_requested"], 'quote_date' => $sb["quote_date"],
							'frequency_order' => $sb["pal_frequency_order"], 'what_used_for' => $sb["pal_what_used_for"], 'miles' => $miles_from, 'acc_owner' => $acc_owner,
							'tot_trans' => $tot_trans, 'summtd_SUMPO' => $summtd_SUMPO, 
							'need_pallets' => $sb["pal_need_pallets"], 'notes' => $sb["pal_note"], 'acc_ownerid' => $acc_ownerid, 'desired_price' => $sb["pal_sales_desired_price"]);
							$_SESSION['sortarrayn_finbuy'] = $MGArray;
						  }
						}
					}?>
					
					<script>
						window.location = "<?=$sorturl?>&sort_order_pre=ASC&sort=miles";
					</script>	
					<?
				}
				
				//Super Sack
				if ($inv["box_type"] == "SupersackUCB" || $inv["box_type"] == "SupersacknonUCB" ){
					
					db();
					$query_chk = db_query("Select * from quote_supersacks inner join quote_request on quote_request.quote_id = quote_supersacks.quote_id " ); 

					while($sb = array_shift($query_chk))
					{
						//$count = 5;
						$acc_owner = ""; $miles_from= 0;					
						//if ($count > 4){
							$match_comp_id = $match_comp_id . $sb["companyID"] . ",";
						
							db_b2b();
							
							$warehouse_id = 0;
							$query_comp = db_query("SELECT shipZip, companyInfo.loopid, shipcountry, ship_zip_latitude, ship_zip_longitude, employees.initials as empname, last_contact_date, employees.employeeID FROM companyInfo left join employees on employees.employeeID= companyInfo.assignedto where ID = '" . $sb["companyID"] . "' and companyInfo.status not in (31, 43, 44, 24, 49)"); 
							$display_comp ="no";
							while ($row_comp = array_shift($query_comp)) 
							{
								$display_comp = "yes";
								$warehouse_id = $row_comp["loopid"];
								$acc_owner = $row_comp["empname"];	
								$acc_ownerid = $row_comp["employeeID"];	
								$last_contact_date = $row_comp["last_contact_date"];

								$shipLat = $row_comp["ship_zip_latitude"];
								$shipLong = $row_comp["ship_zip_longitude"];

							//if((remove_non_numeric($row["shipZip"])) !="")
								if(($row_comp["shipZip"]) !="" && $shipLat == "")
								{
									$tmp_zipval = "";
									$tmp_zipval = str_replace(" ", "", $row_comp["shipZip"]);
									if($row_comp["shipcountry"] == "Canada" )
									{ 	
										$zipShipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
									}elseif(($row_comp["shipcountry"]) == "Mexico" ){
										$zipShipStr= "Select * from zipcodes_mexico limit 1";
									}else {
										$zipShipStr= "Select * from ZipCodes WHERE zip = '" . intval($row_comp["shipZip"]) . "'";
									}
													
									$dt_view_res = db_query($zipShipStr,db_b2b() );
									while ($zip = array_shift($dt_view_res)) {
										$shipLat = $zip["latitude"];
										$shipLong = $zip["longitude"];
									}
								}
							}
							
							if($inv["location_country"] == "Canada" )
							{ 	
								$tmp_zipval = str_replace(" ", "", $inv["location_zip"]);
								$zipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
							}elseif(($inv["location_country"]) == "Mexico" ){
								$zipStr= "Select * from zipcodes_mexico limit 1";
							}else {
								$zipStr= "Select * from ZipCodes WHERE zip = '" . intval($inv["location_zip"]) . "'";
							}
									
							if ($inv["location_zip"] != "")		
							{
								if ($inv["availability"] != "-3.5" )
								{
									$inv_id_list .= $inv["I"] . ",";
								}

								if ($inv["location_zip_latitude"] == "" ){
									$dt_view_res3 = db_query($zipStr,db_b2b() );
									while ($ziploc = array_shift($dt_view_res3)) {
										$locLat = $ziploc["latitude"];
										
										$locLong = $ziploc["longitude"];
										
									}
								}else{
									$locLat = $inv["location_zip_latitude"];
									
									$locLong = $inv["location_zip_longitude"];
								}

								//	echo $locLong;
								$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
								$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

								$distA = Sin($distLat/2) * Sin($distLat/2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong/2) * Sin($distLong/2);
								//echo $inv["I"] . " " . $distA . "p <br/>"; 
								$distC = 2 * atan2(sqrt($distA),sqrt(1-$distA));
								
								$miles_from=(int) (6371 * $distC * .621371192);
								
								if ($miles_from <= 250)
								{	//echo "chk gr <br/>";
									$miles_away_color = "green";
								}
								if ( ($miles_from <= 550) && ($miles_from > 250))
								{	
									$miles_away_color = "#FF9933";//#FF9933
								}
								if (($miles_from > 550) )
								{	
									$miles_away_color = "red";
								}						
								
							}
							db();						
						$display="yes";
						if ($_REQUEST["showall_emp"] == "yes"){
							if ($logged_emp_id==$acc_ownerid){
								$display="yes";
							}
							else{
							$display="no";
							}
						}
						
						if($display=="yes"  && $display_comp == "yes")
						{
							$srno = $srno + 1;
							$tot_trans = 0; $summtd_SUMPO = 0;
							if ($warehouse_id > 0){
								$qry_nn="select max_transaction_cnt as s_cnt from loop_warehouse where id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$tot_trans = $myrow_nn['s_cnt'];
								}

								$qry_nn="select sum(total_revenue) as total_revenue from loop_transaction_buyer where `ignore` = 0 and warehouse_id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$summtd_SUMPO = $myrow_nn['total_revenue'];
								}
								
								/*$qry_nn="select count(id) as s_cnt from loop_transaction_buyer where `ignore` = 0 and warehouse_id = '". $warehouse_id . "'";
								$dt_view_res_nn = db_query($qry_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$tot_trans = $tot_trans + $myrow_nn['s_cnt'];
								}

								$qry_s_nn = "SELECT loop_transaction_buyer.po_employee, transaction_date, total_revenue, loop_warehouse.b2bid, loop_warehouse.company_name, inv_date_of, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, 
								loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id 
								inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.warehouse_id = '". $warehouse_id . "' AND loop_transaction_buyer.ignore < 1 order by loop_transaction_buyer.id";
								$dt_view_res_nn = db_query($qry_s_nn , db() );
								while ($myrow_nn = array_shift($dt_view_res_nn)) 
								{
									$inv_amt_totake= $myrow_nn["total_revenue"];

									$summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
								}*/
							}
							
							?>
							<tr>
								<td bgColor="#E4EAEB" width="50px" >
									<? echo $srno; //."--".$ff["loops_id"]."--".$ff["availability"] ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $sb["quote_id"]; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo date("m/d/Y" , strtotime($sb["quote_date"])); ?>
								</td>
								<td bgColor="#E4EAEB" width="300px" >		
									<a href="viewCompany.php?ID=<?=$sb["companyID"];?>" target="_blank"><? echo get_nickname_val('', $sb["companyID"]);?></a>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $acc_owner; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<font color='<?=$miles_away_color?>'><? echo $miles_from; ?></font>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? if ($last_contact_date != "") { echo date("m/d/Y" , strtotime($last_contact_date));} ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $sb["sb_item_length"] . "x" . $sb["sb_item_width"] . "x" . $sb["sb_item_height"]; ?>
								</td>
								<td bgColor="#E4EAEB" width="100px" >
									<? echo $sb["sb_quantity_requested"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_frequency_order"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_sales_desired_price"]; ?>
								</td>

								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_what_used_for"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_need_pallets"]; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									<? echo $sb["sb_notes"]; ?>
								</td>
								
								<td bgColor="#E4EAEB" class="style12">
									<? echo $tot_trans; ?>
								</td>
								<td bgColor="#E4EAEB" class="style12">
									$<? echo $summtd_SUMPO; ?>
								</td>
								
							</tr>	
							<?						
							
							$MGArray[] = array('companyid' => $sb["companyID"], 'last_contact_date' => $last_contact_date, 'quote_id' => $sb["quote_id"], 'company' => get_nickname_val('', $sb["companyID"]), 'item_length' => $sb["sup_item_length"],
							'item_width' => $sb["sup_item_width"], 'quantity_requested' => $sb["sup_quantity_requested"], 'quote_date' => $sb["quote_date"],
							'frequency_order' => $sb["sup_frequency_order"], 'what_used_for' => $sb["sup_what_used_for"], 'miles' => $miles_from, 'acc_owner' => $acc_owner,
							'tot_trans' => $tot_trans, 'summtd_SUMPO' => $summtd_SUMPO, 
							'need_pallets' => $sb["sup_need_pallets"], 'notes' => $sb["sup_notes"], 'acc_ownerid' => $acc_ownerid, 'desired_price' => $sb["sup_sales_desired_price"]);
							$_SESSION['sortarrayn_finbuy'] = $MGArray;
						  }
						//}
					}?>
					
					<script>
						window.location = "<?=$sorturl?>&sort_order_pre=ASC&sort=miles";
					</script>	
					<?
				}

				if($inv["box_type"] != "Gaylord" || $inv["box_type"] != "GaylordUCB" || $inv["box_type"] != "Loop" || $inv["box_type"] != "PresoldGaylord" || $inv["box_type"] != "LoopShipping" || $inv["box_type"] != "Box" || $inv["box_type"] != "Boxnonucb" || $inv["box_type"] != "Presold"	|| $inv["box_type"] != "Medium" || $inv["box_type"] != "Large" || $inv["box_type"] != "Xlarge" || $inv["box_type"] != "PalletsUCB" || $inv["box_type"] != "PalletsnonUCB" || $inv["box_type"] != "SupersackUCB" || $inv["box_type"] != "SupersacknonUCB"){

					echo '<tr><td bgColor="#E4EAEB" colspan="14">Demand matching tool does not work for any items which are not gaylords, shipping boxes, pallets or supersacks</td></tr>';
				}
				
		}
		?>
		</table>	
		<div ><i><font color="red">"END OF REPORT"</font></i></div>	
		<?
	}//end gayloard id!=""
}//end sort else
	?>
</div>
</body>
</html>
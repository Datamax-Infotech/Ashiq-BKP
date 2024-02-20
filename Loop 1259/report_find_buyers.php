<?php
session_start();

require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

$chkinitials =  $_COOKIE['userinitials'];
//
$matchStr_e= "Select * from employees WHERE initials='".$chkinitials."'";

db_b2b();

$res_e = db_query($matchStr_e);
$row_e=array_shift($res_e);
$logged_emp_id=$row_e["employeeID"];
//$logged_emp_id=39;
        //
?>

<html>
<head>
<title>Inventory Item Transaction History Report</title>
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet"> 
<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css' >
	
<!--FIND COMPANIES THAT BOUGHT A BOX-->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="js/jquery.js"></script>
<script language="JavaScript" src="gen_functions.js"></script>
<script language="javascript">
jQuery(document).ready(function($) {
	setInterval("timedCount()",5000);
})

</script>
<style>
	table.sortable {
  		border-collapse: collapse;
	}

	table.sortable td, table.sortable th {
	  border: 1px solid #FFF;
	  /*padding: 8px;*/
	}
 	table.sortable tr th{
        white-space: nowrap;
    }

    </style>
</head>

<body bgcolor="#FFFFFF" text="#333333" link="#333333" vlink="#666666" alink="#333333">
<script type="text/javascript" src="wz_tooltip.js"></script>
<? include("inc/header.php"); ?>
<div class="main_data_css">
	
	<div class="dashboard_heading" style="float: left;">
		<div style="float: left;">Inventory Item Transaction History Report</div>
		&nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
		<span class="tooltiptext">This report shows the user all of the instances where this inventory item was sold, per transaction.</span></div>
		<!-- <div style="height: 13px;">&nbsp;</div> -->
	</div>
	
<!-- <p style="font-size:10pt;"><a href='dashboardnew.php'>Back to Dashboard</a></p> -->

<table border="0" width="100%" cellspacing="0" cellpadding="5">
  <tr>
    <td width="33%" valign="top"></td>
    <td width="33%" valign="top"></td>
    <td width="34%" valign="top"></td>
  </tr>
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

<form action=report_find_buyers.php method=get>
<!-- <h2 style="margin-bottom: 4px;">INVENTORY ITEM TRANSACTION HISTORY</h2>
<div style="margin-bottom: 15px;"><i>This report shows the user all of the instances where this inventory item was sold, per transaction.</i></div> -->
<div ><i>Note: Please wait until you see <font color="red">"END OF REPORT"</font> at the bottom of the report, before using the sort option.</i></div>
<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#E4E4E4">


<tr align="center">
<td colspan="2" bgcolor="#C0CDDA"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Boxes</font></td>
</tr>
<tr align="center">
<td colspan="2" bgcolor="#C0CDDA"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
<?

db();
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
</font></td>
</tr>
<tr>
<td>
<input type=submit value="Find Matches">
</td>
  <td align="right">
      <input type="checkbox" name="showall_emp" id="showall_emp" value="yes" <? if (empty($_GET["showall_emp"])){ } else { echo " checked "; }?> onclick="if(this.checked){this.form.submit()}else{this.form.submit()}" >Show only my record(s)
    </td>
 </tr>
</table>
</form>

	
	<br>
        <?php $sorturl="report_find_buyers.php?gaylordID=".$_REQUEST['gaylordID']; 
        if (isset($_REQUEST["showall_emp"])){ 
            $sorturl.="&showall_emp=yes";
        }else{
        } 
    ?>
	<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#E4E4E4" id="table" class="sortable">
		<thead>
			<tr>
				<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Sr. No.</strong></th>
				
				<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Date of Transaction</strong>
                  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=dt_trans"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=dt_trans"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>  
                </th>
				<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Transaction ID</strong>
                    <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=trans_id"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=trans_id"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                   </font>  
                </th>
				<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company</strong>
                  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=company"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=company"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font>  
                </th>
				<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Rep</strong>
                  <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=rep"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=rep"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                  </font>    
                </th>
				
				<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Last Contact Date</strong>
                    <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=lastcrmdate"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=lastcrmdate"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                   </font> 
                </th>
				<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Account Status</strong>
                     <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=acc_status"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=acc_status"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                   </font> 
				</th>
				<th bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>FOB Price</strong>
                     <font size="1" face="Arial, Helvetica, sans-serif" color="#333333">&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=ASC&sort=fob"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<? echo $sorturl; ?>&sort_order_pre=DESC&sort=fob"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                   </font> 
				</th>
			</tr>
			
			
		</thead>
		<tbody>
		<?
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
			if($_GET['sort'] == "selltoeml")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['selltoeml'];
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
			if($_GET['sort'] == "rep")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['rep'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			
			if($_GET['sort'] == "dt_trans")
			{
					$MGArraysort_I = array();
				    foreach ($MGArray as $MGArraytmp) {
					   $MGArraysort_I[] = strtotime($MGArraytmp['transdtsort']);
					}
				
				/*foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['transdate'];
				}*/
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
				}
			}
			if($_GET['sort'] == "trans_id")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['trans_rec_id'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_GET['sort'] == "lastcrmdate")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['lastcrmdt_sort'];
				}
				if ($_GET['sort_order_pre'] == "ASC") {
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_GET['sort_order_pre'] == "DESC") {
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_GET['sort'] == "fob")
			{
				$MGArraysort_I = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['min_fobval'];
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
            if($MGArraytmp2["rep"]!="")
            {
				$srno = $srno +1;
		?>
			<tr>
				<td bgColor="#E4EAEB" class="style12">
					<? echo $srno; ?>
				</td>
				<td bgColor="#E4EAEB" class="style12">
					<? echo $MGArraytmp2["transdate"]; ?>
				</td>
				<td bgColor="#E4EAEB" class="style12">		
					<a target="_blank" href="viewCompany.php?ID=<?=$MGArraytmp2["companyid"];?>&show=transactions&warehouse_id=<?=$MGArraytmp2["warehouse_id"];?>&rec_type=Supplier&proc=View&searchcrit=&id=<?=$MGArraytmp2["warehouse_id"];?>&rec_id=<?=$MGArraytmp2["trans_rec_id"];?>&display=buyer_view" target="_blank" >
                        <?=$MGArraytmp2["trans_rec_id"];?>
                    </a>
				</td>
				<td bgColor="#E4EAEB" class="style12">		
					<a target="_blank" href="viewCompany.php?ID=<?=$MGArraytmp2["companyid"];?>&show=transactions&warehouse_id=<?=$MGArraytmp2["warehouse_id"];?>&rec_type=Supplier&proc=View&searchcrit=&id=<?=$MGArraytmp2["warehouse_id"];?>&rec_id=<?=$MGArraytmp2["trans_rec_id"];?>&display=buyer_view" target="_blank" >
                        <?=$MGArraytmp2["company"];?>
                    </a>
				</td>
				<td bgColor="#E4EAEB" class="style12">
					<? echo $MGArraytmp2["rep"]; ?>
				</td>
				<td bgColor="#E4EAEB" class="style12" align="center">
					<? echo $MGArraytmp2["lastcrmdate"]; ?>
				</td>
				<td bgColor="#E4EAEB" class="style12">
					<? echo $MGArraytmp2["acc_status"]; ?>
				</td>
				<td bgColor="#E4EAEB" class="style12">
					<? 
					echo '$' . number_format((($MGArraytmp2["quote_total"] - $MGArraytmp2["freight_cost"])/$MGArraytmp2["salesorder_qty"]),2);					
						
					if (number_format((($MGArraytmp2["quote_total"] - $MGArraytmp2["freight_cost"])/$MGArraytmp2["salesorder_qty"]),2) >= number_format($MGArraytmp2["weighted_average_fin"],2)) {
						?>
						<!-- <font color="green">$<? echo number_format((($MGArraytmp2["quote_total"] - $MGArraytmp2["freight_cost"])/$MGArraytmp2["salesorder_qty"]),2);?></font> -->
				<? }else{?>
						<!-- <font color="red">$<? echo number_format((($MGArraytmp2["quote_total"] - $MGArraytmp2["freight_cost"])/$MGArraytmp2["salesorder_qty"]),2);?></font> -->
				<? }?>
				</td>
			</tr>		
		<?
			}
        }
        ?>
		</table>	
		<div ><i><font color="red">"END OF REPORT"</font></i></div>	
			
	<?}else{

		if ($_REQUEST["gaylordID"] != "") {
			db();            
            if ($_REQUEST["showall_emp"] == "yes"){
				$queryb=db_query("select * from loop_manage_quote_buy_temp where report_name='report_find_buyers' AND employeeid = '" .$logged_emp_id."' AND gid=".$_REQUEST["gaylordID"] . " order by rec_id desc"); 
            }
            else
			{
				 $queryb=db_query("select * from loop_manage_quote_buy_temp where report_name='report_find_buyers' and gid=".$_REQUEST["gaylordID"] . " order by rec_id desc"); 
			}
            
            $numrec=tep_db_num_rows($queryb);
            if($numrec>0)
            {
                $srno =0;
                //
                while($objGBmatch=array_shift($queryb))
                {
                       $srno = $srno +1;
                    //
						$lastcrmdt = $objGBmatch["last_contact_date"];
						if ($lastcrmdt != ""){
						$lastcrmdt_sort = date("Y-m-d" , strtotime($lastcrmdt));
						$lastcrmdt = date("m/d/Y" , strtotime($lastcrmdt));
					}else{
						$lastcrmdt_sort = "";
						$lastcrmdt = "";
					}	
					
						//Transaction Date
						$po_poorderamount = 0;
					 	$querytrans=db_query("select * from loop_transaction_buyer where id='".$objGBmatch["rec_id"]."'",db()); 
						$dtt_view_res=array_shift($querytrans);
						$tdatesort=$dtt_view_res["transaction_date"];
						$transdate=date("m/d/Y" , strtotime($dtt_view_res["transaction_date"]));
					//
						$quote_number = $dtt_view_res["quote_number"];
						$freight_cost = $dtt_view_res["po_freight"];
						$po_poorderamount = $dtt_view_res["po_poorderamount"];
					//----	
					$salesorder_qty = 0; $weighted_average = 0;
					$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = '".  $objGBmatch["rec_id"]."'");
					while ($dtt_view_res = array_shift($get_sales_order)) {
						$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];

						//get Min FOB
						db_b2b();
						$min_fob = 0;
						$get_box_data = db_query("Select ulineDollar, ulineCents from inventory where ID = ".  $dtt_view_res["b2b_id"]);
							while ($box_data_res = array_shift($get_box_data)) {
							$b2b_ulineDollar = round($box_data_res["ulineDollar"]);
							$b2b_ulineCents = $box_data_res["ulineCents"];
							$min_fob = $b2b_ulineDollar + $b2b_ulineCents;
						}	
						
						if ($min_fob > 0) {
							$weighted_average = $weighted_average + ($dtt_view_res["qty"] * $min_fob);
						}	
						
						db();
					}
					$weighted_average_fin =  $weighted_average / $salesorder_qty;

					$get_sales_order = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = '".  $objGBmatch["rec_id"]."'");
					while ($dtt_view_res = array_shift($get_sales_order)) {
						$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
					}
					//
					$quote_total = $po_poorderamount;
					
					db_b2b();
					$emp_nm = "";
					$get_sales_order = db_query("Select initials from employees where employeeID = '".  $objGBmatch["employeeid"]."'");
					while ($dtt_view_res = array_shift($get_sales_order)) {
						$emp_nm = $dtt_view_res["initials"];
					}
					
					db();
					//

						?>
					<tr>
						<td bgColor="#E4EAEB" class="style12">
							<? echo $srno; //."--".$ff["loops_id"]."--".$ff["availability"] ?>
						</td>
						<td bgColor="#E4EAEB" class="style12">
						<? echo $transdate; ?>
						</td>
						<td bgColor="#E4EAEB" class="style12">		
							<a href="viewCompany.php?ID=<?=$objGBmatch["companyid"];?>&show=transactions&warehouse_id=<?=$objGBmatch["warehouse_id"];?>&rec_type=Supplier&proc=View&searchcrit=&id=<?=$objGBmatch["warehouse_id"];?>&rec_id=<?=$objGBmatch["rec_id"];?>&display=buyer_view" target="_blank"><?=$objGBmatch["rec_id"];?></a>
						</td>
						<td bgColor="#E4EAEB" class="style12">		
							<a href="viewCompany.php?ID=<?=$objGBmatch["companyid"];?>&show=transactions&warehouse_id=<?=$objGBmatch["warehouse_id"];?>&rec_type=Supplier&proc=View&searchcrit=&id=<?=$objGBmatch["warehouse_id"];?>&rec_id=<?=$objGBmatch["rec_id"];?>&display=buyer_view" target="_blank"><?=$objGBmatch["company"];?></a>
						</td>
						<td bgColor="#E4EAEB" class="style12">
							<? echo $emp_nm; ?>
						</td>
					
						<td bgColor="#E4EAEB" class="style12" align="center">
							<? echo $objGBmatch["last_contact_date"]; ?>
						</td>
						<td bgColor="#E4EAEB" class="style12">
							<? echo $objGBmatch["account_status"]; ?>
						</td>
						<td bgColor="#E4EAEB" class="style12">
						<? 
						$min_fobval=number_format((($quote_total - $freight_cost)/$salesorder_qty),2);
						//echo ($quote_total - $freight_cost) . " - " .  $salesorder_qty . " - " . $weighted_average_fin . "<br>";
						if (number_format((($quote_total - $freight_cost)/$salesorder_qty),2) >= number_format($weighted_average_fin,2)) {
							?>
							<font color="green"><? 
								echo "$" . $min_fobval; ?></font>
							<? }else{?>
								<font color="red"><? 
									echo "$" . $min_fobval; ?>
								</font>
							<? }?>
							</td>
					</tr>	
				<?
					$MGArray[] = array('companyid' => $objGBmatch["companyid"], 'emp_nm' => $emp_nm, 'company' => $objGBmatch["company"], 'acc_status' => $objGBmatch["account_status"], 'selltoeml' => $objGBmatch["sell_to_email"],  
					'rep' => $objGBmatch["account_owner"], 'warehouse_id' => $objGBmatch["warehouse_id"], 'trans_rec_id' => $objGBmatch["rec_id"], 'lastcrmdate'=> $objGBmatch["last_contact_date"], 'lastcrmdt_sort' => $lastcrmdt_sort, 'transdate'=>$transdate,'quote_total'=>$quote_total, 'freight_cost'=>$freight_cost, 'salesorder_qty'=>$salesorder_qty,'weighted_average_fin'=>$weighted_average_fin,'min_fobval'=>$min_fobval, 'transdtsort'=>$tdatesort);
					$_SESSION['sortarrayn_finbuy'] = $MGArray;
				}  
                
            }//end if
            else
            {
				db();
				$matchStr = "Select loop_bol_tracking.trans_rec_id, loop_bol_tracking.box_id, loop_bol_tracking.warehouse_id, loop_warehouse.company_name AS CN, loop_warehouse.b2bid ";
				$matchStr .= " from loop_bol_tracking INNER JOIN loop_warehouse ON loop_bol_tracking.warehouse_id = loop_warehouse.id Where box_id = " . $_REQUEST["gaylordID"];
				$res1 = db_query($matchStr);
				
				$srno = 0;
				while ($objGBmatch = array_shift($res1)) {
					db_b2b();
					$f=db_query("select * from inventory where loops_id=".$_REQUEST["gaylordID"]);
					$ff = array_shift($f); 
				
					$lastcrmdt = ""; $lastcrmdt_sort = "";
					$lastcrmdt_eml = "";
					$nickname = ""; $empname = "";
					if ($objGBmatch["b2bid"] > 0) {

						$acc_status = ""; $email = "";
						if ($_GET["showall_emp"] == "yes"){
							$sql = "SELECT companyInfo.status, companyInfo.email, nickname, last_contact_date, company, shipCity, shipState, employees.name as empname, employees.initials FROM companyInfo left join employees on employees.employeeID= companyInfo.assignedto where ID = " . $objGBmatch["b2bid"]." AND employeeID = '" . $logged_emp_id."'";
						}
						else
						{
							$sql = "SELECT companyInfo.status, companyInfo.email, nickname, last_contact_date, company, shipCity, shipState, employees.name as empname, employees.initials FROM companyInfo left join employees on employees.employeeID= companyInfo.assignedto where ID = " . $objGBmatch["b2bid"];
						}
						
						db_b2b();
						$result = db_query($sql);
						$num_emp=tep_db_num_rows($result);
						while ($myrowsel = array_shift($result)) {
							$lastcrmdt = date("m/d/Y", strtotime($myrowsel["last_contact_date"]));
							
							$result_2 = db_query("select name from status where id = " . $myrowsel["status"]);
							while ($myrowsel_2 = array_shift($result_2)) {
								$acc_status = $myrowsel_2["name"];
							}
							
							$email = $myrowsel["email"];
							
							$empname = $myrowsel["initials"];
							if ($myrowsel["nickname"] != "") {
								$nickname = $myrowsel["nickname"];
							}else {
								$tmppos_1 = strpos($myrowsel["company"], "-");
								if ($tmppos_1 != false)
								{
									$nickname = $myrowsel["company"];
								}else {
									if ($myrowsel["shipCity"] <> "" || $myrowsel["shipState"] <> "" ) 
									{
										$nickname = $myrowsel["company"] . " - " . $myrowsel["shipCity"] . ", " . $myrowsel["shipState"] ;
									}else { $nickname = $myrowsel["company"]; }
								}
							}
						}
					
						if ($lastcrmdt != ""){
							$lastcrmdt_sort = date("Y-m-d" , strtotime($lastcrmdt));
							$lastcrmdt = date("m/d/Y" , strtotime($lastcrmdt));
						}else{
							$lastcrmdt_sort = "";
							$lastcrmdt = "";
						}	
						//
						//Transaction Date
						$po_poorderamount = 0;
					 	$querytrans=db_query("select * from loop_transaction_buyer where id='".$objGBmatch["trans_rec_id"]."'",db()); 
						$dtt_view_res=array_shift($querytrans);
						$tdatesort=$dtt_view_res["transaction_date"];
						$transdate=date("m/d/Y" , strtotime($dtt_view_res["transaction_date"]));
					//
						$quote_number = $dtt_view_res["quote_number"];
						$freight_cost = $dtt_view_res["po_freight"];
						$po_poorderamount = $dtt_view_res["po_poorderamount"];
					//----	
					$salesorder_qty = 0; $weighted_average = 0;
					$get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = '".  $objGBmatch["trans_rec_id"]."'");
					while ($dtt_view_res = array_shift($get_sales_order)) {
						$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];

						//get Min FOB
						db_b2b();
						$min_fob = 0;
						$get_box_data = db_query("Select ulineDollar, ulineCents from inventory where ID = ".  $dtt_view_res["b2b_id"]);
							while ($box_data_res = array_shift($get_box_data)) {
							$b2b_ulineDollar = round($box_data_res["ulineDollar"]);
							$b2b_ulineCents = $box_data_res["ulineCents"];
							$min_fob = $b2b_ulineDollar + $b2b_ulineCents;
						}		
						db();
					}
					$weighted_average_fin =  $weighted_average / $salesorder_qty;

					$get_sales_order = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = '".  $objGBmatch["trans_rec_id"]."'");
					while ($dtt_view_res = array_shift($get_sales_order)) {
						$salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
					}
					//
					$quote_total = $po_poorderamount;
					
					//
					}
					
					db();
				
					$MGArray[] = array('companyid' => $objGBmatch["b2bid"], 'company' => $nickname, 'acc_status' => $acc_status, 'selltoeml' => $email,  
					'rep' => $empname, 'warehouse_id' => $objGBmatch["warehouse_id"], 'trans_rec_id' => $objGBmatch["trans_rec_id"], 'lastcrmdate'=> $lastcrmdt, 'lastcrmdt_sort' => $lastcrmdt_sort, 'transdate'=>$transdate,'quote_total'=>$quote_total, 'freight_cost'=>$freight_cost, 'salesorder_qty'=>$salesorder_qty,'weighted_average_fin'=>$weighted_average_fin,'min_fobval'=>$min_fobval, 'transdtsort'=>$tdatesort);
					$_SESSION['sortarrayn_finbuy'] = $MGArray;

					if($num_emp>0)
					{
						$srno = $srno +1;
					?>	
						
						<tr>
							<td bgColor="#E4EAEB" class="style12">
									<? echo $srno; //."--".$ff["loops_id"]."--".$ff["availability"] ?>
							</td>
							<td bgColor="#E4EAEB" class="style12">
									<? echo $transdate; ?>
							</td>
							<td bgColor="#E4EAEB" class="style12">
								<a href="viewCompany.php?ID=<?=$objGBmatch["b2bid"];?>&show=transactions&warehouse_id=<?=$objGBmatch["warehouse_id"];?>&rec_type=Supplier&proc=View&searchcrit=&id=<?=$objGBmatch["warehouse_id"];?>&rec_id=<?=$objGBmatch["trans_rec_id"];?>&display=buyer_view" target="_blank">
									<? echo $objGBmatch["trans_rec_id"]; ?>
								</a>
							</td>
							<td bgColor="#E4EAEB" class="style12">		
								<a href="viewCompany.php?ID=<?=$objGBmatch["b2bid"];?>&show=transactions&warehouse_id=<?=$objGBmatch["warehouse_id"];?>&rec_type=Supplier&proc=View&searchcrit=&id=<?=$objGBmatch["warehouse_id"];?>&rec_id=<?=$objGBmatch["trans_rec_id"];?>&display=buyer_view" target="_blank"><?=$nickname;?></a>
							</td>
							<td bgColor="#E4EAEB" class="style12">
								<? echo $empname; ?>
							</td>
							<td bgColor="#E4EAEB" class="style12" align="center">
								<? echo $lastcrmdt; ?>
							</td>
							<td bgColor="#E4EAEB" class="style12">
								<? echo $acc_status; ?>
							</td>
						<td bgColor="#E4EAEB" class="style12">
						<? 
						$min_fobval=number_format((($quote_total - $freight_cost)/$salesorder_qty),2);
						//echo '$' . $min_fobval;
						if (number_format((($quote_total - $freight_cost)/$salesorder_qty),2) >= number_format($weighted_average_fin,2)) {
							?>
								<font color="green"><? 
								echo "$" . $min_fobval; ?></font>
							<? }else{?>
								<font color="red"><? 
									 echo "$" . $min_fobval; ?>
								</font>
							<? }?>
							</td>
						</tr>		
					<?
					}
				} 
            }//end else
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
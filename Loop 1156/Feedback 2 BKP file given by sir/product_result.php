<?php
/*
File Name: product_result.php
Page created By: Bhavna
Page created On: 04-09-2023
Last Modified On: 
Last Modified By: Bhavna
Change History:
Date           By            system_description
===============================================================================================================
04-09-2023      Bhavna     This file is created for the inventory.
							
===============================================================================================================
*/

require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

function encrypt_password($txt){
	$key = "1sw54@$sa$offj";
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($txt, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
	$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
	return $ciphertext;
}

$enter_zipcode = "";
$final_filter_str="";
	if(isset($_REQUEST["all_shape_data"]) && isset($_REQUEST["all_shape_data"])!="" ){
		$box_shape_val = $_REQUEST["all_shape_data"];
		if(in_array('1', $box_shape_val)){
			$box_shape = " AND inventory.shape_rect = 1";
		}
		if(in_array('2', $box_shape_val)){
			if($box_shape == ""){
				$box_shape = " AND inventory.shape_oct = 1";
			}else{
				$box_shape .= " OR inventory.shape_oct = 1";
			}
		}
		$final_filter_str.=$box_shape;
	}
	
	$sql_wall = "";
	if(isset($_REQUEST["all_thickness_data"]) && $_REQUEST["all_thickness_data"] != ""){
		$wall_val = $_REQUEST["all_thickness_data"];
		for($i=0; $i<count($wall_val);$i++){
			$wall=$wall_val[$i];
			if($sql_wall == ""){
				$sql_wall = " AND (inventory.bwall = ". $wall;
				if (count($wall_val) == 1){
					$sql_wall = $sql_wall . ") ";
				}
			}else{
				$sql_wall = $sql_wall . " OR inventory.bwall = ". $wall;
			}
		}
		if ($sql_wall != "" && count($wall_val) > 1){
			$sql_wall = $sql_wall . ") ";
		}
		$final_filter_str.=$sql_wall;
	}
	
	if(isset($_REQUEST["all_top_data"]) && $_REQUEST["all_top_data"]!=""){
		$top_config = "";
		$top_config_val = $_REQUEST["all_top_data"];
		$top_config_val_3 = ""; $top_config_val_4 = "";
		if(in_array('3', $top_config_val)){
			$top_config_val_3 = "yes";
		}
		if(in_array('4', $top_config_val)){
			$top_config_val_4 = "yes";
		}

		if(in_array('1', $top_config_val)){
			if ($top_config_val_3 == "yes" || $top_config_val_4 == "yes"){
				$top_config = " AND (inventory.top_nolid = 1";
			}else{
				$top_config = " AND inventory.top_nolid = 1";
			}
		}
		
		if(in_array('2', $top_config_val)){
			if($top_config == ""){
				if ($top_config_val_3 == "yes" || $top_config_val_4 == "yes"){
					$top_config = " AND (inventory.top_nolid = 1";
				}else{
					$top_config = " AND inventory.top_nolid = 1";
				}
			}
		}
		
		if(in_array('3', $top_config_val)){
			if($top_config == ""){
				if (in_array('4', $top_config_val)){
					$top_config = " AND ( inventory.top_partial = 1";
				}else{
					$top_config = " AND inventory.top_partial = 1";
				}				
			}else{
				if(in_array('4', $top_config_val)){
					$top_config = $top_config . " OR inventory.top_partial = 1";
				}else{
					$top_config = $top_config . " OR inventory.top_partial = 1) ";
				}				
			}
		}
			
		if(in_array('4', $top_config_val)){
			if($top_config == ""){
				$top_config = " AND inventory.top_full = 1";
			}else{
				$top_config = $top_config ." OR inventory.top_full = 1) ";
			}
		}
		$final_filter_str.=$top_config;
	}
	
	if(isset($_REQUEST["all_bottom_data"]) && $_REQUEST["all_bottom_data"]!=""){
		$bottom_config = "";
		$bottom_config_val =$_REQUEST["all_bottom_data"];

		if(in_array('1', $bottom_config_val)){
			if (in_array('2', $bottom_config_val) || in_array('3', $bottom_config_val) || in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)){
				$bottom_config = " AND ( inventory.bottom_no = 1";
			}else{
				$bottom_config = " AND inventory.bottom_no = 1";
			}
		}
		
		if(in_array('2', $bottom_config_val)){
			if($bottom_config == ""){
				if (in_array('3', $bottom_config_val) || in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)){			
					$bottom_config = " AND (inventory.bottom_partial = 1";
				}else{
					$bottom_config = " AND inventory.bottom_partial = 1";
				}
			}else{
				if (in_array('3', $bottom_config_val) || in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)){			
					$bottom_config = $bottom_config . " OR inventory.bottom_partial = 1 ";
				}else{
					$bottom_config = $bottom_config . " OR inventory.bottom_partial = 1) ";
				}
			}
		}
		
		if(in_array('3', $bottom_config_val)){
			if($bottom_config == ""){
				if (in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)){			
					$bottom_config = " AND (inventory.bottom_tray = 1";
				}else{
					$bottom_config = " AND inventory.bottom_tray = 1";
				}
			}else{
				if (in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)){			
					$bottom_config = $bottom_config . " OR inventory.bottom_tray = 1";
				}else{
					$bottom_config = $bottom_config . " OR inventory.bottom_tray = 1) ";
				}
			}
		}
			
		if(in_array('4', $bottom_config_val)){
			if($bottom_config == ""){
				if (in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)){			
					$bottom_config = " AND (inventory.bottom_fullflap = 1";
				}else{
					$bottom_config = " AND inventory.bottom_fullflap = 1";
				}
			}else{
				if (in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)){			
					$bottom_config = $bottom_config . " OR inventory.bottom_fullflap = 1";
				}else{
					$bottom_config = $bottom_config . " OR inventory.bottom_fullflap = 1) ";
				}
			}
		}
		if(in_array('5', $bottom_config_val)){
			if($bottom_config == ""){
				if (in_array('6', $bottom_config_val)){			
					$bottom_config = " AND (inventory.bottom_partialsheet = 1";
				}else{
					$bottom_config = " AND inventory.bottom_partialsheet = 1";
				}
			}else{
				if (in_array('6', $bottom_config_val)){			
					$bottom_config = $bottom_config . " OR inventory.bottom_partialsheet = 1 ";
				}else{
					$bottom_config = $bottom_config . " OR inventory.bottom_partialsheet = 1) ";
				}
			}
		}
		
		if(in_array('6', $bottom_config_val)){
			if($bottom_config == ""){
				$bottom_config = " AND inventory.bottom_flat = 1";
			}else{
				$bottom_config = $bottom_config . " OR inventory.bottom_flat = 1)";
			}
		}
		$final_filter_str.=$bottom_config;
	}

	if(isset($_REQUEST["all_vents_data"]) && isset($_REQUEST["all_vents_data"])!="" ){
		$box_shape_val = $_REQUEST["all_vents_data"];
		if(in_array('1', $box_shape_val)){
			$box_shape = " AND inventory.vents_yes = 1";
		}
		if(in_array('2', $box_shape_val)){
			if($box_shape == ""){
				$box_shape = " AND inventory.vents_no = 1";
			}else{
				$box_shape .= " OR inventory.vents_no = 1";
			}
		}
		$final_filter_str.=$box_shape;
	}

    if(isset($_REQUEST["min_length"]) || isset($_REQUEST['max_length'])){
		if($_REQUEST["min_length"] != 0 || $_REQUEST["max_length"] != 99){
            $final_filter_str .= " AND ((lengthInch + if(lengthFraction > 0, CAST(SUBSTRING_INDEX(lengthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(lengthFraction, '/', -1) AS DECIMAL(10,2)), 0)) >= ". $_REQUEST["min_length"] ." 
			and (lengthInch + if(lengthFraction > 0, CAST(SUBSTRING_INDEX(lengthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(lengthFraction, '/', -1) AS DECIMAL(10,2)), 0)) <= ". $_REQUEST["max_length"] .")";
		}
	}
    
    if(isset($_REQUEST["min_width"]) || isset($_REQUEST['max_width'])){
        if($_REQUEST["min_width"] != 0 || $_REQUEST["max_width"] != 99){
            $final_filter_str .= " AND ((widthInch + if(widthFraction > 0, CAST(SUBSTRING_INDEX(widthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(widthFraction, '/', -1) AS DECIMAL(10,2)), 0)) >= ". $_REQUEST["min_width"] ." 
			and (widthInch + if(widthFraction > 0, CAST(SUBSTRING_INDEX(widthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(widthFraction, '/', -1) AS DECIMAL(10,2)), 0)) <= ". $_REQUEST["max_width"] .")";
		}
	}
	
	if(isset($_REQUEST["min_height"]) || isset($_REQUEST['max_height'])){
		if($_REQUEST["min_height"] != 0 || $_REQUEST["max_height"] != 99){
			//$final_filter_str.= " AND (CONVERT(SUBSTRING_INDEX(LWH, 'x', -1), UNSIGNED INTEGER) >= ". $_REQUEST["min_height"] ." AND CONVERT(SUBSTRING_INDEX(LWH, 'x', -1), UNSIGNED INTEGER) <= ".$_REQUEST["max_height"]." )" ;
			
			$final_filter_str .= " AND ((depthInch + if(depthFraction > 0, CAST(SUBSTRING_INDEX(depthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(depthFraction, '/', -1) AS DECIMAL(10,2)), 0)) >= ". $_REQUEST["min_height"] ." 
			and (depthInch + if(depthFraction > 0, CAST(SUBSTRING_INDEX(depthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(depthFraction, '/', -1) AS DECIMAL(10,2)), 0)) <= ". $_REQUEST["max_height"] .")";
            
		}
	}
    
    if(isset($_REQUEST["min_cubic_footage"]) || isset($_REQUEST['max_cubic_footage'])){
        if($_REQUEST["min_cubic_footage"] != 0.00 || $_REQUEST["max_cubic_footage"] != 99.99){
            $final_filter_str .= " AND cubicFeet >= ". $_REQUEST["min_cubic_footage"] ." AND cubicFeet <= ". $_REQUEST["max_cubic_footage"] ."";
		}
	}


	// if(isset($_REQUEST['box_type']) && $_REQUEST['box_type'] == "Gayload"){
	// 	if(isset($_REQUEST['rectangular']) || isset($_REQUEST['octagonal'])){
	// 		$final_filter_str .= "AND ";
	// 		shape_rect
	// 	}
	// }

	$dropoff_add1 = strval($_REQUEST["txtaddress"]); 
	$dropoff_add2 = strval($_REQUEST["txtaddress2"]); 
	$dropoff_city = strval($_REQUEST["txtcity"]); 
	$dropoff_state = strval($_REQUEST["txtstate"]); 
	$dropoff_country = $_REQUEST["txtcountry"]; 
	if (strtolower($_REQUEST["txtcountry"]) == "usa"){
		$dropoff_zip = substr(strval($_REQUEST["txtzipcode"]),0,5); 
	}else{
		$dropoff_zip = $_REQUEST["txtzipcode"]; 
	}	

	// if(isset($_REQUEST['vents']) && $_REQUEST['vents'] == 1){
	// 	$final_filter_str.= " AND inventory.vents_yes=1";
	// }
	if(isset($_REQUEST['include_sold_out_items']) && $_REQUEST['include_sold_out_items'] == 1){
		//$final_filter_str.= " AND inventory.quantity_available > 0";
	}
	if(isset($_REQUEST['include_presold_and_loops']) && $_REQUEST['include_presold_and_loops'] == 1){
       // $final_filter_str.= " AND inventory.box_type IN('Gaylord','GaylordUCB', 'Loop','PresoldGaylord')";	
    }
	if(isset($_REQUEST['ltl_allowed']) && $_REQUEST['ltl_allowed'] == 1){
		$final_filter_str.= " AND inventory.ship_ltl=1";
	}
	if(isset($_REQUEST['customer_pickup_allowed']) && $_REQUEST['customer_pickup_allowed'] == 1){
		$final_filter_str.= " AND inventory.customer_pickup_allowed=1";
	}
	if(isset($_REQUEST['urgent_clearance']) && $_REQUEST['urgent_clearance'] == 1){
		$final_filter_str.= " AND inventory.box_urgent=1";
	}
	if(isset($_REQUEST['ect_burst']) && $_REQUEST['ect_burst'] != ""){
		$final_filter_str.= " AND inventory.burst LIKE '%".$_REQUEST['ect_burst']."%'";
		// if($_REQUEST['ect_burst'] == "ect"){
		// }else{
		// 	$final_filter_str.= " AND inventory.burst=Burst";
		// }
	}

	$box_status_filter = ""; $availalesort = ""; $availale_selectval = ""; 
	if(isset($_REQUEST['available'])){
		$availale_selectval = $_REQUEST['available']; 
		switch ($_REQUEST['available']) {
			case 'quantities':
				$availalesort = " ,`inventory`.`quantity_available`";
				break;
			case "actual";
				$availalesort = " ,`inventory`.`actual_inventory`";
				break;
			case "frequency";
				$availalesort = " ,`inventory`.`expected_loads_per_mo`";
				break;
		}
	}

	$sql_fil="";

	if(isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Gaylord") == 0){
		if(isset($_REQUEST['box_subtype']) &&  (strcasecmp($_REQUEST["box_subtype"], "all")!= 0	 && $_REQUEST['box_subtype'] !="")){
			$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";		
		}else{
			$sql_fil .= " AND inventory.box_type IN('Gaylord','GaylordUCB', 'Loop','PresoldGaylord')";	
		}
	}else if(isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Pallets") == 0){
		if(isset($_REQUEST['box_subtype']) && (strcasecmp($_REQUEST["box_subtype"], "all") == -1 && $_REQUEST['box_subtype']!="")){
			$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";		
		}else{
			$sql_fil .= " AND inventory.box_type IN ('PalletsUCB','PalletsnonUCB')";	
		}
	}
	else if(isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Shipping") == 0){
		if(isset($_REQUEST['box_subtype']) && (strcasecmp($_REQUEST["box_subtype"], "all") == -1 && $_REQUEST['box_subtype']!="")){
			$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";		
		}else{
			if(isset($_REQUEST['include_presold_and_loops']) && $_REQUEST['include_presold_and_loops'] == 1){
				$sql_fil .= " AND inventory.box_type IN ('Box','Boxnonucb','LoopShipping','Presold','Medium','Large','Xlarge','Boxnonucb')";	
			}else{
				$sql_fil .= " AND inventory.box_type IN ('Box','Boxnonucb','Medium','Large','Xlarge','Boxnonucb')";	 
			}
		}
	}else if(isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Supersacks") == 0){
		if(isset($_REQUEST['box_subtype']) && (strcasecmp($_REQUEST["box_subtype"], "all") == -1 && $_REQUEST['box_subtype']!="")){
			$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";		
		}else{
			$sql_fil .= " AND inventory.box_type IN ('SupersackUCB','SupersacknonUCB','Supersacks')";	
		}
	} 
	
	$shipLat = ""; $shipLong = "";
	$enter_zipcode = $_REQUEST['txtzipcode'];

	if ( $enter_zipcode !="" ) {
		$tmp_zipval = "";
		$tmp_zipval = str_replace(" ", "", $enter_zipcode);
		//if($country == "Canada" )
		//{ 	
		//	$zipShipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
		//}elseif(($country) == "Mexico" ){
		//	$zipShipStr= "Select * from zipcodes_mexico limit 1";
		//}else {
			$zipShipStr = "Select latitude, longitude from ZipCodes WHERE zip = '" . intval($enter_zipcode) . "'";
		//}
		//echo $zipShipStr . "<br>";
		$zip_view_res = db_query($zipShipStr,db_b2b() );
		while ($ziprec = array_shift($zip_view_res)) {
			$shipLat = $ziprec["latitude"];
			$shipLong = $ziprec["longitude"];
		}
	}
	//echo "shipLat " . $enter_zipcode . $shipLat . " = " . $shipLong . "<br>";
	//exit;

    $warehouse_innerjoin_sql="";
	if(isset($_REQUEST['warehouse']) && $_REQUEST['warehouse'] != "all"){
        $warehouse_innerjoin_sql = "INNER JOIN tmp_inventory_list_set2 ON tmp_inventory_list_set2.trans_id = inventory.loops_id";
		$final_filter_str.= " AND tmp_inventory_list_set2.wid = '" . $_REQUEST['warehouse'] . "' ";
	}


	//$b2b_status_str = " and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2 or b2b_status=2.8)";
	$b2b_status_str = " and (inventory.b2b_status=1.0 or inventory.b2b_status=1.1 or inventory.b2b_status=1.2)";
	
	$dt_view_qry = "SELECT *, inventory.b2b_status as invb2b_status , inventory.id as b2b_id, inventory.loops_id, inventory.location_city, inventory.location_state, inventory.location_zip, inventory.bwall,
	inventory.expected_loads_per_mo, inventory.vendor, inventory.lengthInch, inventory.widthInch, inventory.depthInch, inventory.vendor_b2b_rescue,inventory.ship_ltl,
	inventory.material, inventory.customer_pickup_allowed, inventory.uniform_mixed_load, inventory.lead_time, inventory.box_sub_type, inventory.bwall_max,
	inventory.bwall_min,inventory.box_type,inventory.date, inventory.system_description, inventory.quantity_per_pallet, inventory.quantity, inventory.additional_description_text, inventory.location_zip_latitude, inventory.buy_now_load_can_ship_in,
	inventory.location_zip_longitude from inventory $warehouse_innerjoin_sql WHERE inventory.Active LIKE 'A' $b2b_status_str $sql_fil $final_filter_str order by inventory.b2b_status $availalesort asc";
	// and actual_qty_calculated > 0 
	//and ID = 5267 
	// echo $dt_view_qry . "<br>";
	//exit;
	
	$dt_view_res = db_query($dt_view_qry, db_b2b() );
	$products_array=array(); $final_product_array = array();$supplier_name = "";$load_av_after_po =	0;
	$query_count = 0;
	while ($dt_view_row = array_shift($dt_view_res)) {

		// $qtyAvailable = $dt_view_row["quantity_available"];
		$description="";	
		$box_sub_type = "";		
		$q1 = "SELECT sub_type_name FROM loop_boxes_sub_type_master where unqid = '" . $dt_view_row["box_sub_type"] . "'";
		$query = db_query($q1, db());
		while($fetch = array_shift($query))
		{
			$box_sub_type = $fetch['sub_type_name'];
		}
		
		$box_type_txt = "";
		if ($dt_view_row["box_type"] == 'Gaylord' || $dt_view_row["box_type"] == 'GaylordUCB' || $dt_view_row["box_type"] == 'Loop' || $dt_view_row["box_type"] == 'PresoldGaylord') {
			$box_type_txt = "Gaylord";
		}
		if ($dt_view_row["box_type"] == 'Box' || $dt_view_row["box_type"] == 'Boxnonucb' || $dt_view_row["box_type"] == 'Presold' || $dt_view_row["box_type"] == 'Medium' || $dt_view_row["box_type"] == 'Large'
		|| $dt_view_row["box_type"] == 'Xlarge' || $dt_view_row["box_type"] == 'Boxnonucb') {
			$box_type_txt = "Shipping Boxes";
		}
		if ($dt_view_row["box_type"] == 'PalletsUCB' || $dt_view_row["box_type"] == 'PalletsnonUCB') {
			$box_type_txt = "Pallets";
		}
		if ($dt_view_row["box_type"] == 'SupersackUCB' || $dt_view_row["box_type"] == 'SupersacknonUCB' || $dt_view_row["box_type"] == 'Supersacks') {
			$box_type_txt = "Supersacks";
		}
		if ($dt_view_row["box_type"] == 'DrumBarrelUCB' || $dt_view_row["box_type"] == 'DrumBarrelnonUCB') {
			$box_type_txt = "Drums/Barrels/IBCs";
		}
		if ($dt_view_row["box_type"] == 'Recycling' || $dt_view_row["box_type"] == 'Other' || $dt_view_row["box_type"] == 'Waste-to-Energy') {
			$box_type_txt = "Recycling+Other";
		}
		
		$box_sub_type_str= $box_sub_type=="" ? "": " - $box_sub_type"; 
		if($dt_view_row["uniform_mixed_load"] == "Uniform"){
			if(isset($_REQUEST['view_type']) && $_REQUEST['view_type']=="grid_view"){
				$description = ucfirst($box_type_txt).": ". $dt_view_row["bwall"] . "ply" . $box_sub_type_str . "  <br>(ID " . $dt_view_row["ID"] . ")";
			}else{
				$description = ucfirst($box_type_txt).": ". $dt_view_row["bwall"] . "ply" . $box_sub_type_str . " (ID " . $dt_view_row["ID"] . ")";
			}
		}else{
			$wall_str = "";
			if ($dt_view_row["bwall_min"] == $dt_view_row["bwall_max"]){
				$wall_str = $dt_view_row["bwall_min"];
			}else{
				$wall_str = $dt_view_row["bwall_min"] . "-" . $dt_view_row["bwall_max"];
			}
			if(isset($_REQUEST['view_type']) && $_REQUEST['view_type']=="grid_view"){
				$description = ucfirst($box_type_txt).": ". $wall_str . "ply" . $box_sub_type_str . " <br>(ID " . $dt_view_row["ID"] . ")";
			}else{
				$description = ucfirst($box_type_txt).": ". $wall_str . "ply" . $box_sub_type_str . " (ID " . $dt_view_row["ID"] . ")";
			}
		} 

		$system_description= $dt_view_row["system_description"] . " " . number_format($dt_view_row["quantity_per_pallet"]) . "/pallet, " . number_format($dt_view_row["quantity"]) . "/load " . $dt_view_row["additional_description_text"];    
        $get_loc_qry = "Select state from state_master where state_code ='".$dt_view_row["location_state"]."'";
		$get_loc_res = db_query($get_loc_qry,db() );
		$loc_row = array_shift($get_loc_res);
		$ship_from = $loc_row["state"]; 
		$added_on=$dt_view_row["date"];
		$ltl=$dt_view_row["ship_ltl"]==1 ? "Yes" : "No"; 
		$customer_pickup_allowed=$dt_view_row["customer_pickup_allowed"]==1 ? "Yes" : "No"; 
		$updated_on="";
		$img="";
		$loads=" - ";
		$vendor_b2b_rescue = $dt_view_row["vendor_b2b_rescue"];
		$supplier_owner="";
		$first_load_can_ship_in="";
		$vendor_id = $dt_view_row["vendor"];
		$b2b_status=$dt_view_row["invb2b_status"];
		$b2b_id=$dt_view_row["b2b_id"];
		$lead_time_for_FTL = $dt_view_row["buy_now_load_can_ship_in"];
		$shipFromLocation = $dt_view_row["location"];
        $actual_qty = $dt_view_row["actual_qty_calculated"];

		$supplier_name_query = db_query("SELECT id, company_name, b2bid FROM loop_warehouse where id = '" . $dt_view_row["vendor_b2b_rescue"] . "'",db());
		while($fetch = array_shift($supplier_name_query))
		{
			$supplier_name = get_nickname_val($fetch['company_name'], $fetch["b2bid"]);
		} 

		$st_query = "Select * from b2b_box_status where status_key='".$b2b_status."' $box_status_filter";
		$st_res = db_query($st_query, db());
		$st_row = array_shift($st_res);
		$status = $st_row["box_status"];
	
		$loop_id = "";
		$qry_sku = "select id, box_warehouse_id, sku, bpallet_qty, boxes_per_trailer, type, ship_ltl, customer_pickup_allowed, bpic_1, flyer_notes,last_modified_date, after_po, expected_loads_per_mo from loop_boxes where b2b_id=". $dt_view_row["b2b_id"];	
		$sku = "";$flyer_notes="";
		$dt_view_sku = db_query($qry_sku,db() );
		$total_no_of_loads=0;
		while ($sku_val = array_shift($dt_view_sku)) 
		{
            
			//$img = "../boxpics_thumbnail/".$sku_val['bpic_1'];
			$img = $sku_val['bpic_1'];
			
			$boxes_per_trailer= $sku_val['boxes_per_trailer']; 				
			$flyer_notes = $sku_val['flyer_notes'];
			$last_modified_date=$sku_val['last_modified_date'];
			$loop_id = $sku_val['id'];
			$loop_boxes_txtafterPo = $sku_val['after_po'];
			$frequency = $sku_val['expected_loads_per_mo'];
			$box_warehouse_id = $sku_val['box_warehouse_id'];

            $rec_found_box = "n"; $after_po_val_tmp = 0;
            $tmp_inventory_list_set2_table_query = db_query("SELECT sales_order_qty, per_trailer,afterpo FROM tmp_inventory_list_set2 WHERE trans_id = '".$loop_id."' ORDER BY warehouse, type_ofbox, Description",db_b2b());
            while($tmp_inventory_list_set2_table_data = array_shift($tmp_inventory_list_set2_table_query)){
                $rec_found_box = "y";
                $sales_order_qty = $tmp_inventory_list_set2_table_data['sales_order_qty'];
                $per_trailer = $tmp_inventory_list_set2_table_data['per_trailer'];
                $afterpo = $tmp_inventory_list_set2_table_data['afterpo'];
            }

            $actual_po = $dt_view_row["actual_qty_calculated"] - $sales_order_qty; 
			
            if ($per_trailer > 0){
                $load_av_after_po = round(($actual_po/$per_trailer)*100,2);
            }

            $txt_after_po = $rec_found_box == "n" ? $loop_boxes_txtafterPo :  $afterpo;

            $expected_loads_per_mo_to_display = round($txt_after_po/$boxes_per_trailer,2);

			if ($availale_selectval == "actual")
			{
				$txt_after_po = $dt_view_row["actual_qty_calculated"];
			}

			$percent_per_load = (($txt_after_po / $dt_view_row["quantity"])*100);

			$sales_order_qty = 0;
			$dt_so_item = "SELECT sum(qty) as sumqty FROM loop_salesorders INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id WHERE location_warehouse_id = ". $box_warehouse_id." AND box_id = ".$loop_id." AND loop_transaction_buyer.bol_create = 0 AND loop_transaction_buyer.Preorder=0 AND loop_transaction_buyer.ignore = 0 ";
			$dt_res_so_item = db_query($dt_so_item,db() );
			while ($so_item_row = array_shift($dt_res_so_item)) {
				if ($so_item_row["sumqty"] > 0) {
					$sales_order_qty = $so_item_row["sumqty"];
				}else{
					$sales_order_qty = 0;
				}
			}

			$reccnt = 0;
			if ($sales_order_qty > 0) { $reccnt = $sales_order_qty; }

            if ($txt_after_po == 0 && $expected_loads_per_mo_to_display == 0) {
                $qtyAvailable = "<font color='black'>" . number_format($txt_after_po,0) . "</font></td>";
            }else if ($txt_after_po >= $boxes_per_trailer) {
                $qtyAvailable = "<font color='green'>".number_format($txt_after_po,0)."</font></td>";
            } else { 
                $qtyAvailable = "<font color='black'>".number_format($txt_after_po,0)."</font></td>";
            }

			$to_show_rec_main = "";
			if ($txt_after_po > 0){
				$to_show_rec_main = "y";
			}
			
			//Filter by 'Qty Avail', default is unchecked, if user checks box, then adjust the default from 'Qty Avail' > 0 to no filter instead.
			if(isset($_REQUEST['include_sold_out_items']) && $_REQUEST['include_sold_out_items'] == 1){
				$to_show_rec_main = "y";
			}
	
			if ($last_modified_date != "")
			{
				$days = number_format((strtotime(date("Y-m-d")) - strtotime($last_modified_date)) / (60 * 60 * 24));
				$updated_on = date("d-m-Y" , strtotime($last_modified_date)) ." ( ".$days. " days ago)";								
				if(isset($_REQUEST['view_type']) && $_REQUEST['view_type']=="list_view"){
					$updated_on = date("d-m-Y" , strtotime($last_modified_date)) ." <br> ( ".$days. " days ago)";								
				}
			}
			
			// To get the Shipsinweek
			$no_of_loads = 0; $shipsinweek = ""; $to_show_rec = ""; $total_no_of_loads = 0;
			if(isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 5) {
				$to_show_rec = "";
				$next_2_week_date = date("Y-m-d", strtotime("+2 week"));
				$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =".$loop_id." and inactive_delete_flg = 0 
				and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
				//echo $dt_view_qry . "<br>";
				$dt_view_res_box = db_query($dt_view_qry, db() );
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
						$no_of_loads = $no_of_loads + 1;
						$to_show_rec = "y";	
					}	
					$total_no_of_loads = $total_no_of_loads + 1;
					
					if ($no_of_loads == 1){
						$now_date = time();
						$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
						$datediff = $next_load_date - $now_date;
						$shipsinweek_org = round($datediff / (60 * 60 * 24));
						//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
						if ($inv["lead_time"] > $shipsinweek_org){
							$shipsinweekval = $inv["lead_time"];
						}else{
							$shipsinweekval = $shipsinweek_org;
						}
						if ($shipsinweekval == 0) { $shipsinweekval = 1; }
						if ($shipsinweekval >= 10){
							$shipsinweek = round($shipsinweekval / 7) . " weeks";
						}
						if ($shipsinweekval >= 2 && $shipsinweekval < 10){
							$shipsinweek = $shipsinweekval . " days";
						}
						if ($shipsinweekval == 1){
							$shipsinweek = $shipsinweekval . " day";
						}
					}
					
				}
			}

            // Can ship in 4 weeks
			else if(isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 6) {
				$to_show_rec = "";
				$next_4_week_date = date("Y-m-d", strtotime("+4 week"));
				$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =".$loop_id." and inactive_delete_flg = 0 
				and (load_available_date <= '" . $next_4_week_date . "') order by load_available_date";
				//echo $dt_view_qry . "<br>";
				$dt_view_res_box = db_query($dt_view_qry, db() );
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
						$no_of_loads = $no_of_loads + 1;
						$to_show_rec = "y";	
					}	
					$total_no_of_loads = $total_no_of_loads + 1;
					
					if ($no_of_loads == 1){
						$now_date = time();
						$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
						$datediff = $next_load_date - $now_date;
						$shipsinweek_org = round($datediff / (60 * 60 * 24));
						//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
						if ($inv["lead_time"] > $shipsinweek_org){
							$shipsinweekval = $inv["lead_time"];
						}else{
							$shipsinweekval = $shipsinweek_org;
						}
						if ($shipsinweekval == 0) { $shipsinweekval = 1; }
						if ($shipsinweekval >= 10){
							$shipsinweek = round($shipsinweekval / 7) . " weeks";
						}
						if ($shipsinweekval >= 2 && $shipsinweekval < 10){
							$shipsinweek = $shipsinweekval . " days";
						}
						if ($shipsinweekval == 1){
							$shipsinweek = $shipsinweekval . " day";
						}
					}
					
				}
			}
			
			//Can ship next month a date range of the 1st day of next month to last day of next month 
			else if(isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 7) {
				$to_show_rec = "";
				$next_month_date = date("Y-m-t");
				$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =".$loop_id." and inactive_delete_flg = 0 
				and (load_available_date <= '" . $next_month_date . "')
				order by load_available_date";
				//echo $dt_view_qry . "<br>";
				$dt_view_res_box = db_query($dt_view_qry, db() );
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
						$no_of_loads = $no_of_loads + 1;
						$to_show_rec = "y";	
					}	
					$total_no_of_loads = $total_no_of_loads + 1;
					
					if ($no_of_loads == 1){
						$now_date = time();
						$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
						$datediff = $next_load_date - $now_date;
						$shipsinweek_org = round($datediff / (60 * 60 * 24));
						//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
						if ($inv["lead_time"] > $shipsinweek_org){
							$shipsinweekval = $inv["lead_time"];
						}else{
							$shipsinweekval = $shipsinweek_org;
						}
						if ($shipsinweekval == 0) { $shipsinweekval = 1; }
						if ($shipsinweekval >= 10){
							$shipsinweek = round($shipsinweekval / 7) . " weeks";
						}
						if ($shipsinweekval >= 2 && $shipsinweekval < 10){
							$shipsinweek = $shipsinweekval . " days";
						}
						if ($shipsinweekval == 1){
							$shipsinweek = $shipsinweekval . " day";
						}
						
					}
					
				}
				//echo "in step 7 " . $to_show_rec . "<br>";	
			}

			//Can ship next month
			// else if(isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 8) {
			// 	$to_show_rec = "";
			// 	$next_month_date = date("Y-m-t", strtotime("+1 month"));
			// 	$dt_view_qry = "SELECT load_available_date, trans_rec_id  from loop_next_load_available_history where inv_loop_id =".$loop_id." and inactive_delete_flg = 0 
			// 	and (load_available_date between '" . date("Y-m-1", strtotime("+1 month")) . "' and '" . $next_month_date . "') order by load_available_date";
			// 	//echo $dt_view_qry . "<br>";
			// 	$dt_view_res_box = db_query($dt_view_qry, db() );
			// 	while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			// 		if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
			// 			$no_of_loads = $no_of_loads + 1;
			// 			$to_show_rec = "y";	
			// 		}	
			// 		$total_no_of_loads = $total_no_of_loads + 1;
					
			// 		if ($no_of_loads == 1){
			// 			$now_date = time();
			// 			$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
			// 			$datediff = $next_load_date - $now_date;
			// 			$shipsinweek_org = round($datediff / (60 * 60 * 24));
			// 			//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
			// 			if ($inv["lead_time"] > $shipsinweek_org){
			// 				$shipsinweekval = $inv["lead_time"];
			// 			}else{
			// 				$shipsinweekval = $shipsinweek_org;
			// 			}
			// 			if ($shipsinweekval == 0) { $shipsinweekval = 1; }
			// 			if ($shipsinweekval >= 10){
			// 				$shipsinweek = round($shipsinweekval / 7) . " weeks";
			// 			}
			// 			if ($shipsinweekval >= 2 && $shipsinweekval < 10){
			// 				$shipsinweek = $shipsinweekval . " days";
			// 			}
			// 			if ($shipsinweekval == 1){
			// 				$shipsinweek = $shipsinweekval . " day";
			// 			}								}
			// 	}
			// }

			//Enter ship by date = Take user input of 1 date
			else if(isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 9 && $_REQUEST["selected_date"] != '') {
				$to_show_rec = "";
				$next_month_date = date("Y-m-d", strtotime($_REQUEST["selected_date"]));
				$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =".$loop_id." and inactive_delete_flg = 0 
				and load_available_date <= '" . $next_month_date . "' order by load_available_date";
				$dt_view_res_box = db_query($dt_view_qry, db() );
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
						$no_of_loads = $no_of_loads + 1;
						$to_show_rec = "y";	
					}	
					$total_no_of_loads = $total_no_of_loads + 1;
					
					if ($no_of_loads == 1){
						$now_date = time();
						$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
						$datediff = $next_load_date - $now_date;
						$shipsinweek_org = round($datediff / (60 * 60 * 24));
						if ($inv["lead_time"] > $shipsinweek_org){
							$shipsinweekval = $inv["lead_time"];
						}else{
							$shipsinweekval = $shipsinweek_org;
						}
						if ($shipsinweekval == 0) { $shipsinweekval = 1; }
						if ($shipsinweekval >= 10){
							$shipsinweek = round($shipsinweekval / 7) . " weeks";
						}
						if ($shipsinweekval >= 2 && $shipsinweekval < 10){
							$shipsinweek = $shipsinweekval . " days";
						}
						if ($shipsinweekval == 1){
							$shipsinweek = $shipsinweekval . " day";
						}								
					}
				}
			}
            
            // Ready Now 
			else if(isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 4) {
				$next_2_week_date = date("Y-m-d", strtotime("+3 day"));
				$to_show_rec = "";
				$dt_view_qry = "SELECT load_available_date, trans_rec_id  from loop_next_load_available_history where inv_loop_id =".$loop_id." and inactive_delete_flg = 0 
				and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
				$dt_view_res_box = db_query($dt_view_qry, db() );
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
						$no_of_loads = $no_of_loads + 1;
						$to_show_rec = "y";	
					}	
					$total_no_of_loads = $total_no_of_loads + 1;
					
					if ($no_of_loads == 1){
						$now_date = time();
						$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
						$datediff = $next_load_date - $now_date;
						$shipsinweek_org = round($datediff / (60 * 60 * 24));
						if ($inv["lead_time"] > $shipsinweek_org){
							$shipsinweekval = $inv["lead_time"];
						}else{
							$shipsinweekval = $shipsinweek_org;
						}
						if ($shipsinweekval == 0) { $shipsinweekval = 1; }
						if ($shipsinweekval >= 10){
							$shipsinweek = round($shipsinweekval / 7) . " weeks";
						}
						if ($shipsinweekval >= 2 && $shipsinweekval < 10){
							$shipsinweek = $shipsinweekval . " days";
						}
						if ($shipsinweekval == 1){
							$shipsinweek = $shipsinweekval . " day";
						}
					}
				}
			
			}else{
				$to_show_rec = "";
				$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =".$loop_id." and inactive_delete_flg = 0 
				order by load_available_date";
				$dt_view_res_box = db_query($dt_view_qry, db() );
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
						$no_of_loads = $no_of_loads + 1;
						$to_show_rec = "y";	
					}	
					$total_no_of_loads = $total_no_of_loads + 1;
					
					if ($no_of_loads == 1){
						$now_date = time();
						$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
						$datediff = $next_load_date - $now_date;
						$shipsinweek_org = round($datediff / (60 * 60 * 24));
						if (($inv["lead_time"] > $shipsinweek_org) || ($shipsinweek_org < 0)){
							$shipsinweekval = $inv["lead_time"];
						}else{
							$shipsinweekval = $shipsinweek_org;
						}
						
						if ($shipsinweekval == 0) { $shipsinweekval = 1; }
						
						if ($shipsinweekval >= 10){
							$shipsinweek = round($shipsinweekval / 7) . " weeks";
						}
						if ($shipsinweekval >= 2 && $shipsinweekval < 10){
							$shipsinweek = $shipsinweekval . " days";
						}
						if ($shipsinweekval == 1){
							$shipsinweek = $shipsinweekval . " day";
						}
					}
				}
			}
			$no_of_loads_str="";
			$first_load_can_ship_in= $shipsinweek;
			if ($total_no_of_loads == 1){ $no_of_loads_str = " Load"; }
			if ($total_no_of_loads > 1){ $no_of_loads_str = " Loads"; }
			$loads= $no_of_loads. " of " .$total_no_of_loads."".$no_of_loads_str; 
		}
		
		
		$companyID = "";
		/*if($vendor_b2b_rescue>0){
			
			$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
			$query = db_query($q1, db());
			while($fetch = array_shift($query))
			{
				$vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);
				
				$comqry="select initials,ship_zip_latitude,ship_zip_longitude, ID from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.ID=".$fetch["b2bid"];
				$comres=db_query($comqry,db_b2b());
				while($comrow=array_shift($comres))
				{
					$supplier_owner=$comrow["initials"];
					$shipLat = $comrow["ship_zip_latitude"];
					$shipLong = $comrow["ship_zip_longitude"];
					$companyID = $comrow["ID"];
				}
			}
		}else{
			$vendor_b2b_rescue=$vendor_id;
			$q1 = "SELECT Name, b2bid FROM vendors where id = $vendor_b2b_rescue";
			$query = db_query($q1, db_b2b());
			while($fetch = array_shift($query))
			{
				$vendor_name = $fetch["Name"];
				
				$comqry="select initials, ID from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=".$fetch["b2bid"];
				$comres=db_query($comqry,db_b2b());
				while($comrow=array_shift($comres))
				{
					$supplier_owner=$comrow["initials"];
					$companyID = $comrow["ID"];
				}
			}
		}*/
		
		//&& $_REQUEST["timing"] != ""
		if ($to_show_rec_main == "y" ) {

			if ($dt_view_row["location_zip"] != "" && $enter_zipcode != "")		
			{
				$locLat = $dt_view_row["location_zip_latitude"];
				$locLong = $dt_view_row["location_zip_longitude"];

				$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
				$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

				$distA = Sin($distLat/2) * Sin($distLat/2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong/2) * Sin($distLong/2);
				$distC = 2 * atan2(sqrt($distA),sqrt(1-$distA));
			}

			$miles_from=(int) (6371 * $distC * .621371192);
			//echo "miles_from = " . $miles_from . "<br>";
			
			if ($miles_from <= 250)
			{	$miles_away_color = "green";
			}
			if ( ($miles_from <= 550) && ($miles_from > 250))
			{	
				$miles_away_color = "#FF9933";
			}
			if (($miles_from > 550) )
			{	
				$miles_away_color = "red";
			}	

			if ($enter_zipcode == ""){
				$miles = "<font color='red'>Enter Zipcode</font>";
			}else{
				$miles = "<font color='$miles_away_color'>" . $miles_from . " mi away</font>";
			}
			
			$b2b_ulineDollar = round($dt_view_row["ulineDollar"]);
			$b2b_ulineCents = $dt_view_row["ulineCents"];
			if ($b2b_ulineDollar != "" || $b2b_ulineCents != ""){
				$price = "$" . number_format($b2b_ulineDollar + $b2b_ulineCents,2); 
			}else{
				$price = "$0.00"; 
			}	
			$minfob = str_replace(",", "" , $price);
			$minfob = str_replace("$", "" , $minfob);
            if($_REQUEST["min_price"] == 0 && $_REQUEST["max_price"] == 500 && $_REQUEST["timing"] == ""){
                $products_array[]=array(
				'qtyAvailable'=>$qtyAvailable,
                'img'=>$img,
                'description'=>$description, 
                'status'=>$status,
                'ship_from'=>$ship_from,
                'system_description'=>$system_description,
                'flyer_notes'=>$flyer_notes,
                'lead_time_of_FTL'=>$lead_time_for_FTL,
                'distance'=>$miles,
				'distance_sort'=>$miles_from,
                'ltl'=>$ltl,
                'customer_pickup'=>$customer_pickup_allowed,
                'supplier_owner'=>$supplier_owner,
                'updated_on'=>$updated_on,
                'price'=>$price,
                'loads'=>$loads,
                'shipsinweekval'=>$shipsinweekval,
                'first_load_can_ship_in'=>$first_load_can_ship_in,
                'b2b_id'=>$b2b_id,
                'loop_id'=>$loop_id,
                'box_warehouse_id'=>$box_warehouse_id,
                'companyID'=>$companyID,
                'minfob' => $minfob,
                'txtaddress' => $dropoff_add1,
                'txtaddress2' => $dropoff_add2,
                'txtcity' => $dropoff_city,
                'txtstate' => $dropoff_state,
                'txtcountry' => $dropoff_country,
                'txtzipcode' => $dropoff_zip,
                'added_on'=> $added_on,
				'supplier_name'=>$supplier_name,
				'shipFromLocation'=>$shipFromLocation,
				'percent_per_load'=>number_format($percent_per_load,0),
				'actual_qty'=>$actual_qty,
				'actual_po'=>$actual_po,
				'load_av_after_po'=>$load_av_after_po,
				'frequency'=>$frequency,
				'vendor_b2b_rescue'=>$vendor_b2b_rescue,
				'reccnt'=>$reccnt,
                'loop_id_encrypt_str'=> urlencode(encrypt_password($loop_id)),
                );
            }else{
                if(isset($_REQUEST["min_price"]) && $_REQUEST["min_price"] != 0 || isset($_REQUEST['max_price']) && $_REQUEST["max_price"] != 500){
                    if($minfob >= $_REQUEST["min_price"] && $minfob <= $_REQUEST["max_price"]){
                        $products_array[]=array(
						'qtyAvailable'=>$qtyAvailable,
                        'img'=>$img,
                        'description'=>$description, 
                        'status'=>$status,
                        'ship_from'=>$ship_from,
                        'system_description'=>$system_description,
                        'flyer_notes'=>$flyer_notes,
						'lead_time_of_FTL'=>$lead_time_for_FTL,
                        'distance'=>$miles,
						'distance_sort'=>$miles_from,
                        'ltl'=>$ltl,
                        'customer_pickup'=>$customer_pickup_allowed,
                        'supplier_owner'=>$supplier_owner,
                        'updated_on'=>$updated_on,
                        'price'=>$price,
                        'loads'=>$loads,
                        'shipsinweekval'=>$shipsinweekval,
                        'first_load_can_ship_in'=>$first_load_can_ship_in,
                        'b2b_id'=>$b2b_id,
                        'loop_id'=>$loop_id,
						'box_warehouse_id'=>$box_warehouse_id,
                        'companyID'=>$companyID,
                        'minfob' => $minfob,
                        'txtaddress' => $dropoff_add1,
                        'txtaddress2' => $dropoff_add2,
                        'txtcity' => $dropoff_city,
                        'txtstate' => $dropoff_state,
                        'txtcountry' => $dropoff_country,
                        'txtzipcode' => $dropoff_zip,
                        'added_on'=> $added_on,
						'supplier_name'=>$supplier_name,
						'shipFromLocation'=>$shipFromLocation,
						'percent_per_load'=>number_format($percent_per_load,0),
                        'actual_qty'=>$actual_qty,
                        'actual_po'=>$actual_po,
                        'load_av_after_po'=>$load_av_after_po,
                        'frequency'=>$frequency,
                        'vendor_b2b_rescue'=>$vendor_b2b_rescue,
						'reccnt'=>$reccnt,
						'QTYfunction'=> display_preoder_sel($query_count,$reccnt,$loop_id,$box_warehouse_id),
                        'loop_id_encrypt_str'=> urlencode(encrypt_password($loop_id)),
                        );
                    }
                }
                else if(isset($_REQUEST["timing"]) && $_REQUEST["timing"] != ""){
                    /*if($to_show_rec=='y'){ */
                        $products_array[]=array(
						'qtyAvailable'=>$qtyAvailable,
                        'img'=>$img,
                        'description'=>$description, 
                        'status'=>$status,
                        'ship_from'=>$ship_from,
                        'system_description'=>$system_description,
                        'flyer_notes'=>$flyer_notes,
						'lead_time_of_FTL'=>$lead_time_for_FTL,
                        'distance'=>$miles,
						'distance_sort'=>$miles_from,
                        'ltl'=>$ltl,
                        'customer_pickup'=>$customer_pickup_allowed,
                        'supplier_owner'=>$supplier_owner,
                        'updated_on'=>$updated_on,
                        'price'=>$price,
                        'loads'=>$loads,
                        'shipsinweekval'=>$shipsinweekval,
                        'first_load_can_ship_in'=>$first_load_can_ship_in,
                        'b2b_id'=>$b2b_id,
                        'loop_id'=>$loop_id,
						'box_warehouse_id'=>$box_warehouse_id,
                        'companyID'=>$companyID,
                        'minfob' => $minfob,
                        'txtaddress' => $dropoff_add1,
                        'txtaddress2' => $dropoff_add2,
                        'txtcity' => $dropoff_city,
                        'txtstate' => $dropoff_state,
                        'txtcountry' => $dropoff_country,
                        'txtzipcode' => $dropoff_zip,
                        'added_on'=> $added_on,
						'supplier_name'=>$supplier_name,
						'shipFromLocation'=>$shipFromLocation,
						'percent_per_load'=>number_format($percent_per_load,0),
                        'actual_qty'=>$actual_qty,
                        'actual_po'=>$actual_po,
                        'load_av_after_po'=>$load_av_after_po,
                        'frequency'=>$frequency,
                        'vendor_b2b_rescue'=>$vendor_b2b_rescue,
						'reccnt'=>$reccnt,
                        'loop_id_encrypt_str'=> urlencode(encrypt_password($loop_id)),
                        );
                    /*}*/
                }
            }
		}
		$query_count++;
	}
	
	//Sort By
	if(isset($_REQUEST['sort_by']) && $_REQUEST['sort_by']!=""){
		if($_REQUEST['sort_by']=='low-high'){;
			$key_values = array_column($products_array, 'minfob'); 
			array_multisort($key_values, SORT_ASC, $products_array);
		}else if($_REQUEST['sort_by']=='high-low'){
			$key_values = array_column($products_array, 'minfob'); 
			array_multisort($key_values, SORT_DESC, $products_array);
		}else if($_REQUEST['sort_by']=='nearest'){
			$key_values = array_column($products_array, 'distance_sort'); 
			array_multisort($key_values, SORT_ASC, $products_array);
		}else if($_REQUEST['sort_by']=='furthest'){
			$key_values = array_column($products_array, 'distance_sort'); 
			array_multisort($key_values, SORT_DESC, $products_array);
		}
	}else{
		//$key_values = array_column($products_array, 'added_on'); 
		//array_multisort($key_values, SORT_ASC, $products_array);
	}
	

	//Pagination
	// $no_of_product_per_page=15;
	// if(isset($_REQUEST['active_page_id'])){
	// 	$start_index=0;
	// 	if($_REQUEST['active_page_id']!=1){
	// 		$start_index=($_REQUEST['active_page_id']-1)*$no_of_product_per_page;
	// 	}
	// }
	// $no_of_pages=ceil(count($products_array)/$no_of_product_per_page);
	$final_product_array=array_slice($products_array,$start_index,$no_of_product_per_page);
	//Final Response Of Products/ Inventory
	$final_res=array(
		// 'no_of_pages'=>$no_of_pages,
		'data'=>$final_product_array,
		'total_items'=>count($products_array),
	);
	
	echo json_encode($final_res);

?>

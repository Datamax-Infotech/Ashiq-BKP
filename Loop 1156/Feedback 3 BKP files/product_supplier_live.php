<?
/*
File Name: inventory_v1_new.php
Page created By: Ashiq
Page created On: 15-02-2024 (Dublicated From Product Page)
Last Modified On: 
Last Modified By: Ashiq
Change History:
Date           By            Description
===============================================================================================================
15-02-2024      Ashiq     This file is created for the inventory Supplier product view.
===============================================================================================================
*/

require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

?>
<html>
<head>
	<title>B2B Inventory: Used Shipping Boxes</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<link rel='stylesheet' type='text/css' href='css/bomerang_style.css'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<script>
		function display_preoder_sel(tmpcnt, reccnt, box_id, wid) {
		if (reccnt > 0 ) {
		
			if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') 
			{ document.getElementById('inventory_preord_top_' + tmpcnt).style.display='none'; } else {
			document.getElementById('inventory_preord_top_' + tmpcnt).style.display='table-row'; }
		
			document.getElementById("inventory_preord_middle_div_"+tmpcnt).innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />"; 				
		
			if (window.XMLHttpRequest)
			{
			xmlhttp=new XMLHttpRequest();
			}
			else
			{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
				document.getElementById("inventory_preord_middle_div_"+tmpcnt).innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET","inventory_preorder_childtable.php?box_id=" +box_id+"&wid="+wid,true);
			xmlhttp.send();

		}
	}
	
	</script>
</head>

<?
include("inc/header.php");
db();
?>
<br>
<br>
<script type="text/javascript" src="wz_tooltip.js"></script>
<div class="main-section">
	<div class="container-fluid">
		<div class="dashboard_heading" style="font-size: 24px;font-family: 'Titillium Web', sans-serif;font-weight: 600;padding: 1% 0;">
				B2B Inventory: Used Shipping Boxes
		</div>
		<div class="row">
		<div class="col-md-2 inventory-filter">
			<div class="col-md-12 filter-box1">
				<div class="row">
					<div class="col-md-12 p-0" id="your-selections">
					<p><b>YOUR SELECTIONS</b></p>
					<div id="selections"></div>
					<a class="map_link float-right" href="#" id="clear_all">Clear All</a>
					</div>
					<form id="filter_form" class="w-100">					
					<div class="form-group">
						<label class="font-weight-bold">Warehouse</label><br>
                        <select name="warehouse" id="warehouse" onchange="change_warehouse()">
                            <option value="all" display_val="All" selected>All</option>
                            <?
                            $warehouse_get_query = db_query("SELECT id, warehouse_name FROM loop_warehouse WHERE rec_type = 'Sorting' AND Active = 1 ORDER BY warehouse_name", db());
                            while($warehouse = array_shift($warehouse_get_query))
                            {
                                $name = $warehouse['warehouse_name'];
                                $id = $warehouse['id'];
                            ?>
                                <option value="<?=$id?>" display_val="<?= $name ?>"><?= $name ?></option>
                            <?	} ?>
                        </select>
					</div>

					<div class="form-group">
						<label class="font-weight-bold">Timing</label><br>
						<select name="timing" id="timing" onchange="change_timing()">
							<option value="4" display_val="Ready Now" selected>Ready Now</option>
							<option value="5" display_val="Can ship in 2 weeks">Can ship in 2 weeks</option>
							<option value="6" display_val="Can ship in 4 weeks">Can ship in 4 weeks</option>
							<option value="7" display_val="Can ship this month">Can ship this month</option>
							<option value="9" display_val="Enter ship by date">Enter ship by date</option>
						</select>
						<div class="mt-2 d-none" id="timing_date_div">
						<input type="date" class="w-100" name="timing_date" id="timing_date"/>
						<p id="timing_date_error" class="text-danger d-none"><small>Please Select Date</small></p>
						<div class="text-center">
						<button type="button" class="btn apply-filter btn-sm" id="apply-timimg" onclick="show_inventories('',1)"> Load</button>
						</div>
						</div>
					</div>
					
					<div class="form-group">
						<input class="include_FTL_Rdy_Now_Only" type="checkbox" display_val="FTL Rdy Now Only" value="1" name="include_FTL_Rdy_Now_Only" id="include_FTL_Rdy_Now_Only" onchange="show_inventories('',1)"/> FTL Rdy Now Only
					</div>

					<div class="form-group">
						<input class="include_sold_out_items" type="checkbox" display_val="Include Sold Out Items" value="1" name="include_sold_out_items" id="include_sold_out_items" onchange="show_inventories('',1)"/> Include Sold Out Items
					</div>
					<div class="form-group ">
						<input class="include_presold_and_loops" type="checkbox" display_val="Include Presold and Loops" value="1" name="include_presold_and_loops" id="include_presold_and_loops" onchange="show_inventories('',1)"/> Include Presold and Loops
					</div>
					<div class="form-group ">
						<input class="ltl_allowed" type="checkbox" display_val="LTL Allowed" value="1" name="ltl_allowed" id="ltl_allowed" onchange="show_inventories('',1)"/> LTL Allowed?
					</div>
					<div class="form-group ">
						<input class="customer_pickup_allowed" type="checkbox" display_val="Customer Pickup Allowed" value="1" name="customer_pickup_allowed" id="customer_pickup_allowed" onchange="show_inventories('',1)"/> Customer Pickup Allowed?
					</div>
					<div class="form-group ">
						<input class="urgent_clearance" type="checkbox" display_val="Urgent/Clearance" value="1" name="urgent_clearance" id="urgent_clearance" onchange="show_inventories('',1)"/> Urgent/Clearance
					</div>

                    <div class="form-group">
						<p id="enter_address_text" class="text-primary text-center m-0 flex-grow-1 ml-3" style="cursor:pointer"><u>Enter Full Adddress</u></p>

						<div id="full_address_div" class="d-none">
							<label class="font-weight-bold">Address</label>
							<input type="text" class="w-100" name="txtaddress" id="txtaddress"/>
							<label class="font-weight-bold">Address 2</label>
							<input type="text" class="w-100" name="txtaddress2" id="txtaddress2"/>
							<label class="font-weight-bold">City</label>
							<input type="text" class="w-100" name="txtcity" id="txtcity"/>
							<label class="font-weight-bold">State</label>
							<select class="w-100" name="txtstate" id="txtstate">
								<option value="">Select State</option>
								<?
								$tableedit  = "SELECT * FROM zones where zone_country_id in (223,38,37) ORDER BY zone_country_id desc, zone_name";
								$dt_view_res = db_query($tableedit,db_b2b() );
								while ($row = array_shift($dt_view_res)) {
								?>
								<option 
								<? 
								if ((trim($_REQUEST["txtstate"]) == trim($row["zone_code"])) ||  (trim($_REQUEST["txtstate"]) == trim($row["zone_name"])))
								echo " selected ";
								?> value="<?=trim($row["zone_code"])?>" display_val="<?=trim($row["zone_code"])?>">
								<?=$row["zone_name"]?>
								(<?=$row["zone_code"]?>)
								</option>
								<?
								}
								?>
							</select>
						</div>

						<label class="d-flex font-weight-bold">Zip Code</label>
						<input type="text" class="w-100" name="txtzipcode" id="txtzipcode"/>
						<div class="text-center">
							<button type="button" class="btn apply-filter btn-sm" id="clear-deliverydata" onclick="clear_filter('deliverydata')">Clear Zipcode</button>
							<button type="button" class="btn apply-filter btn-sm" id="apply-deliverydata" onclick="show_inventories('2')"> Apply Zipcode</button>
						</div>
					</div>

				</div>
			</div>
			<div class="col-md-12 filter-box2">
				<div class="row">
					<?php
						$box_type=isset($_REQUEST['box_type']) && $_REQUEST['box_type']!="" ? $_REQUEST['box_type'] : "Shipping";
						$box_subtype=isset($_REQUEST['box_subtype']) && $_REQUEST['box_subtype']!="" ? $_REQUEST['box_subtype'] : "all";
					?>
					<div class="form-group">
						<label class="font-weight-bold">Price (ea)</label>
						<table class="form-table">
							<tr>
								<td>
									<div class="input-group-addon">
										<span>$</span>
										<input name="min_price_each"  class="w-100" value="$0.00" id="price_each-from" onchange="show_inventories('',1)"/>
									</div>
								</td>
								<td>&nbsp;-&nbsp;</td>
								<td>
									<div class="input-group-addon">
										<span>$</span>
										<input name="max_price_each"  class="w-100" value="$99.99" id="price_each-to" onchange="show_inventories('',1)"/>
									</div>
								</td>
							</tr>
						</table>
						<!--<div class="row">
							<div class="col-sm-5 form-group m-0">
								<input name="min_price_each"  class="w-100" value="$0.00" id="price_each-from" onchange="show_inventories('',1)"/>
							</div>
							<div class="col-sm-2">
								-
							</div>
							<div class="col-sm-5 form-group m-0">
								<input name="max_price_each"  class="w-100" value="$99.99" id="price_each-to" onchange="show_inventories('',1)"/>
							</div>
						</div>-->
					</div>

					<div class="form-group">
						<label class="font-weight-bold">Length(in)</label>
						<table class="form-table">
							<tr>
								<td><input name="min_length"  class="w-100" value='0"' id="length-from" onchange="show_inventories('',1)"/></td>
								<td>&nbsp;-&nbsp;</td>
								<td><input name="max_length"  class="w-100" value='99"' id="length-to" onchange="show_inventories('',1)"/></td>
							</tr>
						</table>
					</div>
					<div class="form-group">
						<label class="font-weight-bold">Width(in)</label>
						<table class="form-table">
							<tr>
								<td><input name="min_width"  class="w-100" value='0"' min="0" max="99" id="width-from" onchange="show_inventories('',1)"/></td>
								<td>&nbsp;-&nbsp;</td>
								<td><input name="max_width"  class="w-100" value='99"' min="0" max="99" id="width-to" onchange="show_inventories('',1)"/></td>
							</tr>
						</table>
					</div>
					<div class="form-group">
						<label class="font-weight-bold">Height(in)</label>
						<table class="form-table">
							<tr>
								<td><input name="min_height"  class="w-100" value='0"' min="0" max="99" id="height-from" onchange="show_inventories('',1)"/></td>
								<td>&nbsp;-&nbsp;</td>
								<td><input name="max_height"  class="w-100" value='99"' min="0" max="99" id="height-to" onchange="show_inventories('',1)"/></td>
							</tr>
						</table>
					</div>
					<div class="form-group">
						<label class="font-weight-bold">Cubic Footage(in)</label>
						<table class="form-table">
							<tr>
								<td><input name="min_cubic_footage"  class="w-100" value="0.00" min="0.00" max="99.99" id="cubic_footage-from" onchange="show_inventories('',1)"/></td>
								<td>&nbsp;-&nbsp;</td>
								<td><input name="max_cubic_footage"  class="w-100" value="99.99" min="0.00" max="99.99" id="cubic_footage-to" onchange="show_inventories('',1)"/></td>
							</tr>
						</table>
					</div>

					<div class="form-group">
						<label class="font-weight-bold">ECT/Burst</label><br>
						<select class="w-100" name="ect_burst" id="ect_burst" onchange="show_inventories('',1)">
							<option value="" display_val="" selected>All</option>
                            <option value="1" display_val="Light Duty (< 32 ECT or 200# Burst)">Light Duty (< 32 ECT or 200# Burst)</option>
                            <option value="2" display_val="Standard (>= 32 ECT or 200# Burst)">Standard (>= 32 ECT or 200# Burst)</option>
                            <option value="3" display_val="Heavy Duty (>= 44 ECT or 275# Burst)">Heavy Duty (>= 44 ECT or 275# Burst)</option>
                            <option value="4" display_val="Super Heavy Duty (>= 48 ECT or 275# Burst)">Super Heavy Duty (>= 48 ECT or 275# Burst)</option>
						</select>
					</div>

                    <div class="form-group">
                        <label class="font-weight-bold">Printing</label><br>
                        <div class="d-block">
                            <input class="printing" type="checkbox" display_val="Printing" value="1" name="printing[]" id="printing" onchange="show_inventories('',1)"/> Printing
                        </div>
                        <div class="d-block">
                            <input class="plain" type="checkbox" display_val="Plain" value="2" name="printing[]" id="plain" onchange="show_inventories('',1)"/> Plain                             
                        </div>
                    </div>

                    <div class="form-group col-md-12 p-0">
                        <label class="font-weight-bold">Work as a Kit Box</label><br>
                        <div class="d-block">
                            <input class="medium" type="checkbox" display_val="Medium" value="1" name="work_as_a_kit_box[]" id="medium" onchange="show_inventories('',1)"/> Medium
                        </div>
                        <div class="d-block">
                            <input class="large_ideal" type="checkbox" display_val="Partial Flaps w/o Slipsheet" value="2" name="work_as_a_kit_box[]" id="large_ideal" onchange="show_inventories('',1)"/> Large (iIdeal)                             
                        </div>
                        <div class="d-block">
                            <input class="large_fold_at_seam" type="checkbox" display_val="Partial Flaps w/o Slipsheet" value="3" name="work_as_a_kit_box[]" id="large_fold_at_seam" onchange="show_inventories('',1)"/> Large (fold at seam)
                        </div>
                        <div class="d-block">
                            <input class="x_large_ideal" type="checkbox" display_val="Removable Tray " value="4" name="work_as_a_kit_box[]" id="x_large_ideal" onchange="show_inventories('',1)"/> X-Large (ideal)
                        </div>
                        <div class="d-block">
                            <input class="x_large_fold_at_seam" type="checkbox" display_val="Removable Tray " value="5" name="work_as_a_kit_box[]" id="x_large_fold_at_seam" onchange="show_inventories('',1)"/> X-Large (fold at seam)
                        </div>
                    </div>

                    <div class="form-group col-md-12 p-0">
                        <label class="font-weight-bold">Tags</label><br>
						
                        <select name="box_tags" id="box_tags" onchange="show_inventories('',1)">
                            <option value=""></option>
                            <?
                            $warehouse_get_query = db_query("select * from loop_inv_tags order by id asc", db());
                            while($warehouse = array_shift($warehouse_get_query))
                            {
                                $name = $warehouse['tags'];
                                $id = $warehouse['id'];
                            ?>
                                <option value="<?=$id?>" display_val="<?= $name ?>"><?= $name ?></option>
						<?	} ?>
                        </select>
						
                    </div>

					</form>
				</div>
			</div>
		</div>
		<div class="col-md-9">

			<div class="row m-0 total_value_table">
                <table class="table table-sm table-bordered text-center" style="width : 20%">
                    <thead>
                        <tr>
                            <th id="heading_total_SKU" class="text-center">Total SKUs</th>
                            <th id="heading_total" class="text-center">Avail Total</th>
                            <th id="heading_truckload" class="text-center">Avail Truckloads</th>
                            <!-- <th class="text-center">Loads Available After PO</th>
                            <th class="text-center">Frequency (Loads/Mo)</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="total_SKU">0</td>
                            <td id="sub_table_total_value">0</td>
                            <td id="sub_table_truckload">0</td>
                            <!-- <td id="load_av_after_po">0</td>
                            <td id="frequency">0</td>-->
                        </tr>
                    </tbody>
                </table>
			</div>

			<div class="row justify-content-end align-items-end m-0">
					<div class="col-md-1 p-0">
						<!-- <select class="form-control form-control-sm bg-light" id="available" onchange="change_available()"> -->
						<select class="bg-light w-100" id="available" name="available" onchange="show_inventories('',1)" >
							<option value="quantities"> Quantities </option>
							<option value="available" selected>Available</option>
							<option value="actual">Actual</option>
							<option value="frequency">Frequency</option>
						</select>
						<input type="hidden" id="active_page_id" value="1"/>
					</div>
					<!-- <div class="col-md-1 p-0 ml-3">
						<select class="bg-light w-100" id="list_by_item" onchange="change_list_by_item()">
							<option value="groupby"> Group by </option>
							<option value="list-by-item" selected>List by item</option> 
							<option value="group-by-location">Group by Location</option>
						</select>
						<input type="hidden" id="active_page_id" value="1"/>
					</div>-->
					<div class="col-md-1 p-0 ml-3">
						<select class="bg-light w-100" id="sort_by" onchange="change_sort_by()">
							<option value=""> Sort By </option>
							<option value="low-high" selected>Price: Low-High</option>
							<option value="high-low">Price: High-Low</option>
							<option value="nearest">Distance: Nearest</option>
							<option value="furthest">Distance: Furthest</option>
							<option value="freq-most-least">Frequnecy: Most-Least</option>
							<option value="freq-least-most">Frequency: Least-Most</option>
							<option value="qty-most-least">Quantity: Most-Least</option>
							<option value="qty-least-most">Quantity: Least-Most</option>
							<option value="leadtime-soonest-latest">Lead Time (FTL): Soonest-Latest</option>
							<option value="leadtime-latest-soonest">Lead Time (FTL): Latest-Soonest</option>
						</select>
						<input type="hidden" id="active_page_id" value="1"/>
					</div>
					<div class="col-md-1 p-0 ml-1 text-center">
						<input type="hidden" id="view_type" value="table_view"/>
						<button style="font-size:10px;padding:5px" class="btn btn-light btn-active-view btn-sm" id="table-view-button" onclick="change_view_type('table_view')"><i class="fa fa-align-justify"></i></button>
						<button style="font-size:12px;padding:5px" class="btn btn-light btn-sm" id="list-view-button" onclick="change_view_type('list_view')"><i class="fa fa-th-large"></i></button>
						<!-- <button class="btn btn-light btn-sm" id="grid-view-button" onclick="change_view_type('grid_view')"><i class="fa fa-th-large"></i></button> -->
					</div>
			</div>
			<div class="products_div_main">
				<div class="result_products" id="result_products">
				</div>
				
				<div id="loader">
					<img src="images/boomerang/loading.gif" height="150" width="150"/>
				</div>
			</div>
			<!-- <div class="col-md-12 mt-4 pagination d-none justify-content-center">
		
			</div> -->
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="available_loads_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Available Load Ship Dates</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="ship_data">
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="scripts/jquery-3.7.1.min.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="scripts/boomerang.js"></script>
<script>
	var selection_obj={};
	var default_sel_gaylord=true;
	var default_sel_timing=true;
	var default_sel_warehouse = true;	
	var default_sel_tags =true;	
	
	function products_loading(){
		$('#loader').removeClass('d-none');
		$('html, body').animate({scrollTop:0},500);
		$('#result_products').addClass('d-none');
		$('.pagination').addClass('d-none');
	}
	function products_loaded(){
		$('#loader').addClass('d-none');
		$('#result_products').removeClass('d-none')
		$('.pagination').removeClass('d-none');
	}
	function change_type(){
		default_sel_gaylord=false;
		show_inventories('',1);
	}
	function change_sort_by(){
		var sort_by=$('#sort_by').val();
		if(sort_by=="nearest" || sort_by=="furthest"){
			if($('#txtzipcode').val()==""){
				var alert_msg= sort_by=="nearest" ? 'Nearest' : 'furthest';
				alert("Enter Zipcode For "+alert_msg+" Products");
				$('#txtzipcode').focus();
				//$('#txtzipcode').attr("zip_for_sorting",1)
			}else{
				show_inventories('',1);
			}
		}else{
			show_inventories('',1);
		}
	}
	$('#txtzipcode').focusout(function(){
		var sort_by=$('#sort_by').val();
		if($('#txtzipcode').val()!="" && (sort_by=="nearest" || sort_by=="furthest")){
			show_inventories('',1);
		}
	})
	function clear_filter(clear_filter_of){
        if(clear_filter_of=='deliverydata'){
			$("input[name='txtaddress']").val("");
			$("input[name='txtaddress2']").val("");
			$("input[name='txtcity']").val("");
			$("input[name='txtstate']").val("");
			$("input[name='txtcountry']").val("");
			$("input[name='txtzipcode']").val("");
			$("#apply-deliverydata").css('width', '100%');
			$("#clear-deliverydata").css('display','none');
			selection_obj.deliverydata={};
		}
		show_inventories("",1);
	}

	
	function change_view_type(view_type){
		if(view_type=="list_view"){
			$("#products-table-view").css("display","none");
			$("#products-list-view").css("display","block");
			$('#list-view-button').addClass('btn-active-view');
			$('#table-view-button').removeClass('btn-active-view');
		}else{
			$("#products-list-view").css("display","none");
			$("#products-table-view").css("display","block");
			$('#table-view-button').addClass('btn-active-view');
			$('#list-view-button').removeClass('btn-active-view');
		}
		$('#view_type').val(view_type);
		show_inventories();
	}
	
	function change_timing(){
		default_sel_timing=false;
		var timing=$("select[name='timing']").val();
		if(timing==9){
			$('#timing_date_div').removeClass('d-none');
		}else{
			$('#timing_date_div').addClass('d-none');
			show_inventories("",1);
		}
	}

	function change_warehouse(){
		default_sel_warehouse=false;
		var warehouse=$("select[name='warehouse']").val();
		if(warehouse!='all'){
			show_inventories("",1);
		}
	}

    $('#apply-deliverydata').on('click', function(){
        if($('#txtzipcode').val() == ''){
            alert('Please Enter zipcode');
        }
    })
	
	var totalPages=0;
	var numberOfItems = 0;
	// var limitPerPage = 15;
	// var paginationSize = 10 ; 
    var currentPage;
	function show_inventories(reset_form="", filter=""){
		products_loading();
		var no_data=false;
		if(filter==1){
			$("#active_page_id").val(1);
		}
		if(reset_form==1){
			selection_str="";
		}
		var active_page_id=$("#active_page_id").val();
		var view_type=$("#view_type").val();
		selection_str="";
		var box_type= "Shipping"; 
		var box_subtype= "all";

		var sort_by=$("#sort_by").val();
		var available=$("#available").val();
		//var list_by_item=$("#list_by_item").val();

        var txtaddress=$("input[name='txtaddress']").val();
		var txtaddress2=$("input[name='txtaddress2']").val();
		var txtcity=$("input[name='txtcity']").val();
		var txtstate=$("select[name='txtstate']").find(':selected').attr('display_val');
		var txtcountry=$("select[name='txtcountry']").find(':selected').attr('display_val');
		var txtzipcode=$("input[name='txtzipcode']").val();

        if($("input[name='txtzipcode']").val() != "" && reset_form == 2){
            $("#sort_by").val('nearest');
            sort_by = 'nearest';
        }

		var warehouse=$("select[name='warehouse']").val();
		var warehouse_display_val=$("select[name='warehouse']").find(':selected').attr('display_val');
		if(default_sel_warehouse || warehouse==""){	
			selection_obj.warehouse={};
		}else if(warehouse != "all"){
			selection_obj.warehouse={'input_type':'select', 'input_name':'warehouse', data:warehouse, 'display_val':warehouse_display_val};
		}


		var ect_burst=$("select[name='ect_burst']").val();
		var ect_burst_display_val=$("select[name='ect_burst']").find(':selected').attr('display_val');
		if (ect_burst != ""){
			selection_obj.ect_burst={'input_type':'select', 'input_name':'ect_burst', data:ect_burst, 'display_val':ect_burst_display_val};
		}else{
			selection_obj.ect_burst={};
		}

		var box_tag=$("select[name='box_tags']").val();
		var box_tags_display_val=$("select[name='box_tags']").find(':selected').attr('display_val');
		if (box_tag != ""){
			selection_obj.box_tags={'input_type':'select', 'input_name':'box_tags', data:box_tags, 'display_val':box_tags_display_val};
		}else{
			selection_obj.box_tags={};
		}

		var timing=$("select[name='timing']").val();
		var display_val=$("select[name='timing']").find(':selected').attr('display_val');
		var selected_date="";
		if(default_sel_timing || timing==""){	
			selection_obj.timing={};
		}else if(timing != ""){
			if(timing==9){
				var selected_date=$('#timing_date').val();
				if(selected_date==""){
					$('#timing_date_error').removeClass('d-none');
                    alert('Please enter ship by date timimg');
					// return false;
				}else{
					$('#timing_date_error').addClass('d-none');
					selection_obj.timing={'input_type':'select', 'input_name':'timing', data:timing, 'display_val':display_val, selected_date};
				}
			}else{
				selection_obj.timing={'input_type':'select', 'input_name':'timing', data:timing, 'display_val':display_val};
			}	
		}

		var include_FTL_Rdy_Now_Only="";
		if($('#include_FTL_Rdy_Now_Only').is(':checked')){
			include_FTL_Rdy_Now_Only=$("#include_FTL_Rdy_Now_Only").val();
			var classname=$("#include_FTL_Rdy_Now_Only").attr('class');
			var display_val=$("#include_FTL_Rdy_Now_Only").attr('display_val');
			selection_obj.include_FTL_Rdy_Now_Only={'input_type':'checkbox', 'input_name':'include_FTL_Rdy_Now_Only', 'classname':classname, data:include_FTL_Rdy_Now_Only, 'display_val':display_val};
		}else{
			selection_obj.include_FTL_Rdy_Now_Only={};
		}

		var include_sold_out_items="";
		if($('#include_sold_out_items').is(':checked')){
			include_sold_out_items=$("#include_sold_out_items").val();
			var classname=$("#include_sold_out_items").attr('class');
			var display_val=$("#include_sold_out_items").attr('display_val');
			selection_obj.include_sold_out_items={'input_type':'checkbox', 'input_name':'include_sold_out_items', 'classname':classname, data:include_sold_out_items, 'display_val':display_val};
		}else{
			selection_obj.include_sold_out_items={};
		}
		
		var include_presold_and_loops="";
		if($('#include_presold_and_loops').is(':checked')){
			include_presold_and_loops=$("#include_presold_and_loops").val();
			var classname=$("#include_presold_and_loops").attr('class');
			var display_val=$("#include_presold_and_loops").attr('display_val');
			selection_obj.include_presold_and_loops={'input_type':'checkbox', 'input_name':'include_presold_and_loops', 'classname':classname, data:include_presold_and_loops, 'display_val':display_val};
		}else{
			selection_obj.include_presold_and_loops={};
		}
		var ltl_allowed="";
		if($('#ltl_allowed').is(':checked')){
			ltl_allowed=$("#ltl_allowed").val();
			var classname=$("#ltl_allowed").attr('class');
			var display_val=$("#ltl_allowed").attr('display_val');
			selection_obj.ltl_allowed={'input_type':'checkbox', 'input_name':'ltl_allowed', 'classname':classname, data:ltl_allowed, 'display_val':display_val};
		}else{
			selection_obj.ltl_allowed={};
		}
		var customer_pickup_allowed="";
		if($('#customer_pickup_allowed').is(':checked')){
			customer_pickup_allowed=$("#customer_pickup_allowed").val();
			var classname=$("#customer_pickup_allowed").attr('class');
			var display_val=$("#customer_pickup_allowed").attr('display_val');
			selection_obj.customer_pickup_allowed={'input_type':'checkbox', 'input_name':'customer_pickup_allowed', 'classname':classname, data:customer_pickup_allowed, display_val:display_val};
		}else{
			selection_obj.customer_pickup_allowed={};
		}
		var urgent_clearance="";
		if($('#urgent_clearance').is(':checked')){
			urgent_clearance=$("#urgent_clearance").val();
			var classname=$("#urgent_clearance").attr('class');
			var display_val=$("#urgent_clearance").attr('display_val');
			selection_obj.urgent_clearance={'input_type':'checkbox', 'input_name':'urgent_clearance', 'classname':classname, data:urgent_clearance, display_val:display_val};
		}else{
			selection_obj.urgent_clearance={};
		}
		
		var min_price_each = $("input[name='min_price_each']").val().replace("$", "");
		var max_price_each = $("input[name='max_price_each']").val().replace("$", "");
		if(min_price_each!=0.00 || max_price_each!=99.99){
			selection_obj.price_each={'input_type':'text', 'input_name':'price_each', data:[min_price_each, max_price_each]};
		}else{
			selection_obj.price_each={};
		}

		var min_length=$("input[name='min_length']").val().replace('"', "");
		var max_length=$("input[name='max_length']").val().replace('"', "");
		if(min_length!=0 || max_length!=99){
			selection_obj.length={'input_type':'text', 'input_name':'length', data:[min_length, max_length]};
		}else{
			selection_obj.length={};
		}

		var min_width=$("input[name='min_width']").val().replace('"', "");
		var max_width=$("input[name='max_width']").val().replace('"', "");
		if(min_width!=0 || max_width!=99){
			selection_obj.width={'input_type':'text', 'input_name':'width', data:[min_width, max_width]};
		}else{
			selection_obj.width={};
		}

		var min_height=$("input[name='min_height']").val().replace('"', "");
		var max_height=$("input[name='max_height']").val().replace('"', "");
		if(min_height!=0 || max_height!=99){	
			selection_obj.height={'input_type':'text', 'input_name':'height', data:[min_height, max_height]};
		}else{
			selection_obj.height={};
		}

		var min_cubic_footage=$("input[name='min_cubic_footage']").val();
		var max_cubic_footage=$("input[name='max_cubic_footage']").val();
		if(min_cubic_footage!=0.00 || max_cubic_footage!=99.99){
			selection_obj.cubic_footage={'input_type':'text', 'input_name':'cubic_footage', data:[min_cubic_footage, max_cubic_footage]};
		}else{
			selection_obj.cubic_footage={};
		}

        var printing=[]; 
		var all_printing_data=[];
		if($("input:checkbox[name='printing[]']").filter(':checked').length>0){
			$("input[name='printing[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				printing.push({'values':$(this).val(), 'classname':classname, display_val});
				all_printing_data.push($(this).val());				
			});
			selection_obj.printing={'input_type':'checkbox', 'input_name':'printing', data:printing};
		}else{
			selection_obj.printing={};
		}

        var work_as_a_kit_box=[]; 
		var all_work_as_a_kit_box_data=[];
		if($("input:checkbox[name='work_as_a_kit_box[]']").filter(':checked').length>0){
			$("input[name='work_as_a_kit_box[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				work_as_a_kit_box.push({'values':$(this).val(), 'classname':classname, display_val});
				all_work_as_a_kit_box_data.push($(this).val());				
			});
			selection_obj.work_as_a_kit_box={'input_type':'checkbox', 'input_name':'work_as_a_kit_box', data:work_as_a_kit_box};
		}else{
			selection_obj.work_as_a_kit_box={};
		}

		$.each(selection_obj, function(k,v){
			if(Object.keys(v).length!=0){
				// console.log(v.input_name);
				if(v.input_name=="include_sold_out_items" || v.input_name=="include_FTL_Rdy_Now_Only" || v.input_name=="include_presold_and_loops" || v.input_name=="ltl_allowed" || v.input_name=="customer_pickup_allowed" || v.input_name=="urgent_clearance"){
					selection_str+="<p class='added_selection' classname='"+v.classname+"' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span> "+v.display_val +" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}else if(v.input_name=="warehouse"){
					selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.display_val+" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}else if(v.input_name=="box_tags" || v.input_name=="ect_burst"){
					selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.display_val+" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}else if(v.input_name=="timing"){
					if(v.data==9){
						selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.display_val+"( "+v.selected_date+" ) </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					}else{
					    selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.display_val+" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					}
                }else if(v.input_name=="printing" || v.input_name=="work_as_a_kit_box"){
					for(var x=0; x<v.data.length; x++){
						selection_str+="<p class='added_selection' classname='"+v.data[x].classname+"' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span> "+v.data[x].display_val +" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					}
				}else{
					// selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.data[0]+" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span class='text-capitalize'>"+v.input_name+"</span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}
			}
		});
		
		$('#selections').html(selection_str);
		if(selection_str!=""){
			$('#your-selections').css('display',"block");
		}else{
			$('#your-selections').css('display',"none");
		}
		
		// $("#warehouse").find('option').not(':first').remove();
		var data={box_type,view_type,active_page_id, box_subtype,sort_by,available,txtaddress,txtaddress2, txtcity, txtstate, txtcountry, txtzipcode,warehouse,timing,selected_date,include_sold_out_items, include_FTL_Rdy_Now_Only,
		include_presold_and_loops,ltl_allowed,customer_pickup_allowed,urgent_clearance,min_price_each, max_price_each, min_length,max_length,min_width,max_width,min_height,max_height,min_cubic_footage,max_cubic_footage,ect_burst,all_printing_data,all_work_as_a_kit_box_data,box_tag};
		console.log(data);
		$.ajax({
			url:'product_result.php',
			type:'get',
			data:data,
			datatype:'json',
			success:function(response){
				//$('#result_products').html(response);
				//return;
				//console.log(response);
				
				result_str="";
				var all_data=JSON.parse(response);
				var result=all_data.data;
				var no_of_pages=all_data.no_of_pages;
				numberOfItems = all_data.total_items;
				// limitPerPage = 15;
				// totalPages = Math.ceil(numberOfItems / limitPerPage);
				if( !$.isArray(result) ||  result.length ==0 ) {
					result_str +='<div class="col-md-12 mt-5 alert alert-danger">';
					result_str +='<h6 class="mb-0">No Data Available </h6>';
					result_str +='</div>';
					no_data=true;
				}else{
                    var sub_table_total_value = 0;
                    var sub_table_truckload = 0;
                    // var load_av_after_po = 0;
                    // var frequency = 0;
					var default_img="images/boomerang/default_product.jpg";
					if(view_type=="list_view"){
						result_str +='<div id="products-list-view" class="mt-3 m-auto">';
						$.each(result,function(index,res){
                            sub_table_total_value += parseInt(res['qtynumbervalue'].replace(/[^0-9]/g, ""));
                            sub_table_truckload += parseInt(res['percent_per_load'].replace(/[^0-9]/g, ""));
                            // actual_qty += res['actual_qty'];
                            // actual_po += res['actual_po'];
                            // frequency += res['frequency'];
                            // load_av_after_po += res['load_av_after_po'];
							result_str +='<div class="col-md-12 product-box-list thikness-5">';
							result_str +='<div class="row align-items-center">';
							result_str +='<div class="col-md-2">';
							var prod_src=res['img']==""? default_img :  "../boxpics_thumbnail/"+res['img'];
							result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'"><img src="'+prod_src+'" class="img-fluid product-img"/></a>';
							result_str +='</div>';
							result_str +='<div class="col-md-8 product-description-list">';
							result_str +='<div class="col-md-12 product-additional-info">';
							result_str +='<div class="row">';
							result_str +='<div class="col-md-8 p-0">';
							result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'"><h5>'+res["description"]+'</h5></a>';
							result_str +='<div class="media">';
							result_str +='<div class="media-img-box"><img class="mr-2 img-fluid" src="images/boomerang/icon_status.png" alt="Generic placeholder image"></div>';
							result_str +='<div class="media-body"><p><span class="product_desc">Status: '+res["status"]+'</p></div>';
							result_str +='</div>';
							result_str +='<div class="media">';
							result_str +='<div class="media-img-box"><img class="mr-2 img-fluid" src="images/boomerang/icon_Location.png" alt="Generic placeholder image"></div>';
							result_str +='<div class="media-body"><span class="product_desc">Ship From: </span> '+res["ship_from"]+'</div>';
							result_str +='</div>';
                            result_str +='<div class="media">';
							result_str +='<div class="media-img-box"><img class="mr-2 img-fluid" src="images/boomerang/Icon_Dimensions.png" alt="Generic placeholder image"></div>';
							var system_description=res['system_description'];
							result_str +='<div class="media-body"><p><span class="product_desc">Description: </span>'+system_description+'</p></div>';
							result_str +='</div>';
							result_str +='<div class="media">';
							result_str +='<div class="media-img-box"><img class="mr-2 img-fluid" src="images/boomerang/Icon_notes.png" alt="Generic placeholder image"></div>';
							var flyer_notes=res['flyer_notes'];
							result_str +='<div class="media-body"><p><span class="product_desc">Flyer Notes: </span> '+flyer_notes+'</p></div>';
							result_str +='</div>';
							result_str +='</div>';
							result_str +='<div class="col-md-4">';
							// if($('#txtzipcode').val()==""){
							// 	result_str +='<p class="my-1"><span class="highlight-detail-list" onclick="get_miles_away(); return false;">Add zip for mi away</span></p>';
							// }else{
							// 	result_str +='<p class="my-1"><span class="highlight-detail-list">'+res["distance"]+' mi away</span></p>';
							// }
							result_str +='<p class="my-1 text-center">'+res["distance"]+'</p>';
							result_str +='<div class="media">';
							result_str +='<div class="media-img-box"><img class="mr-2 img-fluid" src="images/boomerang/Icon_truck.png" alt="Generic placeholder image"></div>';
							result_str +='<div class="media-body"><p><span class="product_desc">Can Ship LTL? </span> '+res["ltl"]+'</p></div>';
							result_str +='</div>';
							result_str +='<div class="media">';
							result_str +='<div class="media-img-box"><img class="mr-2 img-fluid" src="images/boomerang/can_customer_pickup.png" alt="Generic placeholder image"></div>';
							result_str +='<div class="media-body"><p><span class="product_desc">Can Customer Pickup? </span> '+res["customer_pickup"]+'</p></div>';
							result_str +='</div>';
                            result_str +='<div class="media flex-column">';
							result_str +='<p class="text-center w-100"><a class="product_link available_loads" data-toggle="modal" data-target="#available_loads_modal" loop_id="'+res.loop_id+'">'+res["loads"]+'</a></p>';
							result_str +='<p class="text-center w-100">First Load Can Ship In <br> '+res["first_load_can_ship_in"]+'</p>';
							result_str +='</div>';
							/*result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="images/boomerang/Icon_supplier_owner.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body"><p><span class="product_desc">Supplier Owner: '+res["supplier_owner"]+'</p></div>';
							result_str +='</div>';*/
							/*result_str +='<div class="media">';
							result_str +='<div class="media-body"><p><span class="product_desc" >Updated:</span> '+res["updated_on"]+'</p></div>';
							result_str +='</div>';*/
							result_str +='</div>';
							// result_str +='<div class="col-md-12 p-0">';
							// result_str +='<div class="media">';
							// result_str +='<img class="mr-2 img-fluid" src="images/boomerang/Icon_Dimensions.png" alt="Generic placeholder image">';
							// var system_description=res['system_description'];
							// result_str +='<div class="media-body"><p><span class="product_desc">Description: </span>'+system_description+'</p></div>';
							// result_str +='</div>';
							// result_str +='<div class="media">';
							// result_str +='<img class="mr-2 img-fluid" src="images/boomerang/Icon_notes.png" alt="Generic placeholder image">';
							// var flyer_notes=res['flyer_notes'];
							// result_str +='<div class="media-body"><p><span class="product_desc">Flyer Notes: </span> '+flyer_notes+'</p></div>';
							// result_str +='</div>';
							// result_str +='<div class="media">';
							// result_str +='<img class="mr-2 img-fluid" src="images/boomerang/Icon_notes.png" alt="Generic placeholder image">';
							// result_str +='<div class="media-body"><p><span class="product_desc">% of Load: </span> '+res['lead_time_of_FTL']+'</p></div>';
							// result_str +='</div>';
							// result_str +='</div>';
							result_str +='</div></div></div>';
							result_str +='<div class="col-md-2 align-self-end p-0">';
							result_str +='<div class="load_ship">';
							var delivery_cal_para=res['b2b_id']+" , '"+res['txtaddress']+"' , '"+res['txtaddress2']+"' , '"+res['txtcity']+"' , '"+res['txtstate']+"' , '"+res['txtcountry']+"' , '"+res['txtzipcode']+"' , "+res['minfob'];
							result_str +='<h5 class="font-weight-bold m-0" id="cal_delh4'+res['b2b_id']+'">'+res["price"]+' </h5><div id="cal_del'+res['b2b_id']+'"><a class="product_link" href="#" onclick="calculate_delivery('+delivery_cal_para+'); return false;">Calculate Delivery</a></div>';
							// var delivery_cal_para=res['b2b_id']+" , '"+res['txtaddress']+"' , '"+res['txtaddress2']+"' , '"+res['txtcity']+"' , '"+res['txtstate']+"' , '"+res['txtcountry']+"' , '"+res['txtzipcode']+"' , "+res['minfob'];
							// result_str +='<h6 class="font-weight-bold" id="cal_delh4'+res['b2b_id']+'">'+res["price"]+' </h6>';
							//result_str +='<h6>'+res["price"]+' </h6><div id="cal_del'+res['b2b_id']+'"><a class="product_link" href="#">Calculate Delivery</a></div>';
							result_str +='</div>';
							result_str +='<div class="load_ship mt-2">';
							result_str +='<ul class="pl-1 text-left m-0" style="list-style-type: none;">';
							// result_str +=`<li><b>Quantity </b> : <a href="javascript:void(0)" onclick="display_preoder_sel(${index}, ${res['reccnt']}, ${res['loop_id']}, ${res['box_warehouse_id']})"><u>${res['qtyAvailable']}</u></a></li>`;
							result_str +=`<li><b>Quantity </b> : ${res['colorvalueQty']}</li>`;
							result_str +='<li><b>Lead Time </b> : '+res['lead_time_of_FTL']+'</li>';
							result_str +='<li><b>% of Load</b> : '+res['percent_per_load']+'%</li>';
							result_str +='</ul>';
							result_str +='</div>';
							result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'&checkout=1" class="btn btn-cart">Buy Now</a>';
							
							result_str +='</div></div></div>';

						});
						result_str +='</div>';
					}else{
					result_str +='<div id="products-table-view" class="mt-3">';
					result_str +='<table class="w-100" cellSpacing="1" cellPadding="1" border="0">';
					result_str += '<thead><tr>';
										
										if (available == 'actual'){
											result_str += '<th>Actual</th>';
										}else{
											result_str += '<th>Qty Avail</th>';
										}
										
										result_str += `<th>Lead Time for FTL</th>
											<th>% of Load</th>
											<th>MIN FOB</th>
											<th>B2B ID</th>
											<th>Miles Away</th>
											<th>B2B Status</th>
											<th>Description</th>
											<th>Supplier</th>
											<th>Ship From</th>
										</tr>
									</thead>
									<tbody>`;
					$.each(result,function(index,res){
                        sub_table_total_value += parseInt(res['qtynumbervalue'].replace(/[^0-9]/g, ""));
                        sub_table_truckload += parseInt(res['percent_per_load'].replace(/[^0-9]/g, ""));
						result_str +=`<tr>	
										<td bgColor="${res['td_bg']}" id="after_po${index}"><a href="javascript:void(0)" onclick="display_preoder_sel(${index}, ${res['reccnt']}, ${res['loop_id']}, ${res['box_warehouse_id']})"><u>${res['colorvalueQty']}</u></a></td>
										<td bgColor="${res['td_bg']}">${res['lead_time_of_FTL']}</td>
										<td bgColor="${res['td_bg']}">${res['percent_per_load']}%</td>
										<td bgColor="${res['td_bg']}">$${res["minfob"]}</td>
										<td bgColor="${res['td_bg']}">${res['b2b_id']}</td>
										<td bgColor="${res['td_bg']}">${res["distance"]}</td>
										<td bgColor="${res['td_bg']}">${res["status"]}</td>
										<td bgColor="${res['td_bg']}" class="text-left"><a class="text-primary" onmouseover="Tip('${res['description_hover_notes']}')"  onmouseout="UnTip()" target="_blank" href="manage_box_b2bloop.php?id=${res['loop_id']}&proc=View&"><u>${res['system_description']}</u></a></td>
										<td bgColor="${res['td_bg']}" class="text-left">${res['supplier_name']}</td>
										<td bgColor="${res['td_bg']}" class="text-left">${res["shipFromLocation"]}</td>
									</tr>`;

                        if(res['reccnt'] > 0){
                                result_str +=`<tr id="inventory_preord_top_${index}' align="middle" style="display:none;">
                                    <td>&nbsp;</td>
                                    <td colspan="13" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
                                            <div id="inventory_preord_middle_div_${index}"></div>		
                                    </td>
                                </tr>`;
						}
					});
					result_str +='</tbody>';
					result_str +='</div>';
				};
			}

				$('#result_products').html(result_str);
				$('#total_SKU').html(result.length);
				// $('#actual_qty,#load_av_after_po,#frequency,#actual_po').html(0);
				switch (available) {
					case 'quantities':
						$('#heading_total').html("Qty Total");
						$('#heading_truckload').html("Qty Truckloads");
						// $('#actual_qty').html(actual_qty.toLocaleString('en-US'));
					break;

					case 'available':
						$('#heading_total').html("Avail Total");
						$('#heading_truckload').html("Avail Truckloads");
						// $('#actual_qty').html(actual_qty.toLocaleString('en-US'));
					break;

					case 'actual':
                        $('#heading_total').html("Actual Total");
						$('#heading_truckload').html("Actual Truckloads");
						//$('#load_av_after_po').html(load_av_after_po.toFixed(2)+"%");
					break;

					case 'frequency':
                        $('#heading_total').html("Frequency/mo Total");
						$('#heading_truckload').html("Frequency/mo Truckloads");
						//$('#frequency').html(frequency.toFixed(2));
					break;
                }
				
				if (sub_table_truckload > 0){
					var truckloadValue = sub_table_truckload / 100;
					$('#sub_table_truckload').html(truckloadValue.toFixed(2)+"%");
				}else{
					$('#sub_table_truckload').html("");
				}

				if (sub_table_total_value > 0){
					$('#sub_table_total_value').html(sub_table_total_value.toLocaleString('en-US'));
				}else{
					$('#sub_table_total_value').html("0");
				}
			},
			complete: function () {
				if(no_data==false){
					/*$('#loader').fadeOut();
					$('.pagination').removeClass('d-none');*/
					$('#txtzipcode').removeAttr("zip_for_sorting");
					products_loaded();
					// showPage(Number(active_page_id));
				}else{
					products_loaded();
					// $('.pagination').addClass('d-none');
				}
				
			},
		})
	}
	
    show_inventories();
	
	// $(".pagination").append(
	// 	$("<li>").addClass("page-item").attr({ id: "previous-page" }).append(
	// 		$("<a>").addClass("page-link").attr({
	// 			href: "javascript:void(0)"}).html('<i class="fa fa-angle-double-left"></i>')
	// 	),
	// 	$("<li>").addClass("page-item").attr({ id: "next-page" }).append(
	// 		$("<a>").addClass("page-link").attr({
	// 			href: "javascript:void(0)"}).html('<i class="fa fa-angle-double-right"></i>')
	// 	)
	// );
	
	// function showPage(whichPage) {
	// 	if (whichPage < 1 || whichPage > totalPages) return false;
	// 	currentPage = whichPage;           
	// 	$(".pagination li").slice(1, -1).remove();
	// 	getPageList(totalPages, currentPage, paginationSize).forEach( item => {
	// 		$("<li>").addClass("page-item")
	// 				 .addClass(item ? "current-page" : "disabled")
	// 				 .toggleClass("active", item === currentPage).append(
	// 			$("<a>").addClass("page-link").attr({
	// 				href: "javascript:void(0)"}).text(item || "...")
	// 		).insertBefore("#next-page");
	// 	});
		
	// 	$("#previous-page").toggleClass("disabled", currentPage === 1);
	// 	$("#next-page").toggleClass("disabled", currentPage === totalPages);
	// 	return true;
	// }
	
	 // Use event delegation, as these items are recreated later    
    // $(document).on("click", ".pagination li.current-page:not(.active)", function () {
	// 	$("#active_page_id").val(+$(this).text());
	// 	show_inventories();
	// 	return showPage(+$(this).text());
    // });
	
    // $("#next-page").on("click", function () {
	// 	var isDisabled=$("#next-page").hasClass("disabled");
	// 	if(!isDisabled){
	// 		$("#active_page_id").val(+$(this).text());
	// 		show_inventories();
	// 		return showPage(currentPage+1);
	// 	}
    // });

    // $("#previous-page").on("click", function () {
	// 	var isDisabled=$("#previous-page").hasClass("disabled");
	// 	if(!isDisabled){
	// 		$("#active_page_id").val(+$(this).text());
	// 		show_inventories();
	// 		return showPage(currentPage-1);
	// 	}
    // });
	
	// function getPageList(totalPages, page, maxLength) {
	// 	if (maxLength < 5) throw "maxLength must be at least 5";

	// 	function range(start, end) {
	// 		return Array.from(Array(end - start + 1), (_, i) => i + start); 
	// 	}
		
	// 	var sideWidth = maxLength < 9 ? 1 : 2;
	// 	var leftWidth = (maxLength - sideWidth*2 - 3) >> 1;
	// 	var rightWidth = (maxLength - sideWidth*2 - 2) >> 1;
	// 	if (totalPages <= maxLength) {
	// 		// no breaks in list
	// 		return range(1, totalPages);
	// 	}
	// 	if (page <= maxLength - sideWidth - 1 - rightWidth) {
	// 		// no break on left of page
	// 		return range(1, maxLength - sideWidth - 1)
	// 			.concat(0, range(totalPages - sideWidth + 1, totalPages));
	// 	}
	// 	if (page >= totalPages - sideWidth - 1 - rightWidth) {
	// 		// no break on right of page
	// 		return range(1, sideWidth)
	// 			.concat(0, range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages));
	// 	}
	// 	// Breaks on both sides
	// 	return range(1, sideWidth)
	// 		.concat(0, range(page - leftWidth, page + rightWidth),
	// 				0, range(totalPages - sideWidth + 1, totalPages));
	// }
	$(document).on('mouseenter','.product-box-grid',function(){
		$(this).find('.fixed_height_description').addClass('d-none');
		$(this).find('.complete_description').removeClass('d-none');
		$(this).find('.fixed_height_flyer_note').addClass('d-none');
		$(this).find('.complete_flyernote').removeClass('d-none');
	})
	$(document).on('mouseleave','.product-box-grid',function(){
		$(this).find('.fixed_height_description').removeClass('d-none');
		$(this).find('.complete_description').addClass('d-none');
		$(this).find('.fixed_height_flyer_note').removeClass('d-none');
		$(this).find('.complete_flyernote').addClass('d-none');
	})

	$(document).on('click','.remove_selection', function(){
		var remove_val_of=$(this).parents('.added_selection').attr('respective_input');
		var input_type=$(this).parents('.added_selection').attr('respective_type');
		if(input_type=="text"){
			if(remove_val_of == "length"){
				$("input[name='min_length']").val('0"');
				$("input[name='max_length']").val(99);
			}else if(remove_val_of == "price_each"){
				$("input[name='min_price_each']").val("$0.00");
				$("input[name='max_price_each']").val("$99.99");
			}else if(remove_val_of == "width"){
				$("input[name='min_width']").val(0);
				$("input[name='max_width']").val(99);
			}else if(remove_val_of == "height"){
				$("input[name='min_height']").val(0);
				$("input[name='max_height']").val(99);
			}else if(remove_val_of == "cubic_footage"){
				$("input[name='min_cubic_footage']").val(0.00);
				$("input[name='max_cubic_footage']").val(99.99);
			}else{
				$("#"+remove_val_of).val("");
			}
		}else if(input_type=="select"){
			if(remove_val_of=="box_type"){
				$("#"+remove_val_of).val("Gaylord");
				default_sel_gaylord=true;
			}else if(remove_val_of=="timing"){
				$("#"+remove_val_of).val(4);
				default_sel_timing=true;
			}else if(remove_val_of=="warehouse"){
				$("#"+remove_val_of).val('all');
				default_sel_warehouse=true;
			}else{
				$("#"+remove_val_of).val("");
			}
		}else if(input_type=="checkbox"){
			var classname=$(this).parents('.added_selection').attr('classname');
			$("."+classname).prop("checked", false );
		}	
		$(this).parents('.added_selection').css('display','none');
		show_inventories("",1);
	})
	$("#clear_all").click(function(){
		$("#filter_form")[0].reset();
		show_inventories(1,1);
		return false;
	});

	function get_miles_away()
	{
		$('#txtzipcode').focus();
	}

	function calculate_delivery(inv_b2b_id, txtaddress, txtaddress2, txtcity, txtstate, txtcountry, txtzipcode, minfob){
		if ( $('#txtaddress').val()=="" || $('#txtcity').val()=="" || $('#txtstate').val()=="" || $('#txtcountry').val()=="" || $('#txtzipcode').val()==""){
			alert("Enter the Delivery address to calculate the delivery.")
			$('#txtaddress').focus();
		}else{
			$.ajax({
				url:'uber_freight_matching_tool_v3.php',
				type:'get',
				data:"inv_b2b_id="+inv_b2b_id+"&txtaddress="+txtaddress+"&txtaddress2="+txtaddress2+"&txtcity="+txtcity+"&txtstate="+txtstate+"&txtcountry="+txtcountry+"&txtzipcode="+txtzipcode+"&minfob="+minfob,
				datatype:'text',
				beforeSend: function () {
					$('#cal_del'+inv_b2b_id).html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
				},
				success:function(res){
					$('#cal_delh4'+inv_b2b_id).css('display','none');
					$('#cal_del'+inv_b2b_id).html(res);
				},	
			})
		}
	}

	$(document).on('click','.available_loads', function(){
		var loop_id=$(this).attr('loop_id');
		$.ajax({
				url:'show_available_load_data.php',
				type:'get',
				data:{loop_id},
				datatype:'json',
				beforeSend: function () {
					$("#ship_data").html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
				},
				success:function(response){
					var res=JSON.parse(response);
					if(res.count>0){
						var table_data="<table class='table table-bordered'><thead><tr><th>S. no</th><th>Available Load Ship Date</th></tr></thead>";
						$.each(res.data,function(i,d){
							table_data+="<tr>";	
							table_data+="<td>"+(i+1)+"</td>";	
							table_data+="<td>"+d.load_available_date+"</td>";	
							table_data+="</tr>";	
						})
						table_data+="</table>";
						$("#ship_data").html(table_data);
					}else{
						$("#ship_data").html('No data for this inventory item at this time.');
					}
				},	
			})
	});

	$(document).on('click','#enter_address_text', function(){
		if($('#full_address_div').hasClass('d-none')){
			$('#full_address_div').removeClass('d-none');
			$(this).text('Hide Address');
			$('#clear-deliverydata').text('Clear Address');
			$('#apply-deliverydata').text('Apply Address');

		}else{
			$('#full_address_div').addClass('d-none');
			$(this).text('Enter Full Adddress');
			$('#clear-deliverydata').text('Clear Zipcode');
			$('#apply-deliverydata').text('Apply Zipcode');
		}
	})

</script>

</html>
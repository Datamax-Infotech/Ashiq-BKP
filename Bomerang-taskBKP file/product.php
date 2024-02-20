<?
/*
File Name: inventory_v1_new.php
Page created By: Bhavna
Page created On: 17-08-2023
Last Modified On: 
Last Modified By: Bhavna
Change History:
Date           By            Description
===============================================================================================================
04-09-2023      Bhavna     This file is created for the inventory product view.
							  
							
===============================================================================================================
*/
require_once('header.php');
//require ("inc/header_session.php");
require ("../mainfunctions/database.php");
require ("mainfunctions/general-functions.php");
?>
<div class="main-section mt-5">
	<div class="container">
		<div class="row">
		<div class="col-md-3 inventory-filter">
			<div class="col-md-12 filter-box1">
				<div class="row">
					<div class="col-md-12" id="your-selections">
					<p><b>YOUR SELECTIONS</b></p>
					<div id="selections"></div>
					<a class="map_link float-right" href="#" id="clear_all">Clear All</a>
					</div>
					<form id="filter_form">
					<div class="form-group col-md-12">
						<label>Price</label>
						<div class="range-slider" id="range-slider-price">
							<div class="row">
								<div class="col-sm-5">
									<div class="input-group input-group-sm">
									  <div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">$</span>
									  </div>
									  <input name="min_price" type="number" class="form-control form-control-sm" value="0" min="0" max="500" id="price-from"/>
									</div>
								</div>
								<div class="col-sm-2">
									-
								</div>
								<div class="col-sm-5">
									<div class="input-group input-group-sm">
									  <div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">$</span>
									  </div>
									  <input  name="max_price" type="number" class="form-control form-control-sm" value="500" min="0" max="500" id="price-to"/>
									</div>
								</div>
								<div class="col-sm-12 mt-3">
									<input name="min_range_slide" value="0" min="0" max="500" step="10" type="range"/>
									<input name="max_range_slide" value="500" min="0" max="500" step="10" type="range"/>
								</div>
							</div>
						</div>
						<div class="text-center apply-price-div">
							<button type="button" class="btn apply-filter btn-sm" id="clear-price" onclick="clear_filter('price')">Clear Price</button>
							<button type="button" class="btn apply-filter btn-sm" id="apply-price" onclick="show_inventories()">Apply Price</button>
						</div>
					</div>
					
					<div class="form-group col-md-12">
						<h5 class="inventory_category">Delivery Address
						<i class="fa fa-exclamation-circle tooltip_icon" data-toggle="tooltip" data-placement="right" data-html="true" title="Enter where you need the boxes delivered, and the inventory catalog will sort the inventory by the items closest to you (closer = cheaper to deliver)"></i>
						</h5>
						<label>Address</label>
						<input type="text" class="form-control  form-control-sm" name="txtaddress" id="txtaddress"/>
						<label>Address 2</label>
						<input type="text" class="form-control  form-control-sm" name="txtaddress2" id="txtaddress2"/>
						<label>City</label>
						<input type="text" class="form-control  form-control-sm" name="txtcity" id="txtcity"/>
						<label>State</label>
						<select class="form-control form-control-sm" name="txtstate" id="txtstate">
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
						<label>Zip Code</label>
						<input type="text" class="form-control  form-control-sm" name="txtzipcode" id="txtzipcode"/>
						<label>Country</label>
						<select class="form-control form-control-sm" name="txtcountry" id="txtcountry">
							<option value="USA" display_val="USA" <? if ($_REQUEST["txtcountry"] == "USA") { echo " selected "; }?>>USA</option> 
							<option value="Canada" display_val="Canada" <? if ($_REQUEST["txtcountry"] == "Canada") { echo " selected "; }?>>Canada</option> 
							<option value="Mexico" display_val="Mexico" <? if ($_REQUEST["txtcountry"] == "Mexico") { echo " selected "; }?>>Mexico</option> 
						</select>
						<div class="text-center">
							<button type="button" class="btn apply-filter btn-sm" id="clear-deliverydata" onclick="clear_filter('deliverydata')">Clear Delivery data</button>
							<button type="button" class="btn apply-filter btn-sm" id="apply-deliverydata" onclick="show_inventories()"> Apply Delivery data</button>
						</div>
					</div>
					
					<div class="form-group col-md-12">
						<label>Timing</label>
						<select class="form-control form-control-sm" name="timing" id="timing" onchange="change_timing()">
							<option value="4" display_val="Can ship in 2 weeks" selected>Can ship in 2 weeks</option>
							<option value="5" display_val="Can ship immediately">Can ship immediately</option>
							<option value="7" display_val="Can ship this month">Can ship this month</option>
							<option value="8" display_val="Can ship next month">Can ship next month</option>
							<option value="6" display_val="Ready to ship whenever">Ready to ship whenever</option>
							<option value="9" display_val="Enter ship by date">Enter ship by date</option>
						</select>
						<div class="mt-2 d-none" id="timing_date_div">
						<input type="date" class="form-control  form-control-sm" name="timing_date" id="timing_date"/>
						<p id="timing_date_error" class="text-danger d-none"><small>Please Select Date</small></p>
						<div class="text-center">
						<button type="button" class="btn apply-filter btn-sm" id="apply-timimg" onclick="show_inventories('',1)"> Load</button>
						</div>
						</div>
					</div>
					
					<div class="form-group col-md-12">
						<label>LTL Allowed ?</label><br>
						<input class="ltl_allowed" type="checkbox" display_val="LTL Allowed" value="1" name="ltl_allowed" id="ltl_allowed" onchange="show_inventories('',1)"/> Yes
					</div>
					<div class="form-group col-md-12">
						<label>Customer Pickup Allowed ?</label><br>
						<input class="customer_pickup_allowed" type="checkbox" display_val="Customer Pickup Allowed" value="1" name="customer_pickup_allowed" id="customer_pickup_allowed" onchange="show_inventories('',1)"/> Yes
					</div>
				</div>
			</div>
			<div class="col-md-12 filter-box2">
				<div class="row">
					<div class="form-group col-md-12">
					<label>Type</label>
					<?php
						$box_type=isset($_REQUEST['box_type']) && $_REQUEST['box_type']!="" ? $_REQUEST['box_type'] : "Gaylord";
						$box_subtype=isset($_REQUEST['box_subtype']) && $_REQUEST['box_subtype']!="" ? $_REQUEST['box_subtype'] : "all";
					?>
					<!--<input type="hidden" value="<?php //echo $box_type;?>" name="box_type"/> -->
					<input type="hidden" value="<?php echo $box_subtype;?>" name="box_subtype"/>
					<select class="form-control form-control-sm" id="box_type" name="box_type" onchange="change_type()">
						<option <?php echo $box_type=="Gaylord" ? " selected " : "" ?>  value="Gaylord"> Gaylord Totes</option>
						<option <?php echo $box_type=="Shipping" ? " selected " : "" ?> value="Shipping">Shipping Boxes</option>
						<option <?php echo $box_type=="Pallets" ? " selected " : "" ?> value="Pallets">Pallets</option>
						<option <?php echo $box_type=="Supersacks" ? " selected " : "" ?> value="Supersacks">Supersacks </option>
					</select>
					</div>
					<div class="form-group col-md-12">
					<label>Height</label>
					<div class="row">
						<div class="col-sm-5 form-group">
						  <input name="min_height" type="number" class="form-control form-control-sm" value="0" min="0" max="99" id="height-from"/>
						</div>
						<div class="col-sm-2">
							-
						</div>
						<div class="col-sm-5 form-group">
						   <input name="max_height" type="number" class="form-control form-control-sm" value="99" min="0" max="99" id="height-to"/>
						</div>
						<div class="text-center col-md-12">
						<button type="button" class="btn apply-filter btn-sm" id="clear-height" onclick="clear_filter('height')">Clear Height</button>
						<button type="button" class="btn apply-filter btn-sm" id="apply-height" onclick="show_inventories('',1)"> Apply Height</button>
						</div>
					</div>
					</div>
					<div class="form-group col-md-12">
						<label>Walls Thick</label><br>
						<input type="checkbox" display_val="1ply" value="1" class="wall_thickness1" name="wall_thickness[]" onchange="show_inventories('',1)" /> 1ply <br>
						<input type="checkbox" display_val="2ply" value="2" class="wall_thickness2"  name="wall_thickness[]" onchange="show_inventories('',1)" /> 2ply <br>
						<input type="checkbox" display_val="3ply" value="3" class="wall_thickness3" name="wall_thickness[]" onchange="show_inventories('',1)" /> 3ply <br>
						<input type="checkbox" display_val="4ply" value="4" class="wall_thickness4" name="wall_thickness[]" onchange="show_inventories('',1)" /> 4ply <br>
						<input type="checkbox" display_val="5ply" value="5" class="wall_thickness5" name="wall_thickness[]" onchange="show_inventories('',1)" /> 5ply <br>
						<input type="checkbox" display_val="6ply" value="6" class="wall_thickness6" name="wall_thickness[]" onchange="show_inventories('',1)" /> 6ply <br>
						<input type="checkbox" display_val="7ply" value="7" class="wall_thickness7" name="wall_thickness[]" onchange="show_inventories('',1)" /> 7ply <br>
						<input type="checkbox" display_val="8ply" value="8" class="wall_thickness8" name="wall_thickness[]" onchange="show_inventories('',1)" /> 8ply <br>
						<input type="checkbox" display_val="9ply" value="9" class="wall_thickness9" name="wall_thickness[]" onchange="show_inventories('',1)" /> 9ply <br>
						<input type="checkbox" display_val="10ply" value="10" class="wall_thickness10" name="wall_thickness[]" onchange="show_inventories('',1)" /> 10ply
					</div>
					<div class="form-group col-md-12">
						<label>Shape</label><br>
						<input type="checkbox" display_val="Rectangular" value="1" class="shape1" name="shape[]" onchange="show_inventories('',1)"/> Rectangular <br>
						<input type="checkbox" display_val="Octagonal" value="2" class="shape2" name="shape[]" onchange="show_inventories('',1)"/> Octagonal <br>
					</div>
					<div class="form-group col-md-12">
						<label>Top</label><br>
						<input type="checkbox" display_val="No Top" value="1" class="top1" name="top[]" onchange="show_inventories('',1)"/> No Top <br>
						<input type="checkbox" display_val="Lid Top" value="2" class="top2" name="top[]" onchange="show_inventories('',1)"/> Lid Top <br>
						<input type="checkbox" display_val="Partial Flap Top" value="3" class="top3" name="top[]" onchange="show_inventories('',1)"/> Partial Flap Top<br>
						<input type="checkbox" display_val="Full Flap Top" value="2" class="top4" name="top[]" onchange="show_inventories('',1)"/> Full Flap Top <br>
					</div>
					<div class="form-group col-md-12">
						<label>Bottom</label><br>
						<input type="checkbox" display_val="No Bottom" value="1" class="bottom1" name="bottom[]" onchange="show_inventories('',1)"/> No Bottom <br>	
						<input type="checkbox" display_val="Partial Flaps w/o Slipsheet"  value="2" class="bottom2" name="bottom[]" onchange="show_inventories('',1)"/> Partial Flaps w/o Slipsheet Bottom<br>
						<input type="checkbox" display_val="Tray Bottom" class="bottom3" value="3" name="bottom[]" onchange="show_inventories('',1)"/> Tray Bottom<br>
						<input type="checkbox" display_val="Full Flap Bottom" class="bottom4" value="4" name="bottom[]" onchange="show_inventories('',1)"/> Full Flap Bottom <br>
						<input type="checkbox" display_val="Partial Flaps w/ Slipsheet" value="5" class="bottom5" name="bottom[]" onchange="show_inventories('',1)"/> Partial Flaps w/ Slipsheet Bottom<br>
						<input type="checkbox" display_val="Partial Flap Bottom" value="6" class="bottom6" name="bottom[]" onchange="show_inventories('',1)"/>  Partial Flap Bottom <br>
						
					</div>
					<div class="form-group col-md-12">
						<label>Vents Okay?</label><br>
						<input id="vents" class="vents" type="checkbox" display_val="Vents" value="1" name="vents" onchange="show_inventories('',1)"/> Yes<br>
					</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="row justify-content-end align-items-end">
					<div class="col-md-3 p-0">
						<select class="form-control form-control-sm bg-light" id="sort_by" onchange="change_sort_by()">
							<option value=""> Sort By </option>
							<option value="low-high" selected>Price Low - High </option>
							<option value="high-low">Price High - Low</option>
							<option value="nearest">Nearest</option>
							<option value="furthest">Furthest</option>
						</select>
						<input type="hidden" id="active_page_id" value="1"/>
					</div>
					<div class="col-md-2 p-0 ml-1">
						<input type="hidden" id="view_type" value="list_view"/>
						<button class="btn btn-light btn-active-view btn-sm" id="list-view-button" onclick="change_view_type('list_view')"><i class="fa fa-bars"></i></button>
						<button class="btn btn-light btn-sm" id="grid-view-button" onclick="change_view_type('grid_view')"><i class="fa fa-th-large"></i></button>
					</div>
			</div>
			<div class="products_div_main">
				<div class="result_products" id="result_products">
				</div>
				<div id="loader">
					<img src="assets/images/loading.gif" height="150" width="150"/>
				</div>
			</div>
			<div class="col-md-12 mt-4 pagination d-none justify-content-center">
		
			</div>
		</div>
	</div>
</div>
<?php require_once('footer.php');?>
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
<script>
	var selection_obj={};
	var default_sel_gaylord=true;
	var default_sel_timing=true;
	
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
		if(clear_filter_of=='price'){
			$("input[name='min_price']").val(0);
			$("input[name='max_price']").val(500);
			$("input[name='min_range_slide']").val(0);
			$("input[name='max_range_slide']").val(500);
			$("#apply-price").css('width', '100%');
			$("#clear-price").css('display','none');
			selection_obj.price={};
		}
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
		
		if(clear_filter_of=='height'){
			$("input[name='min_height']").val(0);
			$("input[name='max_height']").val(99);
			$("#apply-height").css('width', '100%');
			$("#clear-height").css('display','none');
			selection_obj.height={};
		}
		show_inventories("",1);
	}

	function change_view_type(view_type){
		if(view_type=="grid_view"){
			$("#products-grid-view").css("display","block");
			$("#products-list-view").css("display","none");
			$('#grid-view-button').addClass('btn-active-view');
			$('#list-view-button').removeClass('btn-active-view');
		}else{
			$("#products-grid-view").css("display","none");
			$("#products-list-view").css("display","block");
			$('#list-view-button').addClass('btn-active-view');
			$('#grid-view-button').removeClass('btn-active-view');
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
	
	var totalPages=0;
	var numberOfItems = 0;
	var limitPerPage = 15;
	var paginationSize = 10 ; 
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
		var box_type= $("#box_type").val(); //$("input[name='box_type']").val();
		var box_subtype=$("input[name='box_subtype']").val();
		var sort_by=$("#sort_by").val();
		var txtaddress=$("input[name='txtaddress']").val();
		var txtaddress2=$("input[name='txtaddress2']").val();
		var txtcity=$("input[name='txtcity']").val();
		var txtstate=$("select[name='txtstate']").find(':selected').attr('display_val');
		var txtcountry=$("select[name='txtcountry']").find(':selected').attr('display_val');
		var txtzipcode=$("input[name='txtzipcode']").val();
		
		var min_price=$("input[name='min_price']").val();
		var max_price=$("input[name='max_price']").val();
		if(min_price!=0 || max_price!=500){
			$("#apply-price").css('width', '49%');
			$("#clear-price").css({'width': '49%', 'display':'inline'});	
			selection_obj.price={'input_type':'text', 'input_name':'price', data:[min_price, max_price]};
		}else{
			selection_obj.price={};
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
					return false;
				}else{
					$('#timing_date_error').addClass('d-none');
					selection_obj.timing={'input_type':'select', 'input_name':'timing', data:timing, 'display_val':display_val, selected_date};
				}
			}else{
				selection_obj.timing={'input_type':'select', 'input_name':'timing', data:timing, 'display_val':display_val};
			}	
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
		var box_type_filter=$("select[name='box_type']").val();
		if(default_sel_gaylord || box_type_filter==""){	
			selection_obj.box_type_filter={};
		}else{
			//$("input[name='box_type']").val(box_type_filter);
			$("input[name='box_subtype']").val("all");
			box_type=box_type_filter;
			box_subtype="all";
			selection_obj.box_type_filter={'input_type':'select', 'input_name':'box_type', data:[box_type_filter]};
		}
		
		
		var min_height=$("input[name='min_height']").val();
		var max_height=$("input[name='max_height']").val();
		if(min_height!=0 || max_height!=99){
			$("#apply-height").css('width', '49%');
			$("#clear-height").css({'width': '49%', 'display':'inline'});	
			selection_obj.height={'input_type':'text', 'input_name':'height', data:[min_height, max_height]};
		}else{
			selection_obj.height={};
		}
		var wall_thickness=[]; 
		var all_thickness_data=[];
		if($("input:checkbox[name='wall_thickness[]']").filter(':checked').length>0){
			$("input[name='wall_thickness[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				wall_thickness.push({'values':$(this).val(), 'classname':classname, display_val});
				all_thickness_data.push($(this).val());
			});
			selection_obj.wall_thickness={'input_type':'checkbox', 'input_name':'wall_thickness', data:wall_thickness};
		}else{
			selection_obj.wall_thickness={};
		}
		
		var shape=[]; 
		var all_shape_data=[];
		if($("input:checkbox[name='shape[]']").filter(':checked').length>0){
			$("input[name='shape[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				shape.push({'values':$(this).val(), 'classname':classname, display_val});
				all_shape_data.push($(this).val());
			});
			selection_obj.shape={'input_type':'checkbox', 'input_name':'shape', data:shape};
		}else{
			selection_obj.shape={};
		}
		var all_top_data=[];
		var top=[]; 
		if($("input:checkbox[name='top[]']").filter(':checked').length>0){
			$("input[name='top[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				top.push({'values':$(this).val(), 'classname':classname, display_val});
				all_top_data.push($(this).val());
				
			});
			selection_obj.top={'input_type':'checkbox', 'input_name':'top', data:top};
		}else{
			selection_obj.top={};
		}
		var bottom=[]; 
		var all_bottom_data=[];
		if($("input:checkbox[name='bottom[]']").filter(':checked').length>0){
			$("input[name='bottom[]']:checked").each(function(){
				var classname=$(this).attr('class');
				var display_val=$(this).attr('display_val');
				bottom.push({'values':$(this).val(), 'classname':classname, display_val});
				all_bottom_data.push($(this).val());				
			});
			selection_obj.bottom={'input_type':'checkbox', 'input_name':'bottom', data:bottom};
		}else{
			selection_obj.bottom={};
		}
		var vents="";
		if($('#vents').is(':checked')){
			vents=$("#vents").val();
			var classname=$("#vents").attr('class');
			var display_val=$("#vents").attr('display_val');
			selection_obj.vents={'input_type':'checkbox', 'input_name':'vents', 'classname':classname, data:vents,display_val:display_val };
		}else{
			selection_obj.vents={};
		}
		$.each(selection_obj, function(k,v){
			if(Object.keys(v).length!=0){
				if(v.input_name=="price"){
					selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span> $"+v.data[0] +" - $"+ v.data[1] +" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}else if(v.input_name=="ltl_allowed" || v.input_name=="customer_pickup_allowed" || v.input_name=="vents"){
					selection_str+="<p class='added_selection' classname='"+v.classname+"' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span> "+v.display_val +" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}else if(v.input_name=="timing"){
					if(v.data==9){
						selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.display_val+"( "+v.selected_date+" ) </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					}else{
					selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.display_val+" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					}
				}else if(v.input_name=="wall_thickness" || v.input_name=="shape" || v.input_name=="top" || v.input_name=="bottom" ){
					for(var x=0; x<v.data.length; x++){
						selection_str+="<p class='added_selection' classname='"+v.data[x].classname+"' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span> "+v.data[x].display_val +" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
					}
				}else{
					selection_str+="<p class='added_selection' respective_input='"+v.input_name+"' respective_type='"+v.input_type+"'><span>"+v.data[0]+" </span><span class='float-right'><button class='remove_selection'><i class='fa fa-times'></i></button></span></p>";
				}
			}
		});
		$('#selections').html(selection_str);
		if(selection_str!=""){
			$('#your-selections').css('display',"block");
		}else{
			$('#your-selections').css('display',"none");
		}
		var data={box_type,view_type,active_page_id, box_subtype,sort_by,txtaddress,txtaddress2, txtcity, txtstate, txtcountry, txtzipcode, min_price,max_price,timing,selected_date,ltl_allowed,customer_pickup_allowed,min_height,max_height,all_thickness_data,all_shape_data,all_top_data,all_bottom_data,vents};
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
				limitPerPage = 15;
				totalPages = Math.ceil(numberOfItems / limitPerPage);
				if( !$.isArray(result) ||  result.length ==0 ) {
					result_str +='<div class="col-md-12 mt-5 alert alert-danger">';
					result_str +='<h6 class="mb-0">No Data Available </h6>';
					result_str +='</div>';
					no_data=true;
				}else{
					var default_img="assets/images/default_product.jpg";
					if(view_type=="grid_view"){
						result_str +='<div id="products-grid-view">';
						result_str +='<div class="row">';
						$.each(result,function(index,res){
							result_str +='<div class="col-md-4 mt-4 product-box-grid">';
							result_str +='<div class="product-bg-light">';
							var prod_src=res['img']==""?  default_img :  "../boxpics_thumbnail/"+res['img'];
							result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'"><img src="'+prod_src+'" class="img-fluid product-img"/></a>';
							result_str +='<div class="product-description-grid">';
							result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'"><h6> '+res["description"]+' </h6></a>';
							if($('#txtzipcode').val()==""){
								result_str +='<p class="mb-0"><span class="highlight-detail-grid" onclick="get_miles_away(); return false;">Add zip for mi away</span></p>';
							}else{
								result_str +='<p class="mb-0"><span class="highlight-detail-grid">'+res["distance"]+' mi away</span></p>';
							}
							result_str +='</div>';
							result_str +='<div class="product-additional-info">';
							result_str +='<div class="p-2">';
							result_str +='<div class="media">';
							var status=res['status'];
							if(status.length>30){
								status=status.substr(0,30)+"....";
							}
							result_str +='<img class="mr-2 img-fluid" src="assets/images/icon_status.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body"><p class="prod-status"><span class="product_desc">Status: </span> '+status+' </p></div>';
							result_str +='</div>';
							result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="assets/images/icon_Location.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body">';
							result_str +='<p><span class="product_desc">Ship From: </span> '+res["ship_from"]+'</p>';
							result_str +='</div></div>';
							result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="assets/images/Icon_Dimensions.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body">';
							var system_description=res['system_description'];
							if(system_description.length>100){
								system_description=system_description.substr(0,100)+"....";
							}
							result_str +='<p class="fixed_height_description"><span class="product_desc">Description: </span> '+system_description+'</p>';
							result_str +='<p class="d-none complete_description"><span class="product_desc">Description: </span> '+res['system_description'];+'</p>';
							result_str +='</div></div>';
							result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="assets/images/Icon_truck.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body">';
							result_str +='<p><span class="product_desc">Can Ship LTL? </span> '+res["ltl"]+'</p>';
							result_str +='</div></div>';
							result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="assets/images/can_customer_pickup.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body">';
							result_str +='<p><span class="product_desc">Can Customer Pickup? </span> '+res["customer_pickup"]+'</p>';
							result_str +='</div></div>';				
							/*result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="assets/images/Icon_supplier_owner.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body">';
							result_str +='<p><span class="product_desc">Supplier Owner: </span> '+res["supplier_owner"]+'</p>';
							result_str +='</div></div>';*/
							result_str +='<div class="media">';
							result_str +='<img class="mr-2 img-fluid" src="assets/images/Icon_notes.png" alt="Generic placeholder image">';
							result_str +='<div class="media-body">';
							var flyer_notes=res['flyer_notes'];
							if(flyer_notes.length>60){
								flyer_notes=flyer_notes.substr(0,60)+"....";
							}
							result_str +='<p class="fixed_height_flyer_note"><span class="product_desc">Flyer Notes: </span>'+flyer_notes+'</p>';
							result_str +='<p class="d-none complete_flyernote"><span class="product_desc">Flyer Notes: </span> '+res['flyer_notes'];+'</p>';
							
							/*result_str +='<p><span class="product_desc">Updated:</span> '+res["updated_on"]+'</p>';*/
							result_str +='</div></div></div>';
							result_str +='<div  class="load_ship mt-2">';
							result_str +='<h6 id="cal_delh4'+res['b2b_id']+'">'+res["price"]+' </h6>';
							var delivery_cal_para=res['b2b_id']+" , '"+res['txtaddress']+"' , '"+res['txtaddress2']+"' , '"+res['txtcity']+"' , '"+res['txtstate']+"' , '"+res['txtcountry']+"' , '"+res['txtzipcode']+"' , "+res['minfob'];
							result_str +='<div id="cal_del'+res['b2b_id']+'"><a class="product_link" href="#" onclick="calculate_delivery('+delivery_cal_para+'); return false">Calculate Delivery</a></div></div>';
							//result_str +='<div id="cal_del'+res['b2b_id']+'"><a class="product_link" href="#" >Calculate Delivery</a></div></div>';
							result_str +='<div class="load_ship mt-2">';
							result_str +='<a class="product_link available_loads" data-toggle="modal" data-target="#available_loads_modal" loop_id="'+res.loop_id+'" >'+res["loads"]+'</a>';
							result_str +='<p>First Load Can Ship In '+res["first_load_can_ship_in"]+'</p>';
							result_str +='</div>';
							result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'&checkout=1" class="btn btn-cart">Buy Now</a>';
							result_str +='</div>';
							result_str +='</div>';
							result_str +='</div>';
						});
						result_str +='</div>';
						result_str +='</div>';
					}else{
					result_str +='<div id="products-list-view" class="mt-3">';
					$.each(result,function(index,res){
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
						result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'"><h6>'+res["description"]+'</h6></a>';
						result_str +='<div class="media">';
						result_str +='<img class="mr-2 img-fluid" src="assets/images/icon_status.png" alt="Generic placeholder image">';
						result_str +='<div class="media-body"><p><span class="product_desc">Status: '+res["status"]+'</p></div>';
						result_str +='</div>';
						result_str +='<div class="media">';
						result_str +='<img class="mr-2 img-fluid" src="assets/images/icon_Location.png" alt="Generic placeholder image">';
						result_str +='<div class="media-body"><span class="product_desc">Ship From: </span> '+res["ship_from"]+'</div>';
						result_str +='</div>';
						result_str +='</div>';
						result_str +='<div class="col-md-4">';
						if($('#txtzipcode').val()==""){
							result_str +='<p class="my-1"><span class="highlight-detail-list" onclick="get_miles_away(); return false;">Add zip for mi away</span></p>';
						}else{
							result_str +='<p class="my-1"><span class="highlight-detail-list">'+res["distance"]+' mi away</span></p>';
						}
						result_str +='<div class="media">';
						result_str +='<img class="mr-2 img-fluid" src="assets/images/Icon_truck.png" alt="Generic placeholder image">';
						result_str +='<div class="media-body"><p><span class="product_desc">Can Ship LTL? </span> '+res["ltl"]+'</p></div>';
						result_str +='</div>';
						result_str +='<div class="media">';
						result_str +='<img class="mr-2 img-fluid" src="assets/images/can_customer_pickup.png" alt="Generic placeholder image">';
						result_str +='<div class="media-body"><p><span class="product_desc">Can Customer Pickup? </span> '+res["customer_pickup"]+'</p></div>';
						result_str +='</div>';
						/*result_str +='<div class="media">';
						result_str +='<img class="mr-2 img-fluid" src="assets/images/Icon_supplier_owner.png" alt="Generic placeholder image">';
						result_str +='<div class="media-body"><p><span class="product_desc">Supplier Owner: '+res["supplier_owner"]+'</p></div>';
						result_str +='</div>';*/
						/*result_str +='<div class="media">';
						result_str +='<div class="media-body"><p><span class="product_desc" >Updated:</span> '+res["updated_on"]+'</p></div>';
						result_str +='</div>';*/
						result_str +='</div>';
						result_str +='<div class="col-md-12 p-0">';
						result_str +='<div class="media">';
						result_str +='<img class="mr-2 img-fluid" src="assets/images/Icon_Dimensions.png" alt="Generic placeholder image">';
						var system_description=res['system_description'];
						result_str +='<div class="media-body"><p><span class="product_desc">Description: </span>'+system_description+'</p></div>';
						result_str +='</div>';
						result_str +='<div class="media">';
						result_str +='<img class="mr-2 img-fluid" src="assets/images/Icon_notes.png" alt="Generic placeholder image">';
						var flyer_notes=res['flyer_notes'];
						result_str +='<div class="media-body"><p><span class="product_desc">Flyer Notes: </span> '+flyer_notes+'</p></div>';
						result_str +='</div></div>';
						result_str +='</div></div></div>';
						result_str +='<div class="col-md-2 align-self-end p-0">';
						result_str +='<div class="load_ship">';
						var delivery_cal_para=res['b2b_id']+" , '"+res['txtaddress']+"' , '"+res['txtaddress2']+"' , '"+res['txtcity']+"' , '"+res['txtstate']+"' , '"+res['txtcountry']+"' , '"+res['txtzipcode']+"' , "+res['minfob'];
						result_str +='<h6 id="cal_delh4'+res['b2b_id']+'">'+res["price"]+' </h6><div id="cal_del'+res['b2b_id']+'"><a class="product_link" href="#" onclick="calculate_delivery('+delivery_cal_para+'); return false;">Calculate Delivery</a></div>';
						//result_str +='<h6>'+res["price"]+' </h6><div id="cal_del'+res['b2b_id']+'"><a class="product_link" href="#">Calculate Delivery</a></div>';
						result_str +='</div>';
						result_str +='<div class="load_ship mt-2">';
						result_str +='<a class="product_link available_loads" data-toggle="modal" data-target="#available_loads_modal" loop_id="'+res.loop_id+'">'+res["loads"]+'</a>';
						result_str +='<p>First Load Can Ship In <br> '+res["first_load_can_ship_in"]+'</p>';
						result_str +='</div>';
						result_str +='<a target="_blank" href="https://b2b.usedcardboardboxes.com/?id=' + res['loop_id_encrypt_str'] +'&checkout=1" class="btn btn-cart">Buy Now</a>';
						
						result_str +='</div></div></div>';
					});
					result_str +='</div>';
				};
			}
				$('#result_products').html(result_str);
			},
			complete: function () {
				if(no_data==false){
					/*$('#loader').fadeOut();
					$('.pagination').removeClass('d-none');*/
					$('#txtzipcode').removeAttr("zip_for_sorting");
					products_loaded();
					showPage(Number(active_page_id));
				}else{
					products_loaded();
					$('.pagination').addClass('d-none');
				}
				
			},
		})
	}
	
    show_inventories();
	
	$(".pagination").append(
		$("<li>").addClass("page-item").attr({ id: "previous-page" }).append(
			$("<a>").addClass("page-link").attr({
				href: "javascript:void(0)"}).html('<i class="fa fa-angle-double-left"></i>')
		),
		$("<li>").addClass("page-item").attr({ id: "next-page" }).append(
			$("<a>").addClass("page-link").attr({
				href: "javascript:void(0)"}).html('<i class="fa fa-angle-double-right"></i>')
		)
	);
	
	function showPage(whichPage) {
		if (whichPage < 1 || whichPage > totalPages) return false;
		currentPage = whichPage;           
		$(".pagination li").slice(1, -1).remove();
		getPageList(totalPages, currentPage, paginationSize).forEach( item => {
			$("<li>").addClass("page-item")
					 .addClass(item ? "current-page" : "disabled")
					 .toggleClass("active", item === currentPage).append(
				$("<a>").addClass("page-link").attr({
					href: "javascript:void(0)"}).text(item || "...")
			).insertBefore("#next-page");
		});
		
		$("#previous-page").toggleClass("disabled", currentPage === 1);
		$("#next-page").toggleClass("disabled", currentPage === totalPages);
		return true;
	}
	
	 // Use event delegation, as these items are recreated later    
    $(document).on("click", ".pagination li.current-page:not(.active)", function () {
		$("#active_page_id").val(+$(this).text());
		show_inventories();
		return showPage(+$(this).text());
    });
	
    $("#next-page").on("click", function () {
		var isDisabled=$("#next-page").hasClass("disabled");
		if(!isDisabled){
			$("#active_page_id").val(+$(this).text());
			show_inventories();
			return showPage(currentPage+1);
		}
    });

    $("#previous-page").on("click", function () {
		var isDisabled=$("#previous-page").hasClass("disabled");
		if(!isDisabled){
			$("#active_page_id").val(+$(this).text());
			show_inventories();
			return showPage(currentPage-1);
		}
    });
	
	function getPageList(totalPages, page, maxLength) {
		if (maxLength < 5) throw "maxLength must be at least 5";

		function range(start, end) {
			return Array.from(Array(end - start + 1), (_, i) => i + start); 
		}
		
		var sideWidth = maxLength < 9 ? 1 : 2;
		var leftWidth = (maxLength - sideWidth*2 - 3) >> 1;
		var rightWidth = (maxLength - sideWidth*2 - 2) >> 1;
		if (totalPages <= maxLength) {
			// no breaks in list
			return range(1, totalPages);
		}
		if (page <= maxLength - sideWidth - 1 - rightWidth) {
			// no break on left of page
			return range(1, maxLength - sideWidth - 1)
				.concat(0, range(totalPages - sideWidth + 1, totalPages));
		}
		if (page >= totalPages - sideWidth - 1 - rightWidth) {
			// no break on right of page
			return range(1, sideWidth)
				.concat(0, range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages));
		}
		// Breaks on both sides
		return range(1, sideWidth)
			.concat(0, range(page - leftWidth, page + rightWidth),
					0, range(totalPages - sideWidth + 1, totalPages));
	}
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
			if(remove_val_of=="price"){
				$("input[name='min_price']").val(0);
				$("input[name='max_price']").val(500);
				$("input[name='min_range_slide']").val(0);
				$("input[name='max_range_slide']").val(500);
				$("#apply-price").css('width', '100%');
				$("#clear-price").css('display','none');
			}else if(remove_val_of == "height"){
				$("input[name='min_height']").val(0);
				$("input[name='max_height']").val(99);
				$("#apply-height").css('width', '100%');
				$("#clear-height").css('display','none');
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

		var txtaddress=$("input[name='txtaddress']").val();
		var txtaddress2=$("input[name='txtaddress2']").val();
		var txtcity=$("input[name='txtcity']").val();
		var txtstate=$("input[name='txtstate']").val();
		var txtcountry=$("input[name='txtcountry']").val();
		var txtzipcode=$("input[name='txtzipcode']").val();


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

</script>
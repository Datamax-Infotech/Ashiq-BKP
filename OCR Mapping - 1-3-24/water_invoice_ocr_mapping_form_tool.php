<?
	require ("inc/header_session.php");
	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
?>

<html>
	<head>
		<title>UCBZeroWaste Invoice Entry</title>
	<style>
        body{
            font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif!important;
        }
        .form-control{
            border-radius: 0;
        }
        
        .thead-dark td{
            color:#000;
           background-color: #E5E5E5;
            text-align: center;
        }
        .table th, .table td{
            vertical-align: middle !important;
        }
        .top-box{
            padding:15px;
            box-shadow: 0 1px 5px 0 rgba(0,0,0,0.14);
            font-size: 20px;
        }
        .btn.btn-outline-primary{
            color: rgba(38, 102, 180, 1);
            border: 1px solid rgba(38, 102, 180, 1);
        }
        .btn.btn-primary{
            background:rgba(38, 102, 180, 1);
        }
        .btn.btn-outline-primary:hover{
            color: rgba(38, 102, 180, 1);
            border: 1px solid rgba(38, 102, 180, 1);
            background-color: #FFF;
           
        }
        .btn.btn-primary:hover{
            background:rgba(38, 102, 180, 1);
        }
        .form-group{
            margin-bottom: .5rem !important;
        }
        .highlight_error:focus{
            border:solid 1px red;
        }
	</style>
	

    <link href="css/bootstrap4.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet"/>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    -->
    <script type="text/javascript" src="scripts/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="scripts/pdf-lib.js"></script>
    <script type="text/javascript" src="scripts/utils.js"></script>
    <script type="text/javascript" src="https://unpkg.com/@pdf-lib/fontkit/dist/fontkit.umd.js"></script>
    <script>
        var select_options="<select class='form-control form-control-sm generic_field_dropdown'><option></option></select>";
        $(".generic_field_dropdown_div").html(select_options);
            
        function loaddata(warehouse_id, editflg, compid, loadvendor)
        {
            compid = document.getElementById("company_id").value;
            vendor_id = document.getElementById("vendor_id").value;
            if(vendor_id == "addvendor"){		
                window.open("https://loops.usedcardboardboxes.com/water_vendor_master_new.php?proc=New&compid="+compid,"_blank"); 
                return;
            }
            
            if (loadvendor == 1){
                if (window.XMLHttpRequest)
                {
                xmlhttp1=new XMLHttpRequest();
                }
                else
                {
                xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp1.onreadystatechange=function()
                {
                if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
                {
                    document.getElementById("div_vendor").innerHTML = xmlhttp1.responseText;
                }
                }

                xmlhttp1.open("POST","water_ocr_entry_get_vendor.php?warehouse_id="+warehouse_id+"&company_id="+compid+"&editflg="+editflg,true);			
                xmlhttp1.send();	


            }else{
                vendor_id  = document.getElementById("vendor_id").value;
                if (window.XMLHttpRequest)
                {
                xmlhttp1=new XMLHttpRequest();
                }
                else
                {
                xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp1.onreadystatechange=function()
                {
                if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
                {
                    document.getElementById("water_material1").innerHTML = xmlhttp1.responseText;
                    document.getElementById("water_material2").innerHTML = xmlhttp1.responseText;
                }
                }

                xmlhttp1.open("POST","water_ocr_entry_get_material.php?warehouse_id="+warehouse_id+"&vendor_id="+vendor_id,true);			
                xmlhttp1.send();	
            }
         }

        function onsubmitform(){
            var ocr_file=$('#ocr_file').val();
            if(ocr_file==""){
                alert("Choose OCR File!");
                return false;
            }else{
                return  true;
            }
        } 
    </script>
</head>
<body>
<?
    $ocr_id = $_REQUEST["ocr_id"];
    $inview = "no";
    if (isset($_REQUEST["inview"])){
        $inview = $_REQUEST["inview"];
    }

    $inedit = "no";
    if (isset($_REQUEST["inedit"])){
        $inedit = $_REQUEST["inedit"];
    }


    $accuracy_threshold = 0.80;

    db_water_inbox_email();	
    $vendor_id = ""; $company_id = ""; $warehouse_id = ""; $water_trans_rec_id = ""; $mainstr = ""; $filename = ""; $org_filename = "";
    $ocr_inv_number = ""; $ocr_inv_date = ""; $invoice_date_accuracy = 0; $inv_number_accuracy = 0; $water_invoice_email_ocr_id = "";
    $invoice_amount = ""; $invoice_amount_accuracy = ""; $account_no = "";
    $qry = "Select * from water_invoice_email_ocr where unqid = '" . $ocr_id . "'";
    $row1 = db_query($qry,db_water_inbox_email());
    while ($main_res1 = array_shift($row1)){
        $water_invoice_email_ocr_id = $main_res1["unqid"];
        $vendor_id = $main_res1["vendor_id"];
        $account_no = $main_res1["account_no"];
        $company_id = $main_res1["company_id"];
        $water_trans_rec_id = $main_res1["water_trans_rec_id"];
        $ocr_inv_number = $main_res1["inv_number"];
        $inv_number_accuracy = $main_res1["inv_number_accuracy"];
        $ocr_inv_date = $main_res1["invoice_date"];
        $invoice_date_accuracy = $main_res1["invoice_date_accuracy"];
        $rec_id = $water_trans_rec_id;
        
        $invoice_amount = round($main_res1["invoice_amount"],2);
        $invoice_amount_accuracy = $main_res1["invoice_amount_accuracy"];
        
        $mainstr = $main_res1["ocr_extract_text"];
        $org_filename = str_replace("-", " ", $main_res1["email_attachment"]);
        
        $qry1 = "Select inward_date from tblemail where unqid = '" . $main_res1["emailid"] . "'";
        $row2 = db_query($qry1);
        while ($main_res2 = array_shift($row2)){
            $inward_date = $main_res2["inward_date"];
        }

        $filename = Date("Y", strtotime($inward_date)) . "_" . Date("m", strtotime($inward_date)) . "/" . $main_res1["emailid"] . "/" . $main_res1["email_attachment"];
        
    }


    db();	
    $template_name = "";
    $qry = "Select template_name from water_ocr_account_mapping where account_no = '" . $account_no . "'";
    $row1 = db_query($qry);
    while ($main_res1 = array_shift($row1)){
        $template_name = $main_res1["template_name"];
    }

    $qry = "Select id from loop_warehouse where b2bid = '" . $company_id . "'";
    $row1 = db_query($qry);
    while ($main_res1 = array_shift($row1)){
        $warehouse_id = $main_res1["id"];
    }

    $water_entry_done = "no";
    $qry = "Select report_entered from water_transaction where id = '" . $water_trans_rec_id . "'";
    $row1 = db_query($qry);
    while ($main_res1 = array_shift($row1)){
        $water_entry_done = "yes";
    }

    $company_id = 0;
    $warehouse_id = 0;

    $rec_type = "Manufacturer";
    /*$rec_id = 0;
    if ($water_trans_rec_id == 0)
    {	
        $qry_newtrans = "INSERT INTO water_transaction SET company_id = '" . $company_id . "', tran_status = 'Pickup'";
        $res_newtrans = db_query($qry_newtrans);
        
        $rec_id = tep_db_insert_id();
        
        db_water_inbox_email();	
        $qry_newtrans = "Update water_invoice_email_ocr SET water_trans_rec_id = '" . $rec_id . "' where unqid = '" . $water_invoice_email_ocr_id . "'";
        $res_newtrans = db_query($qry_newtrans);

    }else{
        $rec_id = $water_trans_rec_id;
    }
    */
    db();

    if ($inview != "yes") { 

        if ($inedit == "yes") { 

            $dt_view_tran_qry = "SELECT * from water_transaction WHERE id = " . $rec_id;
            $dt_view_tran = db_query($dt_view_tran_qry,db() );
            $dt_view_tran_row = array_shift($dt_view_tran);

            $saved_ocr_val_flg = $dt_view_tran_row["saved_ocr_val_flg"];

            $chkdoubt_val = "";
            if ($dt_view_tran_row["have_doubt"] == 1){
                $chkdoubt_val = " checked ";
            }
            $doubt = $dt_view_tran_row["doubt"];
            //$company_id = $dt_view_tran_row["company_id"];
            $vendor_id = $dt_view_tran_row["vendor_id"];
            $report_date = $dt_view_tran_row["report_date"];
            $service_begin_date = $dt_view_tran_row["service_begin_date"];
            $invoice_due_date = $dt_view_tran_row["invoice_due_date"];
            $invoice_date = $dt_view_tran_row["invoice_date"];
            $new_invoice_date = $dt_view_tran_row["new_invoice_date"];
            $ocr_inv_number = $dt_view_tran_row["invoice_number"];
            $vendors_net_cost_or_revenue = $dt_view_tran_row["vendors_net_cost_or_revenue"];
            $client_net_savings = $dt_view_tran_row["client_net_savings"];
            $ucb_savings_split = $dt_view_tran_row["ucb_savings_split"];
            $total_due_to_ucb = $dt_view_tran_row["total_due_to_ucb"];
            
            $company_name = ""; $warehouse_id = 0;
            $q1 = "SELECT nickname, loopid FROM companyInfo where ID = '". $company_id  . "'";
            $query = db_query($q1, db_b2b());
            while($fetch = array_shift($query))
            {
                $company_name = $fetch['nickname'];
                $warehouse_id = $fetch['loopid'];
            }

            $vender_nm = "";
            $q1 = "SELECT * FROM water_vendors where active_flg = 1 and id = '". $dt_view_tran_row["vendor_id"] . "'";
            $query = db_query($q1, db());
            while($fetch = array_shift($query))
            {
                $vender_nm = $fetch['Name'];
            }	
        
        }
    ?>

    <div class="top-box d-flex justify-content-between" role="alert">
        <span>
            <button type="button" class="close float-left" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            &nbsp;&nbsp;<span><b>Water Mapping Tool</b></span>
        </span>
        <span>
            <a href="water_invoice_ocr_mapping_templates.php" class="btn btn-outline-primary btn-sm">View All Templates</a>
        </span>
    </div>
    <div id="account_no_not_found_container_for_html" class="d-none">
        <div class="row form-group">
            <div class="offset-md-4 col-sm-4 generic_field_dropdown_div"> 
            <select class='form-control form-control-sm generic_field_dropdown' name="acc_no_not_found_mapped_field[]">
                <option></option>
            </select>
            </div> 
            <div class="col-sm-4 d-flex">
                <input type="text" name="acc_no_not_found_field_value[]"  class="form-control form-control-sm generic_field_input"> 
                <button type="button" class="btn btn-danger btn-sm mx-1 remove_added_more_field"><i class="fa fa-times"></i></button>
            </div>
        </div>
    </div>
    <? if(isset($_GET['action']) && $_GET['action']=='edit'){
        $template_id=$_GET['id'];
        $account_query = db_query("SELECT * FROM water_ocr_account_mapping where unqid=$template_id",db());
        $account_data = array_shift($account_query);
        $company_id=$account_data['company_id'];  
        $vendor_id=$account_data['vendor_id'];    
        ?>
        <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-6">
                <form name="frmsort" method="post" action="#" encType="multipart/form-data" id="mapping_tool_main_form">
                   <div class="form-group row mt-2">
                        <label class="col-sm-3 col-form-label">Select Company</label>
                        <div class="col-sm-9">
                            <select id="company_id" name="company_id" onchange="loaddata(<? echo $warehouse_id; ?>,0, <?=$company_id?>, 1)" class="form-control form-control-sm" required>
                                <option value=""></option>
                                <?	
                                    $query = db_query( "SELECT ID, nickname FROM companyInfo where active = 1 and ucbzw_account_status = 83 order by nickname", db_b2b() );
                                    while ($rowsel_getdata = array_shift($query)) {
                                        $tmp_str = "";
                                        if (isset($_REQUEST["company_id"])) {
                                            if ($_REQUEST["company_id"] == $rowsel_getdata["ID"]) {
                                                $tmp_str = " selected ";
                                            }
                                        }else{
                                            if ($account_data['company_id'] == $rowsel_getdata["ID"]) {
                                                $tmp_str = " selected ";
                                            }
                                        }
                                    ?>
                                        <option value="<? echo $rowsel_getdata["ID"];?>" <? echo $tmp_str;?> ><? echo $rowsel_getdata['nickname'];?></option>
                                    <?}
                                ?>
                            </select>
                        </div>
                    </div>
                    <div id="div_vendor" class="full_input__box form-group row mt-2">
                    <script>
                            if (window.XMLHttpRequest)
                            {
                            xmlhttp1=new XMLHttpRequest();
                            }
                            else
                            {
                            xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp1.onreadystatechange=function()
                            {
                            if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
                            {
                                document.getElementById("div_vendor").innerHTML = xmlhttp1.responseText;
                                $("#vendor_id").val('<?= $vendor_id ?>');
                            }
                            }

                            xmlhttp1.open("POST","water_ocr_entry_get_vendor.php?warehouse_id="+'<?=$warehouse_id; ?>'+"&company_id="+'<?= $company_id;?>'+"&editflg=0",true);			
                            xmlhttp1.send();	
                    </script>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-sm-3 col-form-label">Template</label>
                        <div class="col-sm-9">
                            <input type="text" value="<?= $account_data['template_name'];?>" name="template_name" class="form-control form-control-sm" id="template_name" onblur="check_template_name()" required/>
                            <p id="template_name_error" class="text-danger mb-0 d-none highlight_error">Template name already exists, use other! </p>
                        </div> 
                    </div>
                    <?
                    $inv_file_for_ocr = "https://loops.usedcardboardboxes.com/AZFormR/formrecognizer/ai-form-recognizer/upload/" . $account_data['ocr_file_name'];
                    $mainstr = shell_exec("/usr/bin/node analyzeDocumentByModelIducbdata.js '". escapeshellarg($inv_file_for_ocr) ."'");
        
                    $ocr_file=$account_data['ocr_file_name'];
                
                    $mapping_field_sql = db_query("SELECT *, gf.id as uniq, fv.field_name as maaped_field_name, fv.id as fv_id FROM water_ocr_mapping_field_and_values as fv JOIN water_ocr_account_mapping_generic_fields gf ON gf.id = fv.mapped_with where fv.template_id = $template_id", db());
                    $field_count=1;
                    while($fields_arr = array_shift($mapping_field_sql)){
                        $field_name_unq = strtolower(str_replace(' ', '_', $fields_arr['field_name']));
                        ?>
                        <div id="mapping_field_div_".$field_count>
                        <div class="form-group row mt-2">
                            <label class="col-sm-4 col-form-label <?= $field_count == 1 ? "d-flex justify-content-between" : "" ;?>">
                                <? echo $fields_arr['field_name'];
                                $checked_str = $account_data['account_no'] == 0 ? " checked " : "";

                                if($field_count == 1) 
                                    echo "<small class='d-flex align-items-center '><input type='checkbox' id='checkbox_account_no' $checked_str />&nbsp;Acc No not found</small>"; 
                                
                                ?>
                            </label>
                            <div class="col-sm-4 generic_field_dropdown_div"> 
                                <select edit_val="<?= $fields_arr['maaped_field_name']; ?>" <?= $field_count==1 && $account_data['account_no'] == 0 ? " readonly " : "" ?>  class='form-control form-control-sm generic_field_dropdown' name="mapped_field[]" required >
                                    <option></option>
                                </select>
                            </div> 
                            <div class="col-sm-4">
                                <input type="hidden" name="mapped_with[]"  value="<?= $fields_arr['uniq']; ?>">
                                <input type="hidden" name="fv_id[]"  value="<?= $fields_arr['fv_id']; ?>"> 
                                <input type="text" <?= $field_count==1 && $account_data['account_no'] == 0 ? " readonly " : "" ?> value="<?= $fields_arr['field_value']; ?>" name="field_value[]" id="<?= $field_name_unq; ?>" class="form-control form-control-sm generic_field_input <?=$fields_arr['field_type'] ? "date" : ""; ?>" required> 
                            </div> 
                        </div>
                        <? if($field_count==1){ ?>
                        <div id="accout_no_not_found_div" class="<?=$account_data['account_no'] == 0 ? "" : "d-none"; ?> border py-2">
                            <div id="account_no_not_found_container">
                            <? 
                                if($account_data['account_no'] == 0) {
                                    $select_no_fields = db_query("SELECT * FROM water_ocr_account_mapping_account_not_found where template_id = $template_id", db());
                                    while($no_account_data = array_shift($select_no_fields)){ 
                                ?> 
                                    <div class="row form-group">
                                        <div class="offset-md-4 col-sm-4 generic_field_dropdown_div"> 
                                        <select edit_val="<?= $no_account_data['field_name']; ?>" class='form-control form-control-sm generic_field_dropdown' name="acc_no_not_found_mapped_field[]">
                                            <option></option>
                                        </select>
                                        </div> 
                                        <div class="col-sm-4 d-flex">
                                            <input type="text" value="<?= $no_account_data['field_value']; ?>" name="acc_no_not_found_field_value[]"  class="form-control form-control-sm generic_field_input"> 
                                            <input type="hidden" name="no_acc_fv_id[]"  value="<?= $no_account_data['id']; ?>"> 
                                            <button type="button" class="btn btn-danger btn-sm mx-1 remove_added_more_field" field_value_id="<?= $no_account_data['id']; ?>"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                <? } ?>
                            <? } ?>
                            </div>
                            <div class="text-right"><button type="button" id="add_more_field_for_unqiue_account_no" class="btn-sm btn btn-success">Add Field to Create Account No</button></div>
                        
                        </div>
                        <? 
                        }
                        ?>

                    </div>
                    <? $field_count++; } ?>
                
                   <div class="col-md-12 p-0 table-responsive mt-3">
                        <table class="table table-sm table-bordered">
                            <thead >
                                <tr>
                                    <td colspan="9">
                                        <input type="checkbox">
                                        Consider this table for Water mapping
                                    </td>
                                </tr>
                                <tr class="thead-dark">
                                <td>Material</td>
                                <td>Fee</td>
                                <td>Ignore Line Item</td>
                                <!--<td>Description
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Option 1</option>
                                        <option>Option 2</option>
                                        <option>Option 3</option>
                                    </select>
                                </td>-->
                                <td><span data-toggle="tooltip" data-placement="top" title="Enter number of character length to consider for OCR (Part text take from Water material name)">Selected Text Length </span></td>
                                <td>Invoice #
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td>Terms<select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td>Due Date
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td>Customer #
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td><button class="btn btn-primary btn-sm">Save</button></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> 
                                        <div class="d-flex" id="water_material1">
                                            <select class="form-control form-control-sm mr-1">
                                                <option value="0"></option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                    <div class="d-flex">
                                        <select class="form-control form-control-sm mr-1">
                                            <option value=""></option>
                                            <?	
                                                $query = db_query("SELECT * FROM water_additional_fees  where active_flg = 1 order by display_order", db() );
                                                while ($rowsel_getdata = array_shift($query)) {
                                                ?>
                                                    <option value="<? echo $rowsel_getdata["id"];?>" ><? echo $rowsel_getdata['additional_fees_display'];?></option>
                                                <?}
                                            ?>
                                        </select>
                                    </div>
                                    </td>
                                <!-- <td>
                                        <input type="text" class="form-control form-control-sm" value="Overage Service Yards - Recycle Materials Incident# 46739914"/>
                                    </td>-->
                                    <td>
                                        <input type="checkbox">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="7654014" onfocus="select_box_change(this.value, 0.4572, 5.6968,2.3345,0.2551, -0.15)" >
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="1st of Following Month" onfocus="select_box_change(this.value, 1.608, 5.6968,4,0.2551, -0.15)">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="11/1/2023" onfocus="select_box_change(this.value, 3.5621, 5.6968,2.3345,0.2551, -0.15)">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="7540337530" onfocus="select_box_change(this.value, 4.6975, 5.6968,2.3345,0.2551, -0.15)">
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td colspan="9" align="right">
                                        <button class="btn btn-primary btn-sm">Add Row</button>
                                    </td>
                                </tr>
                            </tbody>
                            
                        </table>
                        <hr>
                        <table class="table table-sm table-bordered my-4">
                            <thead >
                                <tr>
                                    <td colspan="8">
                                        <input type="checkbox">
                                        Consider this table for Water mapping
                                    </td>
                                </tr>
                                <tr class="thead-dark">
                                <td>Material</td>
                                <td>Fee</td>
                                <td>Ignore Line Item</td>
                                <td><span data-toggle="tooltip" data-placement="top" title="Enter number of character length to consider for OCR (Part text take from Water material name)">Selected Text Length </span></td>
                                <td>Description
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td>Amount
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td><button class="btn btn-primary btn-sm">Save</button></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> 
                                        <select class="form-control form-control-sm mr-1" id="water_material2">
                                            <option value="0"></option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm mr-1">
                                            <option value=""></option>
                                            <?	
                                                $query = db_query("SELECT * FROM water_additional_fees  where active_flg = 1 order by display_order", db() );
                                                while ($rowsel_getdata = array_shift($query)) {
                                                ?>
                                                    <option value="<? echo $rowsel_getdata["id"];?>" ><? echo $rowsel_getdata['additional_fees_display'];?></option>
                                                <?}
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="checkbox">
                                    </td>
                                    
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value=""/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="Waste Removal Services Provided - Nov 2023" onfocus="select_box_change(this.value, 0.4572, 5.6968,6,0.2551, 0.45)" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="$333.52" onfocus="select_box_change(this.value, 7, 5.6968,2.3345,0.2551, 0.45)" />
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="9" align="right">
                                        <button class="btn btn-primary btn-sm">Add Row</button>
                                    </td>
                                </tr>
                        
                            </tbody>
                        </table>
                        
                        <div class="text-center">
                            <div id="save-ocr-data-loader" class="d-none spinner spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                            </div>
                            <input type="hidden" id="" name="template_id_hidden" value="<?= $template_id; ?>">
                            <input type="hidden" id="form_action" name="form_action" value="update">
                            <button id="save-ocr-data" class="btn btn-sm btn-primary" type="submit">SAVE</button>
                            <!--<button class="btn btn-sm btn-primary">RESET</button> -->
                        </div>
                        <div class="alert d-none mt-2" id="save-msg" data-dismiss="alert">
                            <span id="save-msg-text"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                   <p class="mb-1"><span id="pdf_file_name_pdf_lib"></span><span class="">&nbsp;&nbsp;<a id="pdf_file_href_pdf_lib" href="" class="text-dark fa fa-share-square-o fa-1x"></a></span></p>
                    <div class="embed-responsive embed-responsive-21by9"style="height:100%">
                        <iframe id="pdf_frame" width='100%' height='100%'></iframe>
                    <!--<iframe class="embed-responsive-item" src="https://loops.usedcardboardboxes.com/water_email_inbox_inv_files/2024_01/173/2024-01---Brand-Aromatics---WM--15-13198-32007--3335354-0515-8.pdf"></iframe>-->
                    </div>               
                    <script>
                        load_pdf_first_time();
                        async function load_pdf_first_time(){
                            const url = `upload/<?=$ocr_file; ?>`;  
                            const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer()) 
                            var bytes = new Uint8Array(existingPdfBytes);
                            const pdfDoc = await PDFDocument.load(existingPdfBytes)
                            pdfBytes = await pdfDoc.save()    
                            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
                            const blobUrl = URL.createObjectURL(blob);
                            document.getElementById('pdf_frame').src = blobUrl; 
                        }   
                   </script>
            </div>
        </div>
        </div>
    <? 
    } else{?>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-6">
                <? if(!isset($_FILES['ocr_file'])){ ?>
                <form id="choose-ocr-form" method="POST" action="water_invoice_ocr_mapping_form_tool.php" encType="multipart/form-data" onsubmit="return onsubmitform();">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">OCR File</label>
                        <div class="col-sm-4">
                            <input type="file" id="ocr_file" name="ocr_file" class="form-control-file form-control-sm">
                            <input type="hidden" name="action" value="add"/>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit"  id="choose-ocr" class="btn btn-outline-primary btn-sm">Process OCR</button>
                        </div>
                    </div>
                </form>
                
                <hr>
                <? } ?>
                <form name="frmsort" method="post" action="#" encType="multipart/form-data" id="mapping_tool_main_form">
                    <div class="form-group row mt-2">
                        <label class="col-sm-3 col-form-label">Select Company</label>
                        <div class="col-sm-9">
                            <select id="company_id" name="company_id" onchange="loaddata(<? echo $warehouse_id; ?>,0, <?=$company_id?>, 1)" class="form-control form-control-sm" required>
                                <option value=""></option>
                                <?	
                                    $query = db_query( "SELECT ID, nickname FROM companyInfo where active = 1 and ucbzw_account_status = 83 order by nickname", db_b2b() );
                                    while ($rowsel_getdata = array_shift($query)) {
                                        $tmp_str = "";
                                        if (isset($_REQUEST["company_id"])) {
                                            if ($_REQUEST["company_id"] == $rowsel_getdata["ID"]) {
                                                $tmp_str = " selected ";
                                            }
                                        }else{
                                            if ($company_id == $rowsel_getdata["ID"]) {
                                                $tmp_str = " selected ";
                                            }
                                        }
                                    ?>
                                        <option value="<? echo $rowsel_getdata["ID"];?>" <? echo $tmp_str;?> ><? echo $rowsel_getdata['nickname'];?></option>
                                    <?}
                                ?>
                            </select>
                        </div>
                    </div>
                    <div id="div_vendor" class="full_input__box form-group row mt-2">
                    <label class="col-sm-3 col-form-label"><span class="details">Select vendor&nbsp;&nbsp;<img src="images/refreshimg.png" width="10px" height="10px" onclick="reload_page()"/></span></label>
                        <div class="col-sm-9">
                            <select id="vendor_id" name="vendor_id" onchange="loaddata(<? echo $warehouse_id; ?>,0, <?=$company_id?>, 0)" class="form-control form-control-sm" required>
                                <option value=""></option>
                                <?	
                                    $vendor_ids = "";
                                    $query = db_query("SELECT water_inventory.vendor FROM water_boxes_to_warehouse INNER JOIN water_inventory ON water_boxes_to_warehouse.water_boxes_id = water_inventory.id
                                    WHERE water_boxes_to_warehouse.water_warehouse_id = " . $warehouse_id . " group by water_inventory.vendor", db() );
                                    while ($rowsel_getdata = array_shift($query)) {
                                        $vendor_ids = $vendor_ids . $rowsel_getdata["vendor"] . ",";
                                    }
                                    if ($vendor_ids != ""){
                                        $vendor_ids = substr($vendor_ids, 0, strlen($vendor_ids)-1);
                                    }

                                    $query = db_query( "SELECT * FROM water_vendors where active_flg = 1 and id in ($vendor_ids) order by Name", db() );
                                    while ($rowsel_getdata = array_shift($query)) {
                                        $tmp_str = "";
                                        if ($vendor_id == $rowsel_getdata["id"]) {
                                            $tmp_str = " selected ";
                                        }

                                        $main_material = $rowsel_getdata['description'];

                                        //$vender_nm = $rowsel_getdata['Name']. " - ". $rowsel_getdata['city']. ", ". $rowsel_getdata['state']. " ". $rowsel_getdata['zipcode'];
                                        $vender_nm = $rowsel_getdata['Name']. " - ". $main_material;
                                    ?>
                                        <option value="<? echo $rowsel_getdata["id"];?>" <? echo $tmp_str;?> ><? echo $vender_nm;?></option>
                                    <?}
                                ?>
                                <option value="addvendor">Add Vendor</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label class="col-sm-3 col-form-label">Template</label>
                        <div class="col-sm-9">
                            <input type="text" name="template_name" class="form-control form-control-sm" id="template_name" onblur="check_template_name()" required/>
                            <p id="template_name_error" class="text-danger mb-0 d-none highlight_error">Template name already exists, use other! </p>
                        </div> 
                    </div>
                    <?
                    if(isset($_FILES['ocr_file']) && $_FILES['ocr_file']!=""){
                    if(!empty( $_FILES['ocr_file']['error']))
                    {
                        echo "<h2 class='text text-danger'>Try Again</h2>";
                    }else{
                        $tmpName = $_FILES['ocr_file']['tmp_name'];
                        if(!empty( $tmpName ) && is_uploaded_file( $tmpName ) ){				
                                $filename = str_replace(' ',"",$_FILES['ocr_file']['name']);
                                $filename = str_replace('(',"",$filename);
                                $filename = str_replace(')',"",$filename);
                                $ocr_file = str_replace('#',"",$filename);
                                
                                $target_file = "upload/". $ocr_file;
                                //echo $target_file;
                                if (move_uploaded_file($tmpName, $target_file)) {
                                    echo "The file ". htmlspecialchars( basename( $_FILES["ocr_file"]["name"])). " has been uploaded.";
                                    
                                    $inv_file_for_ocr = "https://loops.usedcardboardboxes.com/AZFormR/formrecognizer/ai-form-recognizer/upload/" . $ocr_file;
                                    
                                    $mainstr = shell_exec("/usr/bin/node analyzeDocumentByModelIducbdata.js '". escapeshellarg($inv_file_for_ocr) ."'");
                                    
                                } else {
                                    echo "<p style='color:red;'>Sorry, there was an error uploading your file.</p>";
                                }
                        }
                    }
                    } 
                    $mapping_field_sql = db_query("SELECT * FROM water_ocr_account_mapping_generic_fields ORDER BY id ASC");
                    $field_count=1;
                    while($fields_arr = array_shift($mapping_field_sql)){
                        $field_name_unq = strtolower(str_replace(' ', '_', $fields_arr['field_name']));
                        ?>
                        <div id="mapping_field_div_".$field_count>
                        <div class="form-group row mt-2">
                            <label class="col-sm-4 col-form-label <?= $field_count == 1 ? "d-flex justify-content-between" : "" ;?>">
                                <?= $fields_arr['field_name'] ?>
                                <? if($field_count == 1) echo '<small class="d-flex align-items-center "><input type="checkbox" id="checkbox_account_no"/>&nbsp;Acc No not found</small>'; ?>
                            </label>
                            <div class="col-sm-4 generic_field_dropdown_div"> 
                                <select class='form-control form-control-sm generic_field_dropdown' name="mapped_field[]" required>
                                    <option></option>
                                </select>
                            </div> 
                            <div class="col-sm-4">
                                <input type="hidden" name="mapped_with[]"  value="<?= $fields_arr['id']; ?>"> 
                                <input type="text" name="field_value[]" id="<?= $field_name_unq; ?>" class="form-control form-control-sm generic_field_input <?=$fields_arr['field_type'] ? "date" : ""; ?>" required> 
                            </div> 
                        </div>
                        <? if($field_count==1){ ?>
                        <div id="accout_no_not_found_div" class="d-none border py-2">
                            <div id="account_no_not_found_container">  
                                <div class="row form-group">
                                    <div class="offset-md-4 col-sm-4 generic_field_dropdown_div"> 
                                    <select class='form-control form-control-sm generic_field_dropdown' name="acc_no_not_found_mapped_field[]">
                                        <option></option>
                                    </select>
                                    </div> 
                                    <div class="col-sm-4 d-flex">
                                        <input type="text" name="acc_no_not_found_field_value[]"  class="form-control form-control-sm generic_field_input"> 
                                        <button type="button" class="btn btn-danger btn-sm mx-1 remove_added_more_field"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right"><button type="button" id="add_more_field_for_unqiue_account_no" class="btn-sm btn btn-success">Add Field to Create Account No</button></div>
                        
                        </div>
                        <? } ?>
                    </div>
                    <? $field_count++; } ?>
                    
                
                    <? if(isset($_FILES['ocr_file']) && $_FILES['ocr_file']!=""){ ?>
                    <div class="col-md-12 p-0 table-responsive mt-3">
                        <table class="table table-sm table-bordered">
                            <thead >
                                <tr>
                                    <td colspan="9">
                                        <input type="checkbox">
                                        Consider this table for Water mapping
                                    </td>
                                </tr>
                                <tr class="thead-dark">
                                <td>Material</td>
                                <td>Fee</td>
                                <td>Ignore Line Item</td>
                                <!--<td>Description
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Option 1</option>
                                        <option>Option 2</option>
                                        <option>Option 3</option>
                                    </select>
                                </td>-->
                                <td><span data-toggle="tooltip" data-placement="top" title="Enter number of character length to consider for OCR (Part text take from Water material name)">Selected Text Length </span></td>
                                <td>Invoice #
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td>Terms<select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td>Due Date
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td>Customer #
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td><button class="btn btn-primary btn-sm">Save</button></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> 
                                        <div class="d-flex" id="water_material1">
                                            <select class="form-control form-control-sm mr-1">
                                                <option value="0"></option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                    <div class="d-flex">
                                        <select class="form-control form-control-sm mr-1">
                                            <option value=""></option>
                                            <?	
                                                $query = db_query("SELECT * FROM water_additional_fees  where active_flg = 1 order by display_order", db() );
                                                while ($rowsel_getdata = array_shift($query)) {
                                                ?>
                                                    <option value="<? echo $rowsel_getdata["id"];?>" ><? echo $rowsel_getdata['additional_fees_display'];?></option>
                                                <?}
                                            ?>
                                        </select>
                                    </div>
                                    </td>
                                <!-- <td>
                                        <input type="text" class="form-control form-control-sm" value="Overage Service Yards - Recycle Materials Incident# 46739914"/>
                                    </td>-->
                                    <td>
                                        <input type="checkbox">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="7654014" onfocus="select_box_change(this.value, 0.4572, 5.6968,2.3345,0.2551, -0.15)" >
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="1st of Following Month" onfocus="select_box_change(this.value, 1.608, 5.6968,4,0.2551, -0.15)">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="11/1/2023" onfocus="select_box_change(this.value, 3.5621, 5.6968,2.3345,0.2551, -0.15)">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="7540337530" onfocus="select_box_change(this.value, 4.6975, 5.6968,2.3345,0.2551, -0.15)">
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td colspan="9" align="right">
                                        <button class="btn btn-primary btn-sm">Add Row</button>
                                    </td>
                                </tr>
                            </tbody>
                            
                        </table>
                        <hr>
                        <table class="table table-sm table-bordered my-4">
                            <thead >
                                <tr>
                                    <td colspan="8">
                                        <input type="checkbox">
                                        Consider this table for Water mapping
                                    </td>
                                </tr>
                                <tr class="thead-dark">
                                <td>Material</td>
                                <td>Fee</td>
                                <td>Ignore Line Item</td>
                                <td><span data-toggle="tooltip" data-placement="top" title="Enter number of character length to consider for OCR (Part text take from Water material name)">Selected Text Length </span></td>
                                <td>Description
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td>Amount
                                    <select class="form-control form-control-sm mt-1">
                                        <option></option>
                                        <option>Material/Fee column</option>
                                        <option>Quantity</option>
                                        <option>Unit Price</option>
                                        <option>Amount</option>
                                    </select>
                                </td>
                                <td><button class="btn btn-primary btn-sm">Save</button></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> 
                                        <select class="form-control form-control-sm mr-1" id="water_material2">
                                            <option value="0"></option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm mr-1">
                                            <option value=""></option>
                                            <?	
                                                $query = db_query("SELECT * FROM water_additional_fees  where active_flg = 1 order by display_order", db() );
                                                while ($rowsel_getdata = array_shift($query)) {
                                                ?>
                                                    <option value="<? echo $rowsel_getdata["id"];?>" ><? echo $rowsel_getdata['additional_fees_display'];?></option>
                                                <?}
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="checkbox">
                                    </td>
                                    
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value=""/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="Waste Removal Services Provided - Nov 2023" onfocus="select_box_change(this.value, 0.4572, 5.6968,6,0.2551, 0.45)" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" value="$333.52" onfocus="select_box_change(this.value, 7, 5.6968,2.3345,0.2551, 0.45)" />
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="9" align="right">
                                        <button class="btn btn-primary btn-sm">Add Row</button>
                                    </td>
                                </tr>
                        
                            </tbody>
                        </table>
                        
                        <div class="text-center">
                            <div id="save-ocr-data-loader" class="d-none spinner spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                            </div>
                            <input type="hidden" id="form_action" name="form_action" value="add">
                            <input type="hidden" name="ocr_file_name" id="uploaded_ocr_file_name" value="<?= $ocr_file; ?>">
                            <button id="save-ocr-data" class="btn btn-sm btn-primary" type="submit">SAVE</button>
                            <!--<button class="btn btn-sm btn-primary">RESET</button> -->
                        </div>
                        <div class="alert d-none mt-2" id="save-msg" data-dismiss="alert">
                            <span id="save-msg-text"></span>
                        </div>
                    </div>
                    <? } ?>
                </form>
            </div>
            <div class="col-md-6">
                <? if(isset($_FILES['ocr_file']) && $_FILES['ocr_file']!="") { ?>
                    <p class="mb-1"><span id="pdf_file_name_pdf_lib"></span><span class="">&nbsp;&nbsp;<a id="pdf_file_href_pdf_lib" href="" class="text-dark fa fa-share-square-o fa-1x"></a></span></p>
                    <div class="embed-responsive embed-responsive-21by9"style="height:100%">
                        <iframe id="pdf_frame" width='100%' height='100%'></iframe>
                    <!--<iframe class="embed-responsive-item" src="https://loops.usedcardboardboxes.com/water_email_inbox_inv_files/2024_01/173/2024-01---Brand-Aromatics---WM--15-13198-32007--3335354-0515-8.pdf"></iframe>-->
                    </div>    
                   <script>
                        load_pdf_first_time();
                        async function load_pdf_first_time(){
                            const url = `<?=$ocr_file; ?>`;  
                            const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer()) 
                            var bytes = new Uint8Array(existingPdfBytes);
                            const pdfDoc = await PDFDocument.load(existingPdfBytes)
                            pdfBytes = await pdfDoc.save()    
                            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
                            const blobUrl = URL.createObjectURL(blob);
                            document.getElementById('pdf_frame').src = blobUrl; 
                        }   
                   </script>
                             
                <? } ?>
            </div>
        </div>
    </div>
    <? 
    } 
    } ?>
        <script>
            var data=`<?= $mainstr; ?>`;
            var primary_array = data.replace(/\s+/g, ' ').replace().split("|Delimiter|")[0].split("|Keypair|<br> ");
            var select_options="<option></option>";
            for(var count=0; count < primary_array.length ; count++){
            var item_array=primary_array[count].split('<br> ');
            var option_name,option_val,cor_x, cor_y,w,h;
                var first_option=item_array[0].split("|");
                var option_data=first_option[0].split("^");
                var option_name=option_data[0];
                var option_val=option_data[1];
                if(first_option[1]){
                    var position_data_array=first_option[1].split(" ");
                    if (position_data_array[1]){
                        var cor_x = position_data_array[1].split('^')[0];
                    }
                    if (position_data_array[2]){
                        var cor_x2 = position_data_array[2].split('^')[0];
                    }
                    if (position_data_array[3]){
                        var cor_y = position_data_array[3].split('^')[1];
                        cor_y = cor_y.replace("x", "");
                    }
                    if (position_data_array[1]){
                        var cor_y2 = position_data_array[1].split('^')[1];
                        cor_y2 = cor_y2.replace("x", "");
                    }                
                    var w = (cor_x2 - cor_x).toFixed(4);
                    var h = (cor_y - cor_y2).toFixed(4);
                    //console.log(`${option_name} : ${cor_x} ${cor_y}  ${w}  ${h}`);
                    select_options+= `<option value="${option_name}" cor_x="${cor_x}" cor_y="${cor_y}", w="${w}" h="${h}" option_val="${option_val}">${option_name}</option>`;
                }
            }
            select_options+= "";
            $(".generic_field_dropdown").html(select_options);
            var select_default_fields= $(".generic_field_dropdown");
            $.each(select_default_fields,function(index,data){
                $(this).val($(this).attr('edit_val'));
            })

            $("#pdf_file_name_pdf_lib").html('<?= $ocr_file; ?>');
            $("#pdf_file_href_pdf_lib").attr('href','upload/<?= $ocr_file; ?>');

            $('body').on("change",".generic_field_dropdown",function(){
                var selected_op=$(this).val();
                //console.log("selected_val "+selected_val);
                var option_val = $('option:selected', this).attr('option_val');
                console.log(option_val);
                var cor_x = $('option:selected', this).attr('cor_x');
                var cor_y = $('option:selected', this).attr('cor_y');
                var w = $('option:selected', this).attr('w');
                var h = $('option:selected', this).attr('h');
                $(this).parents(".form-group").find('.generic_field_input ').val(option_val);
                $(this).parents(".form-group").find('.generic_field_input ').attr({'selected_op':selected_op,'option_val':option_val, 'cor_x':cor_x, 'cor_y':cor_y,'w':w,h});
                modifyPdf(cor_x, cor_y, w, h);
            });

            $('body').on("focus",".generic_field_input",function(){
                var cor_x = $(this).attr('cor_x');
                if (typeof cor_x == 'undefined' || cor_x == false || cor_x=="") {
                    alert("Choose the value from the dropdown!");
                    $(this).blur();
                    $(this).parents(".form-group").find('.generic_field_dropdown').focus();
                }else{
                    var cor_y = $(this).attr('cor_y');
                    var w = $(this).attr('w');
                    var h = $(this).attr('h');
                    var option_val = $(this).attr('option_val');
                    var selected_op = $(this).attr('selected_op');
                    modifyPdf(cor_x, cor_y, w, h);
                }
            });
            const { drawRectangle,grayscale,PDFDocument,rgb,colorRgb} = PDFLib
                    
                    const renderInIframe = (pdfBytes) => {
                        const blob = new Blob([pdfBytes], { type: 'application/pdf' });
                        const blobUrl = URL.createObjectURL(blob);
                        //document.getElementById('iframe').src = blobUrl;
                        document.getElementById('pdf_frame').src = blobUrl; 
                    };
                    async function modifyPdf(cor_x="", cor_y="", w="", h=""){
                        const url = `upload/<?=$ocr_file; ?>`;        
                        const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer())
                            
                        var bytes = new Uint8Array(existingPdfBytes);
                        const pdfDoc = await PDFDocument.load(existingPdfBytes)
                        const pages = pdfDoc.getPages();
                        var firstPage = pages[0];
                        var pgheight = firstPage.getHeight();
                        pgheight = pgheight - 2;
                        if(cor_x!=""){
                        const { pg_width, pg_height } = firstPage.getSize();
                        var x= cor_x * 72;
                        var y = pgheight - (cor_y * 72);
                        var width = w*72;
                        var height = h*72;

                        try{
                            firstPage.drawRectangle({
                                x,
                                y,
                                width,
                                height,
                                borderWidth: 1,
                                borderColor: rgb(0, 1, 0), //green
                                opacity: 1,
                            }); 
                        }catch(err){
                            console.log(err.message);
                        }
                        }
                        pdfBytes = await pdfDoc.save()    
                        renderInIframe(pdfBytes);
                    }

                        function check_template_name(){
                            var res;
                            $.ajax({
                                url: 'water_invoice_ocr_mapping_form_save.php',
                                data: {template_name:$("#template_name").val(),check_for_template:1,'template_form_action':$('#form_action').val(), 'template_id':'<?= $template_id ?>'},
                                type: 'POST',
                                async: false,
                                success: function(data){ 
                                    console.log("data "+data);
                                    if(data == 1){
                                        $('#template_name_error').removeClass('d-none');
                                    res = false;
                                    }else{
                                        $('#template_name_error').addClass('d-none');
                                        res = true;
                                    }
                                },
                            });
                            console.log(res);
                            return res;
                        }

            $(document).ready(function() {
                $("#mapping_tool_main_form").submit(function(){
                    var temp_name_val = check_template_name();
                    if(temp_name_val == true){
                        var fd = new FormData(this);   
                        fd.append('account_no', $("#account_number").val()); 
                        if($('#checkbox_account_no').prop('checked')){
                            fd.append('generate_unique_account_no',1);
                        }else{
                            fd.append('generate_unique_account_no',0);
                        }
                        $.ajax({
                            url: 'water_invoice_ocr_mapping_form_save.php',
                            data: fd,
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            async: false,
                            beforeSend: function () {
                                $("#save-ocr-data-loader").removeClass('d-none');
                                $('#save-ocr-data').attr('disabled',true);
                            },
                            success: function(data){
                                if(data==1){
                                    $("#save-msg").addClass('alert-primary').removeClass('d-none');
                                    $("#save-msg #save-msg-text").html("Mapping data saved successfully!");
                                    var msg=$('#form_action').val()=='update' ? "Template updated Successfully" : "Template Added Successfully";
                                    alert(msg)
                                    window.location.href="water_invoice_ocr_mapping_templates.php";
                                }else{
                                    $("#save-msg").addClass('alert-danger').removeClass('d-none');
                                    $("#save-msg #save-msg-text").html("Something went wrong, try again later!")
                                }
                            },
                            complete:function () {
                                $("#save-ocr-data-loader").addClass('d-none');
                                $('#save-ocr-data').attr('disabled',false);
                                
                            },
                        });
                    }else{
                        $('#template_name').focus();
                    }
                    return false;
                });

                $(document).on('change',"#checkbox_account_no",function(){
                    if($('#checkbox_account_no').prop('checked')){
                        $("#accout_no_not_found_div").removeClass("d-none");
                        
                        $("select[name='acc_no_not_found_mapped_field[]']").attr('required','required');
                        $("input[name='acc_no_not_found_field_value[]']").attr('required','required');
                        $("#account_number").removeAttr('required').attr('readonly','true').addClass('disabled');
                        $("#account_number").parents('.form-group').find('.generic_field_dropdown').removeAttr('required').attr('readonly','true').addClass('disabled');
                        $("#account_number").val("");
                        $("#account_number").parents('.form-group').find('.generic_field_dropdown').val("");
                    }else{
                        $("#accout_no_not_found_div").addClass("d-none");
                        $("select[name='acc_no_not_found_mapped_field[]']").removeAttr('required');
                        $("input[name='acc_no_not_found_field_value[]']").removeAttr('required');
                        $("#account_number").attr('required','required').removeAttr('readonly').removeClass('disabled');
                        $("#account_number").parents('.form-group').find('.generic_field_dropdown').attr('required','required').removeAttr('readonly').removeClass('disabled');
                
                    }
                })
       
                var unq_account_no_html= $('#account_no_not_found_container_for_html').html();
               $(document).on('click',"#add_more_field_for_unqiue_account_no",function(){
                    $("#account_no_not_found_container").append(unq_account_no_html);  
                });
                
                $(document).on('click',".remove_added_more_field",function(){
                    if($('#form_action').val()=="update"){
                        var data_id=$(this).attr('field_value_id');
                        alert(data_id)
                        $.ajax({
                            url: 'water_invoice_ocr_mapping_form_save.php',
                            data: {data_id, 'form_action':'remove_value'},
                            type: 'post',
                            async: false,
                            beforeSend: function () {
                                $("#save-ocr-data-loader").removeClass('d-none');
                                $('#save-ocr-data').attr('disabled',true);
                            },
                            success: function(data){
                                if(data==1){
                                    $("#save-msg").addClass('alert-primary').removeClass('d-none');
                                    $("#save-msg #save-msg-text").html("Mapping data saved successfully!");
                                }else{
                                    $("#save-msg").addClass('alert-danger').removeClass('d-none');
                                    $("#save-msg #save-msg-text").html("Something went wrong, try again later!")
                                }
                            },
                            complete:function () {
                                $("#save-ocr-data-loader").addClass('d-none');
                                $('#save-ocr-data').attr('disabled',false);
                            },
                        });
                    }
                    $(this).parents(".form-group").remove();  
                });
            });
            setTimeout(function() { $('#save-msg').addClass('d-none'); }, 5000);
        </script>
</body>
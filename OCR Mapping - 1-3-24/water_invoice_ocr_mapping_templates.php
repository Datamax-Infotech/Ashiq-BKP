<?
	require ("inc/header_session.php");
	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");

?>

<html>
	<head>
		<title>UCBZeroWaste Invoice Entry</title>
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
<style>
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
</style>
</head>
<body>
<div class="top-box d-flex justify-content-between" role="alert">
        <span>
            <button type="button" class="close float-left" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            &nbsp;&nbsp;<span><b>Water Mapping Tool</b></span>
        </span>
        <!--<span>
            <button type="button" class="btn btn-outline-primary btn-sm">Save and Next</button>
            <button type="button" class="btn btn-primary btn-sm">Save and Close</button>
        </span> -->
        <a class="btn btn-outline-primary btn-sm" href="water_invoice_ocr_mapping_form_tool.php">Add New Template</a>
    </div>
    <div class="container-fluid mt-1">
        <div class="col-md-12 p-0 table-responsive mt-3">
           <table class="mt-3 table table-sm table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>S.No.</th>
                        <th>Company</th>
                        <th>Vendor</th>
                        <th>Template Name </th>
                        <th>Account No</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?
                        $account_query = db_query("SELECT * FROM water_ocr_account_mapping ORDER BY company_id",db());
                        if(tep_db_num_rows($account_query) == 0){
                            echo "<tr><td colspan='6'><div class='alert alert-danger mb-0'>No Template Available, Click here to <a href='#'>Add New</a></div></td></tr>";
                        }else{
                            $count=0;
                            while( $row = array_shift($account_query)){ 
                                $company_name_qry = db_query( "SELECT ID, nickname FROM companyInfo where ID = '".$row['company_id']."' ", db_b2b() );
                                $vendor_name_qry = db_query( "SELECT Name,description FROM water_vendors where id = '".$row['vendor_id']."'", db() );
                                $vendor_name = array_shift($vendor_name_qry);   
                                ?>
                                <tr>
                                    <td><?= ++$count; ?></td>
                                    <td><?= array_shift($company_name_qry)['nickname']; ?></td>
                                    <td><?= $vendor_name['Name']." - ".$vendor_name['description'] ?></td>
                                    <td><?= $row['template_name']; ?></td>
                                   
                                    <td>
                                        <?
                                            if($row['account_no'] == 0 || $row['account_no'] == ""){
                                                $select_account= db_query("SELECT * FROM water_ocr_mapping_field_and_values where template_id = '".$row['unqid']."'",db());
                                                $new_account_no=[];
                                                while($account_data = array_shift($select_account)){
                                                    if($account_data['field_value']!=""){
                                                        $new_account_no[] = $account_data['field_value'];
                                                    }
                                                }
                                                echo implode(" - ", $new_account_no);
                                            }else{
                                                echo $row['account_no'];
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="water_invoice_ocr_mapping_form_tool.php?action=edit&&id=<?=$row['unqid'];?>">Edit</a> &nbsp;
                                        <!--<a href="?edit_template=1&&id=<?=$row['unqid'];?>">Delete</a> -->
                                    </td>
                                </tr>
                            <? 
                            } 
                        } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
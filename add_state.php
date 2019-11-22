<?php
//index.php
//$rec_id =$_REQUEST['record'];
//$singlepurchase =$_REQUEST['single_purchase_c'];
$rec_id ='5555555';
//echo"<pre>";print_r($singlepurchase);exit();

$connect = new PDO("mysql:host=suchimsapps-db.ckesvqmivyzg.us-east-1.rds.amazonaws.com;dbname=testecsm", "suchimsapps", "Super1*_9833");
global $db;
//$connect = new PDO("mysql:host=suchimsapps-db.ckesvqmivyzg.us-east-1.rds.amazonaws.com;dbname=testing4", "root", "");
function fill_state_select_box($connect)
{ 
 $output = '';
 //$query = $db->query("SELECT State_Name, country  FROM state_verification_master");
 $query = "SELECT '-- -- -- United States -- -- --' AS State_Name, 'United States' AS country UNION SELECT State_Name, country FROM state_verification_master  WHERE country='United States'  UNION SELECT '-- -- -- -- -- Canada -- -- -- -- --' AS State_Name, 'Canada' AS country UNION SELECT State_Name, country  FROM state_verification_master WHERE country='Canada' ORDER BY country DESC, State_Name ASC";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 //echo"<pre>";print_r($result);exit();
 foreach($result as $row)
 {
  $output .= '<option value="'.$row["State_Name"].'">'.$row["State_Name"].'</option>';
 }
 return $output;
}

function fill_reason_select_box($connect)
{ 
 $output = '';
 $query = "SELECT distinct description FROM r_reason  WHERE deleted =0 ORDER BY description ASC";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output .= '<option value="'.$row["description"].'">'.$row["description"].'</option>';
 }
 return $output;
}
?>
<!DOCTYPE html>
<html>
 <head>
 <style>.btn-info {border-color: #ff8080!important;background-color: #ecf1f4!important;color: #000!important;background-image: url(../../../../index.php?entryPoint=getImage&themeName=Sugar5&imageName=bgBtn.gif)!important;font-weight: normal;background-position: top!important;padding-bottom: 1px!important;padding-right: 6px!important;padding-left: 6px!important;vertical-align: middle;font-size: 12px!important;border-radius: 0px!important;}
	.btn-info:hover{background-color: #ff4d4d!important;border-color: #ff4d4d!important;color: #fff!important;background-image: none;cursor: pointer;font-weight: normal;background-image: none!important;}
	.table{margin-bottom:10px!important;}.container {width: 1095px!important;}
	.btn-sm.add {
    border-color: #ff8080!important;
    background-color: #ecf1f4!important;
    color: #000!important;
    background-image: url(../../../../index.php?entryPoint=getImage&themeName=Sugar5&imageName=bgBtn.gif)!important;
    font-weight: normal;
    background-position: top!important;
    padding-bottom: 1px!important;
    padding-right: 6px!important;
    padding-left: 6px!important;
    vertical-align: middle;
    font-size: 12px!important;
	border-radius: 0px!important;
	margin-left: 3px;
}
    .btn-sm.remove{
	border-color: #ff8080!important;
    background-color: #ecf1f4!important;
    color: #000!important;
    background-image: url(../../../../index.php?entryPoint=getImage&themeName=Sugar5&imageName=bgBtn.gif)!important;
    font-weight: normal;
    background-position: top!important;
    padding-bottom: 1px!important;
    padding-right: 6px!important;
    padding-left: 6px!important;
    vertical-align: middle;
    font-size: 12px!important;
	border-radius: 0px!important;
	}
	 h4.titless {
    color: #333;
    font-weight: bold;
    font-size: 18px;
}
    .table>tbody>tr>th{font-weight:normal;font-size:12px;padding:5px!important;}
	.form-control {
    display: block;
    width: 100%;
    height: 26px!important; 
    padding: 4px 12px!important;
    font-size: 12px!important;
    color: #000!important;
    background-color: #fff;
    background-image: none!important;
    border: 1px solid #ccc;
    border-radius: 0px!important; 
   -webkit-box-shadow: none!important; 
   box-shadow: none!important; 
    -webkit-transition: none!important; 
    -o-transition: none!important;
     transition: none!important; 
	 border-radius: 0px!important;
}.begin_date {
    line-height: 18px !important;
}
.end_date{line-height: 18px !important;}
	 </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  
  <div class="container">
   <!--<h3 align="center">Add Remove Select Box Fields Dynamically using jQuery Ajax in PHP</h3>-->
   <br />
   <h4 class="titless" align="center">Add Multiple States</h4>
   <br />
   <form method="post" id="insert_form">
    <div class="table-repsonsive">
     <span id="error"></span>
     <table class="table table-bordered" id="item_table">
      <tr>
       <th>State Name</th>
       <th>Reason</th>
       <th>Certificate</th>
	   <th>Begin Date</th>
       <th>End Date</th>
	   <th style="width:20%;">Notes</th>
       <th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
      </tr>
     </table>
	 <button type="button" name="add" class="btn btn-success btn-sm add">Add</button>
     <div align="center">
      <input type="submit" name="submit" class="btn btn-info" value="Save" /><br/><br/>
	  
     </div>
    </div>
   </form>
   <br/></br/>
   <span style="color:red;font-size:11px;">State Name, Reason, Certificate, Begin Date, End Date fields are required*</span>
  </div>
 </body>
</html>

<script>
$(document).ready(function(){
 var rowcount;
 rowcount=0;
 $(document).on('click', '.add', function(){
  rowcount=rowcount+ 1;
  var html = '';
  html += '<tr>';
  html += '<td><select name="state_name[]" class="form-control state_name" id="state_name'+ rowcount +'" onchange="getdatas('+rowcount+');"><option value="">Select State</option><?php echo fill_state_select_box($connect); ?></select></td>';
   html += '<td><select name="reason[]" class="form-control reason"><option value="">Select Reason</option><?php echo fill_reason_select_box($connect); ?></select></td>';
  html += '<td><input type="text" name="certificate[]" class="form-control certificate" /></td>';
  html += '<td><input type="date" name="begin_date[]" id="begin_date'+ rowcount +'" onchange="getEndExemptions('+rowcount+')"  data-date-inline-picker="false" data-date-open-on-focus="true" class="form-control begin_date"  /></td>';
  html += '<td><input type="date" name="end_date[]" id="end_date'+ rowcount +'" data-date-inline-picker="false" data-date-open-on-focus="true" class="form-control end_date" /></td>';
  html += '<td><input type="text" name="notes[]" class="form-control item_quantity" /></td>';
  html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
  $('#item_table').append(html);
 });
 
 $(document).on('click', '.remove', function(){
  $(this).closest('tr').remove();
  
 });
 
 $('#insert_form').on('submit', function(event){
  event.preventDefault();
  var state_count=0;
  var error = '';
  $('.state_name').each(function(){
   var count = 1;
   state_count =1;
   if($(this).val() == '')
   {
     error += "<p>Select State Name at "+count+" Row</p>";
	//alert("Select State Name");
    return false;
   }
   count = count + 1;
  });
  
  if(state_count ==0){
	  alert("Atleast add one row");
	  return false;
	  }
  
  $('.reason').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
	   error += "<p>Select Reason at "+count+" Row</p>";
	 // alert("Select Reason Name");
    return false;
   }
   count = count + 1;
  });
  
  $('.begin_date').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
	   //alert("Select Begin Date");
  error += "<p>Select Begin date at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });
   $('.end_date').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
	// alert("Select End Date");
    error += "<p>Select End date at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });
  $('.certificate').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
	// alert("Select Certificate #");
   error += "<p>Select Certificate at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });
  document.getElementById('state_name'+rowcount).options[1].disabled = true;            
  document.getElementById('state_name'+rowcount).options[54].disabled = true;
  
   var record_id = "<?php echo $rec_id; ?>";
  var form_data = $(this).serialize() + "&record_id=" + record_id;
   //alert(form_data);
   if(error == '')
  { 
   $.ajax({
    url:"index.php?entryPoint=insert_state_data",
    method:"POST",
    data:form_data,
    success:function(data)
    {
	    //alert(data);
		//$('#error').html(data);
     if(data == 'ok')
     {
       
      $('#item_table').find("tr:gt(0)").remove();
	 // $('#error').html(data);
      $('#error').html('<div class="alert alert-success">Item Details Saved</div>');
     }
	  else{
		 $('#error').html('<div class="alert alert-danger">'+data+'</div>');
		 } 
    }
   });
  }
   else
  {
   $('#error').html('<div class="alert alert-danger">'+error+'</div>');
  } 
 });
 
});



<!---------get end date -------------->
function getdatas(rowcount)
{
   var state_name = document.getElementById('state_name'+ rowcount).value;
   //var lisss =document.getElementById('state_name'+ rowcount).options[1];
   //alert(lisss);
  // options[1].disabled = true;
   //document.getElementById('state_name'+ rowcount).options[54].disabled = true;
   //var end_dte =document.getElementById('end_date').value;
 // var form_datass = $(this).serialize();
  //alert(form_datass);
    
    $.ajax({
            url : "index.php?entryPoint=end_date_data", 
            method : 'POST',
      dataType: 'JSON',
            data : {'state_name':state_name}, // Some data
            success: function(response) {
                var result = response ;  
                 //alert(result[0]['formula_c']);
				// alert(result[0]['name']);
				//alert(result[0]['type_c']);
                 var end_date = result[0]['formula_c'];
                 var begin_date =result[0]['name'];
                
				   document.getElementById('end_date'+ rowcount).value ="";
                   document.getElementById('begin_date'+ rowcount).value ="";
				
                if(result[0]['type_c'] == 'Date')
                {
			       // alert(end_date);
					var end_dte = end_date.replace(/(..).(..).(....)/, "$3-$1-$2");
					var begin_dte = begin_date.replace(/(..).(..).(....)/, "$3-$1-$2");
				   document.getElementById('end_date'+ rowcount).value= end_dte;
                 document.getElementById('begin_date'+ rowcount).value =begin_dte;
                }
			}

        })
		    //}
		if (document.getElementById('begin_date').value != ""){
			
			setTimeout(function(){ getEndExemption(); }, 500);
			
			
		}

}
<!---------get end date end -------------->
<!---------begin date calculation start -------------->

function getEndExemptions(rowcount)
  {

        var begin_exemption_date = document.getElementById('begin_date'+rowcount).value;
		//alert(begin_exemption_date);
        var customer_state = document.getElementById('state_name'+rowcount).value;

           $.ajax({
                    url : "index.php?entryPoint=end_exemption_date", 
                    method : 'POST',
              dataType: 'JSON',
                    data : {'begin_exemption_date':begin_exemption_date,'customer_state':customer_state}, // Some data
                    success: function(response) {
                        var result = response ;  
                        // alert(result);
                         var date= result.replace("\\", "");
						 var end_dtes = date.replace(/(..).(..).(....)/, "$3-$1-$2");
						 
                        document.getElementById('end_date'+rowcount).value =  end_dtes;
                        // document.getElementById('state_exemption_period_rule_c'+rowcount).value =  customer_state;
                       
                    }

                })

  }
<!---------begin date calculation end -------------->

 
</script>

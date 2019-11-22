<?php
//index.php
$connect = new PDO("mysql:host=suchimsapps-db.ckesvqmivyzg.us-east-1.rds.amazonaws.com;dbname=testecsm", "suchimsapps", "Super1*_9833");
//$connect = new PDO("mysql:host=suchimsapps-db.ckesvqmivyzg.us-east-1.rds.amazonaws.com;dbname=testing4", "root", "");
function fill_state_select_box($connect)
{ 
 $output = '';
 $query = "SELECT State_Name, country  FROM state_verification_master";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output .= '<option value="'.$row["State_Name"].'">'.$row["State_Name"].'</option>';
 }
 return $output;
}

function fill_reason_select_box($connect)
{ 
 $output = '';
 $query = "SELECT description FROM r_reason  WHERE deleted =0 ORDER BY description ASC";
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
  <title>Add Remove Select Box Fields Dynamically using jQuery Ajax in PHP</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <br />
  <div class="container">
   <!--<h3 align="center">Add Remove Select Box Fields Dynamically using jQuery Ajax in PHP</h3>-->
   <br />
   <h4 align="center">Enter Item Details</h4>
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
	   <th>Notes</th>
       <th><button type="button" name="add" class="btn btn-success btn-sm add"><span class="glyphicon glyphicon-plus"></span></button></th>
      </tr>
     </table>
     <div align="center">
      <input type="submit" name="submit" class="btn btn-info" value="Insert" />
     </div>
    </div>
   </form>
  </div>
 </body>
</html>

<script>
$(document).ready(function(){
 
 $(document).on('click', '.add', function(){
  var html = '';
  html += '<tr>';
  html += '<td><select name="state_name[]" class="form-control state_name"><option value="">Select State</option><?php echo fill_state_select_box($connect); ?></select></td>';
   html += '<td><select name="reason[]" class="form-control reason"><option value="">Select Reason</option><?php echo fill_reason_select_box($connect); ?></select></td>';
  html += '<td><input type="text" name="certificate[]" class="form-control certificate" /></td>';
  html += '<td><input type="date" name="begin_date[]" data-date-inline-picker="false" data-date-open-on-focus="true" class="form-control begin_date"  /></td>';
  html += '<td><input type="date" name="end_date[]" data-date-inline-picker="false" data-date-open-on-focus="true" class="form-control end_date" /></td>';
  html += '<td><input type="text" name="notes[]" class="form-control item_quantity" /></td>';
  html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr>';
  $('#item_table').append(html);
 });
 
 $(document).on('click', '.remove', function(){
  $(this).closest('tr').remove();
 });
 
 $('#insert_form').on('submit', function(event){
  event.preventDefault();
  var error = '';
  $('.state_name').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
    error += "<p>Select State Name at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });
  
  $('.reason').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
    error += "<p>Select Reason at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });
  
  $('.begin_date').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
    error += "<p>Select Begin date at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });
   $('.end_date').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
    error += "<p>Select End date at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });
   
  var form_data = $(this).serialize();
  if(error == '')
  {
   $.ajax({
    url:"insert.php",
    method:"POST",
    data:form_data,
    success:function(data)
    {
     if(data == 'ok')
     {
       
      $('#item_table').find("tr:gt(0)").remove();
      $('#error').html('<div class="alert alert-success">Item Details Saved</div>');
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
</script>

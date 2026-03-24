<!DOCTYPE html>
<html lang="en">
<?php   

print_r($name4);


?>


<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<form method="post" action="" id="myform">
  <select name="select" id="year">
   

  <?php
foreach($studyroutes as $value)
{ 
  if($name1== $value->ro_id)
  {


?>
 
 <option selected value="<?php echo $value->ro_id ?>"><?php echo $value->route ?></option>

 <?php
  }
  else{
    ?>
   <option  value="<?php echo $value->ro_id ?>"><?php echo $value->route ?></option>
  
    <?php
  }
} 
?>

</select>


<select name="desig" >
   

  <?php
foreach($studydesig as $value)
{ 
  if($name3== $value->de_id)
  {


?>
 
 <option selected value="<?php echo $value->de_id ?>"><?php echo $value->destination ?></option>

 <?php
  }
  else{
    ?>
   <option  value="<?php echo $value->de_id ?>"><?php echo $value->destination ?></option>
  
    <?php
  }
}
?>

</select>
<select name="name" >
   <?php

 for($i=2012;$i<=2015;$i++)
{
  
if($name4==$i)
{
print_r($name4);



 ?>
   <option  selected value=<?php echo"$i"?>><?php echo"$i"?> </option>
  
  <?php  
}
else{

 ?>
 <option value=<?php echo"$i"?>> <?php echo"$i"?> </option>
 <?php
}
}
?>

</select>
  <button type="submit"  value="Submit">Submit</button>
</form>
</body>
</html>
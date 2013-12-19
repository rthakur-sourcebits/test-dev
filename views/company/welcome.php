<div class="welcome_message">
	<?php  
	if(isset($_SESSION['employee_name']))
	{
		echo 'Welcome '.$_SESSION['employee_name']; 
	}
	else 
	{
		echo "Welcome to Dharma";
	}
	?>
</div>
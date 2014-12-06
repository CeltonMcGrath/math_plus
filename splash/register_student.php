<?php



     
?> 

<h1>Register</h1> 
<form action="register.php" method="post"> 
	<span class="success"><?php error?></span>
	<span class="error">*Required fields</span>
	<br><br />
	
    Email:<br /> 
    <input type="text" name="email" value="<?php echo $emailEntry;?>" /> 
    <span class="error">* <?php echo $emailErr;?></span>
    <br /><br /> 
    Re-enter your email:<br /> 
    <input type="text" name="email2" value="" /> 
    <span class="error">* <?php echo $email2Err;?></span>
    <br /><br /> 
    Password:<br /> 
    <input type="password" name="password" value="" /> 
    <span class="error">* <?php echo $passwordErr;?></span>
    <br /><br /> 
    Re-enter password:<br /> 
    <input type="password" name="password2" value="" /> 
    <span class="error">* <?php echo $password2Err;?></span>
    <br /><br />
    <input type="submit" value="Register" /> 
</form>
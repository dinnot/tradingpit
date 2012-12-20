<?
    if($error) {
        echo $error."<hr />";
    }
    
    echo validation_errors(); 
	
	    
    echo form_open('general/index/login');
    echo "Email: ".form_input('email')."<br />";
    echo "Password: ".form_password('password')."<br />";
    echo form_submit('login', "Login");
    echo form_close();
    
    echo '<hr /><br />';
    
    echo form_open('general/index/register');
    echo "Email: ".form_input('email')."<br />";
    echo "Username: ".form_input('username')."<br />";
    echo "Password: ".form_password('password')."<br />";
    echo "Confirm Password: ".  form_password('confirm_password')."<br />";
    echo form_submit('register', "Register");
    echo form_close();
?>

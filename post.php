<?php
####### db config ##########
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'chat';

####### db config end ##########

if($_POST)
{
	
	$sql_con = mysqli_connect($db_host, $db_username, $db_password,$db_name)or die('could not connect to database');
	

    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    } 
	
	if(isset($_POST["message"]) &&  strlen($_POST["message"])>0)
	{
		$username = filter_var(trim($_POST["username"]));
		$message = filter_var(trim($_POST["message"]));
		$user_ip = $_SERVER['REMOTE_ADDR'];
		
		if(mysqli_query($sql_con,"INSERT INTO chat_messages(user, message, ip_address) value('$username','$message','$user_ip')"))
		{
			$msg_time = date('h:i A M d',time()); 
			echo '<div class="chat_msg"><time>'.$msg_time.'</time><span class="username">'.$username.'</span><span class="message">'.$message.'</span></div>';
		}
		
	}
	elseif($_POST["fetch"]==1)
	{
		$results = mysqli_query($sql_con,"SELECT user, message, date_time FROM (select * from chat_messages ORDER BY id DESC LIMIT 10) chat_messages ORDER BY chat_messages.id ASC");
		while($row = mysqli_fetch_array($results))
		{
			$msg_time = date('h:i A M d',strtotime($row["date_time"])); 
			echo '<div class="chat_msg"><time>'.$msg_time.'</time><span class="username">'.$row["user"].'</span> <span class="message">'.$row["message"].'</span></div>';
		}
	}
	else
	{
		header('HTTP/1.1 500 Are you kiddin me?');
    	exit();
	}
}
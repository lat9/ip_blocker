<?php
require_once('includes/application_top.php');

if (isset($_SESSION['ip_blocker_pass']) && $_SESSION['ip_blocker_pass'] == TRUE) {
  header('Location: ./index.php');
}

$message_show = FALSE;

if (isset($_POST) && isset($_POST['pwd'])) {
  $message_show = TRUE;
  
  $password = zen_db_prepare_input($_POST['pwd']);
  
  if ($password == '') {
    $message = 'Password required !';
  } else {
    $password = md5(md5($password . '_secure_key'));
    
    $ib_password = $db->Execute('SELECT ib_password FROM `' . TABLE_IP_BLOCKER . '` WHERE ib_id=1');
    $ib_password = $ib_password->fields['ib_password'];
    
    if ($ib_password != '' && $password != $ib_password) {
      $message = 'Wrong password!';
      
    } elseif ($ib_password != '' && $password == $ib_password) {
      $_SESSION['ip_blocker_pass'] = true;
      header('Location: ./index.php');
      
    }
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Special Login</title>
<style>
div, input, form, span { margin:0; padding:0; }
body{font-size:14px;font-family:Verdana, Arial, Helvetica, sans-serif;}
.d_w{width:45%;margin:0 auto;margin-top:10%;border:3px solid #0000FF;padding:5px;}
.d_message{width:70%;margin:0 auto;margin-bottom:35px;padding:5px;border:1px solid #ff0000;background-color:#FFECEC;font-weight:bold;}
.d_t{font-size:18px;padding:10px;border-bottom:1px solid #8C8CFF;}
.d_p{text-align:center;margin:35px 0;}
</style>
</head>

<body>
<div class="d_w">
  <div class="d_t">Login to continue ...</div>
  <form action="" name="i_l" method="POST" target="_self">
    <div class="d_p">Password:&nbsp;&nbsp;&nbsp;<input type="password" name="pwd" size="35" value="" />&nbsp;&nbsp;&nbsp;<input type="submit" value="Login" /></div>
<?php
if ($message_show) {
?>
    <div class="d_message"><?php echo $message; ?></div>
<?php
}
?>
  </form>
  <script>document.i_l.pwd.focus()</script>
</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
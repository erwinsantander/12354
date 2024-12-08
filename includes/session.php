<?php
 session_start();

class Session {

 public $msg;
 private $user_is_logged_in = false;

 function __construct(){
   $this->flash_msg();
   $this->userLoginSetup();
 }

  public function isUserLoggedIn(){
    return $this->user_is_logged_in;
  }
  public function login($user_id){
    $_SESSION['user_id'] = $user_id;
  }
  private function userLoginSetup()
  {
    if(isset($_SESSION['user_id']))
    {
      $this->user_is_logged_in = true;
    } else {
      $this->user_is_logged_in = false;
    }

  }
  public function logout(){
    unset($_SESSION['user_id']);
  }

  public function msg($type = '', $msg = ''){
    if(!empty($msg)){
        if(strlen(trim($type)) == 1){
            $type = str_replace( array('d', 'i', 'w','s'), array('error', 'info', 'warning','success'), $type );
        }
        $_SESSION['sweet_alert'] = ['type' => $type, 'message' => $msg];
    } else {
        return $this->msg;
    }
}

private function flash_msg(){
    if(isset($_SESSION['sweet_alert'])) {
        $this->msg = $_SESSION['sweet_alert'];
        unset($_SESSION['sweet_alert']);
    } else {
        $this->msg = null;
    }
}
}

$session = new Session();
$msg = $session->msg();

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

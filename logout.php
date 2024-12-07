<?php
  require_once('includes/load.php');
  if(!$session->logout()) {
      echo '<script type="text/javascript">
              document.addEventListener("DOMContentLoaded", function(event) {
                Swal.fire({
                  title: "Success",
                  text: "Successfully Logout.",
                  icon: "sucess",
                  confirmButtonText: "OK"
                }).then((result) => {
                  if (result.isConfirmed) {
                    window.location.href = "index.php";
                  }
                });
              });
            </script>';
 
  }
?>

<?php
/* condition for incorrect username/password */
if ($user_id) {
    $session->msg("d", "Sorry, Username/Password incorrect.");
    echo '<script>window.onload = function() {
            document.getElementById("popupMessage").innerText = "'. $session->msg_text() .'";
            document.getElementById("popup").style.display = "block";
        }</script>';
} else {
    redirect('index.php', false);
}
?>
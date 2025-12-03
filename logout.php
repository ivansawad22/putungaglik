<?php
session_start();
session_unset();
session_destroy();

// Hapus cookie session biar browser benar-benar lupa
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Redirect dengan cache buster
header("Location: index.php?logout=".time());
exit;
?>
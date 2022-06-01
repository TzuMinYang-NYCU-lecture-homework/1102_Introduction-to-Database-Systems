<?php
session_start();
if($_SESSION['Authenticated']==true)
{
    session_unset();
    session_destroy();
}
header("Location: ..");
?>
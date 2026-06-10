<?php
session_start();
session_destroy();

header("Location: intro_pg.php");
exit;
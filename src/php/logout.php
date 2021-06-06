<?php
/**
 * File: logout.php
 * Logout a user by destroying session
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
session_start();
session_destroy();
header('Location: ../../index.php');
?>

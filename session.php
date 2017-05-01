<?php
/**
 * Created by PhpStorm.
 * User: tommy
 * Date: 29/3/17
 * Time: 11:27 PM
 */
session_start();
require_once 'User.php';
$sesssion = new User();
// if user session is not active(not loggedin) this page will help 'home.php and profile.php' to redirect to login page
// put this file within secured pages that users (users can't access without login)
if (!$sesssion->is_loggedin())
{
    // session no set redirects to login page
    $sesssion->redirect('index.php');
}
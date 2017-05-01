<?php
session_start();
require_once ('User.php');
$login = new User();
if ($login -> is_loggedin()!="")
{
    // if loggin success
    $login->redirect('view-view-food.php');
}
if (isset($_POST['button-login']))
{
    // Get user input from user form
    $userName = strip_tags($_POST['txt_uname_email']);
    $userEmail = strip_tags($_POST['txt_uname_email']);
    $userPass = strip_tags($_POST['txt_password']);
    // Server side validation
    if ($login->login($userName,$userEmail,$userPass))
    {
        $login->redirect('view-food.php');
    }
    else
    {
        $error = "Wrong details";
    }
}
?>
<?php
include_once 'Header.php';
include_once 'Navigation-bar.php';
?>

<div class="signin-form">

    <div class="container">


        <form class="form-signin" method="post" id="login-form">

            <h2 class="form-signin-heading">Log In</h2><hr />

            <div id="error">
                <?php
			if(isset($error))
			{
				?>
                <div class="alert alert-danger">
                    <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> !
                </div>
                <?php
			}
		?>
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="txt_uname_email" placeholder="Username or E mail ID" required />
                <span id="check-e"></span>
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="txt_password" placeholder="Your Password" />
            </div>

            <hr />

            <div class="form-group">
                <button type="submit" name="button-login" class="btn btn-default">
                    <i class="glyphicon glyphicon-log-in"></i> &nbsp; SIGN IN
                </button>
            </div>
            <br />
            <label>Don't have account yet ! <a href="signup.php">Sign Up</a></label>
        </form>

    </div>

</div>

<?php
include_once 'Footer.php';

?>
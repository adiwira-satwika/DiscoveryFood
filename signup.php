<?php
/**
 * Created by PhpStorm.
 * User: tommy
 * Date: 29/3/17
 * Time: 9:47 PM
 */
session_start();
require_once ('User.php');
$user = new User();
// call method of is_loggedin to start the session
if($user -> is_loggedin()!="")
{
    // redirect user to login page
    $user->redirect('view-food.php');
}
if(isset($_POST['button-signup']))
{
    // Get user input from user form
    $userName = strip_tags($_POST['txt_uname']);
    $userEmail = strip_tags($_POST['txt_umail']);
    $userPass = strip_tags($_POST['txt_upass']);

    // Server side validation
    if ($userName=="")
    {
        $error[]="provide username";
    }
    else if ($userEmail=="")
    {
        $error[]="provide email id";
    }
    // Validate user email
    else if (!filter_var($userEmail,FILTER_VALIDATE_EMAIL))
    {
        $error[]="Please enter valid email address";
    }
    else if ($userPass=="")
    {
        $error[] = "Provide password";
    }
    else if (strlen($userPass) < 6)
    {
        $error[] = "Password must be at least 6 characters";
    }
    else
    {
        // if validation pass , proceed to inject user data to database
        try
        {
            $stmt = $user -> runQuery("SELECT user_name,user_email FROM users WHERE user_name=:userName OR user_email=:userEmail");
            $stmt->execute(array(':userName'=>$userName,'userEmail'=>$userEmail));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['user_name'] == $userName)
            {
                $error[]='username has already taken';
            }
            else if ($row['user_email'] == $userEmail)
            {
                $error[]="email has already taken";
            }
            else
            {
                if ($user ->register($userName,$userEmail,$userPass))
                {
                    $user->redirect('signup.php?joined');
                }
            }
        }catch (PDOException $e)
        {
            $e ->getMessage();
        }
    }
}

?>
<?php
include_once 'Header.php';
include_once 'Navigation-bar.php';
?>


<div class="signin-form">

    <div class="container">

        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Sign up.</h2><hr />
            <?php
            if(isset($error))
            {
                foreach($error as $error)
                {
                    ?>
                    <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                    </div>
                    <?php
                }
            }
            else if(isset($_GET['joined']))
            {
                ?>
                <div class="alert alert-info">
                    <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
                </div>
                <?php
            }
            ?>
            <div class="form-group">
                <input type="text" class="form-control" name="txt_uname" placeholder="Enter Username" value="<?php if(isset($error)){echo $userName;}?>" />
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="txt_umail" placeholder="Enter E-Mail ID" value="<?php if(isset($error)){echo $userEmail;}?>" />
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="txt_upass" placeholder="Enter Password" />
            </div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
                <button type="submit" class="btn btn-primary" name="button-signup">
                    <i class="glyphicon glyphicon-open-file"></i>&nbsp;SIGN UP
                </button>
            </div>
            <br />
            <label>have an account ! <a href="index.php">Sign In</a></label>
        </form>
    </div>
</div>

</div>





<?php
include_once 'Footer.php';

?>

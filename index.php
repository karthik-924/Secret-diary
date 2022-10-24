<?php
session_start();
$error = "";
$configs = include('config.php');
if(array_key_exists("logout",$_GET)){
    session_destroy();
    setcookie("id","",time()-60*60);
    $_COOKIE="";
header("Location: index.php");
}else if((array_key_exists("id",$_SESSION) AND $_SESSION['id']) OR (array_key_exists("id",$_COOKIE) AND $_COOKIE['id'])){
    header("Location: loggedin.php");
}
if (array_key_exists("submit", $_POST)) {
    $link=mysqli_connect($configs['host'], $configs['username'], $configs['password'], $configs['db']);
    if(mysqli_connect_error()){
        die("Cannot connect DB");
    }
    
    if (!$_POST['email']) {
        $error .= "An email is required!<br>";
    }
    if (!$_POST['password']) {
        $error .= " A password is required<br>";
    }
    if ($error != "") {
    }else{
        if($_POST['signup']=='1'){
            $query1="select id from `users` where email='".mysqli_real_escape_string($link,$_POST["email"])."' LIMIT 1";
            $result=mysqli_query($link,$query1);
            if (mysqli_num_rows($result)>0){
                $error= "Email already exists.";
            }else{
                $query="insert into `users` (`email`,`password`,`diary`) values ('".mysqli_real_escape_string($link,$_POST['email'])."','".mysqli_real_escape_string($link,$_POST['password'])."','')";
                if(!mysqli_query($link,$query)){
                    $error= "Inserting failed";
                }else{
                    $query="update `users` set password='".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' where id=".mysqli_insert_id($link)." LIMIT 1";
                    mysqli_query($link,$query);
                    $_SESSION['id']=mysqli_insert_id($link);
                    if($_POST['Loggedin']=='1'){
                        setcookie("id",mysqli_insert_id($link),time()+60*60*24);
                    }
                    echo $_SESSION['id'];
                    header("Location: loggedin.php");
                }
            }
        }
        else{
            $query="select * from `users` where email='".mysqli_real_escape_string($link,$_POST['email'])."'";
            $result=mysqli_query($link,$query);
            $row=mysqli_fetch_array($result);
            if($row){
            if(array_key_exists('id',$row)){
                $hashedPass=md5(md5($row['id']).$_POST['password']);
                if ($hashedPass==$row['password']){
                    $_SESSION['id']=$row['id'];
                    echo $_SESSION['id'];
                    if($_POST['Loggedin']=='1'){
                        setcookie("id",$row['id'],time()+60*60*24);
                    }
                    header("Location: loggedin.php");
                }
                else{
                    $error= "Incorrect Password. Please try again.";
                }
            }
        }else{
            $error= "Email doesn't exist";
        }
            
        }
    }
    
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
    .container {
        text-align: center;
        width: 400px;
    }

    body {
        background: none;
        margin-top: 10%;
    }

    html {
        background: url(backgroundmainmain.png) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }

    h1 {
        font-weight: bolder;
    }

    #loginform {
        display: none;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Secret Diary</h1>
        <p><strong>Unleash your thoughts.</strong></p>
        <div id="error"><?php if( $error!=""){
            echo '<div class="alert alert-danger" role="alert">
            '.$error.'
          </div>';
        } ?></div>
        <form method="POST" id="signupform">
            <p>Interested Sign in now.</p>
            <div class="mb-3">
                <input class="form-control" type="text" name="email" placeholder="Enter email">
            </div>
            <div class="mb-3">
                <input class="form-control" type="password" name="password" placeholder="Enter password">
            </div>
            <div class="mb-3">
                <input class="form-check-label" type="checkbox" name="Loggedin" value="1">
                <b>Stay logged In</b>
            </div>
            <div class="mb-3">
                <input type="hidden" name="signup" value="1">
                <input class="btn btn-primary" type="submit" name="submit" value="Sign Up!">
            </div>
            <p>
                <a href="#" class="showlogin">Log in</a>
            </p>
        </form>
        <form method="POST" id="loginform">
            <p>Login with username and password.</p>
            <div class="mb-3">
                <input class="form-control" id="exampleInputEmail1" type="text" name="email" placeholder="Enter email">
            </div>
            <div class="mb-3">
                <input class="form-control" type="password" name="password" placeholder="Enter password">
            </div>
            <div class="mb-3">
                <input class="form-check-label" type="checkbox" name="Loggedin" value="1">
                <b>Stay logged In</b>
            </div>
            <div class="mb-3">
                <input type="hidden" name="signup" value="0">
                <input class="btn btn-primary" type="submit" name="submit" value="Log In!">
            </div>
            <p>
                <a href="#" class="showlogin">Sign up</a>
            </p>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" defer>
    $(".showlogin").click(() => {
        $("#signupform").toggle();
        $("#loginform").toggle();
    });
    </script>
</body>

</html>
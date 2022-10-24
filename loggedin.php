<?php
    session_start();
    $diaryContent="";
    $configs = include('config.php');
    if(array_key_exists("id",$_COOKIE)){
        $_SESSION['id']=$_COOKIE['id'];
    }
    
    if(array_key_exists("id",$_SESSION)){
        
        $link=mysqli_connect($configs['host'], $configs['username'], $configs['password'], $configs['db']);
                if(mysqli_connect_error()){
                    die("Cannot connect DB");
                }
        $query="select diary from `users` where id=".mysqli_real_escape_string($link,$_SESSION['id'])." limit 1";
        $row=mysqli_fetch_array(mysqli_query($link,$query));
        $diaryContent=$row['diary'];
    }else{
        header("Location: index.php");
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

    #diary {
        width: 100%;
        height: 40%;
    }

    .container-fluid {
        margin-top: 3%;
    }

    .pull-xs-right {
        position: fixed;
        right: 0;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light navbar-fixed-top">
        <a class="navbar-brand" href="#">Secret Diary</a>
        <div class="pull-xs-right">
            <a href="index.php?logout=1"><button class="btn btn-outline-success"
                    type="submit">Logout</button></button></a>
        </div>
    </nav>
    <div class=" container-fluid">
        <textarea class="form-control" name="diary" id="diary" cols="30" rows="5"
            placeholder="Enter text here"><?php echo $diaryContent?></textarea>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" defer>
    $(".showlogin").click(() => {
        $("#signupform").toggle();
        $("#loginform").toggle();
    })
    $('#diary').bind('input propertychange', function() {
        $.ajax({
            method: "POST",
            url: "updateDB.php",
            data: {
                content: $("#diary").val()
            }
        })
    });
    </script>
</body>

</html>
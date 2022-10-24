<?php
session_start();
if(array_key_exists("content",$_POST)){
    $link=mysqli_connect("localhost","id19711680_root92","XLOY&^@z|g8=b{Ot","id19711680_secretdiary");
        if(mysqli_connect_error()){
            die("Cannot connect DB");
        }
    $query="update `users` set `diary`='".mysqli_real_escape_string($link,$_POST['content'])."' where id=".mysqli_real_escape_string($link,$_SESSION['id'])." limit 1";
    mysqli_query($link,$query);

}
?>
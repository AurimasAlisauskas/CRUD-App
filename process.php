<?php

session_start();

$mysqli = new mysqli('localhost', 'root', '', 'crud') or die(mysqli_error($mysqli));

$id = 0;
$update = false;
$name = '';
$avatar = '';
        
if (isset($_POST['save'])){
    $name = $_POST['name'];
    $avatar = $_POST['avatar'];
    
    if ($name == "" || empty($name) && $avatar == "" || empty($avatar)){
        $_SESSION['message'] = "'Name' and 'Avatar' fields should not be empty!";
        $_SESSION['msg_type'] = "danger";
    } else {
    
    $mysqli->query("INSERT INTO data (name, avatar) VALUES ('$name', '$avatar')")or die($mysqli->error);
    
    $_SESSION['message'] = "Record has been saved!";
    $_SESSION['msg_type'] = "success";
    }
    header("location: index.php");
    
}

if (isset($_GET['delete'])){
    $id = $_GET['delete'];
    
    $mysqli->query("DELETE FROM data WHERE id=$id")or die($mysqli->error);
    
    $_SESSION['message'] = "Record has been deleted!";
    $_SESSION['msg_type'] = "danger";
    
    header("location: index.php");
}

if (isset ($_GET['edit'])){
    $id = $_GET['edit'];
    $update = true;
    $result = $mysqli->query("SELECT * FROM data WHERE id=$id")or die($mysqli->error);
    if (count($result)==1){
        $row = $result->fetch_array();
        $name = $row['name'];
        $avatar = $row['avatar'];
    }
}

if (isset ($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $avatar = $_POST['avatar'];
    
    $mysqli->query("UPDATE data SET name='$name', avatar='$avatar' WHERE id=$id")or die($mysqli->error);
    
    $_SESSION['message'] = "Record has been updated!";
    $_SESSION['msg_type'] = "warning";
    
    header("location: index.php");
}

if (isset($_POST['upload'])){
$url = file_get_contents("https://jsonplaceholder.typicode.com/users");
        $turinys = json_decode($url, true);

        foreach ($turinys as $name) {

            if (is_array($turinys)){
    
                $valuesArr = array();
                $valuesArr1 = array();
    
                    foreach ($turinys as $person_b){
                        $vardas = $person_b['name'];
                        $avataras = $person_b['username'];
        
                        $valuesArr[] = "('$vardas')";
                        $valuesArr1[] = "('$avataras')";
                    }
                    
                    $randNr = rand(0, count($valuesArr)-1);
                    $randVardas = $valuesArr[$randNr];
                    
                    $randNr1 = rand(0, count($valuesArr1)-1);
                    $randAvataras = $valuesArr1[$randNr1];
                    
                    $sql = "INSERT INTO data(name, avatar) VALUES ($randVardas, $randAvataras)";
                    $mysqli->query($sql) or exit(mysql_error());
            break;
            } 
        } 


$_SESSION['message'] = "Uploaded from 'https://jsonplaceholder.typicode.com/users'";
$_SESSION['msg_type'] = "warning";

header("location: index.php");
}
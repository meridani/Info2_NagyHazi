<!DOCTYPE html>

<?php

include 'helper.php';

$link = openDB();
session_start();
$created = false;
$user_created = false;
$user_updated = false;
$name = "";
$email = "";
$button_label = "Hozzáadás";
$badge_label = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['create'])) {
        $name = mysqli_real_escape_string($link, $_POST['AccountName']);
        $email = mysqli_real_escape_string($link, $_POST['Email']);

        if ($email != "" && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (isset($_SESSION['update']) && $_SESSION['update'] == true)
            {
                $queryCreate = sprintf("UPDATE users SET AccountName='%s', Email='%s' WHERE ID='%s'",
                    mysqli_real_escape_string($link, $name),
                    mysqli_real_escape_string($link, $email),
                    mysqli_real_escape_string($link, $_SESSION['UserID']));
                $badge_label = "Felhasználó frissítve";
                $button_label = "Frissítés";
            }
            else
            {
                $queryCreate = sprintf("INSERT INTO users(AccountName, Email) VALUES ('%s', '%s')", $name, $email);
                $user_created = true;
                $badge_label = "Felhasználó hozzáadva";
            }
            mysqli_query($link, $queryCreate) or die (mysqli_error($link));
        }else{
            $badge_label = "Hibás adatok";
        }
    }

}

if ($_SERVER['REQUEST_METHOD'] == 'GET'){

    if (isset($_GET['UserID'])) {
        print_r($_GET);
        $query = sprintf("SELECT AccountName, Email FROM users WHERE ID = '%s'", mysqli_real_escape_string($link, (string)$_GET['UserID']));

        $modifyUser = mysqli_query($link, $query) or die (mysqli_error($link));
        $row = mysqli_fetch_array($modifyUser);

        $name = $row['AccountName'];
        $email = $row['Email'];

        if(isset($_GET['delete'])){
            $query = sprintf("DELETE FROM users WHERE ID='%s'", mysqli_real_escape_string($link, (string)($_GET['UserID'])));
            mysqli_query($link, $query) or die(mysqli_error($link));
            $_SESSION['update'] = 'false';
            $badge_label = "Felhasználó eltávolítva";
            session_unset();
        }else{
            $button_label = "Frissítés";
            $_SESSION['update'] = 'true';
            $_SESSION['UserID'] = $_GET['UserID'];
        }

    } else{
        session_unset();
    }
}


closeDB($link);
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="hu">

<head>
    <?php include "head.html"; ?>
    <title>Felhasználók - GPS nyomkövetés</title>
</head>

<body>
<?php include "header.html"; ?>

<div class="container main-content">
    <form method="post" action="">
        <div class="card">
            <div class="card-header">
                Új felhasználó hozzáadása
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="AccountName">Név</label>
                    <input required class="form-control" name="AccountName" id="AccountName" type="text" value="<?=$name?>">
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input required class="form-control" name="Email" id="AccountName" type="text" value="<?=$email?>">
                </div>
            </div>
            <div class="card-footer">
                <input type="submit" class="btn btn-primary" name="create" value="<?=$button_label?>">
                <?php if ($badge_label == "Felhasználó hozzáadva") {
                    echo '<span class="badge badge-pill badge-success">'.$badge_label.'</span>';
                } elseif ($badge_label == "Felhasználó eltávolítva"){
                    echo '<span class="badge badge-pill badge-warning">'.$badge_label.'</span>';
                } elseif ($badge_label == "Felhasználó frissítve"){
                    echo '<span class="badge badge-pill badge-success">'.$badge_label.'</span>';
                }elseif ($badge_label == "Hibás adatok"){
                    echo '<span class="badge badge-pill badge-danger">'.$badge_label.'</span>';
                }else{

                }
                ?>
                <a class="btn btn-primary btn-sm" href="users.php">Mégse</a>
            </div>
        </div>
    </form>

</div>

<?php include "footer.html"; ?>

<!-- End of Body -->
</body>
</html>
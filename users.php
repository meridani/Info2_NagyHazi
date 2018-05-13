<!DOCTYPE html>

<?php
    include 'helper.php';
    $link = openDB();
?>

<html xmlns="http://www.w3.org/1999/html">

<head>
    <?php include "head.html"; ?>
    <title>Felhasználók - GPS nyomkövetés</title>
</head>

<body>
<?php include "header.html"; ?>
<?php   $search = null;
        if (isset($_POST['search'])) {
            $search = $_POST['search'];
        }
?>
<div class="container main-content">
    <h1>Felhasználók</h1>
    <div class="container">
        <nav class="navbar navbar-light bg-light justify-content">
            <a href="users-actions.php"><i class="fas fa-plus-square"> Új felhasználó rögzítése</i></a>
            <form class="form-inline" method="post">
                <input class="form-control mr-sm-2" type="search" placeholder="Keresés" name="search" value="<?=$search?>">
                <button class="btn btn-outline-success my-2 my-sm0" type="submit">Keresés</button>
            </form>
        </nav>
    </div>

    <?php
    $userlist = listUsers($link, $search);
    ?>

    <br/>

    <table class="table table-striped table-sm table-bordered">
        <thead>
            <tr>
                <th>Név</th>
                <th>Email</th>
                <th>Szélesség</th>
                <th>Hosszúság</th>
                <th>Magasság</th>
                <th class="col col-md-auto">Műveletek</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_array($userlist)): ?>
            <tr>
                <td><?=$row['AccountName']?></td>
                <td><?=$row['Email']?></td>
                <td><?=$row['Latitude']?></td>
                <td><?=$row['Longitude']?></td>
                <td><?=$row['Elevation']?></td>
                <td class="justify-content-center">
                    <a class="btn btn-primary btn-sm" href="users-actions.php?UserID=<?=$row['ID']?>"><i class="fas fa-edit"></i>Szerk.</a>
                    <a class="btn btn-primary btn-sm" href="users-actions.php?UserID=<?=$row['ID']?>"><i class="fas fa-trash-alt"></i>Töröl</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php
        if (mysqli_num_rows($userlist) == 0){
            echo '<span class="container">Nincs találat</span>';
        }?>

    <?php closeDB($link); ?>

</div>

<?php include "footer.html"; ?>
<!-- End of Body -->
</body>
</html>
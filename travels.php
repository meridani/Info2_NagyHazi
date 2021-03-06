<!DOCTYPE html>

<?php
    include 'helper.php';
    $link = openDB();
?>

<?php
$search = null;
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="hu">

<head>
    <?php include "head.html"; ?>
    <title>Utazások - GPS nyomkövetés</title>
</head>

<body>

<?php include "header.html"; ?>
<!-- Start of Body -->
<div class="container main-content">
    <div class="jumbotron">
        <h1>Utazások</h1>
    </div>
    <div class="container">
        <nav class="navbar navbar-light bg-light justify-content">
            <a href="travels-actions.php"><i class="fas fa-plus-square"> Új utazás rögzítése</i></a>
            <form class="form-inline" method="post">
                <input class="form-control mr-sm-2" type="search" placeholder="Keresés" name="search" value="<?=$search?>">
                <button class="btn btn-outline-success my-2 my-sm0" type="submit">Keresés</button>
            </form>
        </nav>
    </div>

    <?php
    $travelList = listTravels($link, $search);
    ?>

    <br/>

    <table class="table table-striped table-sm table-bordered">
        <thead>
        <tr>
            <th>Útvonal neve</th>
            <th>Felhasználó</th>
            <th>Hányszor járt a szakaszon</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_array($travelList)): ?>
            <tr>
                <td><?=$row['TrackName']?></td>
                <td><?=($row['AccountName'] != null ? $row['AccountName'] : "(ismeretlen)");?></td>
                <td><?=$row['COUNT(trav.TrackID)']?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php
    if (mysqli_num_rows($travelList) == 0){
        echo '<span class="container">Nincs találat</span>';
    }?>

    <?php closeDB($link); ?>
</div>

<?php include "footer.html"; ?>

<!-- End of Body -->
</body>
</html>
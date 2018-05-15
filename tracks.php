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
    <title>Útvonalak - GPS nyomkövetés</title>
</head>

<body>

<?php include "header.html"; ?>
<!-- Start of Body -->
<div class="container main-content">
    <div class="jumbotron">
        <h1>Útvonalak</h1>
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
    $tracklist = listTrackNames($link, $search);
    ?>

    <br/>

    <table class="table table-striped table-sm table-bordered">
        <thead>
        <tr>
            <th>Útvonal neve</th>
            <th>Start pozíció</th>
            <th>Végső pozíció</th>
            <th>Hossz</th>
        </tr>
        </thead>
        <tbody>

        <?php while ($row = mysqli_fetch_array($tracklist)): ?>
        <?php //echo '<pre>'.print_r($row).'</pre>' ?>
            <tr>
                <td><?=$row['TrackName']?></td>
                <td><?="Lat: ".$row['StartLatitude'].", Lon: ".$row['StartLongitude'].", Ele: ".$row['StartElevation']?></td>
                <td><?="Lat: ".$row['EndLatitude'].", Lon ".$row['EndLongitude'].", Ele: ".$row['EndElevation']?></td>
                <td><?=round(vincentyGreatCircleDistance($row['StartLatitude'],$row['StartLongitude'],$row['EndLatitude'],$row['EndLongitude'])/1000, 3)." km"?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php
    if (mysqli_num_rows($tracklist) == 0){
        echo '<span class="container">Nincs találat</span>';
    }?>

    <?php closeDB($link); ?>
</div>

<?php include "footer.html"; ?>

<!-- End of Body -->
</body>
</html>
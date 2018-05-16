<!DOCTYPE html>

<?php

include 'helper.php';

$link = openDB();

$button_label = "Hozzáadás";
$badge_label = "Hozzáadva";

$TrackName = 'null';

$StartLatitude = 'null';
$StartLongitude = 'null';
$StartElevation = 'null';

$EndLatitude = 'null';
$EndLongitude = 'null';
$EndElevation = 'null';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $TrackName = mysqli_real_escape_string($link, $_POST['TrackName']);

        $StartLatitude = mysqli_real_escape_string($link, $_POST['StartLatitude']);
        $StartLongitude = mysqli_real_escape_string($link, $_POST['StartLongitude']);
        $StartElevation = mysqli_real_escape_string($link, $_POST['StartElevation']);

        $EndLatitude = mysqli_real_escape_string($link, $_POST['EndLatitude']);
        $EndLongitude = mysqli_real_escape_string($link, $_POST['EndLongitude']);
        $EndElevation = mysqli_real_escape_string($link, $_POST['EndElevation']);

        $query = sprintf("SELECT EXISTS( SELECT * FROM tracks WHERE Trackname like '%s') as TrackExists", $TrackName);
        $TrackNameExists = mysqli_query($link, $query) or die (mysqli_error($link));

        $row = mysqli_fetch_array($TrackNameExists);
        if ($row['TrackExists'] == '0')
        {
            $query = sprintf("SELECT ID, EXISTS(SELECT * FROM locations li WHERE li.Latitude like '%s' && li.Longitude like '%s' && li.Elevation like '%s') as LocationExists
                        FROM locations 
                        WHERE Latitude like '%s' && Longitude like '%s' && Elevation like '%s'
                        ;", $StartLatitude, $StartLongitude, $StartElevation, $StartLatitude, $StartLongitude, $StartElevation);
            $StartLocationExists = mysqli_query($link, $query);
            $query = sprintf("SELECT ID, EXISTS(SELECT * FROM locations li WHERE li.Latitude like '%s' && li.Longitude like '%s' && li.Elevation like '%s') as LocationExists
                        FROM locations 
                        WHERE Latitude like '%s' && Longitude like '%s' && Elevation like '%s'
                        ;", $EndLatitude, $EndLongitude, $EndElevation, $EndLatitude, $EndLongitude, $EndElevation);
            $EndLocationExists = mysqli_query($link, $query);


            $row = mysqli_fetch_array($StartLocationExists);
            if (mysqli_num_rows($StartLocationExists) == '0') {
                $queryCreateLocation = sprintf("INSERT INTO locations(Latitude, Longitude, Elevation) VALUES ('%s', '%s', '%s')", $StartLatitude, $StartLongitude, $StartElevation);
                mysqli_query($link, $queryCreateLocation) or die(mysqli_error($link));
                $queryLocation = sprintf("SELECT * FROM locations WHERE Latitude like '%s' && Longitude like '%s' && Elevation like '%s'", $StartLatitude, $StartLongitude, $StartElevation);
                $createdLocation = mysqli_query($link, $queryLocation);
                $row = mysqli_fetch_array($createdLocation);
            }
            $StartLocationID = $row['ID'];


            $row = mysqli_fetch_array($EndLocationExists);
            if (mysqli_num_rows($EndLocationExists) == '0') {
                $queryCreateLocation = sprintf("INSERT INTO locations(Latitude, Longitude, Elevation) VALUES ('%s', '%s', '%s')", $EndLatitude, $EndLongitude, $EndElevation);
                mysqli_query($link, $queryCreateLocation) or die(mysqli_error($link));
                $queryLocation = sprintf("SELECT * FROM locations WHERE Latitude like '%s' && Longitude like '%s' && Elevation like '%s'", $EndLatitude, $EndLongitude, $EndElevation);
                $createdLocation = mysqli_query($link, $queryLocation);
                $row = mysqli_fetch_array($createdLocation);
            }
            $EndLocationID = $row['ID'];

//            echo $TrackName;
//            echo "<br/>";
//            echo $StartLocationID;
//            echo "<br/>";
//            echo $EndLocationID;
            $queryCreateTrack = sprintf("INSERT INTO tracks(TrackName, StartLocation, EndLocation) VALUES ('%s', '%s', '%s');", $TrackName, $StartLocationID, $EndLocationID);
            mysqli_query($link, $queryCreateTrack) or die(mysqli_error($link));

            $badge_label = "Útvonal hozzáadva";
        }



    }

}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="hu">

<head>
    <?php include "head.html"; ?>
    <title>Útvonalak - GPS nyomkövetés</title>
</head>

<body>
<?php include "header.html"; ?>

<div class="container main-content">
    <form method="post" action="">
        <div class="card">
            <div class="card-header">
                Új útvonal rögzítése
            </div>

            <div class="card-body">

                <div class="form-group">
                    <label for="TrackName">Útvonal neve</label>
                    <input required class="form-control" name="TrackName" id="TrackName" type="text">
                </div>

                <div class="row">
                    <div class="col-sm-6">


                        <div class="card card-body">
                            <div class="form-group">
                                <label for="StartLatitude">Kezdő Szélesség</label>
                                <input required class="form-control" name="StartLatitude" id="StartLatitude"
                                       type="text">
                            </div>
                            <div class="form-group">
                                <label for="StartLongitude">Kezdő Hosszúság</label>
                                <input required class="form-control" name="StartLongitude" id="StartLongitude"
                                       type="text">
                            </div>
                            <div class="form-group">
                                <label for="StartElevation">Kezdő Magasság</label>
                                <input required class="form-control" name="StartElevation" id="StartElevation"
                                       type="text">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">

                        <div class="card card-body">
                            <div class="form-group">
                                <label for="EndLatitude">Cél Szélesség</label>
                                <input required class="form-control" name="EndLatitude" id="EndLatitude" type="text">
                            </div>
                            <div class="form-group">
                                <label for="EndLongitude">Cél Hosszúság</label>
                                <input required class="form-control" name="EndLongitude" id="EndLongitude" type="text">
                            </div>
                            <div class="form-group">
                                <label for="EndElevation">Cél Magasság</label>
                                <input required class="form-control" name="EndElevation" id="EndElevation" type="text">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <input type="submit" class="btn btn-primary" name="create" value="<?= $button_label ?>">
                <?php if ($badge_label == "Útvonal hozzáadva") {
                    echo '<span class="badge badge-pill badge-success">' . $badge_label . '</span>';
                } elseif ($badge_label == "Hibás adatok") {
                    echo '<span class="badge badge-pill badge-danger">' . $badge_label . '</span>';
                } else {

                }
                ?>
                <a class="btn btn-primary btn-sm" href="travels.php">Mégse</a>
            </div>
        </div>
    </form>

</div>

<?php include "footer.html"; ?>

<!-- End of Body -->

<?php closeDB($link); ?>

</body>
</html>
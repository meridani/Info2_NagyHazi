<!DOCTYPE html>

<?php

include 'helper.php';

$link = openDB();
$button_label = "Hozzáadás";
$badge_label = "Hozzáadva";
$UserID = 'null';
$TrackID = 'null';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        if ($_POST['AccountID'] == null) {
            $UserID = 'null';
        } else {
            $UserID = mysqli_real_escape_string($link, $_POST['AccountID']);
        }

        $TrackID = mysqli_real_escape_string($link, $_POST['TrackID']);

        // No update allowed, kept for future
        if (isset($_SESSION['update']) && $_SESSION['update'] == true) {
            $queryCreate = sprintf("UPDATE travels SET UserID='%s', TrackID='%s' WHERE ID='%s'",
                mysqli_real_escape_string($link, $UserID),
                mysqli_real_escape_string($link, $TrackID),
                mysqli_real_escape_string($link, $_SESSION['TravelID']));
            $badge_label = "Szakasz frissítve";
            $button_label = "Frissítés";
        } else {
            $queryCreate = sprintf("INSERT INTO travels(UserID, TrackID) VALUES (%s, '%s');", $UserID, $TrackID);
            $user_created = true;
            $badge_label = "Szakasz hozzáadva";
            //echo $queryCreate . "<br/>";
        }
        mysqli_query($link, $queryCreate) or die (mysqli_error($link));
    } else {
        $badge_label = "Hibás adatok";

    }

}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="hu">

<head>
    <?php include "head.html"; ?>
    <title>Utazás - GPS nyomkövetés</title>
</head>

<body>
<?php include "header.html"; ?>

<div class="container main-content">
    <form method="post" action="">
        <div class="card">
            <div class="card-header">
                Új utazás rögzítése
            </div>
            <?php
            $userlist = listUsers($link, "");
            $travelList = listTrackNames($link, "");
            ?>

            <div class="card-body">

                <div class="input-group">
                    <label for="AccountID" class="input-group-text">Név:</label>
                    <select name="AccountID" class="custom-select">
                        <option value="" selected hidden>(Kérem Válasszon)</option>
                        <?php while ($row = mysqli_fetch_array($userlist)): ?>
                            <option value="<?= $row['ID'] ?>"<?php echo($row['ID'] == $UserID ? 'selected' : ''); ?>><?= $row['AccountName'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="TrackID" class="input-group-text">Útvonal:</label>
                    <select name="TrackID" required class="custom-select">
                        <option value="" selected hidden>(Kérem Válasszon)</option>
                        <?php while ($row = mysqli_fetch_array($travelList)): ?>
                            <option value="<?= $row['ID'] ?>"<?php echo($row['ID'] == $TrackID ? 'selected' : ''); ?>><?= $row['TrackName'] ?></option>
                        <?php endwhile; ?>
                    </select>

                </div>

            </div>
            <div class="card-footer">
                <input type="submit" class="btn btn-primary" name="create" value="<?= $button_label ?>">
                <?php if ($badge_label == "Szakasz hozzáadva") {
                    echo '<span class="badge badge-pill badge-success">' . $badge_label . '</span>';
                } elseif ($badge_label == "Szakasz eltávolítva") {
                    echo '<span class="badge badge-pill badge-warning">' . $badge_label . '</span>';
                } elseif ($badge_label == "Szakasz frissítve") {
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
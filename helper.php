<?php
/**
 * @return mysqli
 */
function openDB()
{
    $link = mysqli_connect("localhost", "root", "")
    or die("Kapcsolódási hiba: " . mysqli_error());
    mysqli_select_db($link, "tracker");
    mysqli_query($link, "set character_set_results='utf8'");
    mysqli_query($link, "set character_set_client='utf8'");
    return $link;
}

/**
 * @param $link
 */
function closeDB($link)
{
    mysqli_close($link);
}

/**
 * @param $link
 * @param $searchcrit
 * @return bool|mysqli_result
 */
function listUsers($link, $searchcrit = "")
{
    if ($searchcrit != "") {
        $escapedString = mysqli_real_escape_string($link, strtolower($searchcrit));
        $searchcrit = sprintf("WHERE LOWER(u.AccountName) LIKE '%%%s%%' OR LOWER(u.Email) LIKE '%%%s%%'", $escapedString, $escapedString);
    }
    $query = "SELECT u.ID, u.AccountName, u.Email, l.Latitude, l.Longitude, l.Elevation FROM users u LEFT JOIN locations l on l.ID = u.LocationID"
        ." " . $searchcrit . " " .
        "ORDER BY AccountName";
//    echo $query;
    $userlist = mysqli_query($link, $query) or die (mysqli_error($link));
    return $userlist;
}

function listTravels($link, $searchcrit = "")
{
    if ($searchcrit != "") {
        $escapedString = mysqli_real_escape_string($link, strtolower($searchcrit));
        $searchrit = sprintf("WHERE u.AccountName like '%%%s%%' OR tr.TrackName like '%%%s%%'", $escapedString, $escapedString);
    }
    $query = "SELECT trav.TrackID, tr.TrackName, u.AccountName, COUNT(trav.TrackID) FROM travels trav LEFT JOIN users u on u.ID = trav.UserID JOIN tracks tr on tr.ID = trav.TrackID"
        ." " . $searchcrit . " " .
        "GROUP BY trav.TrackID, trav.UserID
        ORDER BY tr.TrackName ASC, u.AccountName ASC
    ";
    $travellist = mysqli_query($link, $query) or die (mysqli_error($link));
    return $travellist;
}

function listTrackNames($link)
{
    $query = "SELECT t.ID, t.StartLocation, t.EndLocation, t.TrackName, 
                s.Latitude as StartLatitude, s.Longitude as StartLongitude, s.Elevation as StartElevation, 
                e.Latitude as EndLatitude, e.Longitude as EndLongitude, e.Elevation as EndElevation
                FROM tracks t
                INNER JOIN locations s on s.ID = StartLocation 
                INNER JOIN locations e on e.ID = EndLocation;";
    $travellist = mysqli_query($link, $query) or die (mysqli_error($link));
    return $travellist;
}

/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
function vincentyGreatCircleDistance(
    $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000.0)
{
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $lonDelta = $lonTo - $lonFrom;
    $a = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

    $angle = atan2(sqrt($a), $b);
    return $angle * $earthRadius;
}

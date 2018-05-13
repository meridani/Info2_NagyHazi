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
        $searchcrit = sprintf("WHERE LOWER(u.AccountName) LIKE '%%%s%%' OR LOWER(u.Email) LIKE '%%%s%%'" , mysqli_real_escape_string($link, strtolower($searchcrit)), mysqli_real_escape_string($link, strtolower($searchcrit)));
    }
    $query = "SELECT u.AccountName, u.Email, l.Latitude, l.Longitude, l.Elevation, u.ID FROM users u LEFT JOIN location l on l.ID = u.LocationID " . $searchcrit . " ORDER BY AccountName";
//    echo $query;
    $userlist = mysqli_query($link, $query) or die (mysqli_error($link));
    return $userlist;
}

function deleteUser($link, $ID)
{

}
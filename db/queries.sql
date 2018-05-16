use tracker;

SELECT trav.ID, u.AccountName
	FROM travels as trav
    LEFT JOIN users u on u.ID = trav.id
    ;

SELECT u.AccountName, u.Email, l.Latitude, l.Longitude, l.Elevation 
	FROM users u 
    JOIN location l on l.ID = u.LocationID
    WHERE AccountName IS NOT NULL
    ORDER BY AccountName
    ;
    
DELETE FROM users WHERE ID=2;

SELECT tr.TrackName, u.AccountName, COUNT(trav.TrackID)
	FROM travels trav
    LEFT JOIN users u on u.ID = trav.UserID
    JOIN tracks tr on tr.ID = trav.TrackID
    WHERE u.AccountName like '%M%'
    GROUP BY trav.TrackID, trav.UserID
    ORDER BY tr.TrackName ASC, u.AccountName ASC
    ;
    
SELECT *, l.ID, l.Latitude, l.Longitude, l.Elevation 
	FROM tracks 
	LEFT JOIN locations l ON l.ID = StartLocation
	LEFT JOIN locations lf ON lf.ID = EndLocation
	;
    
SELECT t.ID, t.StartLocation, t.EndLocation, l.Latitude, l.Longitude, l.Elevation, lf.Latitude, lf.Longitude, lf.Elevation 
	FROM tracks t
	INNER JOIN locations l on l.ID = StartLocation 
	INNER JOIN locations lf on lf.ID = EndLocation
    ;
    
SELECT EXISTS(SELECT * FROM tracks WHERE Trackname like 'Balaton kör');

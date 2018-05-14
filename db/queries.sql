use tracker;

SELECT u.AccountName, u.Email, l.Latitude, l.Longitude, l.Elevation 
	FROM users u 
    LEFT JOIN location l on l.ID = u.LocationID
    WHERE AccountName IS NOT NULL
    ORDER BY AccountName
    ;
    
DELETE FROM users WHERE ID=2;

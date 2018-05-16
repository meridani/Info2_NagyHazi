drop database if exists tracker;

create database tracker
	DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;
    
use tracker;

CREATE TABLE locations (
	ID INT PRIMARY KEY AUTO_INCREMENT,
    Latitude CHAR VARYING(20) NOT NULL,
    Longitude CHAR VARYING(20) NOT NULL,
    Elevation CHAR VARYING(11)
);

CREATE TABLE users (
	ID int PRIMARY KEY AUTO_INCREMENT,
    AccountName char varying(50) NOT NULL UNIQUE,
    Email char(255) NOT NULL,
    LocationID INT,
    FOREIGN KEY (`LocationID`) REFERENCES locations(ID)
);

CREATE TABLE tracks (
	ID int PRIMARY KEY AUTO_INCREMENT,
	StartLocation INT NOT NULL,
    EndLocation INT NOT NULL,
    TrackName CHAR VARYING(191) NOT NULL UNIQUE,
    FOREIGN KEY (`StartLocation`) REFERENCES locations(ID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`EndLocation`) REFERENCES locations(ID) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE travels (
	ID int PRIMARY KEY AUTO_INCREMENT,
    UserID int,
    TrackID int NOT NULL,
	FOREIGN KEY ( `UserID` ) REFERENCES users(ID) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY ( `TrackID` ) REFERENCES tracks(ID) ON UPDATE CASCADE ON DELETE CASCADE
);
    
INSERT INTO locations (Latitude, Longitude, Elevation) VALUES ( "12.345678",	"12.345678",	"170"	);
INSERT INTO locations (Latitude, Longitude, Elevation) VALUES ( "2.345678",		"12.34",		"200"	);
INSERT INTO locations (Latitude, Longitude, Elevation) VALUES ( "1.345678",		"5.345678",		"12000"	);
INSERT INTO locations (Latitude, Longitude, Elevation) VALUES ( "10",			"-10",			"12"	);
INSERT INTO locations (Latitude, Longitude, Elevation) VALUES ( "47.4725994",	"19.0598549",	"104,45");
INSERT INTO locations (Latitude, Longitude, Elevation) VALUES ( "47.4733228",	"19.0598791",	"101,56");
INSERT INTO locations (Latitude, Longitude, Elevation) VALUES ( "12.345678",	"12.345679",	"170"	);
    
INSERT INTO users (AccountName, Email, LocationID) VALUES("Meridani",	"szmanndani@gmail.com",	1);
INSERT INTO users (AccountName, Email, LocationID) VALUES("Béla",		"béla@gmail.com",		2);    
INSERT INTO users (AccountName, Email) VALUES ("Józska",		"józska@gmail.com"		);
INSERT INTO users (AccountName, Email, LocationID) VALUES ("Okos",		"okoska@gmail.com",		5);
INSERT INTO users (AccountName, Email, LocationID) VALUES ("Ka",		"kaokos@gmail.com",		6);

INSERT INTO tracks (StartLocation, EndLocation, TrackName) VALUES( 1,	4,	'Kis kör'		);
INSERT INTO tracks (StartLocation, EndLocation, TrackName) VALUES( 2,	4,	'Nagy kör'		);
INSERT INTO tracks (StartLocation, EndLocation, TrackName) VALUES( 3,	4,	'Balaton kör'	);
INSERT INTO tracks (StartLocation, EndLocation, TrackName) VALUES( 5,	6,	'IQ okoska'	);

INSERT INTO travels (UserID, TrackID) VALUES (1, 1);
INSERT INTO travels (UserID, TrackID) VALUES (2, 3);
INSERT INTO travels (UserID, TrackID) VALUES (3, 2);
INSERT INTO travels (UserID, TrackID) VALUES (3, 1);
INSERT INTO travels (UserID, TrackID) VALUES (2, 1);
INSERT INTO travels (UserID, TrackID) VALUES (1, 1);
INSERT INTO travels (UserID, TrackID) VALUES (1, 2);

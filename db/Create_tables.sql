drop database if exists tracker;

create database tracker
	DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;
    
use tracker;

CREATE TABLE location (
	ID INT PRIMARY KEY AUTO_INCREMENT,
    Latitude CHAR VARYING(11) NOT NULL,
    Longtitude CHAR VARYING(11) NOT NULL,
    Elevation CHAR VARYING(11)
);

CREATE TABLE users (
	ID int PRIMARY KEY AUTO_INCREMENT,
    AccountName char varying(50) NOT NULL UNIQUE,
    Email char(255) NOT NULL,
    LocationID INT,
    FOREIGN KEY (`LocationID`) REFERENCES location(ID)
);

CREATE TABLE tracks (
	ID int PRIMARY KEY AUTO_INCREMENT,
	StartPosition INT NOT NULL,
    EndPosition INT NOT NULL,
    FOREIGN KEY (`StartPosition`) REFERENCES location(ID),
    FOREIGN KEY (`EndPosition`) REFERENCES location(ID)
);

CREATE TABLE travels (
	ID int PRIMARY KEY AUTO_INCREMENT,
    UserID int NOT NULL,
    TrackID int NOT NULL,
	FOREIGN KEY ( `UserID` ) REFERENCES users(ID),
    FOREIGN KEY ( `TrackID` ) REFERENCES tracks(ID)
);
    
INSERT INTO location (Latitude, Longtitude, Elevation) VALUES ( "12.345678"	, "12.345678"	, "170"   );
INSERT INTO location (Latitude, Longtitude, Elevation) VALUES ( "2.345678"	, "12.34"		, "200"   );
INSERT INTO location (Latitude, Longtitude, Elevation) VALUES ( "1.345678"	, "5.345678"	, "12000" );
INSERT INTO location (Latitude, Longtitude, Elevation) VALUES ( "10"		, "-10"			, "12"	  );
    
INSERT INTO users (AccountName, Email, LocationID) VALUES("Meridani" 	, "szmanndani@gmail.com"	, 1);
INSERT INTO users (AccountName, Email, LocationID) VALUES("Béla"		, "béla@gmail.com"			, 2);    
INSERT INTO users (AccountName, Email, LocationID) VALUES("Józska"  	, "józska@gmail.com"		, 3);

INSERT INTO tracks (StartPosition, EndPosition) VALUES( 1 ,4 );
INSERT INTO tracks (StartPosition, EndPosition) VALUES( 2 ,4 );
INSERT INTO tracks (StartPosition, EndPosition) VALUES( 3 ,4 );

INSERT INTO travels (UserID, TrackID) VALUES (1, 1);
INSERT INTO travels (UserID, TrackID) VALUES (2, 3);
INSERT INTO travels (UserID, TrackID) VALUES (3, 2);

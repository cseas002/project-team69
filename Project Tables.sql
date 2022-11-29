DROP TABLE [dbo].USERS
DROP TABLE [dbo].ITEM
DROP TABLE [dbo].TYPES
DROP TABLE [dbo].FINGERPRINT 
DROP TABLE [dbo].POI 
DROP TABLE [dbo].BFLOOR 
DROP TABLE [dbo].BUILDING 
DROP TABLE [dbo].CAMPUS 

CREATE TABLE [dbo].USERS
(
  UserID INT IDENTITY (1,1) NOT NULL,
  FName NVARCHAR(30) NOT NULL CHECK (FName != ''),
  LName NVARCHAR(30) NOT NULL CHECK (Lname != ''),
  GovID INT NOT NULL,
  Date_of_Birth DATE NOT NULL,
  Gender CHAR(1) NOT NULL, -- M,F,O
  Username NVARCHAR(30)  NOT NULL CHECK (Username != ''),
  UPassword NVARCHAR(30) NOT NULL CHECK (UPassword != ''),
  UserType TINYINT NOT NULL, -- Root (1), Database Admin (2), Simple User(3)
  CONSTRAINT USERS_UQ_Username UNIQUE (Username),
  CONSTRAINT USERS_UQ_GovID UNIQUE (GovID),
  CONSTRAINT USERS_PK  PRIMARY KEY (UserID), 
  CONSTRAINT UserType_Type CHECK(UserType IN (1, 2, 3)),
  CONSTRAINT Gender_MFO CHECK(Gender IN ('M', 'F', 'O'))
  -- Check for usertype
);

CREATE TABLE [dbo].TYPES
(
  TypeID INT IDENTITY(1,1) NOT NULL,
  Title NVARCHAR(40) NOT NULL CHECK (Title != ''),
  Model NVARCHAR(30) NOT NULL CHECK (Model != ''),
  UserAdded INT,
  UserModified INT,
  Date_Added DATE,
  Date_Modified DATE,
  CONSTRAINT TYPES_PK PRIMARY KEY (TypeID),
  CONSTRAINT TYPES_UQ_Title_Model Unique (Title,Model)
);

CREATE TABLE [dbo].CAMPUS
(
  CampusID INT IDENTITY(1,1) NOT NULL,
  CampusName NVARCHAR(30) NOT NULL CHECK (CampusName != ''),
  Summary NVARCHAR(MAX) NOT NULL CHECK (Summary != ''),
  RegDate DATE NOT NULL,
  UserAdded INT,
  UserModified INT,
  Date_Added DATE,
  Date_Modified DATE,
  Website NVARCHAR(2083) NOT NULL CHECK (Website != ''), -- Maximum URL length
  CONSTRAINT CAMPUS_PK PRIMARY KEY (CampusID),
  CONSTRAINT CAMPUS_UQ_CampusName UNIQUE (CampusName)
);

CREATE TABLE [dbo].BUILDING
(
  BCode INT IDENTITY(1,1) NOT NULL,
  BLDCode INT NOT NULL,
  BName NVARCHAR(30) NOT NULL,
  Summary NVARCHAR(MAX) NOT NULL,
  BAddress NVARCHAR(30) NOT NULL,
  x DECIMAL(15, 12) NOT NULL, -- https://stackoverflow.com/questions/1196415/what-datatype-to-use-when-storing-latitude-and-longitude-data-in-sql-databases#:~:text=Lat%2FLong%20is%20a%20position,it%20is%20almost%20always%20WGS84.
  y DECIMAL(15, 12) NOT NULL,
  BOwner NVARCHAR(30),
  RegDate DATE NOT NULL,
  UserAdded INT,
  UserModified INT,
  Date_Added DATE,
  Date_Modified DATE,
  CampusID INT, -- This is the foreign key, which might be null 
  CONSTRAINT BUILDING_PK PRIMARY KEY (BCode),
  CONSTRAINT BUILDING_U_BLDCode UNIQUE (BLDCode),
  CONSTRAINT BUILDING_FK_CampusID FOREIGN KEY (CampusID) REFERENCES [dbo].CAMPUS(CampusID) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE [dbo].BFLOOR
(
  FloorID INT NOT NULL IDENTITY(1,1),
  Summary NVARCHAR(MAX) NOT NULL CHECK (Summary != ''),
  TopoPlan VARCHAR(MAX) NOT NULL CHECK (TopoPlan != ''),
  FloorZ INT NOT NULL,
  BCode INT NOT NULL,
  UserAdded INT,
  UserModified INT,
  Date_Added DATE,
  Date_Modified DATE,
  CONSTRAINT FLOOR_PK UNIQUE (FloorID),
  CONSTRAINT FLOOR_U_BCode_FloorZ UNIQUE (FloorZ, BCode),
  CONSTRAINT FLOOR_FK_BCode FOREIGN KEY (BCode) REFERENCES [dbo].BUILDING(BCode) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE [dbo].POI
(
  POIID INT IDENTITY(1, 1) NOT NULL,
  POIName NVARCHAR(40) NOT NULL CHECK (POIName != ''),
  Summary NVARCHAR(MAX) NOT NULL CHECK (Summary != ''),
  x DECIMAL(15, 12) NOT NULL,
  y DECIMAL(15, 12) NOT NULL,
  FloorID INT NOT NULL,
  POIOwner NVARCHAR(30) CHECK (POIOwner != ''), -- Owner could be null
  POIType NVARCHAR(30) NOT NULL CHECK (POIType != ''),
  UserAdded INT,
  UserModified INT,
  Date_Added DATE,
  Date_Modified DATE,
  CONSTRAINT POI_PK PRIMARY KEY (POIID),
  CONSTRAINT POI_FK_FloorID FOREIGN KEY (FloorID) REFERENCES [dbo].BFLOOR(FloorID) ON UPDATE CASCADE
);

CREATE TABLE [dbo].FINGERPRINT
(
  FingerprintID INT IDENTITY(1, 1) NOT NULL,
  Date_Added DATE,
  Date_Modified DATE,
  x DECIMAL(15, 12) NOT NULL,
  y DECIMAL(15, 12) NOT NULL,
  FloorID INT,
  Level INT,
  UserAdded INT,  -- NULL at first and then inserted by trigger
  UserModified INT,
  CONSTRAINT FINGERPRINT_PK PRIMARY KEY (FingerprintID),
  CONSTRAINT FINGERPRINT_FK_FloorID FOREIGN KEY (FloorID) REFERENCES [dbo].BFLOOR(FloorID) ON UPDATE CASCADE ON DELETE CASCADE
  -- Checking whether the floor's z is the same with the fingerprint's z 
);

CREATE TABLE [dbo].ITEM
(
  Height DECIMAL(6,3) NOT NULL,
  Width DECIMAL(6,3) NOT NULL,
  ItemID INT IDENTITY(1,1) NOT NULL,
  TypeID int NOT NULL,
  FingerprintID INT NOT NULL,
  UserAdded INT,
  UserModified INT, 
  Date_Added DATE,
  Date_Modified DATE,
  CONSTRAINT ITEM_PK PRIMARY KEY (ItemID),
  CONSTRAINT ITEM_FK_TypeID FOREIGN KEY (TypeID) REFERENCES [dbo].TYPES(TypeID) ON UPDATE CASCADE,
  CONSTRAINT ITEM_FK_TypeInFingerprint FOREIGN KEY (FingerprintID) REFERENCES [dbo].FINGERPRINT(FingerprintID) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE dbo.UserID (
UserID	INT NOT NULL
CONSTRAINT PK_UserID PRIMARY KEY (UserID)
)

CREATE NONCLUSTERED INDEX FINGERPRINT_ON_ITEM ON dbo.ITEM (FingerprintID);  -- For Q12

-- TEST USER gchora01 1234

INSERT INTO dbo.USERS(FName, LName, Date_of_Birth, Gender, Username, UPassword, UserType) VALUES ('Pampos', 'Pampou', '2001-01-01', 'O', 'gchora01', '1234', 1)

-- TRIGGERS

CREATE TRIGGER dbo.Restrict_Item_Deletion ON dbo.ITEM FOR DELETE AS
BEGIN
	SET NOCOUNT ON
	BEGIN TRANSACTION 

	DECLARE @countF INT
	DECLARE @countFinI INT

	SET @countF = (SELECT COUNT(f.FingerprintID) FROM dbo.FINGERPRINT f)
	SET @countFinI = (SELECT COUNT(DISTINCT(i.FingerprintID)) FROM dbo.ITEM i WHERE i.ItemID NOT IN (SELECT ItemID FROM deleted))

	PRINT @countF
	PRINT @countFinI

	IF @countF - @countFinI > 0
    BEGIN 
      ROLLBACK TRANSACTION 
      PRINT 'This deletion leaves a fingerprint without any items'
    END
  ELSE
    BEGIN
      COMMIT TRANSACTION 
    END
END


CREATE TRIGGER dbo.Items_Insert ON dbo.ITEM AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.ITEM SET UserAdded = @user, Date_Added = GETDATE() WHERE UserAdded IS NULL  
 END

CREATE TRIGGER dbo.Items_Update ON dbo.ITEM AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.ITEM SET UserModified = @user, Date_Modified = GETDATE() WHERE ItemID IN (SELECT ItemID FROM inserted) 
 END

 CREATE TRIGGER dbo.BFLOOR_Insert ON dbo.BFLOOR AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.BFLOOR SET UserAdded = @user, Date_Added = GETDATE() WHERE UserAdded IS NULL  
 END
 
CREATE TRIGGER dbo.BFLOOR_Update ON dbo.BFLOOR AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.BFLOOR SET UserModified = @user, Date_Modified = GETDATE() WHERE BCode IN (SELECT BCode FROM inserted) 
 END
 
 CREATE TRIGGER dbo.CAMPUS_Insert ON dbo.CAMPUS AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.CAMPUS SET UserAdded = @user, Date_Added = GETDATE() WHERE UserAdded IS NULL  
 END
 
CREATE TRIGGER dbo.CAMPUS_Update ON dbo.CAMPUS AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.CAMPUS SET UserModified = @user, Date_Modified = GETDATE() WHERE CampusID IN (SELECT CampusID FROM inserted) 
 END

 CREATE TRIGGER dbo.FINGERPRINT_Insert ON dbo.FINGERPRINT AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.FINGERPRINT  SET UserAdded = @user, Date_Added = GETDATE() WHERE UserAdded IS NULL  
 END
 
CREATE TRIGGER dbo.FINGERPRINT_Update ON dbo.FINGERPRINT  AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.FINGERPRINT  SET UserModified = @user, Date_Modified = GETDATE() WHERE FingerprintID  IN (SELECT FingerprintID  FROM inserted) 
 END
 
 CREATE TRIGGER dbo.POI_Insert ON dbo.POI AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.POI  SET UserAdded = @user, Date_Added = GETDATE() WHERE UserAdded IS NULL  
 END
 
CREATE TRIGGER dbo.POI_Update ON dbo.POI  AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.POI  SET UserModified = @user, Date_Modified = GETDATE() WHERE POIID  IN (SELECT POIID  FROM inserted) 
 END

 CREATE TRIGGER dbo.TYPES_Insert ON dbo.TYPES AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.TYPES  SET UserAdded = @user, Date_Added = GETDATE() WHERE UserAdded IS NULL  
 END
 
CREATE TRIGGER dbo.TYPES_Update ON dbo.TYPES  AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.TYPES  SET UserModified = @user, Date_Modified = GETDATE() WHERE TypeID  IN (SELECT TypeID  FROM inserted) 
 END
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
  BOwner NVARCHAR(30) NOT NULL,
  RegDate DATE NOT NULL,
  UserAdded INT,
  UserModified INT,
  Date_Added DATE,
  Date_Modified DATE,
  CampusID INT, -- This is the foreign key, which might be null 
  CONSTRAINT BUILDING_PK PRIMARY KEY (BCode),
  CONSTRAINT BUILDING_FK_CampusID FOREIGN KEY (CampusID) REFERENCES [dbo].CAMPUS(CampusID) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE [dbo].BFLOOR
(
  FloorID INT NOT NULL IDENTITY(1,1),
  Summary NVARCHAR(MAX) NOT NULL CHECK (Summary != ''),
  TopoPlan VARCHAR(MAX) NOT NULL CHECK (TopoPlan != ''),
  FloorZ TINYINT NOT NULL,
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
  POIName NVARCHAR(30) NOT NULL CHECK (POIName != ''),
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
  z INT NOT NULL,
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

CREATE NONCLUSTERED INDEX FINGERPRINT_ON_ITEM ON dbo.ITEM (FingerprintID);  -- For Q12
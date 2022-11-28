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
  FName NVARCHAR(30) NOT NULL,
  LName NVARCHAR(30) NOT NULL,
  UserID INT IDENTITY (1000,2) NOT NULL,
  Date_of_Birth DATE NOT NULL,
  Gender CHAR(1) NOT NULL, -- M,F,O
  Username NVARCHAR(30)  NOT NULL,
  UPassword NVARCHAR(30) NOT NULL,
  UserType TINYINT NOT NULL, -- Root (1), Database Admin (2), Simple User(3)
  CONSTRAINT USERS_UQ_Username UNIQUE (Username),
  CONSTRAINT USERS_PK  PRIMARY KEY (UserID), 
  CONSTRAINT UserType_Type CHECK(UserType IN (0, 1, 2)),
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
  CONSTRAINT TYPES_UQ_Title_Model Unique ( Title,Model)
);

CREATE TABLE [dbo].CAMPUS
(
  CampusID INT IDENTITY(1,1) NOT NULL,
  CampusName NVARCHAR(30) NOT NULL,
  Summary NVARCHAR(MAX) NOT NULL,
  UserAdded INT,
  UserModified INT,
  Date_Added DATE,
  Date_Modified DATE,
  Website NVARCHAR(2083) NOT NULL, -- Maximum URL length
  CONSTRAINT CAMPUS_PK PRIMARY KEY (CampusID),
  CONSTRAINT CAMPUS_UQ_CampusName UNIQUE (CampusName)
);

CREATE TABLE [dbo].BUILDING
(
  BName NVARCHAR(30) NOT NULL,
  BCode INT IDENTITY(1,1) NOT NULL,
  Summary NVARCHAR(MAX) NOT NULL,
  BAddress NVARCHAR(30) NOT NULL,
  x DECIMAL(11, 8) NOT NULL, -- https://stackoverflow.com/questions/1196415/what-datatype-to-use-when-storing-latitude-and-longitude-data-in-sql-databases#:~:text=Lat%2FLong%20is%20a%20position,it%20is%20almost%20always%20WGS84.
  y DECIMAL(11, 8) NOT NULL,
  BOwner NVARCHAR(30) NOT NULL,
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
  Summary NVARCHAR(MAX) NOT NULL,
  TopoPlan VARCHAR(MAX) NOT NULL,
  FloorZ TINYINT NOT NULL,
  BCode INT NOT NULL,
  UserAdded INT,
  UserModified INT,
  Date_Added DATE,
  Date_Modified DATE,
  CONSTRAINT FLOOR_PK PRIMARY KEY (FloorZ, BCode),
  CONSTRAINT FLOOR_FK_BCode FOREIGN KEY (BCode) REFERENCES [dbo].BUILDING(BCode) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE [dbo].POI
(
  Name NVARCHAR(30) NOT NULL,
  Summary NVARCHAR(MAX) NOT NULL,
  x DECIMAL(11, 8) NOT NULL,
  y DECIMAL(11, 8) NOT NULL,
  POIID INT IDENTITY(1, 1) NOT NULL,
  Owner NVARCHAR(30), -- Owner could be null
  POIType NVARCHAR(30) NOT NULL,
  POIZ TINYINT NOT NULL,
  BCode INT NOT NULL,
  UserAdded INT,
  UserModified INT,
  Date_Added DATE,
  Date_Modified DATE,
  CONSTRAINT POI_PK PRIMARY KEY (POIID),
  CONSTRAINT POI_FK_POIZ_BCode FOREIGN KEY (POIZ, BCode) REFERENCES [dbo].BFLOOR(FloorZ, BCode) ON UPDATE CASCADE
);

CREATE TABLE [dbo].FINGERPRINT
(
  Date_Added DATE,
  Date_Modified DATE,
  x DECIMAL(11, 8) NOT NULL,
  y DECIMAL(11, 8) NOT NULL,
  z TINYINT NOT NULL,
  FingerprintID INT IDENTITY(1, 1) NOT NULL,
  FloorZ TINYINT,
  BCode INT,
  UserAdded INT,  -- NULL at first and then inserted by trigger
  UserModified INT,
  CONSTRAINT FINGERPRINT_PK PRIMARY KEY (FingerprintID),
  CONSTRAINT FINGERPRINT_FK_FloorZ_BCode FOREIGN KEY (FloorZ, BCode) REFERENCES [dbo].BFLOOR(FloorZ, BCode) ON UPDATE CASCADE,
  CONSTRAINT FINGERPRINT_CK_FloorZ CHECK (FloorZ IS NULL OR (z = FloorZ)),
  CONSTRAINT FINGERPRINT_CK_FloorZ_BCode_NOTNULL CHECK ((FloorZ IS NULL AND BCode IS NULL) OR (FloorZ IS NOT NULL AND BCode IS NOT NULL))
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
  CONSTRAINT ITEM_FK_TypeID FOREIGN KEY (TypeID) REFERENCES [dbo].TYPES(TypeID) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT ITEM_FK_TypeInFingerprint FOREIGN KEY (FingerprintID) REFERENCES [dbo].FINGERPRINT(FingerprintID) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE NONCLUSTERED INDEX FINGERPRINT_ON_ITEM ON dbo.ITEM (FingerprintID);  -- For Q12
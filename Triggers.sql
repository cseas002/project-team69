CREATE TRIGGER dbo.Restrict_Item_Deletion ON dbo.ITEM FOR DELETE AS
BEGIN
	SET NOCOUNT ON

	DECLARE @countF INT
	DECLARE @countFinI INT;
	
	SET @countF = (SELECT COUNT(f.FingerprintID) FROM dbo.FINGERPRINT f WHERE f.FingerprintID IN 
	(SELECT i2.FingerprintID FROM dbo.ITEM i2) OR f.FingerprintID IN (SELECT FingerprintID FROM deleted))
	-- All the fingerprints that have at least one item before deletion
	SET @countFinI = (SELECT COUNT(DISTINCT(i.FingerprintID)) FROM dbo.ITEM i WHERE i.ItemID NOT IN (SELECT ItemID FROM deleted))
	-- All the fingerprints that have at least one item after deletion

	--PRINT @countF
	--PRINT @countFinI
	IF @countF - @countFinI > 0
	BEGIN
      --PRINT 'This deletion leaves a fingerprint without any items';
      ROLLBACK
     END
END;

GO

CREATE TRIGGER dbo.Items_Insert ON dbo.ITEM AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID);

  DISABLE TRIGGER dbo.Items_Update ON dbo.ITEM;
  UPDATE dbo.ITEM SET UserAdded = @user WHERE UserAdded IS NULL;   
  ENABLE TRIGGER dbo.Items_Update ON dbo.ITEM;
 END
 GO
CREATE TRIGGER dbo.Items_Update ON dbo.ITEM AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.ITEM SET UserModified = @user, Date_Modified = GETDATE() WHERE ItemID IN (SELECT ItemID FROM inserted) 
 END
 GO
 CREATE TRIGGER dbo.BFLOOR_Insert ON dbo.BFLOOR AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID);

  DISABLE TRIGGER dbo.BFLOOR_Update ON dbo.BFLOOR;
  UPDATE dbo.BFLOOR SET UserAdded = @user WHERE UserAdded IS NULL;
  ENABLE TRIGGER dbo.BFLOOR_Update ON dbo.ITEM;
 END
 GO
CREATE TRIGGER dbo.BFLOOR_Update ON dbo.BFLOOR AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.BFLOOR SET UserModified = @user, Date_Modified = GETDATE() WHERE BCode IN (SELECT BCode FROM inserted) 
 END
 GO
 CREATE TRIGGER dbo.CAMPUS_Insert ON dbo.CAMPUS AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID);

  DISABLE TRIGGER dbo.CAMPUS_Update ON dbo.CAMPUS;
  UPDATE dbo.CAMPUS SET UserAdded = @user WHERE UserAdded IS NULL;  
  ENABLE TRIGGER dbo.CAMPUS_Update ON dbo.CAMPUS;
 END
 GO
CREATE TRIGGER dbo.CAMPUS_Update ON dbo.CAMPUS AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.CAMPUS SET UserModified = @user, Date_Modified = GETDATE() WHERE CampusID IN (SELECT CampusID FROM inserted) 
 END
 GO
 CREATE TRIGGER dbo.FINGERPRINT_Insert ON dbo.FINGERPRINT AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID);

  DISABLE TRIGGER dbo.FINGERPRINT_Update ON dbo.FINGERPRINT;
  UPDATE dbo.FINGERPRINT  SET UserAdded = @user WHERE UserAdded IS NULL;
  ENABLE TRIGGER dbo.FINGERPRINT_Update ON dbo.FINGERPRINT;
 END
 GO
CREATE TRIGGER dbo.FINGERPRINT_Update ON dbo.FINGERPRINT  AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.FINGERPRINT  SET UserModified = @user, Date_Modified = GETDATE() WHERE FingerprintID  IN (SELECT FingerprintID  FROM inserted) 
 END
 GO
 CREATE TRIGGER dbo.POI_Insert ON dbo.POI AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID);

  DISABLE TRIGGER dbo.POI_Update ON dbo.POI;
  UPDATE dbo.POI  SET UserAdded = @user WHERE UserAdded IS NULL;  
  ENABLE TRIGGER dbo.POI_Update ON dbo.POI;
 END
 GO
CREATE TRIGGER dbo.POI_Update ON dbo.POI  AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.POI  SET UserModified = @user, Date_Modified = GETDATE() WHERE POIID  IN (SELECT POIID  FROM inserted) 
 END
 GO
 CREATE TRIGGER dbo.TYPES_Insert ON dbo.TYPES AFTER INSERT AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID);

  ENABLE TRIGGER dbo.TYPES_Update ON dbo.TYPES;
  UPDATE dbo.TYPES  SET UserAdded = @user WHERE UserAdded IS NULL;
  DISABLE TRIGGER dbo.TYPES_Update ON dbo.TYPES;
 END
 GO
CREATE TRIGGER dbo.TYPES_Update ON dbo.TYPES  AFTER UPDATE AS
BEGIN
  DECLARE @user INT;
  SET @user = (SELECT TOP 1 * FROM dbo.UserID)

  UPDATE dbo.TYPES  SET UserModified = @user, Date_Modified = GETDATE() WHERE TypeID  IN (SELECT TypeID  FROM inserted) 
 END

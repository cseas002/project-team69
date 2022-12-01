CREATE PROCEDURE [dbo].[Q1_Advanced_Select]
@FName [nvarchar](30),
@LName [nvarchar](30), 
@UserID [int],
@Date_of_Birth [nvarchar](30),
@Gender [nvarchar](1),
@Username [nvarchar](30),
@UserType [nvarchar](1)
AS
BEGIN
	DECLARE @UserIDCheck nvarchar(20) 
	IF @UserID = 0 -- empty
		IF @Date_of_Birth = ''
		SELECT FName, LName, UserID, Gender, CAST(Date_of_Birth AS varchar) AS Date_of_Birth, Username, UserType
		FROM dbo.USERS WHERE FName LIKE '%' + @FName + '%' AND LName LIKE '%' + @LName + '%' AND Gender LIKE '%' + @Gender 
								+ '%' AND Username LIKE + '%' + @Username + '%' AND UserType LIKE + '%' + @UserType		
		ELSE
		SELECT FName, LName, UserID, Gender, CAST(Date_of_Birth AS varchar) AS Date_of_Birth, Username, UserType
		FROM dbo.USERS WHERE FName LIKE '%' + @FName + '%' AND LName LIKE '%' + @LName + '%' AND Gender LIKE '%' + @Gender 
								+ '%' AND Username LIKE + '%' + @Username + '%' AND UserType LIKE + '%' + @UserType
								AND Date_of_Birth = @Date_of_Birth
	ELSE
	IF @Date_of_Birth = ''
	SELECT FName, LName, UserID, Gender, CAST(Date_of_Birth AS varchar) AS Date_of_Birth, Username, UserType
	FROM dbo.USERS WHERE FName LIKE '%' + @FName + '%' AND LName LIKE '%' + @LName + '%' AND Gender LIKE '%' + @Gender 
							+ '%' AND Username LIKE + '%' + @Username + '%' AND UserType LIKE + '%' + @UserType	
							AND UserID = @UserID
	ELSE
	SELECT FName, LName, UserID, Gender, CAST(Date_of_Birth AS varchar) AS Date_of_Birth, Username, UserType
	FROM dbo.USERS WHERE FName LIKE '%' + @FName + '%' AND LName LIKE '%' + @LName + '%' AND Gender LIKE '%' + @Gender 
							+ '%' AND Username LIKE + '%' + @Username + '%' AND UserType LIKE + '%' + @UserType	
							AND UserID = @UserID AND Date_of_Birth = @Date_of_Birth;
END;

CREATE PROCEDURE dbo.Q1_Change_Password
@UserID int,
@UPassword nvarchar(30)
AS
UPDATE dbo.USERS SET UPassword = @UPassword WHERE UserID = @UserID;

CREATE PROCEDURE [dbo].[Q1_Delete]
@UserID int
AS
BEGIN 
DELETE FROM dbo.USERS WHERE UserID = @UserID
END;

CREATE PROCEDURE [dbo].[Q1_Edit_User]
@FName [nvarchar](30),
@LName [nvarchar](30), 
@UserID [int],
@Date_of_Birth [nvarchar](30),
@Gender [nvarchar](1),
@Username [nvarchar](30),
@UserType [nvarchar](1)
AS
BEGIN
	UPDATE dbo.USERS SET FName = @FName, LName = @LName, Date_of_Birth = @Date_of_Birth, Gender = @Gender, Username = @Username, UserType = @UserType
	WHERE UserID = @UserID;
END;

CREATE PROCEDURE [dbo].[Q1_Insert_User]
@FName [nvarchar](30),
@LName [nvarchar](30), 
@Date_of_Birth [nvarchar](30),
@Gender [nvarchar](1),
@Username [nvarchar](30),
@UPassword [nvarchar](30),
@UserType [nvarchar](1)
AS
BEGIN
	INSERT INTO dbo.USERS(FName, LName, Date_of_Birth, Gender, Username, UPassword, UserType) VALUES (@FName, @LName, @Date_of_Birth, 
							@Gender, @Username, @UPassword, @UserType)
END;

CREATE PROCEDURE [dbo].[Q1_Select]
AS
SELECT FName, LName, UserID, Gender, CAST(Date_of_Birth AS varchar) AS Date_of_Birth, Username, UserType FROM dbo.USERS;

CREATE PROCEDURE dbo.Q1_Simple_Select
@Keyword nvarchar(30)
AS
SELECT FName, LName, UserID, Gender, CAST(Date_of_Birth AS varchar) AS Date_of_Birth, Username, UserType FROM dbo.USERS WHERE FName LIKE '%' + @Keyword + '%' OR LName LIKE '%' + @Keyword + '%' OR Gender LIKE '%' + @Keyword 
						+ '%' OR Username LIKE + '%' + @Keyword + '%' OR UserType LIKE + '%' + @Keyword	
						OR Username LIKE + '%' + @Keyword + '%' OR CAST(Date_of_Birth AS nvarchar) LIKE + '%' + @Keyword + '%';

CREATE PROCEDURE dbo.Q10
AS 
BEGIN
SELECT b.FloorID 
FROM dbo.POI p JOIN dbo.BFLOOR b ON p.FloorID = b.FloorID
GROUP BY b.FloorID 
HAVING COUNT(p.POIID) > (
	SELECT 1.0 * COUNT(b2.FloorID)/ COUNT(p2.POIID) -- Average POIs amount per floor
	FROM dbo.POI p2, dbo.BFLOOR b2)
END;

CREATE PROCEDURE dbo.Q11
AS
BEGIN
	SELECT P.BCode, P.POIZ
	FROM dbo.POI as P
	GROUP BY P.BCode,P.POIZ
	HAVING COUNT(*) <= ALL (
		SELECT COUNT(*)
		FROM dbo.POI as P
		GROUP BY P.BCode,P.POIZ)
END;

CREATE PROCEDURE dbo.Q12
AS
BEGIN
	DECLARE @fingerprint int
	CREATE TABLE #temp (f1 int, f2 int)
	DECLARE c CURSOR FAST_FORWARD FOR SELECT f.FingerprintID FROM dbo.FINGERPRINT f
 
	OPEN c
	FETCH NEXT FROM c INTO @fingerprint
 
	WHILE @@FETCH_STATUS = 0
	BEGIN
		INSERT INTO #temp (f1, f2) EXEC dbo.Q12_2 @fingerprint
		FETCH NEXT FROM c INTO @fingerprint
	END
	CLOSE c
	DEALLOCATE c
	SELECT * FROM #temp
END;

CREATE PROCEDURE dbo.Q12_2
@fingerprint int
AS
BEGIN
	SELECT @fingerprint, f.FingerprintID
	FROM dbo.FINGERPRINT f
	WHERE @fingerprint != f.FingerprintID AND NOT EXISTS ((
				(SELECT i.TypeID
				FROM dbo.ITEM i
				WHERE i.FingerprintID = @fingerprint)
				EXCEPT 
				(SELECT i.TypeID
				FROM dbo.ITEM i
				WHERE i.FingerprintID = f.FingerprintID)
				)
				UNION ALL (
				(SELECT i.TypeID
				FROM dbo.ITEM i
				WHERE i.FingerprintID = f.FingerprintID)
				EXCEPT 
				(SELECT i.TypeID
				FROM dbo.ITEM i
				WHERE i.FingerprintID = @fingerprint) )
			)
END;

CREATE PROCEDURE [dbo].[Q12_Test]
AS
BEGIN
	SELECT f2.FingerprintID AS F1, f1.FingerprintID AS F2
	FROM dbo.FINGERPRINT f1 , dbo.FINGERPRINT f2
	WHERE f1.FingerprintID != f2.FingerprintID AND NOT EXISTS (
			(SELECT * 
			FROM dbo.ITEM i
			WHERE (i.TypeID IN (SELECT i2.TypeID 
								FROM dbo.ITEM i2
								WHERE i2.FingerprintID = f1.FingerprintID)
			AND i.TypeID NOT IN (
								(SELECT i3.TypeID 
								FROM dbo.ITEM i3
								WHERE i3.FingerprintID = f2.FingerprintID))	)
			-- There is a type that belongs to f1 but not in f2
			OR 
				(i.TypeID IN (SELECT i4.TypeID 
								FROM dbo.ITEM i4
								WHERE i4.FingerprintID = f2.FingerprintID)
			AND i.TypeID NOT IN (
								(SELECT i5.TypeID 
								FROM dbo.ITEM i5
								WHERE i5.FingerprintID = f1.FingerprintID))	)
			-- There is a type that belongs to f2 but not f1
			)
		)
END;

CREATE PROCEDURE dbo.Q13
@fingerprint int
AS
BEGIN
	SELECT @fingerprint AS Argument, f.FingerprintID 
	FROM dbo.FINGERPRINT f
	WHERE @fingerprint != f.FingerprintID AND NOT EXISTS (
				(SELECT i.TypeID
				FROM dbo.ITEM i
				WHERE i.FingerprintID = @fingerprint)
				EXCEPT 
				(SELECT i.TypeID
				FROM dbo.ITEM i
				WHERE i.FingerprintID = f.FingerprintID)
			
				
			)
END;

CREATE PROCEDURE dbo.[Q14]
@num int
AS
SELECT TOP (@num) I.TypeID, COUNT(DISTINCT I.FingerprintID)
FROM dbo.ITEM AS I
GROUP BY I.TypeID
ORDER BY COUNT(DISTINCT I.FingerprintID) asc;

CREATE PROCEDURE dbo.Q15
AS
SELECT I.TypeID, T.Title, T.Model
FROM dbo.ITEM I
JOIN dbo.TYPES AS T ON T.TypeID=I.TypeID
GROUP BY I.TypeID, T.Title, T.Model
HAVING COUNT(DISTINCT FingerprintID) = (SELECT COUNT(*)
FROM dbo.FINGERPRINT);

CREATE PROCEDURE dbo.Q16
@TypeID INT,
@x1 DECIMAL(15,12),
@y1 DECIMAL(15,12),
@x2 DECIMAL(15,12),
@y2 DECIMAL(15,12)
AS
SELECT *
FROM dbo.ITEM AS I
WHERE I.TypeID=@TypeID AND I.FingerprintID IN (
	SELECT F.FingerprintID
	FROM dbo.FINGERPRINT AS F
	WHERE (F.x BETWEEN @x1 AND @x2 )AND (F.y BETWEEN @y1 AND @y2)--((F.x>=@x1 AND F.x<=@x2)OR(F.x<=@x1 AND F.x>=@x2)) AND ((F.y>=@y1 AND F.y<=@y2)OR(F.y<=@y1 AND F.y>=@y2))
);


CREATE PROCEDURE dbo.Q17
@BCode INT
AS
SELECT B.BCode, MIN(P.x) AS [MIN X], MIN(P.y)AS [MIN Y], MAX(P.x)AS [MAX X], MAX(P.y)AS [MAX Y]
FROM dbo.POI AS P ,BFLOOR B
WHERE B.BCode = @BCode AND B.FloorID=P.FloorID
GROUP BY B.BCode;

CREATE PROCEDURE dbo.Q18
@x DECIMAL(15, 12),
@y DECIMAL(15, 12),
@z int
AS
BEGIN
	SELECT *
	FROM dbo.POI p JOIN dbo.BFLOOR f ON p.FloorID = f.FloorID  
	WHERE dbo.DISTANCE(p.x, p.y, f.FloorZ, @x, @y, @z) = 
					( SELECT MIN(dbo.DISTANCE(p2.x, p2.y, f2.FloorZ, @x, @y, @z))
					  FROM dbo.POI p2 JOIN dbo.BFLOOR f2 ON p2.FloorID = f2.FloorID
					)
END;

CREATE PROCEDURE dbo.Q19
@x DECIMAL(15, 12),
@y DECIMAL(15, 12),
@z int,
@k int
AS
BEGIN
	SELECT TOP (@k) *
	FROM dbo.POI p JOIN dbo.BFLOOR f ON p.FloorID = f.FloorID 
	ORDER BY dbo.DISTANCE(p.x, p.y, f.FloorZ, @x, @y, @z)
END;


CREATE PROCEDURE [dbo].[Q2_Delete]
@TypeID int
AS
BEGIN 
DELETE FROM dbo.TYPES WHERE TypeID = @TypeID
END;

CREATE PROCEDURE [dbo].[Q2_Insert]
@Title nvarchar(40),
@Model nvarchar(30)
AS
BEGIN 
INSERT INTO dbo.TYPES(Title, Model) VALUES(@Title, @Model)
END;

CREATE PROCEDURE [dbo].[Q2_Select]
AS
SELECT * FROM dbo.TYPES ORDER BY TypeID ASC;

CREATE PROCEDURE [dbo].[Q2_Update]
@Title nvarchar(40),
@Model nvarchar(30),
@TypeID int
AS
BEGIN 
UPDATE dbo.TYPES SET Title = @Title, Model = @Model WHERE TypeID = @TypeID
END;

CREATE PROCEDURE dbo.Q20
@floorID int
AS
BEGIN
	SELECT p.POIID AS [POI 1], p2.POIID AS [POI 2], dbo.DISTANCE2D(p.x, p.y, p2.x, p2.y) AS Distance
	FROM dbo.POI p, dbo.POI p2
	WHERE p.FloorID = @floorID AND p2.FloorID = @floorID -- They belong to the same floor
	AND p.POIID != p2.POIID -- And they are not the same	
	AND dbo.DISTANCE2D(p.x, p.y, p2.x, p2.y) <= ALL -- The POI with smallest distance from p
					( SELECT dbo.DISTANCE2D(p.x, p.y, p3.x, p3.y)
					  FROM dbo.POI p3
					  WHERE p.FloorID = @floorID AND p3.FloorID = @floorID AND p3.POIID != p.POIID)
	
END;

CREATE PROCEDURE [dbo].[Q21]
@x DECIMAL(20, 8)
AS
BEGIN
	SET NOCOUNT ON -- We don't want to see the rows changed
	DECLARE @fingerprint INT
	DECLARE c CURSOR LOCAL FAST_FORWARD FOR SELECT f.FingerprintID FROM dbo.FINGERPRINT f

	TRUNCATE TABLE dbo.FPassed -- We delete everything from FPassed and FValid tables
	TRUNCATE TABLE dbo.FValid  
	INSERT INTO dbo.FValid(f1, f2) -- We insert the valid destinations for each fingerprint
	-- A valid destination is a fingerprint which has distance less than @x meters from another one
			SELECT f1.FingerprintID, f2.FingerprintID
			FROM dbo.FINGERPRINT f1, dbo.FINGERPRINT f2
			WHERE f1.FingerprintID != f2.FingerprintID AND dbo.DISTANCE(f1.x, f1.y, f1.[Level], f2.x, f2.y, f2.[Level]) < @x

	OPEN c
	FETCH NEXT FROM c INTO @fingerprint -- We save the first fingerprint ID into @fingerprint variable
	DECLARE @string NVARCHAR(30) -- We declare a string variable to print the paths 
	SET @string = '' 
	WHILE @@FETCH_STATUS = 0 -- For each fingerprint
	BEGIN
		EXEC dbo.Q21_2 @fingerprint, @string -- Execute the recursive procedure
		-- This procedure finds the fingerprints that the current fingerprint can go, and recursively follows them
		-- until there is no fingerprint possible to go. A valid fingerprint is a fingerprint which has not been passed 
		-- and has distance from its previous fingerprint less than @x meters. 
		FETCH NEXT FROM c INTO @fingerprint -- We save each fingerprint ID into @fingerprint variable
	END
	CLOSE c
	DEALLOCATE c
END;

CREATE PROCEDURE [dbo].Q21_2 
-- This is the recursive procedure
@fingerprint INT, -- The origin fingerprint
@string NVARCHAR(30) -- The string to be printed
AS
BEGIN
	SET NOCOUNT ON -- We don't want to see the rows changed
	DECLARE @fingerprint2 INT -- The destination fingerprint
	DECLARE @toPrint BIT -- Value that indicates that our path is complete and needs to be printed
	-- A path is complete when we can't find a possible destination fingerprint for a fingerprint
	SET @toPrint = 1 -- We initialize this value to 1, and if there is at least one possible destination fingerprint we change this
	-- value to 0

	DECLARE c CURSOR LOCAL FAST_FORWARD FOR SELECT f2 FROM dbo.FValid WHERE f1 = @fingerprint AND f2 NOT IN (SELECT * FROM dbo.FPassed) 
	-- For each fingerprint that we did not already passed

	OPEN c
	FETCH NEXT FROM c INTO @fingerprint2

	SET @string = @string + CAST(@fingerprint AS NVARCHAR) + ' ' -- Adds the current fingerprint in the string to print

	WHILE @@FETCH_STATUS = 0
	BEGIN
		SET @toPrint = 0
		INSERT INTO dbo.FPassed(f) SELECT @fingerprint -- We now passed this fingerprint so we save it to a table 
		-- in order to restrict the next fingerprints to go back to this

		EXEC dbo.Q21_2 @fingerprint2, @string -- We now execute the procedure with fingerprint 2 as the source fingerprint

		DELETE FROM dbo.FPassed WHERE f = @fingerprint -- Remove the previous "passed" record. The other fingerprints can now
		-- pass from this. It will not be their next destination because we are currently testing this fingerprint as their next 
		-- destination (It's the current fetch).


		FETCH NEXT FROM c INTO @fingerprint2
	END
	IF @toPrint = 1
		PRINT @string
	CLOSE c
	DEALLOCATE c
END;


CREATE PROCEDURE [dbo].[Q3_DeleteFingerprint]
@FingerprintID INT
AS
DELETE FROM [dbo].FINGERPRINT
WHERE FingerprintID=@FingerprintID;

CREATE PROCEDURE [dbo].[Q3_DeleteItem]
@ItemID INT
AS
DELETE FROM [dbo].ITEM
WHERE ItemID=@ItemID;

CREATE PROCEDURE [dbo].[Q3_EditFingerprint]
@FingerprintID INT,
@x DECIMAL(15,12),
@y DECIMAL(15,12),
@level INT,
@FloorID INT,
@RegDate SMALLDATETIME
AS
UPDATE [dbo].FINGERPRINT
SET x=@x, y=@y, [Level]=@level, FloorID=@FloorID, RegDate=@RegDate
WHERE FingerprintID=@FingerprintID;

CREATE PROCEDURE [dbo].[Q3_EditItem]
@ItemID INT,
@TypeID INT,
@Height DECIMAL(6,3),
@Width DECIMAL(6,3),
@FingerprintID INT
AS
UPDATE [dbo].ITEM
SET TypeID=@TypeID, Height=@Height, Width=@Width, FingerprintID=@FingerprintID
WHERE ItemID=@ItemID;

CREATE PROCEDURE [dbo].[Q3_InsertFingerprint]
@x DECIMAL(15,12),
@y DECIMAL(15,12),
@level INT,
@FloorID INT,
@RegDate SMALLDATETIME
AS
INSERT INTO [dbo].FINGERPRINT(x, y, Level, FloorID, RegDate) VALUES (@x, @y, @level, @FloorID, @RegDate);

CREATE PROCEDURE [dbo].[Q3_InsertItem]
@TypeID INT,
@Height DECIMAL(6,3),
@Width DECIMAL(6,3),
@FingerprintID INT
AS
INSERT INTO [dbo].ITEM(TypeID, Height, Width, FingerprintID) VALUES (@TypeID, @Height, @Width, @FingerprintID);

CREATE PROCEDURE [dbo].[Q3_SelectBuildings]
AS
SELECT B.FloorID, B.FloorZ, BU.BName
FROM dbo.BFLOOR AS B
JOIN dbo.BUILDING AS BU ON BU.BCode=B.BCode;

CREATE PROCEDURE [dbo].[Q3_SelectFingerprints]
AS
SELECT *
FROM [dbo].FINGERPRINT;

CREATE PROCEDURE [dbo].[Q3_SelectFloorsByID]
@FloorID INT
AS
SELECT B.FloorID, B.FloorZ, BU.BName, B.BCode
FROM dbo.BFLOOR AS B
JOIN dbo.BUILDING AS BU ON BU.BCode=B.BCode
WHERE B.FloorID=@FloorID;

CREATE PROCEDURE [dbo].[Q3_SelectItemsOfFingerprint]
@FingerprintID INT
AS
SELECT *
FROM [dbo].ITEM AS I
WHERE I.FingerprintID = @FingerprintID;

CREATE PROCEDURE [dbo].Q4_DeleteBuilding
@BCode INT
AS
DELETE FROM dbo.BUILDING WHERE BCode=@BCode;

CREATE PROCEDURE [dbo].[Q4_DeleteFloor]
@FloorID INT
AS
DELETE FROM dbo.BFLOOR WHERE FloorID=@FloorID;

CREATE PROCEDURE [dbo].[Q4_EditBuilding]
@BCode INT,
@BLDCode NVARCHAR(30),
@BName NVARCHAR(30),
@x DECIMAL(15,12),
@y DECIMAL(15,12),
@BAddress NVARCHAR(30),
@Summary NVARCHAR(MAX),
@BOwner NVARCHAR(30),
@CampusID INT,
@RegDate NVARCHAR(30)
AS
UPDATE [dbo].[BUILDING] 
SET BLDCode=@BLDCode, BName=@BName, x=@x, y=@y, BAddress=@BAddress, Summary=@Summary, BOwner=@BOwner, CampusID=@CampusID, RegDate=@RegDate
WHERE BCode=@BCode;

CREATE PROCEDURE [dbo].[Q4_EditPOI]
@POIID INT,
@POIName NVARCHAR(30),
@Summary NVARCHAR(MAX),
@POIType NVARCHAR(30),
@POIOwner NVARCHAR(30),
@x DECIMAL(15,12),
@y DECIMAL(15,12),
@FloorID INT
AS
UPDATE [dbo].POI
SET POIName=@POIName, Summary=@Summary, POIType=@POIType, POIOwner=@POIOwner, x=@x, y=@y, FloorID=@FloorID
WHERE POIID=@POIID;

Create PROCEDURE [dbo].[Q4_GetFingerprintByID]
@FingerprintID INT
AS
SELECT * FROM dbo.FINGERPRINT AS F WHERE F.FingerprintID=@FingerprintID;

CREATE PROCEDURE [dbo].[Q4_InsertBuilding]
@BLDCode NVARCHAR(30),
@BName NVARCHAR(30),
@x DECIMAL(15,12),
@y DECIMAL(15,12),
@BAddress NVARCHAR(30),
@Summary NVARCHAR(MAX),
@BOwner NVARCHAR(30),
@CampusID INT,
@RegDate NVARCHAR(30)
AS
INSERT INTO dbo.BUILDING(BLDCode, BName, x, y, BAddress, Summary, BOwner, CampusID, RegDate) 
VALUES (@BLDCode,@BName, @x, @y, @BAddress, @Summary, @BOwner, @CampusID, @RegDate);

CREATE PROCEDURE [dbo].[Q4_InsertFloor]
@BCode INT,
@FloorZ INT,
@TopoPlan VARCHAR(MAX),
@Summary NVARCHAR(MAX)
AS
INSERT INTO dbo.BFLOOR (BCode, FloorZ, TopoPlan, Summary)
VALUES (@BCode, @FloorZ, @TopoPlan, @Summary);

CREATE PROCEDURE [dbo].[Q4_SelectBuilding]
AS
SELECT B.BAddress, B.BCode, B.BName, B.BOwner, B.CampusID, CAST(B.RegDate AS VARCHAR) AS RegDate , B.Summary, B.x, B.y, B.BLDCode
FROM [dbo].[BUILDING] AS B;

CREATE PROCEDURE [dbo].Q4_SelectCampus
AS
SELECT CampusID, CampusName FROM dbo.CAMPUS;

CREATE PROCEDURE [dbo].[Q4_SelectFingerprintsOfFloor]
@FloorID INT
AS
SELECT * FROM dbo.FINGERPRINT AS F WHERE F.FloorID=@FloorID;

CREATE PROCEDURE [dbo].[Q4_SelectFloorOfBuilding]
@BCode INT
AS
SELECT * 
FROM dbo.BFLOOR AS B
WHERE B.BCode = @BCode;

CREATE PROCEDURE [dbo].[Q4_SelectPOIsOfFloor]
@FloorID INT
AS
SELECT * FROM dbo.POI AS P WHERE P.FloorID=@FloorID;

CREATE PROCEDURE [dbo].[Q4_UpdateFloor]
@FloorID INT,
@FloorZ INT,
@TopoPlan VARCHAR(MAX),
@Summary NVARCHAR(MAX)
AS
UPDATE dbo.BFLOOR SET FloorZ=@FloorZ, TopoPlan=@TopoPlan, Summary=@Summary WHERE FloorID=@FloorID;

CREATE PROCEDURE dbo.Q6
AS
BEGIN
	SELECT t.FingerprintID, TypesAmt, x, y, z
	FROM (SELECT i.FingerprintID, COUNT(*) AS TypesAmt FROM dbo.ITEM i GROUP BY i.FingerprintID) AS t JOIN dbo.FINGERPRINT f ON t.FingerprintID = f.FingerprintID
END;

CREATE PROCEDURE dbo.Q7
AS
BEGIN
SELECT t.Title, t.Model , t.TypeID 
FROM dbo.ITEM i JOIN dbo.TYPES t ON t.TypeID = i.TypeID
GROUP BY t.Title, t.TypeID, t.Model  -- Grouping by the types
HAVING COUNT(DISTINCT i.FingerprintID) = 
		(SELECT MAX(Fing_amt.FAmt)
		 FROM (SELECT COUNT(DISTINCT i2.FingerprintID) AS FAmt
				FROM dbo.ITEM i2
				GROUP BY i2.TypeID) AS Fing_amt)
END;

CREATE PROCEDURE dbo.Q8
AS
BEGIN
	SELECT b.BCode AS Building, b.FloorZ AS [Floor in Building], COUNT(p.POIType) AS [POI Amount]
	FROM dbo.BFLOOR b , dbo.POI p 
	WHERE p.FloorID = b.FloorID 
	GROUP BY b.BCode , b.FloorZ
END;

CREATE PROCEDURE dbo.Q9
AS 
-- We are calculating the total amount of types found in fingerprints / the total amount of fingerprints
-- Types that belong to two Models (e.g. COCO chair and UCY chair) are considered different
BEGIN
	SELECT TypesInFingerprints.TypeID, TypesInFingerprints.Title, (TypesInFingerprints.TypeCount * 1.0 / Fingerprints.FingerprintsAmt) AS [Average Occurences]
	FROM (	SELECT t.TypeID, t.Title, COUNT(i.ItemID) AS TypeCount
		FROM dbo.TYPES t LEFT JOIN dbo.ITEM i ON i.TypeID = t.TypeID -- Some Types might not be found on items
		GROUP BY t.TypeID, t.Title  ) AS TypesInFingerprints, (SELECT COUNT(*) AS FingerprintsAmt FROM dbo.FINGERPRINT f ) AS Fingerprints
END;

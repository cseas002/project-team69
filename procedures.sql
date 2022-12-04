CREATE TYPE [dbo].[TableType] AS TABLE(
	[fid] [int] NULL,
	[cnt] [int] NULL
)
GO

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
SET NOCOUNT ON
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

GO
CREATE PROCEDURE dbo.Q1_Change_Password
@UserID int,
@UPassword nvarchar(30)
AS
SET NOCOUNT ON
UPDATE dbo.USERS SET UPassword = @UPassword WHERE UserID = @UserID;

GO
CREATE PROCEDURE [dbo].[Q1_Delete]
@UserID int
AS
BEGIN 
SET NOCOUNT ON
DELETE FROM dbo.USERS WHERE UserID = @UserID
END;

GO
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
SET NOCOUNT ON
	UPDATE dbo.USERS SET FName = @FName, LName = @LName, Date_of_Birth = @Date_of_Birth, Gender = @Gender, Username = @Username, UserType = @UserType
	WHERE UserID = @UserID;
END;

GO
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
SET NOCOUNT ON
	INSERT INTO dbo.USERS(FName, LName, Date_of_Birth, Gender, Username, UPassword, UserType) VALUES (@FName, @LName, @Date_of_Birth, 
							@Gender, @Username, @UPassword, @UserType)
END;

GO
CREATE PROCEDURE [dbo].[Q1_Select]
AS
SET NOCOUNT ON
SELECT FName, LName, UserID, Gender, CAST(Date_of_Birth AS varchar) AS Date_of_Birth, Username, UserType FROM dbo.USERS;

GO
CREATE PROCEDURE dbo.Q1_Simple_Select
@Keyword nvarchar(30)
AS
SET NOCOUNT ON
SELECT FName, LName, UserID, Gender, CAST(Date_of_Birth AS varchar) AS Date_of_Birth, Username, UserType FROM dbo.USERS WHERE FName LIKE '%' + @Keyword + '%' OR LName LIKE '%' + @Keyword + '%' OR Gender LIKE '%' + @Keyword 
						+ '%' OR Username LIKE + '%' + @Keyword + '%' OR UserType LIKE + '%' + @Keyword	
						OR Username LIKE + '%' + @Keyword + '%' OR CAST(Date_of_Birth AS nvarchar) LIKE + '%' + @Keyword + '%';

GO
CREATE PROCEDURE [dbo].[Q10]
AS 
BEGIN
DECLARE @COUNT1 FLOAT 
DECLARE @COUNT2 FLOAT 
DECLARE @RESULT FLOAT
SET @COUNT1=( SELECT 1.0 * COUNT(p2.POIID)  -- Average POIs amount per floor
	FROM dbo.POI p2)
SET @COUNT2=(SELECT  COUNT(b2.FloorID)
	FROM DBO.BFLOOR b2)
SET @RESULT = @COUNT1/@COUNT2 
SELECT p.FloorID , COUNT(p.POIID) AS CountAmnt
FROM dbo.POI p 
GROUP BY p.FloorID
HAVING COUNT(p.POIID) >@RESULT
END;

GO
CREATE PROCEDURE [dbo].[Q11]
AS
BEGIN
	SELECT P.FloorID
	FROM dbo.POI as P
	GROUP BY P.FloorID
	HAVING COUNT(*) <= ALL (
		SELECT COUNT(*)
		FROM dbo.POI as P
		GROUP BY P.FloorID)
END;

GO

CREATE PROCEDURE [dbo].[Q12_Test2]
AS
BEGIN
	SET NOCOUNT ON
		 
	SELECT i.FingerprintID, COUNT(DISTINCT(i.TypeID)) AS cnt
	INTO #ValidFingerprints
	FROM dbo.ITEM i
	GROUP BY i.FingerprintID
	--#ValidFingerprints has the fingerprints that have the same amount of types

	SELECT v1.FingerprintID AS f1, v2.FingerprintID AS f2
	INTO #FingerprintsCombinations 
	FROM #ValidFingerprints v1, #ValidFingerprints v2
	WHERE v1.cnt = v2.cnt AND v1.FingerprintID != v2.FingerprintID 
	--#FingerprintsCombinations has the possible fingerprints who have the same types
	
	SELECT f1 as Fingerprint1, f2 as Fingerprint2 FROM #FingerprintsCombinations 
	EXCEPT (
	SELECT f1, f2
	FROM #FingerprintsCombinations fc, dbo.TYPES t
	WHERE EXISTS (SELECT * FROM dbo.ITEM i WHERE i.TypeID = t.TypeID AND i.FingerprintID = fc.f1) 
	-- There is an item of that type that belongs to f1 
	AND NOT EXISTS (SELECT * FROM dbo.ITEM i WHERE i.TypeID = t.TypeID AND i.FingerprintID = fc.f2)
	-- But there is not an item of that type that belongs to f2
	) 
	ORDER BY Fingerprint1
	SET NOCOUNT OFF
END

GO
CREATE PROCEDURE dbo.Q13
@fingerprint int
AS
BEGIN
SET NOCOUNT ON
	CREATE TABLE #FTypes(TypeID INT)
	INSERT INTO #FTypes SELECT i.TypeID FROM dbo.ITEM i WHERE i.FingerprintID = @fingerprint
	-- The types of the specific fingerprint
	
	SELECT f.FingerprintID 
	FROM dbo.FINGERPRINT f
	WHERE f.FingerprintID != @fingerprint AND NOT EXISTS 
		(SELECT * FROM #FTypes ft WHERE ft.TypeID -- A type which belongs to the specific fingerprint
		NOT IN (
		SELECT i.TypeID FROM dbo.ITEM i WHERE i.FingerprintID = f.FingerprintID) 
		-- But doesn't belong to the other fingerprint
		)
END;

GO
CREATE PROCEDURE dbo.[Q14]
@num int
AS
SET NOCOUNT ON
SELECT TOP (@num) I.TypeID, COUNT(DISTINCT I.FingerprintID) AS cnt
FROM dbo.ITEM AS I
GROUP BY I.TypeID
ORDER BY cnt asc;
GO

CREATE PROCEDURE dbo.[Q14_2]
@k int
AS
BEGIN
SET NOCOUNT ON
CREATE TABLE #Temp(TypeID INT, cnt INT);
INSERT INTO #Temp SELECT TOP (@k) I.TypeID, COUNT(DISTINCT I.FingerprintID) 
		FROM dbo.ITEM AS I
		GROUP BY I.TypeID
		ORDER BY COUNT(DISTINCT I.FingerprintID) asc; 

	
SELECT TypeID, cnt 
FROM #Temp UNION 
		SELECT I.TypeID, COUNT(DISTINCT I.FingerprintID) AS cnt
		FROM dbo.ITEM AS I
		GROUP BY I.TypeID 
		HAVING COUNT(DISTINCT I.FingerprintID) = (SELECT TOP 1 cnt FROM #Temp ORDER BY cnt DESC) 
		-- Inserting all the occurences with the same count
		
	ORDER BY cnt ASC

SET NOCOUNT OFF
END
GO

CREATE PROCEDURE dbo.Q15
AS
SET NOCOUNT ON
SELECT I.TypeID, T.Title, T.Model
FROM dbo.ITEM I
JOIN dbo.TYPES AS T ON T.TypeID=I.TypeID
GROUP BY I.TypeID, T.Title, T.Model
HAVING COUNT(DISTINCT FingerprintID) = (SELECT COUNT(*)
FROM dbo.FINGERPRINT);

GO
CREATE PROCEDURE [dbo].[Q16]
@TypeID INT,
@x1 DECIMAL(15,12),
@y1 DECIMAL(15,12),
@x2 DECIMAL(15,12),
@y2 DECIMAL(15,12)
AS
SET NOCOUNT ON
SELECT COUNT(*) AS cnt
FROM dbo.ITEM AS I
WHERE I.TypeID=@TypeID AND I.FingerprintID IN (
	SELECT F.FingerprintID
	FROM dbo.FINGERPRINT AS F
	WHERE F.x BETWEEN @x1 AND @x2 AND F.y BETWEEN @y1 AND @y2--((F.x>=@x1 AND F.x<=@x2)OR(F.x<=@x1 AND F.x>=@x2)) AND ((F.y>=@y1 AND F.y<=@y2)OR(F.y<=@y1 AND F.y>=@y2))
);

GO
CREATE PROCEDURE [dbo].[Q17]
@BCode INT
AS
SET NOCOUNT ON
SELECT MIN(P.x) AS [MINX], MIN(P.y)AS [MINY], MAX(P.x)AS [MAXX], MAX(P.y)AS [MAXY]
FROM dbo.POI AS P ,BFLOOR B
WHERE B.BCode = @BCode AND B.FloorID=P.FloorID;

GO
CREATE PROCEDURE [dbo].[Q18]
@x DECIMAL(15, 12),
@y DECIMAL(15, 12),
@z int
AS
BEGIN
SET NOCOUNT ON
	SELECT p.POIID, p.POIName
	FROM dbo.POI p JOIN dbo.BFLOOR f ON p.FloorID = f.FloorID  
	WHERE dbo.DISTANCE(p.x, p.y, f.FloorZ, @x, @y, @z) = 
					( SELECT MIN(dbo.DISTANCE(p2.x, p2.y, f2.FloorZ, @x, @y, @z))
					  FROM dbo.POI p2 JOIN dbo.BFLOOR f2 ON p2.FloorID = f2.FloorID
					)
END;

--K first not showing more
GO
CREATE PROCEDURE dbo.Q19
@x DECIMAL(15, 12),
@y DECIMAL(15, 12),
@z int,
@k int
AS
BEGIN
	SELECT TOP (@k) POIID , POIName, CAST (dbo.DISTANCE(p.x, p.y, f.FloorZ, @x, @y, @z) AS NVARCHAR) AS Distance
	FROM dbo.POI p JOIN dbo.BFLOOR f ON p.FloorID = f.FloorID 
	ORDER BY dbo.DISTANCE(p.x, p.y, f.FloorZ, @x, @y, @z) ASC
END;

GO
CREATE PROCEDURE dbo.Q19_2
@x DECIMAL(15, 12),
@y DECIMAL(15, 12),
@z int,
@k int
AS
BEGIN
	SET NOCOUNT ON
	CREATE TABLE #Temp(POIID INT, POIName NVARCHAR(40), dis DECIMAL(15, 12));
	INSERT INTO #Temp SELECT TOP (@k) p.POIID , p.POIName , dbo.DISTANCE(p.x, p.y, f.FloorZ, @x, @y, @z) 
	FROM dbo.POI p JOIN dbo.BFLOOR f ON p.FloorID = f.FloorID 
	ORDER BY dbo.DISTANCE(p.x, p.y, f.FloorZ, @x, @y, @z) 
	
	
	SELECT POIID, POIName , CAST(dis AS NVARCHAR) AS Distance
	FROM #Temp UNION 
		SELECT p.POIID, p.POIName , dbo.DISTANCE(p.x, p.y, f.FloorZ, @x, @y, @z) AS dis
		FROM dbo.POI p JOIN dbo.BFLOOR f ON p.FloorID = f.FloorID 
		WHERE dbo.DISTANCE(p.x, p.y, f.FloorZ, @x, @y, @z) = (SELECT TOP 1 dis FROM #Temp ORDER BY dis DESC)
	ORDER BY Distance ASC
	SET NOCOUNT OFF
	
END;


GO
CREATE PROCEDURE [dbo].[Q2_Delete]
@TypeID int
AS
BEGIN 
SET NOCOUNT ON
DELETE FROM dbo.TYPES WHERE TypeID = @TypeID
END;

GO
CREATE PROCEDURE [dbo].[Q2_Insert]
@Title nvarchar(40),
@Model nvarchar(30)
AS
BEGIN 
SET NOCOUNT ON
INSERT INTO dbo.TYPES(Title, Model) VALUES(@Title, @Model)
END;

GO
CREATE PROCEDURE [dbo].[Q2_Select]
AS
SET NOCOUNT ON
SELECT * FROM dbo.TYPES ORDER BY TypeID ASC;

GO
CREATE PROCEDURE [dbo].[Q2_Update]
@Title nvarchar(40),
@Model nvarchar(30),
@TypeID int
AS
BEGIN 
SET NOCOUNT ON
UPDATE dbo.TYPES SET Title = @Title, Model = @Model WHERE TypeID = @TypeID
END;
GO

CREATE PROCEDURE dbo.Q20
@floorID int,
@k INT
AS
BEGIN
	SELECT TOP (@k) p.POIID AS [POI1], p2.POIID AS [POI2], dbo.DISTANCE2D(p.x, p.y, p2.x, p2.y) AS Distance
	FROM dbo.POI p, dbo.POI p2
	WHERE p.FloorID = @floorID AND p2.FloorID = @floorID -- They belong to the same floor
	AND p.POIID != p2.POIID -- And they are not the same	
	ORDER BY dbo.DISTANCE2D(p.x, p.y, p2.x, p2.y) ASC
	
END;
GO


CREATE PROCEDURE dbo.Q20_2
@floorID int,
@k INT
AS
BEGIN
	SET NOCOUNT ON
	CREATE TABLE #Temp(POI1 INT, POI2 INT, Distance DECIMAL(15, 12));

	INSERT INTO #Temp SELECT TOP (@k) p.POIID AS [POI1], p2.POIID AS [POI2], CAST(dbo.DISTANCE2D(p.x, p.y, p2.x, p2.y) AS NVARCHAR) AS Distance
	FROM dbo.POI p, dbo.POI p2
	WHERE p.FloorID = @floorID AND p2.FloorID = @floorID -- They belong to the same floor
	AND p.POIID != p2.POIID -- And they are not the same	
	ORDER BY dbo.DISTANCE2D(p.x, p.y, p2.x, p2.y) ASC
					  
	SELECT * FROM #Temp
	
	SELECT POI1, POI2, Distance
	FROM #Temp 
	UNION
	SELECT p.POIID AS [POI1], p2.POIID AS [POI2], dbo.DISTANCE2D(p.x, p.y, p2.x, p2.y) AS Distance
	FROM dbo.POI p, dbo.POI p2
	WHERE p.FloorID = @floorID AND p2.FloorID = @floorID -- They belong to the same floor
	AND p.POIID != p2.POIID -- And they are not the same	
	AND dbo.DISTANCE2D(p.x, p.y, p2.x, p2.y) = (SELECT TOP 1 Distance FROM #Temp ORDER BY Distance DESC)
	ORDER BY Distance ASC
	
	
	SET NOCOUNT OFF 
END;

GO


CREATE PROCEDURE dbo.Q20_N
@floorID INT,
@k INT
AS
BEGIN
	SET NOCOUNT ON
	SELECT P.POIID, P.x, P.y
	INTO #TempTab
	FROM dbo.POI as P
	WHERE P.FloorID = @floorID

	SELECT P.POIID as POI1, P1.POIID as POI2, CAST(dbo.DISTANCE2D(p.x, p.y, p1.x, p1.y) AS NVARCHAR) AS Distance
 	FROM #TempTab as P, #TempTab as P1
	WHERE P1.POIID IN (
		SELECT TOP (@k) POIID
		FROM #TempTab p2
		WHERE p2.POIID != P.POIID
		ORDER BY dbo.DISTANCE2D(P.x, P.y, p2.x, p2.y) ASC
	)
	ORDER BY POI1, POI2 ASC
	SET NOCOUNT OFF

END;

GO

CREATE PROCEDURE dbo.Q20_N2
@floorID INT,
@k INT
AS
BEGIN
	SET NOCOUNT ON
	SELECT P.POIID, P.x, P.y
	INTO #TempTab
	FROM dbo.POI as P
	WHERE P.FloorID = @floorID

	SELECT P.POIID as POI1, P1.POIID as POI2, dbo.DISTANCE2D(P.x, P.y, P1.x, P1.y) AS Distance
	INTO #TempTab2
	FROM #TempTab as P, #TempTab as P1
	WHERE P1.POIID IN (
		SELECT TOP (@k) POIID
		FROM #TempTab p2
		WHERE p2.POIID != P.POIID
		ORDER BY dbo.DISTANCE2D(P.x, P.y, p2.x, p2.y) ASC
	)

	SELECT t.POIID AS POI1, t2.POIID AS POI2, CAST(dbo.DISTANCE2D(t.x, t.y, t2.x, t2.y) AS NVARCHAR) as Distance
	FROM #TempTab t, #TempTab t2
	WHERE t.POIID != t2.POIID AND dbo.DISTANCE2D(t.x, t.y, t2.x, t2.y) IN 
	(SELECT tt2.Distance FROM #TempTab2 tt2 WHERE tt2.POI1 = t.POIID)
	ORDER BY POI1, POI2, Distance
	SET NOCOUNT OFF

END;

GO
CREATE PROCEDURE [dbo].[Q21_CTE] 
-- This is the recursive procedure
@fingerprint INT, -- The origin fingerprint
@x DECIMAL(15, 12) -- The distance
AS
BEGIN
	SET NOCOUNT ON -- We don't want to see the rows changed

	DECLARE @myTable TableType
	INSERT INTO @myTable(fid, cnt) SELECT i.FingerprintID as fid, COUNT(*) as cnt FROM dbo.ITEM i GROUP BY i.FingerprintID

	CREATE TABLE #FValid (f1 INT, f2 INT)
	INSERT INTO #FValid(f1, f2) -- We insert the valid destinations for each fingerprint
	-- A valid destination is a fingerprint which has distance less than @x meters from another one
			SELECT f1.FingerprintID, f2.FingerprintID
			FROM dbo.FINGERPRINT f1, dbo.FINGERPRINT f2
			WHERE f1.FingerprintID != f2.FingerprintID AND f1.[Level] = f2.[Level] AND
			dbo.DISTANCE(f1.x, f1.y, f1.[Level], f2.x, f2.y, f2.[Level]) < @x;
	
	
	DECLARE @emptystr NVARCHAR(MAX)
	SET @emptystr = ' ' + CAST(@fingerprint AS NVARCHAR);
	WITH FPath (f1, prev, d)
	AS
	(
	 SELECT @fingerprint, @emptystr, 0
	 
	UNION ALL
	SELECT a.f2, CASE WHEN EXISTS (SELECT * FROM #FValid t WHERE t.f1=a.f2 AND t.f2 NOT IN (SELECT value FROM STRING_SPLIT(a.prev, ' '))) THEN a.prev ELSE  a.prev + ' -1' END, a.d
	FROM(
    SELECT f.f2, fp.prev + ' ' + CAST(f.f2 AS NVARCHAR) AS prev, fp.d+1 as d
    FROM #FValid f JOIN FPath fp ON fp.f1 = f.f1 
	WHERE f.f2 NOT IN (SELECT value FROM STRING_SPLIT(fp.prev, ' ')) AND d<32
	) AS a
	)
	SELECT TOP 1000 f.prev as pth, dbo.CALCULATESUM(f.prev, @myTable) as cnt
	FROM FPath f 
	WHERE RIGHT(f.prev,2) = '-1';

	DROP TABLE #FValid
END;


GO
CREATE PROCEDURE [dbo].[Q21_N2] 
@fingerprint INT, -- The origin fingerprint
@x DECIMAL(15,12), -- The origin fingerprint
@string NVARCHAR(MAX) = ''
AS

BEGIN
SET NOCOUNT ON -- We don't want to see the rows changed
	IF @@NESTLEVEL = 1
	BEGIN
		CREATE TABLE #Result (f VARCHAR(MAX))
		CREATE TABLE #FPassed (f int)
		SELECT f1.FingerprintID as f1, f2.FingerprintID as f2
		INTO #FValid
		FROM dbo.FINGERPRINT f1, dbo.FINGERPRINT f2
		WHERE f1.FingerprintID != f2.FingerprintID AND f1.[Level]=f2.[Level] AND dbo.DISTANCE2D(f1.x, f1.y, f2.x, f2.y) < @x

	END
	IF @@NESTLEVEL < 32
	BEGIN
	
	DECLARE @fingerprint2 INT -- The destination fingerprint
	DECLARE @toPrint BIT -- Value that indicates that our path is complete and needs to be printed
	-- A path is complete when we can't find a possible destination fingerprint for a fingerprint
	SET @toPrint = 1 -- We initialize this value to 1, and if there is at least one possible destination fingerprint we change this
	-- value to 0

	DECLARE c CURSOR LOCAL FAST_FORWARD FOR SELECT f2 FROM #FValid WHERE f1 = @fingerprint AND f2 NOT IN (SELECT * FROM #FPassed) 
	-- For each fingerprint that we did not already passed

	OPEN c
	FETCH NEXT FROM c INTO @fingerprint2

	SET @string = @string + CAST(@fingerprint AS NVARCHAR) + ' ' -- Adds the current fingerprint in the string to print

	WHILE @@FETCH_STATUS = 0
	BEGIN
		SET @toPrint = 0
		INSERT INTO #FPassed(f) SELECT @fingerprint -- We now passed this fingerprint so we save it to a table 
		-- in order to restrict the next fingerprints to go back to this

		EXEC dbo.Q21_N2 @fingerprint2, @x, @string -- We now execute the procedure with fingerprint 2 as the source fingerprint

		DELETE FROM #FPassed WHERE f = @fingerprint -- Remove the previous "passed" record. The other fingerprints can now
		-- pass from this. It will not be their next destination because we are currently testing this fingerprint as their next 
		-- destination (It's the current fetch).


		FETCH NEXT FROM c INTO @fingerprint2
	END
	IF @toPrint = 1
		INSERT INTO #Result SELECT @string
	IF @@NESTLEVEL = 1
		BEGIN
		DECLARE @myTable TableType
		INSERT INTO @myTable(fid, cnt) SELECT i.FingerprintID as fid, COUNT(*) as cnt FROM dbo.ITEM i GROUP BY i.FingerprintID

		SELECT TOP 1000 R.f as pth, dbo.CALCULATESUM(R.f, @myTable) as cnt FROM #Result AS R
		END
	CLOSE c
	DEALLOCATE c
	END
	SET NOCOUNT OFF
END;

GO
CREATE PROCEDURE [dbo].[Q3_DeleteFingerprint]
@FingerprintID INT
AS
SET NOCOUNT ON
DELETE FROM [dbo].FINGERPRINT
WHERE FingerprintID=@FingerprintID;

GO
CREATE PROCEDURE [dbo].[Q3_DeleteItem]
@ItemID INT
AS
SET NOCOUNT ON
DELETE FROM [dbo].ITEM
WHERE ItemID=@ItemID;

GO
CREATE PROCEDURE [dbo].[Q3_EditFingerprint]
@FingerprintID INT,
@x DECIMAL(15,12),
@y DECIMAL(15,12),
@level INT,
@FloorID INT,
@RegDate NVARCHAR(40)
AS
SET NOCOUNT ON
UPDATE [dbo].FINGERPRINT
SET x=@x, y=@y, [Level]=@level, FloorID=@FloorID, RegDate=@RegDate
WHERE FingerprintID=@FingerprintID;

GO
CREATE PROCEDURE [dbo].[Q3_EditItem]
@ItemID INT,
@TypeID INT,
@Height DECIMAL(6,3),
@Width DECIMAL(6,3),
@FingerprintID INT
AS
SET NOCOUNT ON
UPDATE [dbo].ITEM
SET TypeID=@TypeID, Height=@Height, Width=@Width, FingerprintID=@FingerprintID
WHERE ItemID=@ItemID;

GO
CREATE PROCEDURE [dbo].[Q3_InsertFingerprint]
@x DECIMAL(15,12),
@y DECIMAL(15,12),
@level INT,
@FloorID INT,
@RegDate VARCHAR(40)
AS
SET NOCOUNT ON
INSERT INTO [dbo].FINGERPRINT(x, y, Level, FloorID, RegDate) VALUES (@x, @y, @level, @FloorID, @RegDate);

GO
CREATE PROCEDURE [dbo].[Q3_InsertItem]
@TypeID INT,
@Height DECIMAL(6,3),
@Width DECIMAL(6,3),
@FingerprintID INT
AS
SET NOCOUNT ON
INSERT INTO [dbo].ITEM(TypeID, Height, Width, FingerprintID) VALUES (@TypeID, @Height, @Width, @FingerprintID);

GO
CREATE PROCEDURE [dbo].[Q3_SelectBuildings]
AS
SET NOCOUNT ON
SELECT B.FloorID, B.FloorZ, BU.BName
FROM dbo.BFLOOR AS B
JOIN dbo.BUILDING AS BU ON BU.BCode=B.BCode;

GO
CREATE PROCEDURE [dbo].[Q3_SelectFingerprints]
AS
SET NOCOUNT ON
SELECT F.FingerprintID, F.x, F.y, F.FloorID, F.Level, CAST(F.RegDate AS VARCHAR) AS RegDate
FROM [dbo].FINGERPRINT AS F;

GO
CREATE PROCEDURE [dbo].[Q3_SelectFloorsByID]
@FloorID INT
AS
SET NOCOUNT ON
SELECT B.FloorID, B.FloorZ, BU.BName, B.BCode
FROM dbo.BFLOOR AS B
JOIN dbo.BUILDING AS BU ON BU.BCode=B.BCode
WHERE B.FloorID=@FloorID;

GO
CREATE PROCEDURE [dbo].[Q3_SelectItemsOfFingerprint]
@FingerprintID INT
AS
SET NOCOUNT ON
SELECT *
FROM [dbo].ITEM AS I
WHERE I.FingerprintID = @FingerprintID;

GO
CREATE PROCEDURE [dbo].Q4_DeleteBuilding
@BCode INT
AS
SET NOCOUNT ON
DELETE FROM dbo.BUILDING WHERE BCode=@BCode;

GO
CREATE PROCEDURE [dbo].[Q4_DeleteFloor]
@FloorID INT
AS
SET NOCOUNT ON
DELETE FROM dbo.BFLOOR WHERE FloorID=@FloorID;

GO
CREATE PROCEDURE [dbo].[Q4_DeletePOI]
@POIID INT
AS
SET NOCOUNT ON
DELETE FROM [dbo].POI
WHERE POIID=@POIID;

GO
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
SET NOCOUNT ON
UPDATE [dbo].[BUILDING] 
SET BLDCode=@BLDCode, BName=@BName, x=@x, y=@y, BAddress=@BAddress, Summary=@Summary, BOwner=@BOwner, CampusID=@CampusID, RegDate=@RegDate
WHERE BCode=@BCode;

GO
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
SET NOCOUNT ON
UPDATE [dbo].POI
SET POIName=@POIName, Summary=@Summary, POIType=@POIType, POIOwner=@POIOwner, x=@x, y=@y, FloorID=@FloorID
WHERE POIID=@POIID;

GO
CREATE PROCEDURE [dbo].[Q4_GetFingerprintByID]
@FingerprintID INT
AS
SET NOCOUNT ON
SELECT F.FloorID, B.BCode
FROM dbo.FINGERPRINT AS F 
JOIN dbo.BFLOOR AS B ON B.FloorID=F.FloorID
WHERE F.FingerprintID=@FingerprintID;

GO
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
SET NOCOUNT ON
INSERT INTO dbo.BUILDING(BLDCode, BName, x, y, BAddress, Summary, BOwner, CampusID, RegDate) 
VALUES (@BLDCode,@BName, @x, @y, @BAddress, @Summary, @BOwner, @CampusID, @RegDate);

GO
CREATE PROCEDURE [dbo].[Q4_InsertFloor]
@BCode INT,
@FloorZ INT,
@TopoPlan VARCHAR(MAX),
@Summary NVARCHAR(MAX)
AS
SET NOCOUNT ON
INSERT INTO dbo.BFLOOR (BCode, FloorZ, TopoPlan, Summary)
VALUES (@BCode, @FloorZ, @TopoPlan, @Summary);

GO
CREATE PROCEDURE [dbo].[Q4_InsertPOI]
@POIName NVARCHAR(30),
@Summary NVARCHAR(MAX),
@POIType NVARCHAR(30),
@POIOwner NVARCHAR(30),
@x DECIMAL(15,12),
@y DECIMAL(15,12),
@FloorID INT
AS
SET NOCOUNT ON
INSERT INTO [dbo].POI (POIName, Summary, POIType, POIOwner, x, y, FloorID) VALUES (@POIName, @Summary, @POIType, @POIOwner, @x, @y, @FloorID);

GO
CREATE PROCEDURE [dbo].[Q4_SelectBuilding]
AS
SET NOCOUNT ON
SELECT B.BAddress, B.BCode, B.BName, B.BOwner, B.CampusID, CAST(B.RegDate AS VARCHAR) AS RegDate , B.Summary, B.x, B.y, B.BLDCode
FROM [dbo].[BUILDING] AS B;

GO
CREATE PROCEDURE [dbo].Q4_SelectCampus
AS
SET NOCOUNT ON
SELECT CampusID, CampusName FROM dbo.CAMPUS;

GO
CREATE PROCEDURE [dbo].[Q4_SelectFingerprintsOfFloor]
@FloorID INT
AS
SET NOCOUNT ON
SELECT F.FingerprintID, F.x, F.y, F.FloorID, F.Level, CAST(F.RegDate AS VARCHAR) AS RegDate FROM dbo.FINGERPRINT AS F WHERE F.FloorID=@FloorID;

GO
CREATE PROCEDURE [dbo].[Q4_SelectFloorOfBuilding]
@BCode INT
AS
SET NOCOUNT ON
SELECT * 
FROM dbo.BFLOOR AS B
WHERE B.BCode = @BCode;

GO
CREATE PROCEDURE [dbo].[Q4_SelectPOIsOfFloor]
@FloorID INT
AS
SET NOCOUNT ON
SELECT * FROM dbo.POI AS P WHERE P.FloorID=@FloorID;

GO
CREATE PROCEDURE [dbo].[Q4_UpdateFloor]
@FloorID INT,
@FloorZ INT,
@TopoPlan VARCHAR(MAX),
@Summary NVARCHAR(MAX)
AS
SET NOCOUNT ON
UPDATE dbo.BFLOOR SET FloorZ=@FloorZ, TopoPlan=@TopoPlan, Summary=@Summary WHERE FloorID=@FloorID;

GO
CREATE PROCEDURE [dbo].[Q5_DeleteCampus]
@CampusID INT
AS
SET NOCOUNT ON
DELETE FROM [dbo].CAMPUS
WHERE CampusID=@CampusID;

GO
CREATE PROCEDURE [dbo].[Q5_EditCampus]
@CampusID INT,
@Name NVARCHAR(30),
@Summary NVARCHAR(MAX),
@Website NVARCHAR(2083),
@RegDate NVARCHAR(40)
AS
SET NOCOUNT ON
UPDATE [dbo].CAMPUS
SET CampusName=@Name, Summary=@Summary, Website=@Website, RegDate=@RegDate
WHERE CampusID=@CampusID;

GO
CREATE PROCEDURE [dbo].[Q5_GetDetailsOfBuilding]
@BCode INT
AS
SET NOCOUNT ON
SELECT BU.CampusID, BU.BName
FROM dbo.BUILDING AS BU
WHERE BU.BCode=@BCode;

GO
CREATE PROCEDURE [dbo].[Q5_GetDetailsOfFingerprint]
@FingerprintID INT
AS
SET NOCOUNT ON
SELECT F.FloorID, B.BCode, BU.CampusID
FROM dbo.FINGERPRINT AS F 
JOIN dbo.BFLOOR AS B ON B.FloorID=F.FloorID
JOIN dbo.BUILDING AS BU ON B.BCode=BU.BCode
WHERE F.FingerprintID=@FingerprintID;

GO
CREATE PROCEDURE [dbo].[Q5_GetDetailsOfFloor]
@FloorID INT
AS
SET NOCOUNT ON
SELECT BU.CampusID, BU.BName, BFL.BCode, BFL.FloorZ
FROM dbo.BFLOOR AS BFL
JOIN dbo.BUILDING AS BU ON BU.BCode=BFL.BCode
WHERE BFL.FloorID=@FloorID;

GO
CREATE PROCEDURE [dbo].[Q5_InsertCampus]
@Name NVARCHAR(30),
@Summary NVARCHAR(MAX),
@Website NVARCHAR(2083),
@RegDate NVARCHAR(40)
AS
SET NOCOUNT ON
INSERT INTO [dbo].CAMPUS (CampusName, Summary, Website, RegDate) VALUES (@Name, @Summary, @Website, @RegDate);

GO
CREATE PROCEDURE [dbo].[Q5_SelectBuildingOfCampus]
@CampusID INT
AS
SET NOCOUNT ON
SELECT B.BAddress, B.BCode, B.BName, B.BOwner, B.CampusID, CAST(B.RegDate AS VARCHAR) AS RegDate , B.Summary, B.x, B.y, B.BLDCode FROM dbo.BUILDING AS B WHERE B.CampusID=@CampusID;

GO
CREATE PROCEDURE [dbo].[Q5_SelectCampus]
AS
SET NOCOUNT ON
SELECT C.CampusID, C.CampusName, C.Summary, C.Website, CAST(C.RegDate AS VARCHAR) AS RegDate FROM dbo.CAMPUS AS C;

GO
CREATE PROCEDURE dbo.Q6
AS
BEGIN
SET NOCOUNT ON
	SELECT t.FingerprintID, TypesAmt, x, y, [Level]
	FROM (SELECT i.FingerprintID, COUNT(*) AS TypesAmt FROM dbo.ITEM i GROUP BY i.FingerprintID) AS t JOIN dbo.FINGERPRINT f ON t.FingerprintID = f.FingerprintID
END;

GO
CREATE PROCEDURE dbo.Q7
AS
BEGIN
SET NOCOUNT ON
SELECT t.Title, t.Model , t.TypeID 
FROM dbo.ITEM i JOIN dbo.TYPES t ON t.TypeID = i.TypeID
GROUP BY t.Title, t.TypeID, t.Model  -- Grouping by the types
HAVING COUNT(DISTINCT i.FingerprintID) = 
		(SELECT MAX(Fing_amt.FAmt)
		 FROM (SELECT COUNT(DISTINCT i2.FingerprintID) AS FAmt
				FROM dbo.ITEM i2
				GROUP BY i2.TypeID) AS Fing_amt)
END;
--change from previous check again for sure
-- COUNT DISTINCT POI TYPES
GO
CREATE PROCEDURE [dbo].[Q8]
AS
BEGIN
SET NOCOUNT ON
	SELECT b.FloorID,p.POIType, COUNT(DISTINCT p.POIID) AS [POI Amount]
	FROM dbo.BFLOOR b
	LEFT JOIN dbo.POI AS p ON p.FloorID=b.FloorID
	GROUP BY b.FloorID, p.POIType
	ORDER BY [POI Amount] DESC
END;


GO
CREATE PROCEDURE [dbo].[Q9]
AS 
-- We are calculating the total amount of types found in fingerprints / the total amount of fingerprints
-- Types that belong to two Models (e.g. COCO chair and UCY chair) are considered different
BEGIN
SET NOCOUNT ON
	SELECT TypesInFingerprints.TypeID, TypesInFingerprints.Title, (TypesInFingerprints.TypeCount * 1.0 / Fingerprints.FingerprintsAmt) AS [Average Occurences]
	FROM (	SELECT t.TypeID, t.Title, COUNT(i.ItemID) AS TypeCount
		FROM dbo.TYPES t LEFT JOIN dbo.ITEM i ON i.TypeID = t.TypeID -- Some Types might not be found on items
		GROUP BY t.TypeID, t.Title  ) AS TypesInFingerprints, (SELECT COUNT(*) AS FingerprintsAmt FROM dbo.FINGERPRINT f ) AS Fingerprints
	ORDER BY [Average Occurences] DESC
END;


-- END OF QUERIES

GO
CREATE PROCEDURE [dbo].Advanced_Search_BFLOOR
@FloorID INT,
@Summary [nvarchar](MAX), 
@TopoPlan [nvarchar](MAX),
@BCode INT
AS
BEGIN
SET NOCOUNT ON
	IF @FloorID = 0 -- empty
		IF @BCode = 0
		SELECT FloorID, Summary, TopoPlan, BCode
		FROM dbo.BFLOOR WHERE Summary LIKE '%' + @Summary + '%' AND TopoPlan LIKE '%' + @TopoPlan
		+ '%'
		ELSE
		SELECT FloorID, Summary, TopoPlan, BCode
		FROM dbo.BFLOOR WHERE Summary LIKE '%' + @Summary + '%' AND TopoPlan LIKE '%' + @TopoPlan
		+ '%' AND BCode = @BCode
	ELSE
	IF @BCode = 0 -- empty
	SELECT FloorID, Summary, TopoPlan, BCode
		FROM dbo.BFLOOR WHERE Summary LIKE '%' + @Summary + '%' AND TopoPlan LIKE '%' + @TopoPlan
		+ '%' AND FloorID = @FloorID
	ELSE
	SELECT FloorID, Summary, TopoPlan, BCode
		FROM dbo.BFLOOR WHERE Summary LIKE '%' + @Summary + '%' AND TopoPlan LIKE '%' + @TopoPlan
		+ '%' AND FloorID = @FloorID AND BCode = @BCode
END;

GO
CREATE PROCEDURE [dbo].Advanced_Search_BUILDING
@BCode INT, 
@BLDCode NVARCHAR(30),
@BName NVARCHAR(30),
@Summary NVARCHAR(MAX),
@BAddress NVARCHAR(30),
@x DECIMAL(15, 12),
@y DECIMAL(15, 12),
@BOwner NVARCHAR(30),
@RegDate VARCHAR(30),
@CampusID INT
AS
BEGIN
SET NOCOUNT ON
	SELECT BCode , BLDCode , BName , Summary , BAddress , x, y, BOwner , RegDate , CampusID 
		FROM dbo.BUILDING WHERE CAST(BCode AS NVARCHAR) LIKE '%' + @BCode + '%' AND BLDCode LIKE '%' + @BLDCode
		+ '%' AND Summary LIKE '%' + @Summary + '%' AND BAddress LIKE '%' + @BAddress + '%' AND CAST(x AS NVARCHAR) 
		LIKE '%' + @x + '%' AND CAST(y AS NVARCHAR) LIKE '%' + @y + '%' AND BOwner LIKE '%' + @BOwner + '%' AND 
		CAST(RegDate AS NVARCHAR) LIKE '%' + @RegDate + '%' AND CAST(CampusID AS NVARCHAR) LIKE '%' + @CampusID + '%'
END;

GO
CREATE PROCEDURE [dbo].Advanced_Search_CAMPUS
@CampusID INT, 
@CampusName NVARCHAR(30),
@Summary NVARCHAR(MAX),
@RegDate VARCHAR(30),
@Website NVARCHAR(2083)
AS
BEGIN
SET NOCOUNT ON
	SELECT CampusID , CampusName , Summary , RegDate , Website
		FROM dbo.CAMPUS WHERE CAST(CampusID AS NVARCHAR) LIKE '%' + @CampusID + '%' AND CampusName LIKE '%' + @CampusName
		+ '%' AND Summary LIKE '%' + @Summary + '%'  AND 
		CAST(RegDate AS NVARCHAR) LIKE '%' + @RegDate + '%' AND Website LIKE '%' + @Website + '%'
END;

GO
CREATE PROCEDURE [dbo].Advanced_Search_FINGERPRINT
@FingerprintID INT, 
@x DECIMAL(15, 12),
@y DECIMAL(15, 12),
@Level INT,
@RegDate VARCHAR(30),
@FloorID INT
AS
BEGIN
SET NOCOUNT ON
	SELECT FingerprintID , x, y, Level , RegDate , FloorID 
	FROM dbo.FINGERPRINT WHERE CAST(FingerprintID AS NVARCHAR) 
	LIKE '%' + @FingerprintID + '%' AND CAST(x AS NVARCHAR) 
	LIKE '%' + @x + '%' AND CAST(y AS NVARCHAR) LIKE '%' + @y + '%' AND CAST(Level AS NVARCHAR) LIKE '%' + @Level + '%' AND 
	CAST(RegDate AS NVARCHAR) LIKE '%' + @RegDate + '%' AND CAST(FloorID AS NVARCHAR) LIKE '%' + @FloorID + '%'
END;

GO
CREATE PROCEDURE [dbo].Advanced_Search_ITEM
@FingerprintID INT, 
@Height DECIMAL(6, 3),
@Width DECIMAL(6, 3),
@TypeID INT,
@ItemID INT
AS
BEGIN
SET NOCOUNT ON
	SELECT FingerprintID , Height, Width, TypeID , ItemID 
	FROM dbo.ITEM WHERE CAST(FingerprintID AS NVARCHAR) 
	LIKE '%' + @FingerprintID + '%' AND CAST(Height AS NVARCHAR) 
	LIKE '%' + @Height + '%' AND CAST(Width AS NVARCHAR) LIKE '%' + @Width + '%' AND CAST(TypeID AS NVARCHAR) 
	LIKE '%' + @TypeID + '%' AND CAST(ItemID AS NVARCHAR) LIKE '%' + @ItemID + '%'
END;

GO
CREATE PROCEDURE [dbo].Advanced_Search_POI
@POIID INT, 
@x DECIMAL(15, 12),
@y DECIMAL(15, 12),
@FloorID INT,
@POIName NVARCHAR(30),
@Summary NVARCHAR(MAX),
@POIOwner NVARCHAR(30),
@POIType NVARCHAR(30)
AS
BEGIN
SET NOCOUNT ON
	SELECT POIID , x, y, FloorID , POIName, Summary , POIOwner , POIType  
	FROM dbo.POI WHERE CAST(POIID AS NVARCHAR) 
	LIKE '%' + @POIID + '%' AND CAST(x AS NVARCHAR) 
	LIKE '%' + @x + '%' AND CAST(y AS NVARCHAR) LIKE '%' + @y + '%' AND CAST(FloorID AS NVARCHAR) 
	LIKE '%' + @FloorID + '%' AND POIName LIKE '%' + @POIName + '%' AND Summary LIKE '%' + @Summary + '%'
	AND POIOwner LIKE '%' + @POIOwner + '%' AND POIType LIKE '%' + @POITYPE + '%'
END;

GO
CREATE PROCEDURE dbo.Search_BFLOOR
@Keyword nvarchar(MAX)
AS
SET NOCOUNT ON
SELECT FloorID, Summary, TopoPlan, BCode FROM dbo.BFLOOR WHERE FloorID LIKE '%' + @Keyword + '%' OR Summary LIKE '%' + @Keyword + '%' OR TopoPlan LIKE '%' + @Keyword 
						+ '%' OR BCode LIKE + '%' + @Keyword + '%';

GO
CREATE PROCEDURE dbo.Search_BUILDING
@Keyword nvarchar(MAX)
AS
SET NOCOUNT ON
SELECT BCode , BLDCode , BName , Summary , BAddress , x, y, BOwner , RegDate , CampusID
FROM dbo.BUILDING WHERE CAST(BCode AS NVARCHAR) LIKE '%' + @Keyword + '%' OR CAST(BLDCode AS NVARCHAR) LIKE '%' 
	+ @Keyword + '%' OR BName LIKE '%' + @Keyword + '%' OR Summary LIKE + '%' + @Keyword + '%' AND BAddress LIKE + 
	'%' + @Keyword + '%' AND CAST(x AS NVARCHAR) LIKE + '%' + @Keyword + '%' AND CAST(y AS NVARCHAR) LIKE + '%' + 
	@Keyword + '%' AND BOwner LIKE + '%' + @Keyword + '%' AND RegDate LIKE + '%' + @Keyword + '%' AND 
	CAST(CampusID AS NVARCHAR) LIKE + '%' + @Keyword + '%';

GO
CREATE PROCEDURE dbo.Search_CAMPUS
@Keyword nvarchar(MAX)
AS
SET NOCOUNT ON
SELECT CampusID , CampusName , Summary , RegDate , Website
FROM dbo.CAMPUS WHERE CampusID LIKE '%' + @Keyword + '%' OR CampusName LIKE '%' + @Keyword + '%' 
OR Summary LIKE + '%' + @Keyword + '%' OR RegDate LIKE + '%' + @Keyword + '%' OR CampusID 
LIKE + '%' + @Keyword + '%';

GO
CREATE PROCEDURE dbo.Search_FINGERPRINT
@Keyword nvarchar(MAX)
AS
SET NOCOUNT ON
SELECT FingerprintID, x, y, Level, RegDate , FloorID
FROM dbo.FINGERPRINT WHERE CAST(FingerprintID AS NVARCHAR) LIKE '%' + @Keyword + '%' OR CAST(x AS NVARCHAR) LIKE '%' + @Keyword + '%' 
OR CAST(y AS NVARCHAR) LIKE + '%' + @Keyword + '%' OR RegDate LIKE + '%' + @Keyword + '%' OR CAST(FloorID AS NVARCHAR)
LIKE + '%' + @Keyword + '%';

GO
CREATE PROCEDURE dbo.Search_ITEM
@Keyword nvarchar(MAX)
AS
SET NOCOUNT ON
SELECT FingerprintID, Height, Width, TypeID, ItemID
FROM dbo.ITEM WHERE CAST(FingerprintID AS NVARCHAR) LIKE '%' + @Keyword + '%' OR CAST(Height AS NVARCHAR) LIKE '%' + @Keyword + '%' 
OR CAST(Width AS NVARCHAR) LIKE + '%' + @Keyword + '%' OR CAST(ItemID AS NVARCHAR)
LIKE + '%' + @Keyword + '%';

GO
CREATE PROCEDURE dbo.Search_POI
@Keyword nvarchar(MAX)
AS
SET NOCOUNT ON
SELECT POIID , x, y, FloorID , POIName, Summary , POIOwner , POIType 
FROM dbo.POI WHERE CAST(FloorID AS NVARCHAR) LIKE + '%' + @Keyword + '%' OR CAST(POIID AS NVARCHAR) LIKE 
'%' + @Keyword + '%' OR CAST(x AS NVARCHAR) LIKE '%' + @Keyword + '%' OR CAST(y AS NVARCHAR) LIKE + '%' + 
@Keyword + '%' OR POIName LIKE + '%' + @Keyword + '%' OR Summary LIKE + '%' + @Keyword + '%' OR POIOwner 
LIKE + '%' + @Keyword + '%' OR POIType LIKE + '%' + @Keyword + '%';

GO
CREATE PROCEDURE [dbo].[UserLogin]
@Username nvarchar(30),
@UPassword nvarchar(30)
AS
BEGIN
SET NOCOUNT ON
SELECT UserID, UserType FROM dbo.USERS WHERE Username = @Username AND UPassword = @UPassword
TRUNCATE TABLE dbo.UserID
INSERT INTO dbo.UserID SELECT UserID FROM dbo.USERS WHERE Username = @Username AND UPassword = @UPassword
SET NOCOUNT OFF
END;
-- LOG PROCEDURES

GO
CREATE PROCEDURE dbo.BFLOOR_LOG 
AS
SET NOCOUNT ON
SELECT FloorID AS ID, UserAdded , UserModified , CAST(Date_Added AS NVARCHAR) AS DateAdded , CAST(Date_Modified AS NVARCHAR) AS DateModified
FROM dbo.BFLOOR  

GO
CREATE PROCEDURE dbo.BUILDING_LOG 
AS
SET NOCOUNT ON
SELECT BCode AS ID, UserAdded , UserModified , CAST(Date_Added AS NVARCHAR) AS DateAdded , CAST(Date_Modified AS NVARCHAR) AS DateModified
FROM dbo.BUILDING

GO
CREATE PROCEDURE dbo.CAMPUS_LOG 
AS
SET NOCOUNT ON
SELECT CampusID AS ID, UserAdded , UserModified , CAST(Date_Added AS NVARCHAR) AS DateAdded , CAST(Date_Modified AS NVARCHAR) AS DateModified
FROM dbo.CAMPUS

GO
CREATE PROCEDURE dbo.FINGERPRINT_LOG 
AS
SET NOCOUNT ON
SELECT FingerprintID AS ID, UserAdded , UserModified , CAST(Date_Added AS NVARCHAR) AS DateAdded , CAST(Date_Modified AS NVARCHAR) AS DateModified
FROM dbo.FINGERPRINT 

GO
CREATE PROCEDURE dbo.ITEM_LOG 
AS
SET NOCOUNT ON
SELECT ItemID AS ID, UserAdded , UserModified , CAST(Date_Added AS NVARCHAR) AS DateAdded , CAST(Date_Modified AS NVARCHAR) AS DateModified
FROM dbo.ITEM

GO
CREATE PROCEDURE dbo.POI_LOG 
AS
SET NOCOUNT ON
SELECT POIID AS ID, UserAdded , UserModified , CAST(Date_Added AS NVARCHAR) AS DateAdded , CAST(Date_Modified AS NVARCHAR) AS DateModified 
FROM dbo.POI 

GO
CREATE PROCEDURE dbo.TYPES_LOG 
AS
SET NOCOUNT ON
SELECT TypeID AS ID, UserAdded , UserModified , CAST(Date_Added AS NVARCHAR) AS DateAdded , CAST(Date_Modified AS NVARCHAR) AS DateModified
FROM dbo.TYPES
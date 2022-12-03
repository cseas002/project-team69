CREATE FUNCTION dbo.DISTANCE(@x1 DECIMAL(15, 12), @y1 DECIMAL(15, 12), @z1 int, @x2 DECIMAL(15, 12), @y2 DECIMAL(15, 12), @z2 int)
RETURNS DECIMAL(15, 12)
AS
BEGIN
	DECLARE @distance DECIMAL(15, 12)
	SET @distance =  SQRT(POWER(@x2 - @x1, 2) + POWER(@y2 - @y1, 2) + POWER(@z2 * 3 - @z1 * 3, 2))
	RETURN @distance
END;

GO
CREATE FUNCTION dbo.DISTANCE2D(@x1 DECIMAL(15, 12), @y1 DECIMAL(15, 12), @x2 DECIMAL(15, 12), @y2 DECIMAL(15, 12))
RETURNS DECIMAL(15, 12)
AS
BEGIN
	DECLARE @distance DECIMAL(15, 12)
	SET @distance =  SQRT(POWER(@x2 - @x1, 2) + POWER(@y2 - @y1, 2))
	RETURN @distance
END;

GO
CREATE FUNCTION [dbo].[CALCULATESUM](@string NVARCHAR(MAX), @Items TableType readonly)
RETURNS INT
AS
BEGIN

DECLARE @TempT TABLE (fid INT)
INSERT INTO @TempT SELECT CAST(value AS INT) FROM string_split(@string, ' ')

DECLARE @res INT
SELECT TOP 1 @res = SUM(i.cnt) FROM @TempT as t JOIN @Items as i ON t.fid=i.fid
WHERE t.fid!=-1

RETURN @res
END;

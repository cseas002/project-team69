CREATE FUNCTION dbo.DISTANCE(@x1 DECIMAL(15, 12), @y1 DECIMAL(15, 12), @z1 int, @x2 DECIMAL(15, 12), @y2 DECIMAL(15, 12), @z2 int)
RETURNS DECIMAL(15, 12)
AS
BEGIN
	DECLARE @distance DECIMAL(15, 12)
	SET @distance =  SQRT(POWER(@x2 - @x1, 2) + POWER(@y2 - @y1, 2) + POWER(@z2 - @z1, 2))
	RETURN @distance
END;

CREATE FUNCTION dbo.DISTANCE2D(@x1 DECIMAL(15, 12), @y1 DECIMAL(15, 12), @x2 DECIMAL(15, 12), @y2 DECIMAL(15, 12))
RETURNS DECIMAL(15, 12)
AS
BEGIN
	DECLARE @distance DECIMAL(15, 12)
	SET @distance =  SQRT(POWER(@x2 - @x1, 2) + POWER(@y2 - @y1, 2))
	RETURN @distance
END;

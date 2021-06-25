USE [MIDAS_RTDB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[GetPassengersStatistics]
	@FromDate datetime,
	@ToDate datetime,
	@borderPoint smallint,
	@culture varchar(10) = 'en'
AS
BEGIN
	SET NOCOUNT ON;
SELECT
    CASE WHEN country.ID IS NULL THEN (SELECT ExternalID FROM dbo.lCountries WHERE ID=p.CitizenshipID) ELSE country.OfficialName END  AS OfficialName,
    CASE WHEN country.ID IS NULL THEN (SELECT ExternalID FROM dbo.lCountries WHERE ID=p.CitizenshipID) ELSE country.Nation END AS Nation,
    destination.OfficialName as Destination,
    p.DocumentNumber,
    p.Surname,
    p.GivenName,
    p.DateOfBirth,
    CASE WHEN Sex.ID IS NULL THEN (SELECT ExternalID FROM lSex WHERE ID=p.SexID) ELSE Sex.Abbreviation END AS Sex,
    p.TravelDate,
    p.TransportNumber,
    CASE WHEN TransportType.ID IS NULL THEN (SELECT ExternalID FROM lTransportTypes WHERE ID=p.TransportTypeID) ELSE TransportType.Description END AS TransportType,
    p.MovementDirectionID,
    CASE WHEN MovementDirection.ID IS NULL THEN (SELECT ExternalID FROM lMovementDirections WHERE ID=p.MovementDirectionID) ELSE MovementDirection.Description END AS MovementDirection,
    p.BorderPointID,
    CASE WHEN BorderPoint.ID IS NULL THEN (SELECT ExternalID FROM lBorderPoints WHERE ID=p.BorderPointID) ELSE BorderPoint.Name END AS BorderPoint,
    p.DocumentTypeID,
    p.Inspector,
    p.Note,
    p.AccomodationAddress
FROM [dbo].[PassengersStatistics] as p
    LEFT OUTER JOIN dbo.lCountriesCultures as country ON p.CitizenshipID=country.OwnerID AND country.Culture=@culture
    LEFT OUTER JOIN dbo.lCountriesCultures as destination on p.DestinationCountryID=destination.OwnerID AND destination.Culture=@culture
    LEFT OUTER JOIN dbo.lSexCultures as Sex ON p.SexID=Sex.OwnerID AND Sex.Culture=@culture
    LEFT OUTER JOIN dbo.lTransportTypesCultures as TransportType ON p.TransportTypeID=TransportType.OwnerID	AND TransportType.Culture=@culture
    LEFT OUTER JOIN dbo.lBorderPointsCultures as BorderPoint ON p.BorderPointID=BorderPoint.OwnerID AND BorderPoint.Culture=@culture
    LEFT OUTER JOIN dbo.lMovementDirectionsCultures as MovementDirection ON p.MovementDirectionID=MovementDirection.OwnerID AND MovementDirection.Culture=@culture
WHERE  p.TravelDate>=@FromDate AND p.TravelDate<=@ToDate
  AND (@borderPoint IS NULL OR p.BorderPointID = @borderPoint)
ORDER BY country.Name


END





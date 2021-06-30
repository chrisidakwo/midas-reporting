USE [MIDAS_RTDB]
GO

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[GetMovementStats]
	@culture VARCHAR(10) = 'en',
	@PeriodStart DATETIME,
	@PeriodEnd DATETIME,
	@RegionID SMALLINT,
	@borderPoint SMALLINT
AS
BEGIN
	SET NOCOUNT ON;

	DECLARE @CountryID INT

SELECT @CountryID = Value
FROM dbo.Settings
WHERE Type = 'Country';

--IsInSelectedRegion 0 = Is Foreigners
--					 1 = Is Region Citizen
--					 2 = Is Country Citizen
--                   3 = Is Foreigner (Flight)
--                   4 = Is Region (Flight)
--                   5 = Is Country (Flight)

SELECT
    BorderPointID,
    BorderPoint,
    ProvinceID,
    Province,
    RegionalSite,
    SUM(CASE WHEN IsFlight=0 THEN 1 ELSE 0 END) AS TotalPassangers,
    SUM(CASE WHEN IsFlight=0 AND Direction='Entry' THEN 1 ELSE 0 END) AS TotalEntryPassangers,
    SUM(CASE WHEN IsFlight=0 AND Direction='Exit' THEN 1 ELSE 0 END) AS TotalExitPassangers,
    SUM(CASE WHEN IsInSelectedRegion = 0 THEN 1 ELSE 0 END) AS TotalForeigners,
    SUM(CASE WHEN IsInSelectedRegion = 1 THEN 1 ELSE 0 END) AS TotalRegionCitizensWithoutCounty,
    SUM(CASE WHEN IsInSelectedRegion = 1 AND Direction='Entry' THEN 1 ELSE 0 END) AS TotalRegionCitizensWithoutCountyEntry,
    SUM(CASE WHEN IsInSelectedRegion = 1 AND Direction='Exit' THEN 1 ELSE 0 END) AS TotalRegionCitizensWithoutCountyExit,

    SUM(CASE WHEN IsInSelectedRegion = 2 THEN 1 ELSE 0 END) AS TotalCitizens,
    SUM(CASE WHEN IsInSelectedRegion = 2 AND Direction='Entry' THEN 1 ELSE 0 END) AS TotalCitizensEntry,
    SUM(CASE WHEN IsInSelectedRegion = 2 AND Direction='Exit' THEN 1 ELSE 0 END) AS TotalCitizensExit,

    SUM(CASE WHEN IsFlight=1 THEN 1 ELSE 0 END) AS TotalFlights,
    SUM(CASE WHEN IsFlight=1 AND Direction='Entry' THEN 1 ELSE 0 END) AS TotalEntryFlights,
    SUM(CASE WHEN IsFlight=1 AND Direction='Exit' THEN 1 ELSE 0 END) AS TotalExitFlights,

    SUM(CASE WHEN IsFlightPassenger = 1 AND Direction='Entry' THEN 1 ELSE 0 END) AS TotalFlightPassengersEntry,
    SUM(CASE WHEN IsFlightPassenger = 1 AND Direction='Exit' THEN 1 ELSE 0 END) AS TotalFlightPassengersExit,

    SUM(CASE WHEN IsInSelectedRegion = 3 AND IsFlight = 1 THEN 1 ELSE 0 END) AS TotalFlightForeigners,
    SUM(CASE WHEN IsInSelectedRegion = 4 AND IsFlight = 1 THEN 1 ELSE 0 END) AS TotalFlightRegionCitizensWithoutCounty,
    SUM(CASE WHEN IsInSelectedRegion = 5 AND IsFlight = 1 THEN 1 ELSE 0 END) AS TotalCountryFlight


FROM lBorderPoints (NOLOCK) INNER JOIN
     (SELECT PassengersStatistics.MovementDirectionID AS DirectionID,
           lMovementDirections.ExternalID AS Direction,
           PassengersStatistics.BorderPointID,
           ISNULL(lBorderPointsCultures.Name, lBorderPoints.ExternalID) AS BorderPoint,
           Province.OwnerID as ProvinceID,
           Province.Name as Province,
           ISNULL(lBorderPointsCultures.RegionalSite, lBorderPoints.ExternalID) AS RegionalSite,
           CASE WHEN PassengersStatistics.CitizenshipID  = @CountryID THEN 2
                 ELSE CASE WHEN lCountries.RegionID IS NOT NULL AND lCountries.RegionID = @RegionID
                               THEN 1 ELSE 0 END END IsInSelectedRegion,
           CASE WHEN TransportTypeID = 8 THEN 1 ELSE 0 END AS IsFlightPassenger,
           0 AS IsFlight
      FROM dbo.PassengersStatistics PassengersStatistics (NOLOCK)
          INNER JOIN lBorderPoints (NOLOCK) ON lBorderPoints.ID = PassengersStatistics.BorderPointID
          LEFT JOIN lBorderPointsCultures (NOLOCK) ON lBorderPointsCultures.OwnerID = lBorderPoints.ID AND lBorderPointsCultures.Culture=@culture
          INNER JOIN lMovementDirections (NOLOCK) ON lMovementDirections.ID = PassengersStatistics.MovementDirectionID
          INNER JOIN lCountries (NOLOCK) ON PassengersStatistics.CitizenshipID=lCountries.ID
          LEFT OUTER JOIN dbo.BorderPointsToProvinces as BorderProvince ON PassengersStatistics.BorderPointID = BorderProvince.BorderPointID
          LEFT OUTER JOIN dbo.lProvincesCultures as Province ON BorderProvince.ProvinceID = Province.OwnerID

      WHERE TravelDate BETWEEN @PeriodStart AND @PeriodEnd
        AND (@borderPoint IS NULL OR @borderPoint = PassengersStatistics.BorderPointID)

      UNION ALL

      SELECT DirectionID,
             lMovementDirections.ExternalID AS Direction,
             TransportCrossedBorder.BorderPointID,
             ISNULL(lBorderPointsCultures.Name, lBorderPoints.ExternalID) AS BorderPoint,
             Province.OwnerID as ProvinceID,
             Province.Name as Province,
             ISNULL(lBorderPointsCultures.RegionalSite, lBorderPoints.ExternalID) AS RegionalSite,
             CASE WHEN lCarriers.CountryID  = @CountryID THEN 5
                 ELSE CASE WHEN lCountries.RegionID IS NOT NULL AND lCountries.RegionID = @RegionID
                     THEN 4 ELSE 3 END END IsInSelectedRegion,
             -1 IsFlightPassenger,
             1 AS IsFlight
      FROM TransportCrossedBorder (NOLOCK) INNER JOIN
           Transports (NOLOCK) ON TransportCrossedBorder.TransportID = Transports.ID
          INNER JOIN lCarriers (NOLOCK) ON lCarriers.ID = Transports.CarrierID
          INNER JOIN lCountries (NOLOCK) ON lCountries.ID =lCarriers.CountryID
          INNER JOIN lBorderPoints (NOLOCK) ON lBorderPoints.ID = TransportCrossedBorder.BorderPointID
          LEFT JOIN lBorderPointsCultures (NOLOCK) ON lBorderPointsCultures.OwnerID = lBorderPoints.ID AND lBorderPointsCultures.Culture=@culture
          INNER JOIN lMovementDirections (NOLOCK) ON lMovementDirections.ID = DirectionID
          LEFT OUTER JOIN dbo.BorderPointsToProvinces as BorderProvince ON TransportCrossedBorder.BorderPointID = BorderProvince.BorderPointID
          LEFT OUTER JOIN dbo.lProvincesCultures as Province ON BorderProvince.ProvinceID = Province.OwnerID
          --LEFT JOIN lMovementDirectionsCultures ON lMovementDirectionsCultures.OwnerID=lMovementDirections.ID AND lMovementDirectionsCultures.Culture='en'
      WHERE TypeID = 8
        AND TransportCrossedBorder.Date BETWEEN @PeriodStart AND @PeriodEnd
        AND (@borderPoint IS NULL OR @borderPoint = TransportCrossedBorder.BorderPointID)) AS Statistic
     ON Statistic.BorderPointID=lBorderPoints.ID
GROUP BY BorderPointID, BorderPoint, ProvinceID, Province, RegionalSite
ORDER BY RegionalSite
END



GO



USE [MIDAS_RTDB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER PROCEDURE [dbo].[GetDoubtablePersons]
	@FromDate DATETIME,
	@ToDate DATETIME,
	@Culture NVARCHAR(2) = N'en',
	@BorderPoint SMALLINT,
	@Province SMALLINT
AS
BEGIN
	SET NOCOUNT ON;
SELECT D.GivenName,
       D.Surname,
       D.DocumentNumber,
       D.DateOfBirth,
       D.SexID,
       S.Description AS Sex,
       D.DocumentTypeID,
       DT.Description AS DocumentType,
       D.Citizenship,
       C.Name AS CountryName,
       P.TravelDate,
       P.MovementDirectionID,
       M.Description AS MovementDirection,
       BP.Name AS BorderPoint,
       P.BorderPointID,
       Province.OwnerID as ProvinceID,
       Province.Name as Province,
       ISNULL(T.Name, N'') AS TransportName
        ,DP.[PhotoJustScanned]
        ,DP.[Supervisor]
        ,DP.[SupervisorRegistrationTime]
        ,DP.[SupervisorPCNumber]
        ,DP.[Status]
        ,DP.[PersonStopID]
        ,DP.[DocumentStopID]
        ,DP.[InspectorsNote]
        ,DP.[SupervisorsNote]
        ,DP.[PerformedActionID]
        ,A.Description AS PerformedAction
        ,DP.[CheckingReason]
FROM   dbo.DoubtablePersons DP INNER JOIN
       dbo.PassportRegistrationData P ON DP.[PassportRegistrationID] = P.ID INNER JOIN
       dbo.Documents D ON D.ID = P.DocumentID LEFT OUTER JOIN
       dbo.Vehicles V ON P.TransportCrossBorderID = V.ID LEFT OUTER JOIN
       dbo.TransportCrossedBorder TCB ON V.ID IS NULL AND P.TransportCrossBorderID = TCB.ID LEFT OUTER JOIN
       dbo.Transports T ON V.ID IS NULL AND TCB.TransportID = T.ID LEFT OUTER JOIN
       dbo.lActionsCultures A ON DP.PerformedActionID = A.OwnerID AND A.Culture = @Culture LEFT JOIN
       dbo.lDocumentTypesCultures DT ON D.DocumentTypeID = DT.OwnerID AND DT.Culture = @Culture LEFT JOIN
       dbo.lCountriesCultures C ON C.OwnerID = D.Citizenship AND C.Culture = @Culture LEFT JOIN
       dbo.lBorderPointsCultures BP ON BP.OwnerID = P.BorderPointID AND BP.Culture = @Culture LEFT JOIN
       dbo.lSexCultures S ON S.OwnerID = D.SexID  AND S.Culture = @Culture LEFT JOIN
       dbo.lMovementDirectionsCultures M ON M.OwnerID = P.MovementDirectionID AND M.Culture = @Culture LEFT OUTER JOIN
       dbo.BorderPointsToProvinces ON BP.OwnerID = BorderPointsToProvinces.BorderPointID LEFT OUTER JOIN
       dbo.lProvincesCultures Province ON BorderPointsToProvinces.ProvinceID = Province.OwnerID
WHERE  (@BorderPoint IS NULL OR P.BorderPointID = @BorderPoint) AND
        (@Province IS NULL OR BorderPointsToProvinces.ProvinceID = @Province) AND
        P.TravelDate >= @FromDate AND
        P.TravelDate <= @ToDate
ORDER BY P.TravelDate DESC
END

USE [MIDAS_RTDB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[GetCountryByName]
    @culture varchar(10) = 'en',
    @countryName varchar(25) = ''
AS
BEGIN
    SET NOCOUNT ON;

    SELECT dbo.lCountries.ID,
           @culture                                                                                                   as Culture,
           CASE
               WHEN dbo.lCountriesCultures.ID IS NULL THEN dbo.lCountries.ID
               ELSE dbo.lCountriesCultures.OwnerID END                                                                AS OwnerID,
           CASE
               WHEN dbo.lCountriesCultures.ID IS NULL THEN dbo.lCountries.ExternalID
               ELSE dbo.lCountriesCultures.Name END                                                                   AS [Name],
           CASE
               WHEN dbo.lCountriesCultures.ID IS NULL THEN dbo.lCountries.ExternalID
               ELSE dbo.lCountriesCultures.Nation END                                                                 AS [Nation]
    FROM dbo.lCountries
             LEFT OUTER JOIN
         dbo.lCountriesCultures ON dbo.lCountries.ID = dbo.lCountriesCultures.OwnerID AND Culture = @culture
    WHERE dbo.lCountriesCultures.Name like '%' + @countryName + '%';

END

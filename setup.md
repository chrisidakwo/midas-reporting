## Database
- Ensure to run migration and any seeder or command that seeds data
- Update StoredProceedure `dbo.GetMovementStatistics` so that when comparing the `@PeriodStart` and `@PeriodEnd` data values, we use the `DateDiff(dd, @date1, @date2)` function 

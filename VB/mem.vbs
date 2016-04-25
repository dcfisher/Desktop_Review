strComputer = "."
Set objWMIService = GetObject("winmgmts:\\" & strComputer & "\root\cimv2")
Set colItems = objWMIService.ExecQuery("Select * from Win32_PerfFormattedData_PerfOS_Memory",,48)
GB = 1024 *1024 * 1024
For Each objItem in colItems
   
    Wscript.Echo "Memory: " & Round(objItem.CommitLimit / GB,3)
    
Next
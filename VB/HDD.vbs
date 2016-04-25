strComputer = "."

Set objWMIService = GetObject("winmgmts:\\" _
& strComputer & "\root\cimv2")
Set colItems = objWMIService.ExecQuery(_
"Select * from Win32_DiskDrive")

For Each objItem in colItems
Wscript.Echo "HDD: " & Int(objItem.Size /(1073741824)) & " GB"
Next


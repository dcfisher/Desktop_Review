<<<<<<< HEAD
strComputer = "."

Set objWMIService = GetObject("winmgmts:\\" _
& strComputer & "\root\cimv2")
Set colItems = objWMIService.ExecQuery(_
"Select * from Win32_DiskDrive")

For Each objItem in colItems
Wscript.Echo "HDD: " & Int(objItem.Size /(1073741824)) & " GB"
Next

=======
strComputer = "."

Set objWMIService = GetObject("winmgmts:\\" _
& strComputer & "\root\cimv2")
Set colItems = objWMIService.ExecQuery(_
"Select * from Win32_DiskDrive")

For Each objItem in colItems
Wscript.Echo "HDD: " & Int(objItem.Size /(1073741824)) & " GB"
Next

>>>>>>> 4a1344fc1996509d36c4d512e72ddf72e3743505

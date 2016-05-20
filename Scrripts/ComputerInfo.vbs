strComputer = "."
Set objWMIService = GetObject("winmgmts:" _
    & "{impersonationLevel=impersonate}!\\" & strComputer & "\root\cimv2")

Set colSettings = objWMIService.ExecQuery _
    ("Select * from Win32_ComputerSystem")
For Each objComputer in colSettings 
    Wscript.Echo "System Name: " & objComputer.Name
    Wscript.Echo "System Manufacturer: " & objComputer.Manufacturer
    Wscript.Echo "System Model: " & objComputer.Model
Next

Set colItems = objWMIService.ExecQuery( _
    "SELECT * FROM Win32_ComputerSystemProduct") 
For Each objItem in colItems 
    Wscript.Echo "Serial Number: " & objItem.IdentifyingNumber
Next
Set objWMIService = GetObject("winmgmts:\\" & strComputer & "\root\cimv2")

Set obj = GetObject("winmgmts:").InstancesOf("Win32_PhysicalMemory") 
i = 1
For Each obj2 In obj 
memTmp1 = obj2.capacity / 1024 / 1024 / 1024
TotalRam = TotalRam + memTmp1 
i = i +1
Next 
wscript.echo "Memory: " & TotalRam & " GB"
Set objWMIService = GetObject("winmgmts:" _
    & "{impersonationLevel=impersonate}!\\" & strComputer & "\root\cimv2")

Set colItems = objWMIService.ExecQuery("Select * from Win32_Processor")

For Each objItem in colItems
    Wscript.Echo "Processor: " & objItem.Name
Next
Set objWMIService = GetObject("winmgmts:\\" _
& strComputer & "\root\cimv2")
Set colItems = objWMIService.ExecQuery(_
"Select * from Win32_DiskDrive")

For Each objItem in colItems
Wscript.Echo "HDD: " & Int(objItem.Size /(1073741824)) & " GB"
Next
Set objWMIService = GetObject("winmgmts:" _
    & "{impersonationLevel=impersonate}!\\" _
    & strComputer & "\root\cimv2")
Set colSettings = objWMIService.ExecQuery _
    ("Select * from Win32_ComputerSystem")
For Each objComputer in colSettings 
    Wscript.Echo "System Name: " & objComputer.Name
    Wscript.Echo "Domain: " & objComputer.Domain
Next
Dim WshShell, blnOffice64, strOutlookPath, regValue
Set WshShell = WScript.CreateObject("WScript.Shell")
blnOffice64=False
On Error Resume Next
regValue = WshShell.RegRead("HKLM\SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\outlook.exe\Path")
If WshShell.ExpandEnvironmentStrings("%PROCESSOR_ARCHITECTURE%") = "AMD64" And instr(regValue, "x86") > 0 then 
  blnOffice64=False
  wscript.echo "Office Match: No"
ElseIf WshShell.ExpandEnvironmentStrings("%PROCESSOR_ARCHITECTURE%") = "AMD64" And instr(regValue,"Program Files") Then
  wscript.echo "Office Match: Yes"
ElseIF WshShell.ExpandEnvironmentStrings("%PROCESSOR_ARCHITECTURE%") = "x86" And instr(regValue,"Program Files") Then
  wscript.echo "Office Match: Yes"
Else
  wscript.echo "Office Match: N/A"
End If

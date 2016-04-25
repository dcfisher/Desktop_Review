<<<<<<< HEAD
Dim WshShell, blnOffice64, strOutlookPath
Set WshShell = WScript.CreateObject("WScript.Shell")
blnOffice64=False
Set oReg = GetObject("winmgmts:{impersonationLevel=impersonate}!\\.\root\default:StdRegProv")

If oReg.EnumKey(HKEY_LOCAL_MACHINE, "SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\outlook.exe\Path\", "", "") = 0 Then

If WshShell.ExpandEnvironmentStrings("%PROCESSOR_ARCHITECTURE%") = "AMD64" And _
    not instr("HKLM\SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\outlook.exe\Path", "x86") > 0 then 
  blnOffice64=True
  wscript.echo "Office Match: Yes"
ElseIf WshShell.ExpandEnvironmentStrings("%PROCESSOR_ARCHITECTURE%") = "AMD64" Then
  wscript.echo "Office Match: No"
Else 
  wscript.echo "Office Match: Yes"
End If
Else
  wscript.echo "Office Match: N/A"
=======
Dim WshShell, blnOffice64, strOutlookPath
Set WshShell = WScript.CreateObject("WScript.Shell")
blnOffice64=False
Set oReg = GetObject("winmgmts:{impersonationLevel=impersonate}!\\.\root\default:StdRegProv")

If oReg.EnumKey(HKEY_LOCAL_MACHINE, "SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\outlook.exe\Path\", "", "") = 0 Then

If WshShell.ExpandEnvironmentStrings("%PROCESSOR_ARCHITECTURE%") = "AMD64" And _
    not instr("HKLM\SOFTWARE\Microsoft\Windows\CurrentVersion\App Paths\outlook.exe\Path", "x86") > 0 then 
  blnOffice64=True
  wscript.echo "Office Match: Yes"
ElseIf WshShell.ExpandEnvironmentStrings("%PROCESSOR_ARCHITECTURE%") = "AMD64" Then
  wscript.echo "Office Match: No"
Else 
  wscript.echo "Office Match: Yes"
End If
Else
  wscript.echo "Office Match: N/A"
>>>>>>> 4a1344fc1996509d36c4d512e72ddf72e3743505
End If
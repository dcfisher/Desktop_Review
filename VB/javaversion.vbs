<<<<<<< HEAD
dim arr()

redim arr(0)
i = 0

sFolder = "C:\Program Files\Java"

Set fso = CreateObject("Scripting.FileSystemObject")
Set f = fso.GetFolder(sFolder)  
Set fc = f.Files
Set ff = f.SubFolders  

For Each f1 in ff
  redim preserve arr(i)
  arr(i) = f1.name
  i = i+1
Next  

For Each f1 in fc
  redim preserve arr(i)
  arr(i) = f1.name
  i = i+1
=======
dim arr()

redim arr(0)
i = 0

sFolder = "C:\Program Files\Java"

Set fso = CreateObject("Scripting.FileSystemObject")
Set f = fso.GetFolder(sFolder)  
Set fc = f.Files
Set ff = f.SubFolders  

For Each f1 in ff
  redim preserve arr(i)
  arr(i) = f1.name
  i = i+1
Next  

For Each f1 in fc
  redim preserve arr(i)
  arr(i) = f1.name
  i = i+1
>>>>>>> 4a1344fc1996509d36c4d512e72ddf72e3743505
Next  
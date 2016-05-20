#! /bin/sh

# get the major version of the OS and store it in osver
osver=`sw_vers -productVersion`

# outpust the OS version to the terminal window, so the tech can see that the script got it right
echo "OS X version" $osver "was detected."
echo "Please wait...."

# if statement to determine which review script to use based on OS version. 10.10.3-10.7.3 and 10.6.8 uses review script script 1.
if [ $osver = 10.10 ] || [ $osver = 10.10.1 ] || [ $osver = 10.10.2 ] || [ $osver = 10.10.3 ] || [ $osver = 10.10.4 ] || [ $osver = 10.10.5 ]|| [ $osver = 10.9 ] || [ $osver = 10.9.1 ] || [ $osver = 10.9.2 ] || [ $osver = 10.9.3 ] || [ $osver = 10.9.4 ] || [ $osver = 10.9.5 ] || [ $osver = 10.8 ] || [ $osver = 10.8.1 ] || [ $osver = 10.8.4 ] || [ $osver = 10.8.2 ] || [ $osver = 10.8.3 ] || [ $osver = 10.8.5 ] || [ $osver = 10.7.1 ] || [ $osver = 10.7.2 ] || [ $osver = 10.7.3 ] || [ $osver = 10.7.4 ] || [ $osver = 10.7.5 ]|| [ $osver = 10.6.8 ] ; then
	revscpt="1"
# if statemant to determine which review script to use based on OS version. 10.6.1-10.6.7 uses review script 2.	
elif [ $osver = 10.6.1 ] || [ $osver = 10.6.2 ] || [ $osver = 10.6.3 ] || [ $osver = 10.6.4 ] || [ $osver = 10.6.5 ] || [ $osver = 10.6.6 ] || [ $osver = 10.6.7 ] ; then
	revscpt="2"
# if statemant to determine which review script to use based on OS version. 10.5.1-10.5.8 uses review script 2.
elif [ $osver = 10.5.1 ] || [ $osver = 10.5.2 ] || [ $osver = 10.5.3 ] || [ $osver = 10.5.4 ] || [ $osver = 10.5.5 ] || [ $osver = 10.5.6 ] || [ $osver = 10.5.7 ] || [ $osver = 10.5.8 ] ; then
	revscpt="2" && echo "Unsuported OS"
# if statement to create review file for OS 10.3, an unsupported OS.
elif [ $osver = 10.4.1 ] || [ $osver = 10.4.2 ] || [ $osver = 10.4.3 ] || [ $osver = 10.4.4 ] || [ $osver = 10.4.5 ] || [ $osver = 10.4.6 ] || [ $osver = 10.4.7 ] || [ $osver = 10.4.8 ] || [ $osver = 10.4.9 ] || [ $osver = 10.4.10 ] || [ $osver = 10.4.11 ] ; then
    revscpt="3" && echo "Unsuported OS"
# if the OS version is older than 10.3 or higher than 10.8. The script will not try to pull information about the machine. 
else 
	echo "Unsupported OS"
fi 

# Determine which Version of Office is installed on the computer if any
if [ -f /Applications/Microsoft\ Office\ 2011/Microsoft\ Word.app/Contents/info.plist ] ; then
	cat /Applications/"Microsoft Office 2011"/"Microsoft Word.app"/Contents/info.plist >> /users/`whoami`/desktop/office2011.txt;
elif [ -f /Applications/Microsoft\ Office\ 2008/Microsoft\ Word.app/Contents/info.plist ] ; then
	cat /Applications/"Microsoft Office 2008"/"Microsoft Word.app"/Contents/info.plist >> /users/`whoami`/desktop/office2008.txt;
elif [ -f /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.6\ Update\ Log.txt ]; then
# These are else if statements to verify which version of Office 2004 is installed on the computer
	cp /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.6\ Update\ Log.txt /users/`whoami`/desktop;
elif [ -f /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.5\ Update\ Log.txt ]; then
	cp /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.5\ Update\ Log.txt /users/`whoami`/desktop;
elif [ -f /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.4\ Update\ Log.txt ]; then
	cp /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.4\ Update\ Log.txt /users/`whoami`/desktop;
elif [ -f /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.3\ Update\ Log.txt ]; then
	cp /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.3\ Update\ Log.txt /users/`whoami`/desktop;
elif [ -f /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.2\ Update\ Log.txt ]; then
	cp /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.2\ Update\ Log.txt /users/`whoami`/desktop;
elif [ -f /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.1\ Update\ Log.txt ]; then
	cp /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.1\ Update\ Log.txt /users/`whoami`/desktop;
elif [ -f /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.0\ Update\ Log.txt ]; then
	cp /Applications/Microsoft\ Office\ 2004/Updater\ Logs/11.6.0\ Update\ Log.txt /users/`whoami`/desktop;
else		
		echo "No office Found"
fi

cd /users/`whoami`/desktop/;

if [ $revscpt = 1 ] ; then
# This is script number 1.  It is used for 10.7-10.8
	# looks for the version of OS X that the system is running and writes it to review.txt
	system_profiler SPSoftwareDataType | grep -w "System Version:" > review.txt;
	# looks for the type of computer and outputs it to review.txt
	system_profiler SPHardwareDataType | grep "Model Name:" >> review.txt;
	# Looks for the Serial Number
	system_profiler SPHardwareDataType | grep -w "Serial Number" >> review.txt;
	# looks for the type of processor and outputs it to review.txt
	system_profiler SPHardwareDataType | grep "Processor Name:" >> review.txt;
	# looks for the speed of the processor and outputs it to review.txt
	system_profiler SPHardwareDataType | grep -w "Processor Speed:" >> review.txt;
	# looks for the amount of RAM and outputs it to review.txt
	system_profiler SPHardwareDataType | grep "Memory:" >> review.txt;
	# gets the amount of space in gigabytes on the boot drive and outputs it to review.txt
	system_profiler SPStorageDataType | grep "Capacity:" >> review.txt;
	java -version 2>> review.txt
	echo “Enter Computer Name ex KL202HELP, if lab us a unique number at the end:”
	read name
	echo “Enter Jack Password”
	sudo scutil --set HostName $name
	# looks for the name of the computer and outputs it to review.txt
	echo "Computer Name: " $name>> review.txt;

elif [ $revscpt = 2 ] ; then
# This is script number 2.  It is used for both 10.5 and 10.6
	# looks for the version of OS X that the system is running and writes it to review.txt
	more profile.txt | grep -w "System Version" > review.txt;
	# looks for the type of computer and outputs it to review.txt
	more profile.txt | grep -w "Model Name" >> review.txt;
	# looks for the name of the computer and outputs it to review.txt
	more profile.txt | grep -w "Computer Name" >> review.txt;
	# looks for the type of processor and outputs it to review.txt
	more profile.txt | grep -w "Processor Name" >> review.txt;
	# looks for the speed of the processor and outputs it to review.txt
	more profile.txt | grep -w "Processor Speed" >> review.txt;
	# looks for the amount of RAM and outputs it to review.txt
	more profile.txt | grep -w "Memory:" >> review.txt;
	# looks for the name of the root drive and outputs it to review.txt
	more profile.txt | grep -w "Boot Volume" >> review.txt;
	# outputs the Graphics/Display info into review.txt
	system_profiler SPDisplaysDataType >> review.txt
	# gets the amount of space in gigabytes on the boot drive and outputs it to review.txt
	df -g | grep -w -B 1 "/" >> review.txt;
	# Checks to see what version of Office is being run on the computer
	if [ -f /Users/`whoami`/Desktop/office2011.txt ]; then
	echo "Office 2011" >> review.txt;
		cat office2011.txt | grep "14.*.*" | head -1 >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/office2008.txt ]; then
		echo "Office 2008" >> review.txt;
		cat office2008.txt | grep "12.*.*" | head -1 >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.6\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.6" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.5\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.5" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.4\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.4" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.3\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.3" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.2\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.2" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.1\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.1" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.0\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.0" >> review.txt;
	else
		echo "No Office" >> review.txt;
	fi
	# looks if there is a drive to burn discs in the machine and outputs it to review.txt
	more profile.txt | grep -w -A 2 "Disc Burning" >> review.txt;
	# End of script 2

elif [ $revscpt = 3 ] ; then
# This is script number 3
	# looks for the version of OS X that the system is running and writes it to review.txt
	more profile.txt | grep -w "System Version" > review.txt;
	# looks for the name of the computer and outputs it to review.txt
	more profile.txt | grep -w "Machine Name" >> review.txt;
	# looks for the type of processor and outputs it to review.txt
	more profile.txt | grep -w "Processor Name" >> review.txt;
	# looks for the type of processor and outputs it to review.txt
	more profile.txt | grep -w "CPU Type" >> review.txt;
	# gets the number of CPUs and their speed and outputs it to review.txt
	more profile.txt | grep -w "Number Of CPUs" >> review.txt;
	# looks for the amount of RAM and outputs it to review.txt
	more profile.txt | grep -w "Memory:" >> review.txt;
	# looks for the name of the root drive and outputs it to review.txt
	more profile.txt | grep -w "Boot Volume" >> review.txt;
	# Checks what version of Office is on the computer
	if [ -f /Users/`whoami`/Desktop/office2011.txt ]; then
	echo "Office 2011" >> review.txt;
		cat office2011.txt | grep "14.*.*" | head -1 >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/office2008.txt ]; then
		echo "Office 2008" >> review.txt;
		cat office2008.txt | grep "12.*.*" | head -1 >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.6\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.6" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.5\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.5" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.4\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.4" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.3\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.3" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.2\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.2" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.1\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.1" >> review.txt;
	elif [ -f /Users/`whoami`/Desktop/11.6.0\ Update\ Log.txt ]; then
		echo "Office 2004" >> review.txt;
		echo "Version 11.6.0" >> review.txt;
	else
		echo "No Office" >> review.txt;
	fi
	# gets the amount of space in gigabytes on the root drive and outputs it to review.txt
	df -g | grep -w -B 1 "/" >> review.txt;
	# looks if there is a drive to burn discs in the machine and outputs it to review.txt
	more profile.txt | grep -w -A 2 "Disc Burning" >> review.txt;
# End of script 3

# elif [ $revscpt = 4 ] ; then
# # This is script number 4. 
# 	# looks for the version of OS X that the system is running and writes it to review.txt
# 	more profile.txt | grep -w "System Version" > review.txt;
# 	# looks for the name of the computer and outputs it to review.txt
# 	more profile.txt | grep -w "Computer Name" >> review.txt;
# 	# looks for the type of processor and outputs it to review.txt
# 	more profile.txt | grep -w "CPU Type" >> review.txt;
# 	# gets the number of CPUs and their speed and outputs it to review.txt
# 	more profile.txt | grep -w "Number Of CPUs" >> review.txt;
# 	# looks for the amount of RAM and outputs it to review.txt
# 	more profile.txt | grep -w "Memory:" >> review.txt;
# 	# looks for the name of the root drive and outputs it to review.txt
# 	more profile.txt | grep -w "Boot Volume" >> review.txt;
# 	# gets the amount of space in gigabytes on the root drive and outputs it to review.txt
# 	df -g | grep -w -B 1 "/" >> review.txt;
# 	# looks if there is a drive to burn discs in the machine and outputs it to review.txt
# 	more profile.txt | grep -w "Disc Burning" >> review.txt;
# 	# looks if there is a drive to burn discs in the machine and outputs its type to review.txt
# 	more profile.txt | grep -w "Drive Type" >> review.txt;
# # end of script 4

fi
# this is the end of the different scripts for gathering data

#if [ $osver = 10.7.1 ] || [ $osver = 10.7.2 ] || [ $osver = 10.7.3 ] || [ $osver = 10.6.8 ] ; then rm profile.txt

#else rm /profile.txt


rm -rf  /users/`whoami`/desktop/temp;

# This next step checks to see if we have SAV installed
if [ -e /Applications/"Symantec AntiVirus" ] ; then
	open /Applications/"Symantec Solutions"/"Symantec AntiVirus.app";
	# this opens the folder containing LiveUpdate.  The tech will start and run the app from there
	echo "SAV RUN" >> review.txt;
	# Writes a line to the main output file to tell that SAV is installed and updated 
elif [ -e /Applications/"Symantec Solutions"/"Symantec Endpoint Protection.app" ] ; then
	open /Applications/"Symantec Solutions"/"Symantec Endpoint Protection.app" ;
	# this opens the folder containing LiveUpdate.  The tech will start and run the app from there
	echo "SAV RUN" >> review.txt;
	# Writes a line to the main output file to tell that SAV is installed and updated 
else
	echo "SAV/SEP is not installed. No action needed."
	# If SAV is not installed then we print that out to the terminal to let the Tech know
fi
# end of SAV fun

# open Apple Software Update
open -a "Software Update"
echo "Ensure all updates have been completed before posting data."

# opens the default web browser to the post data screen
open "http://utsmini1.kl.oakland.edu/desktopreview/PostData.php"

exit 1;
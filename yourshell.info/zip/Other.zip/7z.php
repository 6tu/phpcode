http://www.7-zip.org/
Zip a File with PHP
 Posted on Thursday, February 14 2008  
Learn to play songs by ear! Free Ear Training My blog doesn't rely on MySQL to output each post, instead i generate my pages on my home comp and upload them to the server. It would be a big hassle to FTP 20 files one by one, so instead I zip all the files automatically first, before uploading to my server.
PHP has some zip functionality if you enable the zlib library in your PHP ini. I don't really like to include too many random libraries for tasks that php itself shouldn't be handling.
Instead I call 7-zip through the command line. In linux you can use p7zip. It comes with documentation over all the command line options.
It simplifies things to make bash or batch file first. The basic command line Structure for 7zip is as follows.
7z.exe [what action to take] [additional options] [filenames]
Simple Examples
On Windows: foo.bat

del "C:\blah.zip"
"C:\Program Files\7-Zip\7z.exe" a -tzip "C:\blah.zip" "file1" "file2" "dir\*.*"


On Linux: foo.sh

rm "/home/blah/blah.zip"
"/path/to/7z.exe" a -tzip "/home/blah/blah.zip" "/file1" "/file2" "/dir/*.*"

Then call your batch or shell file in php. Technically you could skip the batch/shell thing but why be messy.
exec("/path/to/foo.whatever");

7zip Command Line options
7z.exe [command] [switches] [arguments]


Commands
a Add
b Benchmark
d Delete
e Extract
l List
t Test
u Update
x eXtract with full paths
Switches
-- Stop switches parsing
-ai Include archive filenames
-an Disable parsing of archive_name
-ao Overwrite mode
-ax Exclude archive filenames
-i Include filenames
-m Set Compression Method
-o Set Output directory
-p Set Password
-r Recurse subdirectories
-scs Set charset for list files
-seml Send archive by email
-slp Set Large Pages mode
-slt Show technical information
-sfx Create SFX archive
-si Read data from StdIn
-so Write data to StdOut
-ssc Set Sensitive Case mode
-ssw Compress files open for writing
-t Type of archive
-u Update options
-v Create Volumes
-w Set Working directory
-x Exclude filenames
-y Assume Yes on all queries
Wild Cards
'*' means a sequence of arbitrary characters.
'?' means any character.
Wildcard Examples
*.txt means all files with an extension of ".txt"
?a* means all files with a second character of "a"
*1* means all names that contains character "1"
*.*.* means all names that contain two "." means characters
Exit Codes
0 No error
1 Warning (Non fatal error(s)). For example, one or more files were locked by some other application, so they were not compressed.
2 Fatal error
7 Command line error
8 Not enough memory for operation
255 User stopped the process
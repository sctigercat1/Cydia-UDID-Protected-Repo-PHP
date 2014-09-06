UDID Protected Cydia Repo in PHP
=============================

This, as stated many times, is a UDID protected cydia repo.

Installation Instructions
=============================
- Open usergroups.php. This is the configuration file.
- Add UDIDs under $Users
- Change $CurrentDirectory to the current repo link.
- Change $CurrentDirectoryFolder to the folder the repo is under.
- Change the four database variables to your database.
- Import downloads.sql into your mysql database (with phpmyadmin, it's the import feature)
- You can change anything else you'd like. The end of basic configuration is at "// Done editing!"
- Finally, open .htaccess
- Look for the line "RewriteBase /repo/"
- Change /repo/ to whatever your directory is; if it's root, do /.
- Once setup is complete, upload the github repository to your cydia repo directory and visit Admin.php in your web browser. Login with your password (default: password) and run "Create file."
- Go into Cydia and add your repo URL!


More Detailed Explanation
=============================
At it's core, everything is pretty simple:
- First, Packages and Release are redirected to /repofiles.php?request=[file]
- That file checks if the user UDID is in the approved list. If so, it checks the user level and builds a package list in $finalfile with the packages that the specific usergroup has permission to see.
- After it has the list, it saves it to Packages and Packages.bz2. The file then redirects Cydia to check Packages.bz2 and Cydia does it thing from there.
- It's mostly admin interfaces, and that's because, without it, it would be difficult to add new files.
- Most files are stored in JSON files, but the counter is stored in a mysql database by the package identifier.


Credits
=============================
- HASHBANG for the iOS 7 css depiction and admin pages
- kirb for their [Cydia Repo Download Counter](https://gist.github.com/kirb/1922421).
- Thanks a bunch guys!

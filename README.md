NB: This project has been deprecated for a while, and honestly I just forgot to make a note about it within the repo. Since I don't currently have a jailbroken device I won't be able to successfully rewrite anything (because that's what this repo could use -- a complete rewrite), but I'd suggest this cydia repo framework in the meantime- https://github.com/82Flex/WEIPDCRM. I'll be leaving this github repo up for archiving purpose but it's ultimately not as functioning as I'd like it to be (as it was written when I began learning php). Thanks for all the support and suggestions from all that I've received over the course of the project's lifetime, it's been truly awesome. :)

UDID Protected Cydia Repo in PHP
=============================

This, as stated many times, is a UDID protected cydia repo.

What makes it different from others is that it allows you to set different usergroups per UDID: that way, you're able to set specific packages to specific users (Example: I'm usergroup 3. I can access packages in 3 and below, but not packages in 4.)

Installation Instructions
=============================
- Open usergroups.php. This is the configuration file.
- Change $CurrentDirectory to the current repo link. NOTE: If you have PHP 5.3 or higher, this is automatically done for you. If you have anything lower, you'll need to remove the line starting with $CurrentDirectory and change it to: ```$CurrentDirectory = "http://www.example.com/repo/";```
- Change $CurrentDirectoryFolder to the folder the repo is under. This is also automatically filled out if you have PHP 5.3 or higher. Otherwise, you'll need to manually add it in too: ```$CurrentDirectoryFolder = "/repo/";```
- Change the four database variables to your database.
- Import downloads.sql into your mysql database (with phpmyadmin, it's the import feature)
- You can change anything else you'd like. The end of basic configuration is at ```// Done editing!```
- Finally, open .htaccess
- Look for the line ```RewriteBase /repo/```
- Change /repo/ to whatever your directory is; if it's root, do /.
- Once setup is complete, upload the github repository to your cydia repo directory and visit Admin.php in your web browser. Login with your password (default: password).
- Now we need to setup permissions.  First, visit the Manage UDIDs page (ManageUDID.php).
- Using the form, add your device's UDID and specified level (as defined in usergroups.php, anything from 0-4).
- Save that, then return to the Admin interface and check out Beta Mode (BetaMode.php). This page (obviously) deals with beta mode which basically makes you require a minimum user level before you can access it. This level is defined in usergroups.php but can be activated/deactivated through this page. Default is off (false) but set to true if you want this.
- Finally, return to the Admin interface and click on Create File. We need to add a package for you to download. Fill out the form on the page and click submit and make sure the deb file actually made it to /debs.
- Go into Cydia and add your repo URL!


More Detailed Explanation
=============================
At it's core, everything is pretty simple:
- First, Packages and Release are redirected to ```/repofiles.php?request=[file]```
- Repofiles.php then checks if the user UDID is in the approved list. If so, it checks the user level and builds a package list in the variable $finalfile with the packages that the specific usergroup has permission to see.
- After it has the list, it saves it to Packages and Packages.bz2. The file then redirects Cydia to check Packages.bz2 and Cydia does it thing from there.
- It's mostly admin interfaces, and that's because, without it, it would be difficult to add new files.
- Most files are stored in JSON files, but the counter is stored in a mysql database by the package identifier.
- In PHP, there's a 128mb limit for getting files using file_get_contents. To get around this, we use ```ini_set('memory_limit', '-1');``` It should be noted, however, that downloading large packages via Cydia may cause lag on the device or other unknown problems. (PHP has this limit for a reason, so just use caution when downloading large files.)

Permissions
=============================
- Some servers will require correct permissions to be in place before the admin interface will work correctly.
- When adding these files, make these packages have permissions 777 and the ones not listed have 755 (or 775 if you're feeling generous):
- /ChangeFile.php
- /CreateFile.php
- /debnames.json
- /DeleteFile.php
- /package_groups.json
- /usergroups.php
- -R to all_packages and recycle_bin (that's recursive by the way, but hopefully you knew that)
- /descriptions/changeDepiction.php
- /descriptions/createDepiction.php
- /descriptions/DeleteDepiction.php
- /descriptions/description.json
- /descriptions/names.json
- /descriptions/pages.php
- /beta_mode.json
- /approved_udids_2.json
- Any deb file that you upload manually (this is optional, you just wouldn't be able to delete it using the interface)
- All new files will be kept up with by the server. Let me know if something isn't working due to permissions (either by email, an issue here on GitHub, or pull request)


Credits
=============================
- HASHBANG for the [iOS 7 css](https://github.com/hbang/iOS-7-CSS) used in the depiction and admin pages
- kirb for their [Cydia Repo Download Counter](https://gist.github.com/kirb/1922421).
- Thanks a bunch guys!

Musikteam
=========

Musikteam is an implementation of an online database for worship-songs with support for generating 
slides in ODP formst, PDF songsheeta, and handling lyrics in the "pro" format.

The implementation is created for and used by Københavnerkirken, a church in Copenhagen, Denmark. 
Because of this most strings etc. is in danish.

The license is GPL3 for now.

Originally it was written on top of odbc, but was then migrated to mysql, but should still work
with both, though mysql is the prefered database and odbc support is most likely to be dropped.

Installation is done by setting up a mysql user and database, entering the database login info
in db.php in the openDB-function in db.php. Also setup other options in the top of db.php.
Then load the install.php through a browser, and you should be good to go!
The only user in a clean install is the "admin" user, with the password "musikteam".

Enjoy at your own risk :)

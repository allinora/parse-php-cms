Parse PHP Albums
=============

Example app to test and demostrate the PHP-SDK for parse.com backend

Authors
-------

- [Atif ghaffar](http://atif.ghaffar@gmail.com)

Contributors
------------

- [You: Just add your name here when you contribute something]

About
------

The goal is to use as much capabilities of Parse.com as the SDK can provide. This will help to both demonstrate the usage and to find bugs.

This web application is for a photo album site where registered  users can create albums and add photos. Other users can see and comment on photos.

The app should touch the following features.

0. Signup  
0. Login
0. Session
0. Caching
0. Parse Files ( to store images )
0. Parse Objects ( to create buckets, albums, etc)
0. Object relations (pictures belong to albums, albums belong to users, comments belong to users and pictures, etc)
0. Eventually Roles to have albums shared with multiple users


Installation
------------
Clone from git into a directory called parse-php-albums

	git clone https://github.com/allinora/parse-php-albums.git
	
Install the dependencies

	cd parse-php-albums
	make install

Edit the parse-php-albums/config/config.php and replace values in  the PARSE constants

Run the application
	
	make run
	
Browse on localhost:8080

Developers
-----------
Almost all the Parse-SDK  functions are in library/ParseApp/Albums.php https://github.com/allinora/parse-php-albums/blob/master/library/ParseApp/Albums.php

Bug reporting
-------------
Please report in the issue tracker at https://github.com/allinora/parse-php-albums/issues


License
-------

The MIT License (MIT)

Copyright (c) 2014 Atif Ghaffar

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

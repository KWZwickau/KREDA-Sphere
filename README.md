KREDA-Sphere
============

Please take into account that current versions of KREDA could contain serious bugs.

Don't use them in production environments.

Be also aware that current interfaces may change rapidly

API Documentation
=================

@ <http://kwzwickau.github.io/KREDA-Sphere/>

-----

Development:

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/708fc862-a692-4279-903d-792f62333644/big.png)](https://insight.sensiolabs.com/projects/708fc862-a692-4279-903d-792f62333644)

[![Code Climate](https://codeclimate.com/github/KWZwickau/KREDA-Sphere/badges/gpa.svg)](https://codeclimate.com/github/KWZwickau/KREDA-Sphere)
[![Coverage Status](https://coveralls.io/repos/KWZwickau/KREDA-Sphere/badge.svg?branch=development)](https://coveralls.io/r/KWZwickau/KREDA-Sphere?branch=development)
[![Build Status](https://travis-ci.org/KWZwickau/KREDA-Sphere.svg?branch=development)](https://travis-ci.org/KWZwickau/KREDA-Sphere)

Master:

[![Coverage Status](https://coveralls.io/repos/KWZwickau/KREDA-Sphere/badge.svg?branch=master)](https://coveralls.io/r/KWZwickau/KREDA-Sphere?branch=master)
[![Build Status](https://travis-ci.org/KWZwickau/KREDA-Sphere.svg?branch=master)](https://travis-ci.org/KWZwickau/KREDA-Sphere)

Requirements
============

Web-Server
----------

- apache2 (2.4 or later)
  - rewrite 
  - headers
  
- php5 (5.5 or later)
  - php5-gmp
  - php5-curl
  - php5-apcu (optional)
  - php5-mysql (depends)

Database-Server
---------------

- mysql-server (5.5 or later)
  - *nix: lower_case_table_names = 0

Configuration
=============

Database
--------

File: 

- /Sphere/Common/Database/Config/{application}.ini

Option:

- [{service}:{consumer}]
- Driver = {PdoMySql}
- Host = {ip-address}
- Port = {integer}
- Username = {string}
- Password = {string}
- Database = {string}

Cache
-----

File: 

- /Sphere/Common/Cache/Config/Cache.ini

Option:

- Cache = {boolean} 

Proxy
-----

File: 

- /Sphere/Common/Proxy/Config/HttpProxy.ini

Option:

- Host = {ip-address}
- Port = {integer}
- Username = {string}
- Password = {string}

Library
=======

- Bootflat
- Bootstrap
- Bootstrap.DateTimePicker
- Bootstrap.FileInput
- Bootstrap.Glyphicons
- jQuery
- jQuery.DataTable
- jQuery.DataTable.Plugins
- jQuery.iCheck
- jQuery.Selecter
- jQuery.Stepper
- Markdownify
- MathJax
- Moment.Js
- Twitter.Typeahead
- Twitter.Typeahead.Bootstrap

- MOC (Mark V)
  - Autoloader
  - FileSystem
  - HttpKernel
    - Symfony
      - HttpFoundation
  - Database
    - Doctrine DBAL
    - Doctrine ORM
  - Document
    - PhpExcel
    - DomPdf
  - Documentation
    - ApiGen
  - Router
    - Symfony
      - EventDispatcher
      - HttpKernel
      - Routing
  - Template
    - Twig
    - Smarty

-----

- KREDA-Request Flowchart

![KREDA-Request Flowchart](TestSuite/Docs/KREDA-Request%20Flowchart.png "KREDA-Request Flowchart")

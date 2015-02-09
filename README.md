KREDA-Sphere
============

Please take into account that current versions of KREDA could contain serious bugs.

Don't use them in production environments.

Be also aware that current interfaces may change rapidly

-----

Development:

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/708fc862-a692-4279-903d-792f62333644/big.png)](https://insight.sensiolabs.com/projects/708fc862-a692-4279-903d-792f62333644)

[![Code Climate](https://codeclimate.com/github/KWZwickau/KREDA-Sphere/badges/gpa.svg)](https://codeclimate.com/github/KWZwickau/KREDA-Sphere)
[![Coverage Status](https://coveralls.io/repos/KWZwickau/KREDA-Sphere/badge.svg?branch=development)](https://coveralls.io/r/KWZwickau/KREDA-Sphere?branch=development)
[![Build Status](https://travis-ci.org/KWZwickau/KREDA-Sphere.svg?branch=development)](https://travis-ci.org/KWZwickau/KREDA-Sphere)

Master:

[![Coverage Status](https://coveralls.io/repos/KWZwickau/KREDA-Sphere/badge.svg?branch=master)](https://coveralls.io/r/KWZwickau/KREDA-Sphere?branch=master)
[![Build Status](https://travis-ci.org/KWZwickau/KREDA-Sphere.svg?branch=master)](https://travis-ci.org/KWZwickau/KREDA-Sphere)

Frontend
========

Navigation:

- Redirect

Form:

- Structure
  - Type
    - Default
  - Grid
    - Group
    - Row
    - Col
    - Title
- Element
  - Input
    - Completer
    - Date
    - File
    - Hidden
    - Password
    - Select
    - Text
    - TextArea

Table

- Structure
  - Type
    - Default (Interactive)
    - Data (Interactive)
  - Grid
    - Head
    - Body
    - Foot
    - Row
    - Col
    - Title

Alert

- Element
  - Message
    - Danger
    - Info
    - Success
    - Warning

Button:

- Structure
  - Group
- Element
  - Link
    - Primary
    - Danger
  - Button
    - Reset
    - Submit

Complex:

- Structure
  - Address

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
- jQuery.Selecter
- jQuery.Stepper
- Markdownify
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

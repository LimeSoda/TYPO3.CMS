=========================================================
Breaking: #72666 - RTE: Remove relative path calculations
=========================================================

Description
===========

Since the removal of the feature editing static files with the Rich Text Editor (option "static_file_edit"), the path calculations for files
within the HtmlParser including the method ``RteHtmlParser->setRelPath()`` have been removed as well.


Impact
======

Using the method ``RteHtmlParser->setRelPath()`` will result in a fatal PHP error.


Affected Installations
======================

Any installations with custom RTE transformations that use a custom implementation of the RteHtmlParser PHP class.
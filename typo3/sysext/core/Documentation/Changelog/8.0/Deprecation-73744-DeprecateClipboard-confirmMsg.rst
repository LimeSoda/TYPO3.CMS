=======================================================
Deprecation: #73744 - Deprecate Clipboard->confirmMsg()
=======================================================

Description
===========

``Clipboard::confirmMsg`` has been marked as deprecated.


Impact
======

Using ``Clipboard::confirmMsg()`` will trigger a deprecation log entry.


Affected Installations
======================

Any TYPO3 instance using a third-party extension using the PHP method above.


Migration
=========

Use ``Clipboard::confirmMsgText()`` to get the confirm message and make use of the Modal API.

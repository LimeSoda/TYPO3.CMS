================================================================
Deprecation: #73511 - BrowserLanguage detection moved to Locales
================================================================

Description
===========

The CharsetConverter contained the calculation to find the corrent language based on
the browsers language settings at the backend login screen.

The according code has been moved to TYPO3\CMS\Localization\Locales. The method
``CharsetConverter::getPreferredClientLanguage()`` and the property ``CharsetConverter::charSetArray`` have
been marked as deprecated.


Impact
======

Calling ``CharsetConverter::getPreferredClientLanguage()`` will trigger a deprecation log entry.


Affected Installations
======================

All installations with a third-party extension using the CharsetConverter language resolving directly.


Migration
=========

Use the method ``Locales->getPreferredClientLanguage()`` instead.
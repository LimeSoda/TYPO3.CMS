==========================================================
Breaking: #75150 - Removed TypoScript option includeJSlibs
==========================================================

Description
===========

The deprecated TypoScript property ``page.includeJSlibs`` has been removed.


Impact
======

Setting parameters to this property will have no effect anymore.


Affected Installations
======================

Any installation using this TypoScript option.


Migration
=========

Use the TypoScript property ``page.includeJSLibs`` instead, which is in line with naming of ``includeCSSLibs``.
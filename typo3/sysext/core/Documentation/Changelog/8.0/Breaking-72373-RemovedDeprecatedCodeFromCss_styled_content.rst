==================================================================
Breaking: #72373 - Removed deprecated code from css_styled_content
==================================================================

Description
===========

The following methods have been removed from ``CssStyledContentController``

``render_bullets``
``render_uploads``
``beautifyFileLink``


Impact
======

Using the methods above directly in any third party extension will result in a fatal error.


Affected Installations
======================

Instances which use calls to the methods above.


Migration
=========

Use default TypoScript from CSS Styled Content derived from the current version.

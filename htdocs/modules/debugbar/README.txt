The prefered way to include the vendor directory would be via composer
    "require": "maximebf/debugbar": "dev-master"

However -- the Resouces dir is modified in modules/debugbar/resources

Specifically, there is a conflict between the default bootstrap based XOOPS theme and the full FONT-AWESOME css supplied by DebugBar. Debugbar does not seem to use the many icon-* css classes defined, and only uses the font by specific glyph. With the full supplied css, the menu icons get overlayed by the icon-tags glyph, causing visual corruption. The solution was to include the font only, without the classes that duplicate the bootstrap ones already specified by the theme. Hence the vendor suppplied vendor include needs to be modified, so a working copy was captured, modified and included in this module as of 13-Sep-2013.

A long term solution will be reconsidered with assetic roll out.

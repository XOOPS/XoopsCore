+=========================+
| CodeMirror for TinyMCE4 |
+=========================+

(c) 2013-2014 Arjan Haverkamp (arjan@avoid.org)
Download: http://www.avoid.org/codemirror-for-tinymce4/
Version: 1.3 (2014-03-04)
License: see LICENSE.txt

Changelog
=========
Version 1.3 - 2014-03-04
- Bugfix: If any text was highlighted in CodeMirror and the code dialog is closed and saved,
          the selected text was removed from TinyMCE.
- Macintosh users now see Macintosh keyboard shortcuts.

Version 1.2 - 2013-09-04
- Dirty state of CodeMirror now passed on to TinyMCE.
- When submitting CodeMirror code to TinyMCE, cursor position is retained.
  Note: this only works when the cursor is *not* inside a <tag>.

Version 1.1 - 2013-07-19
- New options jsFiles and cssFiles.

Version 1.0 - 2013-06-29
- Initial release.

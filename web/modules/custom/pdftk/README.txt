About
PDFK allows PDFs to be merged automatically and added to the node when files
 are uploaded to a special file field.

PDFTK adds functionality from the PDF toolkit binary program through the use of
PHP PDFTK Toolkit. Currently this module is limited to concatenation of PDF
files (while PDFTK itself is quite capable of alot more), but more
functionality will be added as the PHP PDFTK Toolkit progresses.

WARNING:
This module currently requires a working cron to merge the pdfs, and add them
to the content. Your merged files will not appear until your next cron has run,
the default time is 3 hours. I am looking for a solution to this, so the files
will be processed instantly. Please let me know if you find a working solution.
Thanks!

-------------
Installation-
-------------
To install PDF Toolkit (PDFTK) binary, use your systems package manager to
install version 1.45 or greater or download the source from the website and
build it according to your environment.
http://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/

To install PHP PDFTK Toolkit:
Drush command:
  drush pdftk-dl

or zip download:
  http://github.com/bensquire/php-pdtfk-toolkit
  and extract to sites/all/libraries/php-pdtfk-toolkit-master

or git clone:
  cd sites/all/libraries/
  git clone http://github.com/bensquire/php-pdtfk-toolkit.git php-pdtfk-toolkit-master

You must create a file fields, a text field and a checkbox in order for this module to
work.

For example, if you want to merge two 'Bulletins' into one, you create a field
in your content type at admin/structure/types/manage/[content-type]/fields

-------------

Can be anything
Name: Merge PDFs

Must be exact
Machine Name: field_pdftk_merged

Field Type: File
Widget: File

PDFTK can only handle pdf file extensions.
Allowed File extensions: pdf

-----------

You must also create a text field for the merged filename.
You can use tokens in this field

Can be anything
Name: Filename

Must be exact
Machine Name: field_pdftk_output_filename

Field Type: Text
Widget: Text Field

This field should be required as it will cause the actions to fail if
no value is entered.


-----------

Finally you must create a checkbox

Can be anything
Name: Merge Files

Must be exact
Machine Name: field_merge_files

Field Type Boolean
Widget: Checkbox




------
Usage-
------
To merge pdf files, create new content with the field_pdftk_merged field,
field_merge_files and field_pdftk_output_filename field enabled.

Create your content as normal.

Upload multiple files, one at a time to the field field_pdftk_merged

Type in the filename for the final file. You may use tokens.

Check the box for field_merge_files 

Do not enable "Published", this module will publish the new content when the
file has been merged.

Your files will be merged automatically the next time drupal's cron runs.

-------------
Requirements-
-------------
* PDFTK version 1.45 or greater installed on your server
    http://www.pdflabs.com/tools/pdftk-the-pdf-toolkit/

* PHP PDFTK TOOLKIT 0.1.2 or greater installed at:
    sites/all/libraries/php-pdtfk-toolkit-master/pdftk/pdftk.php
    URL: http://github.com/bensquire/php-pdtfk-toolkit

* PHP must be version 5.2 or greater

* PHP's proc_open() function must be enabled

* token module
    http://drupal.org/project/token

* libraries >= 2.x module
    http://drupal.org/project/libraries

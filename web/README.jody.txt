
############# freshdesk module editing:
https://support.freshdesk.com/support/discussions/topics/6556
If something goes wrong, search for bfss custom witin code.

modules/custom/freshdesk_integration/freshdesk_integration.module
  May need to use code:  if (in_array('authenticated', $user->getRoles())) {

############ General Drupal and Configurations.

To rebuild your site from scratch based on your composer.json file, try the following steps:
  Backup and delete the /core and /vendor directories completely.
  Delete the composer.lock file.
  Run: composer install
  Run: composer update

Install drush:
  composer require drush/drush
  composer global remove drush/drush

Debug:
   ksm($var);
   kpm()

########################### UNIX COMMANDS

TAR Backup: bfss-jodi-pdf-2.tgz
  tar -czvpf fillpdf-bfss.tgz . --exclude "*.zip" --exclude "*.sql" --exclude "bfss-jody/*" --exclude "*.tar"

UNTAR:
  tar xvzf fileName.tar.gz or .tgz

find . -type f -print | xargs grep -i 'pdftk'




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

########################## github
you need to:
git checkout master
git pull
git checkout -b bfss-jody-0623
git commit -am "First commit"
git push -u origin my_new_branch


########################### UNIX COMMANDS

TAR Backup: bfss-jodi-pdf-2.tgz
  tar -czvpf fillpdf-bfss.tgz . --exclude "*.zip" --exclude "*.sql" --exclude "bfss-jody/*" --exclude "*.tar"

UNTAR:
  tar xvzf fileName.tar.gz or .tgz

find . -type f -print | xargs grep -i 'pdftk'

 wget --no-check-certificate --content-disposition https://github.com/cleggy28/bfss/archive/master.zip



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
git checkout master                   // Switches to the specified branch and updates the working directory
git pull                              // Updates your current local working branch with all new commits from the corresponding remote branch on GitHub
git checkout -b bfss-jody-0623        // daily branch creation
git commit -am "First commit"         // -a automatically stage all tracked, modified files before the commit.  -m is for message "First Commit"
git push -u origin bfss-jody-0623      // Uploads all local branch commits to GitHub


########################### UNIX COMMANDS

TAR Backup: bfss-jodi-pdf-2.tgz
  tar -czvpf fillpdf-bfss.tgz . --exclude "*.zip" --exclude "*.sql" --exclude "bfss-jody/*" --exclude "*.tar"

UNTAR:
  tar xvzf fileName.tar.gz or .tgz

find . -type f -print | xargs grep -i 'pdftk'

 wget --no-check-certificate --content-disposition https://github.com/cleggy28/bfss/archive/master.zip


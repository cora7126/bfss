
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

##########################  github

you need to:
git checkout master                   // Switches to the specified branch and updates the working directory
git pull                              // Updates your current local working branch with all new commits from the corresponding remote branch on GitHub
git checkout -b bfss-jody-0624        // daily branch creation
git commit -am "First commit"         // -a automatically stage all tracked, modified files before the commit.  -m is for message "First Commit"
git push -u origin bfss-jody-0624     // Uploads all local branch commits to GitHub
------------
When you are done with a branch make sure to add any files that may have been added or removed(1), then commit(2) and  then push it to the repository(3). Then I"ll merge it to master.  Once its merged then you need to switch back to master(4) and make sure to get all the new updates(5) and then cut a new branch from master(6) make a quick commit(7) and push it to the repository(8) then start your work on that branch.
(1) git add -A
(2) git commit -am "Commit Message"
(3) git push -u origin name_of_branch
(4) gco master
(5) git pull
(6) gco -b new_branch_for_day
(7) git commit -am "First Commit"
(8) git push -u origin name_of_branch
-------------
you are supposed to install it with composer
composer require drupal/module_name
this will download and install the module and register it with the package.json so that if we take this site to any other server we can simply run a composer install and it will install all modules and everything that this site needs to run properly.


########################### UNIX COMMANDS

TAR Backup: bfss-jodi-pdf-2.tgz
  tar -czvpf fillpdf-bfss.tgz . --exclude "*.zip" --exclude "*.sql" --exclude "bfss-jody/*" --exclude "*.tar"

UNTAR:
  tar xvzf fileName.tar.gz or .tgz

find . -type f -print | xargs grep -i 'pdftk'

 wget --no-check-certificate --content-disposition https://github.com/cleggy28/bfss/archive/master.zip


I installed a Drupal module called iframe, and am hoping Ryan could create an iframe in the bfss Create Ticketing area using the support url alias  -- seems to be good documentation on drupal iframes here:  https://www.drupal.org/project/iframe
Or if he's busy I will try to figure it out - I spent like 15 mins, but
Ryan could maybe do it fast.

I installed a Drupal module called iframe, and I am having trouble understanding the documentation https://www.drupal.org/project/iframe
If this is second-nature for you, it would save me much time if you could create an iframe here: http://bfss.mindimage.net/node/198  (iframe url would be CNAME http://support.5ppdev1.com/support/tickets/new)

and am hoping you could create an iframe in the Create Ticketing area, probably http://bfss.mindimage.net/node/198 or
I created a CNAME http://support.5ppdev1.com/support/tickets/new which points to freshdesk.com site.  It would be nice if we could place this alias
using the support url alias  -- seems to be good documentation on drupal iframes here:  https://www.drupal.org/project/iframe
Or if he's busy I will try to figure it out - I spent like 15 mins, but
Ryan could maybe do it fast.


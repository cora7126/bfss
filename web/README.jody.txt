
############# freshdesk module editing:
If something goes wrong, search for bfss custom witin code.

modules/custom/freshdesk_integration/freshdesk_integration.module
  May need to use code:  if (in_array('authenticated', $user->getRoles())) {

############ General Drupal notes
Debug:
    ksm($var);

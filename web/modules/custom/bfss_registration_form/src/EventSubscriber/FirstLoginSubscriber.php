<?php
/**
 * Created by PhpStorm.
 * User: Valentine
 * Date: 02.02.2020
 * Time: 18:26
 */

namespace Drupal\bfss_registration_form\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FirstLoginSubscriber implements EventSubscriberInterface
{

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents()
  {
    $events[KernelEvents::REQUEST][] = ['checkForFirstLogin'];
    return $events;
  }

  /**
   * @param GetResponseEvent $event
   */
  public function checkForFirstLogin(GetResponseEvent $event)
  {
    // get current theme
    $current_user = \Drupal::currentUser();
    $routeMatch = \Drupal::routeMatch();
    $routeName = $routeMatch->getRouteName();

    \Drupal::logger('user')->info($routeName);
	$roles = $current_user->getRoles();
	
    if ($current_user->isAuthenticated()) { 
      //	logic for "first login" redirect 
	  if(isset($roles) && in_array('bfss_manager', $roles)){
        //$response = new RedirectResponse("/bfssmanager_dashboard");
        //$response->send();
        //return;
		//print 'dfsssdf';die;
		//$event->setResponse(new RedirectResponse(\Drupal\Core\Url::fromRoute('bfss_manager_defaultprofile')->toString()));
		return;
		
    }
	 
       if (!empty($_SESSION['user_first_login']) && $routeName != 'user.logout' && $routeName != 'bfss_registration_form.complete_registration_page') {
		 
        $event->setResponse(new RedirectResponse(\Drupal\Core\Url::fromRoute('bfss_registration_form.complete_registration_page', ['user' => $current_user->id()])->toString()));
      }

      if ($routeName == 'bfss_registration_form.complete_registration_page' && !isset($_SESSION['user_first_login'])) { 
		
        $event->setResponse(new RedirectResponse(\Drupal\Core\Url::fromRoute('entity.user.canonical', ['user' => $current_user->id()])->toString()));
		/*$user = \Drupal::currentUser();

		\Drupal::logger('user')->notice('Session closed for %name.', array('%name' => $user->getAccountName()));

		\Drupal::moduleHandler()->invokeAll('user_logout', array($user));
		\Drupal::service('session_manager')->destroy();
		$user->setAccount(new AnonymousUserSession());*/
      }

    } else {

      if ($routeName == 'user.reset.form' && $current_user->isAnonymous()) {
        $uid = $routeMatch->getParameter('uid');
        $account = \Drupal\user\Entity\User::load($uid);
//        print_r($account->getLastAccessedTime());
//        print_r($account->getLastLoginTime());
//        exit;
        if ($account->getLastAccessedTime() == 0 || $account->getLastLoginTime() == 0) {
          $_SESSION['user_first_login'] = 1;
          user_login_finalize($account);
          $event->setResponse(new RedirectResponse(\Drupal\Core\Url::fromRoute('bfss_registration_form.complete_registration_page')->toString()));
        }
//      if ($routeName == 'user.reset.form' && !isset($_SESSION['check_is_login'])) {
//        $_SESSION['check_is_login'] = 1;
      }

    }

  }

}
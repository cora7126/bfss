<?php
/**
 * Created by PhpStorm.
 * User: Valentine
 * Date: 02.02.2020
 * Time: 13:40
 */

namespace Drupal\bfss_user\Block;


use Drupal\Core\Access\AccessResultAllowed;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;

class RegisterLink extends BlockBase {

  /**
   * Block output
   *
   * @return array
   */
  function build() {
    // TODO: we can try to use Block Plugin for set title and url
    return [
      '#type' => 'link',
      '#title' => $this->t('Registration'),
      '#url' => Url::fromRoute('user.register'),
      '#attributes' => [
        'title' => $this->t('Registration'),
        'class' => ['user-register-link', 'btn'],
      ],
    ];
  }

  /**
   * Custom block visibility settings
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   * @param bool $return_as_object
   *
   * @return bool|\Drupal\Core\Access\AccessResult|\Drupal\Core\Access\AccessResultInterface
   */
  function access(AccountInterface $account, $return_as_object = FALSE) {
    $currentUser = \Drupal::currentUser();
    return AccessResultAllowed::allowedIf($currentUser->isAnonymous());
  }

}
<?php

namespace Drupal\contact_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;


// Provides an admin page to view contact form submissions.
 
class FormMessagesController extends ControllerBase {

//   Displays submitted contact messages.
  
  public function viewMessages() {
    $header = ['ID', 'Name', 'Email', 'Message', 'Submitted'];
    $rows = [];
    $query = Database::getConnection()->select('contact_form_messages', 'c')
      ->fields('c')
      ->execute();

    foreach ($query as $record) {
      $rows[] = [
        $record->id,
        $record->name,
        $record->email,
        $record->message,
        date('Y-m-d H:i:s', $record->submitted),
      ];
    }

    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('There is no messages found here!'),
    ];
  }

  
// Restricts access to trusted users.

  public function accessCheck() {
    return $this->currentUser()->hasPermission('administer site configuration');
  }
}

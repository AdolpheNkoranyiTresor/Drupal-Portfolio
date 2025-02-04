<?php

namespace Drupal\contact_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/* Let's define the form:*/

class ContactForm extends FormBase {

     public function getFormId(){
        return 'contact_form';
     }

    // Let's build our contact form fields
     public function buildForm(array $form, FormStateInterface $form_state){

        $form['heading'] = [
            '#div' => $this->t('<div>'),
            '#markup' => $this->t('<h2>Contact Me</h2>'),
        ];

        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Name:'),
            '#required' => TRUE,
        ];

        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email Address:'),
            '#required' => False,
        ];

        $form['message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Message:'),
            '#required' => True,
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
            '#div' => $this->t('</div>'),
        ];

        return $form;
     }


    //  Let's implement a method to submit our form to the db for security

    public function submitForm(array &$form, FormStateInterface $form_state){
        $connection = Database::getConnection();
        $connection->insert('contact_form_messages')
            ->fields([
                'name' => $form_state->getValue('name'),
                'email' => $form_state->getValue('email'),
                'message' => $form_state->getValue('message'),
                'submitted' => time(),
            ])
            ->execute();

        $this->messenger()->addMessage($this->t('Thank you for getting it touch with me. I will get back to you soon!'));
        $form_state->setRedirect('<front>');
    }

}
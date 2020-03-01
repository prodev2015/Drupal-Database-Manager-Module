<?php
namespace Drupal\tradesteps\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class FormTableAddMoreRow extends FormBase {

    public function getFormId() {
        return 'tradesteps_form_table_add_row';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        
        // Get the number of rows, default to 2 rows.
        $num_of_rows = $form_state->get('num_of_rows');
        if (empty($num_of_rows)){
            $num_of_rows=2;
            $form_state->set('num_of_rows', $num_of_rows);
        }
        
        // Add the headers.
        $form['contacts'] = array(
                                    '#type' => 'table',
                                    '#title' => 'Sample Table',
                                    '#header' => array('Name', 'Phone'),
                                );
        
        // Create rows according to $num_of_rows.
        for ($i=1; $i<=$num_of_rows; $i++) {
            $form['contacts'][$i]['name'] = array(
                                                '#type' => 'textfield',
                                                '#title' => t('Name'),
                                                '#title_display' => 'invisible',
                                                '#default_value' => 'name'.$i,
                                            );

            $form['contacts'][$i]['phone'] = array(
                                                '#type' => 'tel',
                                                '#title' => t('Phone'),
                                                '#title_display' => 'invisible',
                                                '#default_value' => '763-999-444'.$i,
                                            );
        }
    
        // 'Add row' button.
        $form['actions']['add_row'] = [
                                        '#type' => 'submit',
                                        '#value' => $this->t('Add row'),
                                        '#submit' => array('::addRowCallback'),
                                    ];
        
        // Submit button.
        $form['actions']['submit'] = [
                                        '#type' => 'submit',
                                        '#value' => $this->t('Submit'),
                                    ];

        return $form;
    }
    
    public function addRowCallback(array &$form, FormStateInterface $form_state) {
    
        // Increase by 1 the number of rows.
        $num_of_rows = $form_state->get('num_of_rows');
        $num_of_rows++;
        $form_state->set('num_of_rows', $num_of_rows);
        
        // Rebuild form with 1 extra row.
        $form_state->setRebuild();
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Find out what was submitted.
        $values = $form_state->getValues();
        drupal_set_message(print_r($values['contacts'],true));
    }

}

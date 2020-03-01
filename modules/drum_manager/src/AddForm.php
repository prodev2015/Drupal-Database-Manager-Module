<?php

namespace Drupal\drum_manager;

/**
 * @file
 * Contains \Drupal\drum_manager\AddForm.
 */

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Component\Utility\Html;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AddForm.
 *
 * @package Drupal\drum_manager
 */
class AddForm extends FormBase implements FormInterface, ContainerInjectionInterface {

  use StringTranslationTrait;
  use MessengerTrait;

  /**
   * Our database repository service.
   *
   * @var \Drupal\drum_manager\DrumsStorage
   */
  protected $drums_storage;
  protected $texture_groups_storage;

  /**
   * The current user.
   *
   * We'll need this service in order to check if the user is logged in.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  protected $requestStack;

  /**
   * {@inheritdoc}
   */
  protected $id;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $form = new static(
      $container->get('drum_manager.drums_storage'),
      $container->get('drum_manager.texture_groups_storage'),
      $container->get('current_user'),
      $container->get('request_stack')
    );
    $form->setStringTranslation($container->get('string_translation'));
    $form->setMessenger($container->get('messenger'));
    return $form;
  }

  /**
   * AdminController constructor.
   *
   * @param \Drupal\drum_manager\DrumsStorage $drums_storage
   *   Request stack service for the container.
   * @param \Drupal\drum_manager\TextureGroupsStorage $texture_groups_storage
   *   Request stack service for the container.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Request stack service for the container.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Request stack service for the container.
   */
  public function __construct(DrumsStorage $drums_storage, TextureGroupsStorage $texture_groups_storage, AccountProxyInterface $current_user, RequestStack $request_stack) {
    $this->drums_storage = $drums_storage;
    $this->texture_groups_storage = $texture_groups_storage;
    $this->currentUser = $current_user;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  protected function request() {
    return $this->requestStack->getCurrentRequest();
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'drums_add';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $this->id = $this->request()->get('id');
    $drum = $this->drums_storage->get($this->id);

    $texture_group_ids = $form_state->get("texture_group_ids");
    if($texture_group_ids === null)
    {
      if($drum) {
        $texture_group_ids = $this->drums_storage->getTextureGroupIds($drum->id);
      }
      else
      {
        $texture_group_ids = [];
      }
      $form_state->set("texture_group_ids", $texture_group_ids);
    }

    // drum name
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $drum ? $drum->name : '',
    ];
    //drum name //


    // drum model
    $form['model'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Model'),
      '#default_value' => $drum ? $drum->model : '',
    ];
    // drum model //


    // texture table
    // Table header.
    $header = [
      'id' => $this->t('Id'),
      'name' => $this->t('texture name'),
      'operations' => $this->t('Delete'),
    ];

    $form['texture_table'] = [
      '#type' => 'table',
      '#title' => t('Textures'),
      '#header' => $header,
      //'#rows' => $rows,
      '#attributes' => [
        'id' => 'texture-table',
      ],
    ];

    $rows = [];

      $i = 0;
      foreach ($texture_group_ids as $id) {
        // Row with attributes on the row and some of its cells.
        //$editUrl = Url::fromRoute('drums_edit', ['id' => $content->id]);
        //$deleteUrl = Url::fromRoute('drums_delete', ['id' => $content->id]);
        $content = $this->texture_groups_storage->get($id);
        $form['texture_table'][$i]['id'] = [
          '#type' => 'label',
          '#title' => $content->id,
          '#value' => $content->id,
        ];
        $form['texture_table'][$i]['name'] = [
          '#type' => 'label',
          '#title' => $this->t($content->name),
          '#value' => $content->id,
        ];
        $form['texture_table'][$i]['delete'] = [
          '#type' => 'submit',
          '#name' => $i,
          '#value' => "delete",
          '#submit' => array('::deleteTextureGroup'),
        ];
        $i++;
      }

    // Texture Table //

    // Texture Name
    $texture_group_names_list = [];
    $texture_groups_to_select = [];
    foreach ($this->texture_groups_storage->getAll()  as $content) {
      array_push($texture_group_names_list, $this->t($content->name));
      array_push($texture_groups_to_select, $content);
    }
    $form_state->set("texture_groups_to_select", $texture_groups_to_select);
    $form['texture_name'] = [
      '#type' => 'select',
      '#title' => $this->t('Texture Name'),
      '#options' => $texture_group_names_list,
    ];
    // Texture Name //


    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add Texture Button
    $form['add_texture_button'] = [
      '#type' => 'submit',
      '#value' => $this->t("Add Texture"),
      '#name' => $this->t('addTextureButton'),
      '#submit' => array('::addTextureGroup'),
      //'#submit' => ['::addTextureGroup'],
      '#ajax' => array(
        'wrapper' => 'texture-table',
        'callback' => '::addTextureGroupCallback',
      ),
    ];
    // Add Texture Button //

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $drum ? $this->t('Edit') : $this->t('Add'),
    ];

    $form['#prefix'] = '<div id="ajax-wrapper">';
    $form['#suffix'] = '</div>';
    return $form;
  }
  /**
   * Ajax callback that moves the form to the next step and rebuild the form.
   *
   * @param array $form
   *   The Form API form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The FormState object.
   *
   * @return array
   *   The Form API form.
   */

  public function addTextureGroup(array &$form, FormStateInterface $form_state){
    $texture_group_ids = $form_state->get("texture_group_ids");
    $texture_group_num = $form_state->getValue("texture_name");
    $texture_groups_to_select = $form_state->get("texture_groups_to_select");

    array_push($texture_group_ids, $texture_groups_to_select[$texture_group_num]->id);
    $form_state->set("texture_group_ids", $texture_group_ids);
    $form_state->setRebuild();
  }

  public function addTextureGroupCallback(array &$form, FormStateInterface $form_state){
    return $form["texture_table"];
  }

  public function deleteTextureGroup(array &$form, FormStateInterface $form_state) {
    $removal_index = $form_state->getTriggeringElement()['#name'];
    $texture_group_ids = $form_state->get("texture_group_ids");
    array_splice($texture_group_ids, $removal_index, 1);
    $form_state->set("texture_group_ids", $texture_group_ids);
    $form_state->setRebuild();
    //return $form["texture_table"];
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Verify that the user is logged-in.
    if ($this->currentUser->isAnonymous()) {
      $form_state->setError($form['add'], $this->t('You must be logged in to add values to the database.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
//    if ($form_state->getTriggeringElement()['#name'] == $this->t('addTextureButton')) {
//      $texture_name = $form_state->getValue('texture_name');
//      if (!empty($this->id)) {
//
//      } else
//      {
//        array_push($this->temp_textures, $texture_name);
//      }
//      return;
//    }

    $account = $this->currentUser;
    $name = $form_state->getValue('name');
    $model = $form_state->getValue('model');
    $texture_group_ids = $form_state->get("texture_group_ids");

    if (!empty($this->id)) {
      $return = $this->drums_storage->edit($this->id, Html::escape($name), $texture_group_ids, $model);
      if ($return) {
        $this->messenger()->addMessage($this->t('Drum has been edited.'));
      }
    }
    else {
      //$return = $this->storage->add(Html::escape($name), Html::escape($image[0]), $uid);
      $return = $this->drums_storage->add(Html::escape($name), $texture_group_ids, $model);
      if ($return) {
        $this->messenger()->addMessage($this->t('Drum has been saved.'));
      }
    }
    $form_state->setRedirect('drums_list');
  }

}

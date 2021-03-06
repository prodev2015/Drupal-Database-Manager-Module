<?php


namespace Drupal\drum_manager;

/**
 * @file
 * Contains \Drupal\drum_manager\AddTextureGroupForm.
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
 * Class AddTextureGroupForm.
 *
 * @package Drupal\drum_manager
 */
class AddTextureGroupForm extends FormBase implements FormInterface, ContainerInjectionInterface
{

  use StringTranslationTrait;
  use MessengerTrait;

  /**
   * Our database repository service.
   *
   * @var \Drupal\drum_manager\DrumsStorage
   */
  protected $storage;

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
  public static function create(ContainerInterface $container)
  {
    $form = new static(
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
   * @param \Drupal\drum_manager\TextureGroupsStorage $storage
   *   Request stack service for the container.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Request stack service for the container.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Request stack service for the container.
   */
  public function __construct(TextureGroupsStorage $storage, AccountProxyInterface $current_user, RequestStack $request_stack)
  {
    $this->storage = $storage;
    $this->currentUser = $current_user;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  protected function request()
  {
    return $this->requestStack->getCurrentRequest();
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'drums_add';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $this->id = $this->request()->get('id');
    $texture_group = $this->storage->get($this->id);

//    $form['name'] = [
//      '#type' => 'textfield',
//      '#title' => $this->t('Name'),
//      '#default_value' => $drum ? $drum->name : '',
//    ];
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $texture_group ? $texture_group->name : '',
      '#required' => true
    ];

    $form['tum'] = array(
      '#type'          => 'managed_file',
      '#title'         => t('Tum'),
      '#upload_location' => 'public://images/',
      '#default_value' => $texture_group->tum ? [$texture_group->tum] : '',
      '#description'   => t('Specify an image(s) to display.'),
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#states'        => array(
        'visible'      => array(
          ':input[name="image_type"]' => array('value' => t('Upload New Image(s)')),
        ),
      ),
    );

    $form['snare'] = array(
      '#type'          => 'managed_file',
      '#title'         => t('Snare'),
      '#upload_location' => 'public://images/',
      '#default_value' => $texture_group->snare ? [$texture_group->snare] : '',
      '#description'   => t('Specify an image(s) to display.'),
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#states'        => array(
        'visible'      => array(
          ':input[name="image_type"]' => array('value' => t('Upload New Image(s)')),
        ),
      ),
    );

    $form['floor'] = array(
      '#type'          => 'managed_file',
      '#title'         => t('Floor'),
      '#upload_location' => 'public://images/',
      '#default_value' => $texture_group->floor ? [$texture_group->floor] : '',
      '#description'   => t('Specify an image(s) to display.'),
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#states'        => array(
        'visible'      => array(
          ':input[name="image_type"]' => array('value' => t('Upload New Image(s)')),
        ),
      ),
    );

    $form['bass'] = array(
      '#type'          => 'managed_file',
      '#title'         => t('Bass'),
      '#upload_location' => 'public://images/',
      '#default_value' => $texture_group->bass ? [$texture_group->bass] : '',
      '#description'   => t('Specify an image(s) to display.'),
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#states'        => array(
        'visible'      => array(
          ':input[name="image_type"]' => array('value' => t('Upload New Image(s)')),
        ),
      ),
    );

    $form['thumbnail'] = array(
      '#type'          => 'managed_file',
      '#title'         => t('Thumbnail'),
      '#upload_location' => 'public://images/',
      '#default_value' => $texture_group->thumbnail ? [$texture_group->thumbnail] : '',
      '#description'   => t('Specify an image(s) to display.'),
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#states'        => array(
        'visible'      => array(
          ':input[name="image_type"]' => array('value' => t('Upload New Image(s)')),
        ),
      ),
    );

    $form['texture_material'] = [
      '#type' => 'select',
      '#title' => $this->t('Texture Name'),
      '#options' => [
        '0' => 'Blue',
        '1' => 'White',
        '2' => 'Gray',
        '3' => 'Green',
      ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $texture_group ? $this->t('Edit') : $this->t('Add'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    // Verify that the user is logged-in.
    if ($this->currentUser->isAnonymous()) {
      $form_state->setError($form['add'], $this->t('You must be logged in to add values to the database.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    $account = $this->currentUser;
    $name = $form_state->getValue('name');
    $tum = $form_state->getValue('tum');
    $tum = sizeof($tum) > 0 ? $tum[0] : null;
    $snare = $form_state->getValue('snare');
    $snare = sizeof($snare) > 0 ? $snare[0] : null;
    $floor = $form_state->getValue('floor');
    $floor = sizeof($floor) > 0 ? $floor[0] : null;
    $bass = $form_state->getValue('bass');
    $bass = sizeof($bass) > 0 ? $bass[0] : null;
    $thumbnail = $form_state->getValue('thumbnail');
    $thumbnail = sizeof($thumbnail) > 0 ? $thumbnail[0] : null;
    $material = $form_state->getValue('texture_material');

    if (!empty($this->id)) {
      $return = $this->storage->edit($this->id, Html::escape($name), $tum, $snare, $floor, $bass, $thumbnail, $material);
      if ($return) {
        $this->messenger()->addMessage($this->t('Drum has been edited.'));
      }
    } else {
      //$return = $this->storage->add(Html::escape($name), Html::escape($image[0]), $uid);
      $return = $this->storage->add(Html::escape($name), $tum, $snare, $floor, $bass, $thumbnail, $material);
      if ($return) {
        $this->messenger()->addMessage($this->t('Drum has been saved.'));
      }
    }
    $form_state->setRedirect('texture_groups_list');
  }

}

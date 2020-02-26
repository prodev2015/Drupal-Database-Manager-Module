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

  protected $temp_textures = [];

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $form = new static(
      $container->get('drum_manager.storage'),
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
   * @param \Drupal\drum_manager\DrumsStorage $storage
   *   Request stack service for the container.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Request stack service for the container.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Request stack service for the container.
   */
  public function __construct(DrumsStorage $storage, AccountProxyInterface $current_user, RequestStack $request_stack)
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
    $drum = $this->storage->get($this->id);

//    if(!empty($form_state->getValue("image")[0]))
//    {
//      global $base_url;
//      $drum_image = $form_state->getValue("image");
//      $image_file = file_load($drum_image[0]);
//
//      if($image_file){
//        $form['current_image'] = [
//          '#type'     => 'markup',
//          '#markup' => '<img src="' . $base_url . '/sites/drupal-8-8-1.dd/files/images/' . $image_file->getFilename() . '" />'
//        ];
//      }else
//      {
//        $form['current_image'] = null;
//      }
//    }else
//    {
//      if(!empty($drums))
//      {
//        global $base_url;
//        $drum_image = $drums->image;
//        //$image_file = File::load($drum_image);
//        $image_file = file_load($drum_image);
//
//        $form['current_image'] = [
//          '#type'     => 'markup',
//          '#markup' => '<img src="' . $base_url . '/sites/drupal-8-8-1.dd/files/images/' . $image_file->getFilename() . '" />'
//        ];
//
//      }else
//      {
//        $form['message'] = [
//          '#markup' => $this->t('You do not currently have a drum image.'),
//        ];
//        $form['current_image'] = null;
//      }
//    }


    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $drum ? $drum->name : '',
    ];

//    $form['image'] = array(
//      '#type'          => 'managed_file',
//      '#title'         => t('Choose Image File'),
//      '#upload_location' => 'public://images/',
//      '#default_value' => $drums ? $drums->image : '',
//      '#description'   => t('Specify an image(s) to display.'),
//      '#theme' => 'image_widget',
//      '#preview_image_style' => 'medium',
//      '#states'        => array(
//        'visible'      => array(
//          ':input[name="image_type"]' => array('value' => t('Upload New Image(s)')),
//        ),
//      ),
//    );

    // texture table
    // Table header.
    $header = [
      'id' => $this->t('Id'),
      'name' => $this->t('texture name'),
      'operations' => $this->t('Delete'),
    ];
    $rows = [];
    if ($drum) {
      foreach ($this->storage->getAllTextures($drum->id) as $content) {
        // Row with attributes on the row and some of its cells.
        //$editUrl = Url::fromRoute('drums_edit', ['id' => $content->id]);
        //$deleteUrl = Url::fromRoute('drums_delete', ['id' => $content->id]);

        $rows[] = [
          'data' => [
            //Link::fromTextAndUrl($content->id, $editUrl)->toString(),
            $content->id,
            $content->name,
            "delete"
            //Link::fromTextAndUrl($this->t('Delete'), $deleteUrl)->toString(),
          ],
        ];
      }
    } else {
      for ($i = 0; $i < count($this->temp_textures); $i++) {
        $rows[] = [
          'data' => [
            $i,
            $this->temp_textures[$i],
            "delete"
          ],
        ];
      }
    }

    $form['texture_table'] = [
      '#type' => 'table',
      '#title' => t('Textures'),
      '#header' => $header,
      '#rows' => $rows,
      '#attributes' => [
        'id' => 'texture-table',
      ],
    ];
    // Texture Table //

    // Texture Name
    $form['texture_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Texture Name'),
      '#default_value' => '',
    ];
    // Texture Name //

    // Add Texture Button
    $form['add_texture_button'] = [
      '#type' => 'submit',
      '#value' => $this->t("Add Texture"),
      '#name' => $this->t('addTextureButton'),
    ];
    // Add Texture Button //

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $drum ? $this->t('Edit') : $this->t('Add'),
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
    $input = $form_state->getUserInput();
    if ($form_state->getTriggeringElement()['#name'] == $this->t('addTextureButton')) {
      $texture_name = $form_state->getValue('texture_name');
      if (!empty($this->id)) {

      } else {
        array_push($this->temp_textures, $texture_name);
      }
      return;
    }

    $account = $this->currentUser;
    $name = $form_state->getValue('name');

    $uid = $account->id();
    if (!empty($this->id)) {
      $return = $this->storage->edit($this->id, Html::escape($name));
      if ($return) {
        $this->messenger()->addMessage($this->t('Drum has been edited.'));
      }
    } else {
      //$return = $this->storage->add(Html::escape($name), Html::escape($image[0]), $uid);
      $return = $this->storage->addDrum(Html::escape($name));
      if ($return) {
        $this->messenger()->addMessage($this->t('Drum has been saved.'));
      }
    }
    $form_state->setRedirect('drums_list');
  }

}

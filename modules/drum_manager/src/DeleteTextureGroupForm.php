<?php

namespace Drupal\drum_manager;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class DeleteTextureGroupForm.
 *
 * @package Drupal\drum_manager
 */
class DeleteTextureGroupForm extends ConfirmFormBase {

  use StringTranslationTrait;
  use MessengerTrait;

  /**
   * Our database repository service.
   *
   * @var \Drupal\drum_manager\TextureGroupsStorage
   */
  protected $storage;

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
      $container->get('drum_manager.storage'),
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
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Request stack service for the container.
   */
  public function __construct(TextureGroupsStorage $storage, RequestStack $request_stack) {
    $this->storage = $storage;
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
    return 'texture_groups_delete';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete texture group %id?',
      ['%id' => $this->id]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('texture_groups_list');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->id = $this->request()->get('id');
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if (!empty($this->id)) {
      $return = $this->storage->delete($this->id);
      if ($return) {
        $this->messenger()->addMessage($this->t('Texture Group has been removed.'));
      }
    }
    $form_state->setRedirect('texture_groups_list');
  }

}

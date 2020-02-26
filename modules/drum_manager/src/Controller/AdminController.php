<?php

namespace Drupal\drum_manager\Controller;

/**
 * @file
 * Contains \Drupal\drum_manager\Controller\AdminController.
 */

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Render\Renderer;
use Drupal\drum_manager\DrumsStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AdminController.
 *
 * @package Drupal\drum_manager\Controller
 */
class AdminController extends ControllerBase {

  use StringTranslationTrait;

  /**
   * Our database repository service.
   *
   * @var \Drupal\drum_manager\DrumsStorage
   */
  protected $storage;

  /**
   * Renderer service will be used via Dependency Injection.
   *
   * @var Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('drum_manager.storage'),
      $container->get('renderer')
    );
  }

  /**
   * AdminController constructor.
   *
   * @param \Drupal\drum_manager\DrumsStorage $storage
   *   Request stack service for the container.
   * @param Drupal\Core\Render\Renderer $renderer
   *   Renderer service for the container.
   */
  public function __construct(DrumsStorage $storage, Renderer $renderer) {
    $this->storage = $storage;
    $this->renderer = $renderer;
  }

  /**
   * Get data as content table.
   *
   * @return array
   *   Content table.
   */
  public function content() {
    $url = Url::fromRoute('drums_add');
    $add_link = '<p>' . Link::fromTextAndUrl($this->t('Add Drum'), $url)->toString() . '</p>';

    $text = [
      '#type' => 'markup',
      '#markup' => $add_link,
    ];

    // Table header.
    $header = [
      'id' => $this->t('Id'),
      'name' => $this->t('drum name'),
      'operations' => $this->t('Delete'),
    ];
    $rows = [];
    foreach ($this->storage->getAllDrums() as $content) {
      // Row with attributes on the row and some of its cells.
      $editUrl = Url::fromRoute('drums_edit', ['id' => $content->id]);
      $deleteUrl = Url::fromRoute('drums_delete', ['id' => $content->id]);

      $rows[] = [
        'data' => [
          Link::fromTextAndUrl($content->id, $editUrl)->toString(),
          $content->name,
          Link::fromTextAndUrl($this->t('Delete'), $deleteUrl)->toString(),
        ],
      ];
    }
    $table = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#attributes' => [
        'id' => 'drums-table',
      ],
    ];
    return [
      $text,
      $table,
    ];
  }

}

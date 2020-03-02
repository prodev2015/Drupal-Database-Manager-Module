<?php

namespace Drupal\drum_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\drum_manager\DrumsStorage;
use Drupal\drum_manager\TextureGroupsStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a 'Custom' Block
 *
 * @Block(
 *   id = "drum_viewer_block",
 *   admin_label = @Translation("Drum Viewer"),
 *	 category = @Translation("Drum Viewer Block") * )
 */

class bloque extends BlockBase implements ContainerFactoryPluginInterface  {

  protected $drums_storage;
  protected $texture_groups_storage;

    public function build() {
        // return array(
        //     '#theme' => 'bloque',
        //     '#title' => 'My custom block',
        //     '#description' => ''
        // );

      $build = [
        '#theme' => 'bloque',
        '#attached' => [
          'library' => [
            'drum_manager/drum_viewer_block',
          ],
        ],
      ];

      $drums = [];
      foreach($this->drums_storage->getAll() as $content)
      {
        array_push($drums, $content);
      }
      $build['#attached']['drupalSettings']['drums'] = $drums;

        return $build;

    //     return array(
    //         '#theme' => 'bloque',
    //         //'#title' => 'Drum Viewer',
    //         //'#description' => 'This is a drum viewer',
    //         '#attached' => array(
    //             'drupalSettings' => NULL,
    //             'library' => array(
    //                 'twig_block/twig_block',
    //             ),
    //         ),
    //   );
   }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $block = new static(
      $container->get('drum_manager.drums_storage'),
      $container->get('drum_manager.texture_groups_storage'),
    );

    return $block;
  }

  public function __construct(DrumsStorage $drums_storage, TextureGroupsStorage $texture_groups_storage) {
    $this->drums_storage = $drums_storage;
    $this->texture_groups_storage = $texture_groups_storage;
  }
}



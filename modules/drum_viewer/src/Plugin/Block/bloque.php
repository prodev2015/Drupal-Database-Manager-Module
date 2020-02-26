<?php

namespace Drupal\drum_viewer\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Custom' Block
 *
 * @Block(
 *   id = "drum_viewer_block",
 *   admin_label = @Translation("Drum Viewer"),
 *	 category = @Translation("Drum Viewer Block") * )
 */

class bloque extends BlockBase {

    public function build() {
        // return array(
        //     '#theme' => 'bloque',
        //     '#title' => 'My custom block',
        //     '#description' => ''
        // );

        return [
            '#theme' => 'bloque',
            '#attached' => [
              'library' => [
                'drum_viewer/drum_viewer_block',
              ],
            ],
          ];

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


}



<?php

namespace Drupal\drum_manager\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the drums module.
 */
class AdminControllerTest extends WebTestBase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "drums AdminController's controller functionality",
      'description' => 'Test Unit for module drums and controller AdminController.',
      'group' => 'Other',
    ];
  }

  /**
   * Tests drums functionality.
   */
  public function testAdminController() {
    // Check that the basic functions of module drums.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}

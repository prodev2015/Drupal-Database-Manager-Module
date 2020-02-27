<?php

namespace Drupal\drum_manager;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Class DrumsStorage.
 *
 * @package Drupal\drum_manager
 */
class DrumsStorage extends ControllerBase {

  use MessengerTrait;
  use StringTranslationTrait;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Construct a repository object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The translation service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(Connection $connection, TranslationInterface $translation, MessengerInterface $messenger) {
    $this->connection = $connection;
    $this->setStringTranslation($translation);
    $this->setMessenger($messenger);
  }

  /**
   * Method getAll().
   *
   * @return mixed
   *   DB query.
   */
  public function getAll() {
    $result = $this->connection->select('drums', 's')
      ->fields('s')
      ->execute();
    return $result;
  }

  /**
   * Method getTextures().
   *
   * @return mixed
   *   DB query.
   */
  public function getTextureGroupIds($id) {
    $result = $this->connection->query('SELECT * FROM {drums} WHERE id = :id', [':id' => $id])
      ->fetchAllAssoc('id');
    if ($result) {
      return explode(',', $result[$id]->texture_groups);
    }
    else {
      return FALSE;
    }
  }

  /**
   * Get if $id exists.
   *
   * @param string $id
   *   Id of the record.
   *
   * @return bool
   *   Execute get($id) method and return bool.
   */
  public function exists($id) {
    return (bool) $this->get($id);
  }

  /**
   * Getter of DB Drums data.
   *
   * @param string $id
   *   Id of the record.
   *
   * @return bool|array
   *   DB query.
   */
  public function get($id) {
    $result = $this->connection->query('SELECT * FROM {drums} WHERE id = :id', [':id' => $id])
      ->fetchAllAssoc('id');
    if ($result) {
      return $result[$id];
    }
    else {
      return FALSE;
    }
  }


  /**
   * Add method.
   *
   * @param string $name
   *   Drum's name.
   *
   * * @param string $image
   *   Drum's image.

   * @param string|null $uid
   *   Account User ID.
   *
   * @throws \Exception
   *   DB insert query.
   *
   * @return int|null
   *   DB insert query return value.
   */
  public function add($name, $texture_groups = null, $model = null) {
    $fields = [
      'name' => $name,
      'texture_groups' => implode(',' ,$texture_groups),
      'model' => $model,
    ];
    $return_value = NULL;
    try {
      $return_value = $this->connection->insert('drums')
        ->fields($fields)
        ->execute();
    }
    catch (\Exception $e) {
      $this->messenger()->addMessage($this->t('Insert failed. Message = %message', [
        '%message' => $e->getMessage(),
      ]), 'error');
    }
    return $return_value;
  }

  /**
   * Edit method.
   *
   * @param string $id
   *   Drum's id.
   * @param string $name
   *   Drum's name.
   */
  public function edit($id, $name, $texture_groups = null, $model = null) {
    $fields = [
      'name' => $name,
      'texture_groups' => implode(',' , $texture_groups),
      'model' => $model,
    ];
    $this->connection->update('drums')
      ->fields($fields)
      ->condition('id', $id)
      ->execute();
  }

  /**
   * Delete method.
   *
   * @param string $id
   *   DB delete query.
   */
  public function delete($id) {
    $this->connection->delete('drums')
      ->condition('id', $id)
      ->execute();
  }

}

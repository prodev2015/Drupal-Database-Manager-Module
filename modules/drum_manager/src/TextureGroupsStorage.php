<?php


namespace Drupal\drum_manager;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Class TextureGroupsStorage.
 *
 * @package Drupal\drum_manager
 */
class TextureGroupsStorage extends ControllerBase
{

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
  public function __construct(Connection $connection, TranslationInterface $translation, MessengerInterface $messenger)
  {
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
  public function getAll()
  {
    $result = $this->connection->select('texture_groups', 's')
      ->fields('s')
      ->execute();
    return $result;
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
  public function exists($id)
  {
    return (bool)$this->get($id);
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
  public function get($id)
  {
    $result = $this->connection->query('SELECT * FROM {texture_groups} WHERE id = :id', [':id' => $id])
      ->fetchAllAssoc('id');
    if ($result) {
      return $result[$id];
    } else {
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
   * @return int|null
   *   DB insert query return value.
   * @throws \Exception
   *   DB insert query.
   *
   */
  public function add($name, $tum, $snare, $floor, $bass, $thumbnail, $material)
  {
    $fields = [
      'name' => $name,
      'tum' => $tum,
      'snare' => $snare,
      'floor' => $floor,
      'bass' => $bass,
      'thumbnail' => $thumbnail,
      'material' => $material,
    ];
    $return_value = NULL;
    try {
      $return_value = $this->connection->insert('texture_groups')
        ->fields($fields)
        ->execute();
    } catch (\Exception $e) {
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
  public function edit($id, $name, $tum, $snare, $floor, $bass, $thumbnail, $material)
  {
    $fields = [
      'name' => $name,
      'tum' => $tum,
      'snare' => $snare,
      'floor' => $floor,
      'bass' => $bass,
      'thumbnail' => $thumbnail,
      'material' => $material,
    ];
    $this->connection->update('texture_groups')
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
  public function delete($id)
  {
    $this->connection->delete('texture_groups')
      ->condition('id', $id)
      ->execute();
  }

}

<?php

namespace App\Database;
require_once './database/dbPdo.php';
use App\Database\dbPdo;
use InvalidArgumentException;

class dbFactory
{
  public static function create(array $options)
  {
    if (!array_key_exists('dsn', $options)) {
      if (!array_key_exists('driver', $options)) {
        throw new InvalidArgumentException('Driver not found in options');
      } else {
        $dsn = '';

        switch ($options['driver']) {
          case 'mysql':
            $dsn = 'mysql:host=' . $options['host'] . ';dbname=' . $options['database'] . ';charset=' . $options['charset'];
            break;
          case 'sqlite':
            $dsn = 'sqlite:' . $options['database'];
            break;
          default:
            throw new InvalidArgumentException('Driver not found in options');
            break;
        }
      }
      $options['dsn'] = $dsn;
    }
    return dbPdo::getInstance($options);
  }
}

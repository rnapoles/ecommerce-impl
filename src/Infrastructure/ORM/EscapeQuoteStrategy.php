<?php

namespace App\Infrastructure\ORM;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\QuoteStrategy;

/**
 * ANSI compliant quote strategy.
 * To use this strategy all mapped tables and columns should be ANSI compliant.
 *
 * @since  2.5
 *
 * @author Fabio B. Silva <fabio.bat.silva@gmail.com>
 */
class EscapeQuoteStrategy implements QuoteStrategy
{
  /**
   * {@inheritdoc}
   */
  public function getColumnName($fieldName, ClassMetadata $class, AbstractPlatform $platform): string
  {
    return $platform->quoteIdentifier($class->fieldMappings[$fieldName]['columnName']);
  }

  /**
   * {@inheritdoc}
   */
  public function getTableName(ClassMetadata $class, AbstractPlatform $platform): string
  {
    return $platform->quoteIdentifier($class->table['name']);
  }

  /**
   * {@inheritdoc}
   */
  public function getSequenceName(array $definition, ClassMetadata $class, AbstractPlatform $platform): string
  {
    return $platform->quoteIdentifier($definition['sequenceName']);
  }

  /**
   * {@inheritdoc}
   */
  public function getJoinColumnName(array $joinColumn, ClassMetadata $class, AbstractPlatform $platform): string
  {
    return $platform->quoteIdentifier($joinColumn['name']);
  }

  /**
   * {@inheritdoc}
   */
  public function getReferencedJoinColumnName(array $joinColumn, ClassMetadata $class, AbstractPlatform $platform): string
  {
    return $platform->quoteIdentifier($joinColumn['referencedColumnName']);
  }

  /**
   * {@inheritdoc}
   */
  public function getJoinTableName(array $association, ClassMetadata $class, AbstractPlatform $platform): string
  {
    return $platform->quoteIdentifier($association['joinTable']['name']);
  }

  /**
   * {@inheritdoc}
   */
  public function getIdentifierColumnNames(ClassMetadata $class, AbstractPlatform $platform): array
  {
    $quotedColumnNames = [];

    foreach ($class->identifier as $fieldName) {
      if (isset($class->fieldMappings[$fieldName])) {
        $quotedColumnNames[] = $this->getColumnName($fieldName, $class, $platform);

        continue;
      }

      // Association defined as Id field
      $joinColumns = $class->associationMappings[$fieldName]['joinColumns'];
      $assocQuotedColumnNames = array_map(
        fn($joinColumn): string => isset($joinColumn['quoted'])
                      ? $platform->quoteIdentifier($joinColumn['name'])
                      : $joinColumn['name'],
        $joinColumns
      );

      $quotedColumnNames = array_merge($quotedColumnNames, $assocQuotedColumnNames);
    }

    return $quotedColumnNames;
  }

  /**
   * {@inheritdoc}
   */
  public function getColumnAlias($columnName, $counter, AbstractPlatform $platform, ClassMetadata $class = null): string
  {
    return strtolower($columnName.'_'.$counter);
    //return $platform->getSQLResultCasing($columnName.'_'.$counter);
  }
}

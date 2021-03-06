<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class RoleType extends StringType
{
    public const NAME = 'user_user_role';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Role ? $value->getName() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Role($value) : null;
    }

    public function getName()
    {
        return self::NAME;
    }

    /**
     * The type "user_user_role" was implicitly marked as commented due to the configuration.
     * This is deprecated and will be removed in DoctrineBundle 2.0. Either set the "commented" attribute
     * in the configuration to "false" or mark the type as commented
     * in "App\Model\User\Entity\User\RoleType::requiresSQLCommentHint()."
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}

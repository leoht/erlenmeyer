<?php

namespace LeoHt\Erlenmeyer\Memory;

use LeoHt\Erlenmeyer\Feature\Feature;

/**
 * PDO memory adapter.
 */
class Pdo implements MemoryInterface
{
    public function __construct(\PDO $pdo, $tableName = 'user_features')
    {
        $this->pdo = $pdo;
        $this->tableName = $tableName;
    }

	public function save(Feature $feature, $userKey, $variant)
    {
        $table = $this->tableName;
    	$stmt = $this->pdo->prepare("INSERT INTO $table (id, user, feature, variant) VALUES (NULL, :user, :feature, :variant)");

        $stmt->bindParam('user', $userKey);
        $stmt->bindParam('feature', $feature->getName());
        $stmt->bindParam('variant', $variant);

        return $stmt->execute();
    }
    
    public function get(Feature $feature, $userKey)
    {
    	return null;
    }
    
    public function clear(Feature $feature, $userKey)
    {
    }

    public function createTable()
    {
        $table = $this->tableName;

        return $this->pdo->exec("CREATE TABLE `$table` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
            `feature` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
            `variant` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY (`id`)
        ) CHARSET=utf8 COLLATE=utf8_unicode_ci");
    }
}
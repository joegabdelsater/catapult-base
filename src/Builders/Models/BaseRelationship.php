<?php
namespace Joegabdelsater\CatapultBase\Builders\Models;
use Joegabdelsater\CatapultBase\Models\Relationship;

abstract class BaseRelationship {
    protected $relationship;

    public function __construct(Relationship $relationship) {
        $this->relationship = $relationship;
    }

    public function generateKeys(): string {
        $availableKeys = [
            'foreignKey' => $this->relationship->foreign_key,
            'localKey' => $this->relationship->local_key,
            'ownerKey' => $this->relationship->owner_key
        ];

        $keys = [];

        foreach ($availableKeys as $key => $value) {
            if (!empty($value)) {
                $keys[] = $key. ':' . "'" . $value . "'";
            }
        }

        if(empty($keys)) {
            return '';
        }

        return ', ' . implode(', ', $keys);
    }
}
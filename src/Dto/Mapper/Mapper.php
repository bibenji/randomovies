<?php

namespace Randomovies\Dto\Mapper;

class Mapper
{
    public function map($dto, $entity)
    {
        $reflect = new \ReflectionClass($dto);
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {
            $propName = $prop->getName();
            $setterMethodName = 'set'.ucfirst($propName);

            if (method_exists($entity, $setterMethodName)) {
                $entity->$setterMethodName($dto->{$propName});
            }
        }
    }
}

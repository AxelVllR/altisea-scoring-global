<?php

namespace App\Services;

use Error;

class HydrateService {

    private $entity;

    public function __construct(array $datas, $entity) {
        if(!is_object($entity)) {
            throw new Error("Erreur technique");
        }
        $this->entity = $entity;
        $this->hydrate($datas);
        return true;
    }

    public function getHydratedEntity() {
        return $this->entity;
    }

    public function hydrate(array $datas) {
        foreach($datas as $method => $data) {
            //var_dump($data);
            $method = "set" . ucfirst(($method));
            if(method_exists($this->entity, $method)) {
                $this->entity->$method($data);
            }
        }
    }

}
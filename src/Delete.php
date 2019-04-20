<?php

namespace Solleer\Form;

class Delete implements GenericForm, Loadable {
    private $mapper;
    private $deleteField;
    private $submitted = false;
    private $id;
    private $errors;

    public function __construct(\ArrayAccess $mapper, string $deleteField = "id", bool $submitted = false, $id = null, array $errors = []) {
        $this->mapper = $mapper;
        $this->deleteField = $deleteField;
        $this->submitted = $submitted;
        $this->id = $id;
        $this->errors = $errors;
    }

    public function submit(array $data) {
        $id = $data[$this->deleteField];

        if (!isset($this->mapper[$id]))
            return new self($this->mapper, $this->deleteField, true, $id, ["There is no matching entry in the database"]);

        unset($this->mapper[$id]);

        return new self($this->mapper, $this->deleteField, true, $id);
    }

    public function getData() {
        return (array) $this->mapper[$this->id];
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function isSubmitted(): bool {
        return $this->submitted;
    }

    public function getDeleteField(): string {
        return $this->deleteField;
    }

    public function load($id) {
        return new self($this->mapper, $this->deleteField, $this->submitted, $id);
    }
}
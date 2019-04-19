<?php

namespace Solleer\Form;

use Respect\Validation\Rules\AllOf as RespectAllOf;
use Respect\Validation\Exceptions\NestedValidationException;

class Save implements GenericForm, Loadable {
    private $mapper;
    private $validator;
    private $data;
    private $submitted = false;
    private $errors;

    public function __construct(
        \ArrayAccess $mapper,
        RespectAllOf $validator,
        $submitted = false,
        $data = [],
        $errors = []
    ) {
        $this->mapper = $mapper;
        $this->validator = $validator;
        $this->submitted = $submitted;
        $this->data = $data;
        $this->errors = $errors;
    }

    public function load($id): self {
        $data = $this->mapper[$id];
        return new self($this->mapper, $this->validator, $this->submitted, (array) $data);
    }

    public function submit(array $data): self {

        try {
            $this->validator->assert($data);
        }
        catch (NestedValidationException $e) {
            $errors = $e->getMessages();
            return new self($this->mapper, $this->validator, true, $data, $errors);
        }

        $dataObj = $this->deepConvert($data);
        $this->mapper[] = $dataObj;

        return new self($this->mapper, $this->validator, true, (array)$dataObj);
    }

    private function deepConvert($data) {
        return json_decode(json_encode($data));
    }

    public function getData() {
        return $this->data;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function isSubmitted(): bool {
        return $this->submitted;
    }
}
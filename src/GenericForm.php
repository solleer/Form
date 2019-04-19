<?php

namespace Solleer\Form;

interface GenericForm {
    public function submit(array $data): self;
    public function getData();
    public function getErrors(): array;
    public function isSubmitted(): bool;
}
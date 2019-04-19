<?php

namespace Solleer\Form;

interface GenericForm {
    public function submit(array $data); // Returns self of the implementing class
    public function getData();
    public function getErrors(): array;
    public function isSubmitted(): bool;
}
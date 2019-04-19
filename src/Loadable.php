<?php

namespace Solleer\Form;


interface Loadable {
    public function load($id): self;
}
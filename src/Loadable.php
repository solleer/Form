<?php

namespace Solleer\Form;


interface Loadable {
    public function load($id); // Returns self of the implementing class
}
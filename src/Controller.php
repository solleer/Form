<?php

namespace Solleer\Form;


class Controller {
    private $request;

    public function __construct(\Level2\Core\Request $request) {
        $this->request = $request;
    }

    public function load(GenericForm $model, $id = null): GenericForm {
        if ($id !== null && $model instanceof Loadable)
            return $model->load($id);
        else
            return $model;
    }

    public function submit(GenericForm $model): GenericForm {
        return $model->submit($this->request->post() ?: []);
    }
}
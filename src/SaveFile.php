<?php

namespace Solleer\Form;


class SaveFile implements GenericForm, Loadable{
    private $saver;
    private $uploader;
    private $submitted = false;
    private $fileErrors = [];

    public function __construct(Save $saver, \FileUpload\FileUpload $uploader, bool $submitted = false, array $fileErrors = []) {
        $this->saver = $saver;
        $this->uploader = $uploader;
        $this->uploader->setFileNameGenerator(new \FileUpload\FileNameGenerator\Custom(function ($sourceName) {
            return $this->setNewFileName($sourceName);
        }));
        $this->submitted = $submitted;
        $this->fileErrors = $fileErrors;
    }

    private function setNewFileName($sourceName) {
        return time() . '-' . $sourceName;
    }

    public function submit(array $data): self {
        // Upload File
        list($files) = $this->uploader->processAll();
        $file = $files[0]; // Only supports uploading a single file
        $errors = [];
        if (!$file->completed) $errors[] = $file->error;
        $data['file_name'] = $file->getFilename();

        if (!empty($errors)) return new self($this->saver, $this->uploader, true, $errors);

        $newSaver = $this->saver->submit($data);
        return new self($newSaver, $this->uploader, true);
    }

    public function getData() {
        return $this->saver->getData();
    }

    public function getErrors(): array {
        return array_merge($this->fileErrors, $this->saver->getErrors());
    }

    public function isSubmitted(): bool {
        return $this->submitted;
    }

    public function load($id): self {
        return new self($this->saver->load($id), $this->uploader, $this->submitted);
    }
}
<?php

namespace Solleer\Form;

use League\Flysystem\Filesystem;

class DeleteFile implements GenericForm, Loadable {
    private $deleter;
    private $filesystem;
    private $submitted = false;
    private $fileErrors;

    public function __construct(Delete $deleter, Filesystem $filesystem, bool $submitted = false, array $fileErrors = []) {
        $this->deleter = $deleter;
        $this->filesystem = $filesystem;
        $this->submitted = $submitted;
        $this->fileErrors = $fileErrors;
    }

    public function submit(array $data): self {
        $loadedDeleter = $this->deleter->load($data[$this->deleter->getDeleteField()]);
        $fileName = $loadedDeleter->getData()['file_name'];
        $submittedDeleter = $this->deleter->submit($data);

        if (!empty($submittedDeleter->getErrors())) return new self($submittedDeleter, $this->filesystem, true);
        if (!$this->filesystem->delete($fileName)) $this->fileErrors[] = "The file to be deleted does not exist.";

        return new self($submittedDeleter, $this->filesystem, true);
    }

    public function getData() {
        return $this->deleter->getData();
    }

    public function getErrors(): array {
        return array_merge($this->fileErrors, $this->deleter->getErrors());
    }

    public function isSubmitted(): bool {
        return $this->submitted;
    }

    public function load($id): self {
        return new self($this->deleter->load($id), $this->filesystem, $this->submitted);
    }
}
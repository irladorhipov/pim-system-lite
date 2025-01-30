<?php

declare(strict_types=1);

namespace App\Controller\Api\DTO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractDTO
{
    protected Request $request;
    protected ValidatorInterface $validator;
    protected array $errors = [];

    abstract public function processRequest(array $params = []): void;

    public function __construct(
        RequestStack       $requestStack,
        ValidatorInterface $validator
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->validator = $validator;

        $this->processRequest($this->getRequestData());
    }

    private function getRequestData(): array
    {
        $content = $this->request->getContent();

        $decodedContent = $content !== '' ? json_decode($content, true) : [];

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(sprintf('Invalid JSON format in request body: %s', json_last_error_msg()));
        }

        $attributes = $this->getAttributes();
        $queryParams = $this->request->query->all();

        return array_merge($decodedContent, $attributes, $queryParams);
    }


    private function getAttributes(): array
    {
        $filtered = [];
        foreach ($this->request->attributes->all() as $key => $value) {
            if (!str_starts_with($key, '_')) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    protected function addError(string $field, string $error): void
    {
        $this->errors[$field][] = $error;
    }

    public function validate(): void
    {
        $violations = $this->validator->validate($this);
        foreach ($violations as $violation) {
            $this->addError($violation->getPropertyPath(), $violation->getMessage());
        }
    }
}

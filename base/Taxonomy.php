<?php

namespace mp_general\base;

use dd_parser\Wizardawn\Models\NPC;

if (!defined('ABSPATH')) {
    exit;
}

class Taxonomy
{
    private $label;
    private $slug;
    private $args = [];

    public function __construct(string $label)
    {
        $this->label = $label;
    }

    public function create(PostType $postType): void
    {
        $objectType = $postType->getObjectType();
        register_taxonomy($this->getSlug(), $objectType, $this->getArgs());
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): Taxonomy
    {
        $this->label = $label;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug ?: BaseFunctions::toSnakeCase($this->label);
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getArgs(): array
    {
        $this->args += [
            'hierarchical' => false,
            'label' => $this->label,
            'query_var' => true,
            'rewrite' => [
                'slug' => $this->getSlug(),
                'with_front' => false,
            ],
        ];
        return $this->args;
    }

    public function setArg(string $key, $arg): self
    {
        $this->args[$key] = $arg;
        return $this;
    }
}

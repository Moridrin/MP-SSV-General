<?php

namespace mp_general\base;

if (!defined('ABSPATH')) {
    exit;
}

class PostType
{
    private $name;
    private $namePlural;
    private $supports = [
        'title',
        'editor',
        'author',
        'thumbnail',
        'trackbacks',
        'custom-fields',
        'comments',
        'revisions',
        'page-attributes',
    ];
    /** @var Taxonomy[] */
    private $taxonomies = [];
    private $menuIcon = 'dashicons-location-alt';
    private $labels = [];
    private $args = [];
    private $objectType;

    public function __construct(string $name, string $namePlural = null)
    {
        if ($namePlural === null) {
            $namePlural = $name . 's';
        }
        $this->name = $name;
        $this->namePlural = $namePlural;
    }

    public function create(): void
    {
        register_post_type($this->getObjectType(), $this->getArgs());
        foreach ($this->taxonomies as $taxonomy) {
            $taxonomy->create($this);
        }
    }

    public function getObjectType(): string
    {
        if (!isset($this->objectType)) {
            $this->objectType = BaseFunctions::toSnakeCase($this->name);
        }
        return $this->objectType;
    }

    public function setObjectType(string $objectType): self
    {
        $this->objectType = $objectType;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getNamePlural(): string
    {
        return $this->namePlural;
    }

    public function setNamePlural(string $namePlural): self
    {
        $this->namePlural = $namePlural;
        return $this;
    }

    public function getSupports(): array
    {
        return $this->supports;
    }

    public function setSupports(array $supports): self
    {
        $this->supports = $supports;
        return $this;
    }

    /**
     * @return Taxonomy[]
     */
    public function getTaxonomies(): array
    {
        return $this->taxonomies;
    }

    public function addTaxonomy(Taxonomy $taxonomy): self
    {
        $this->taxonomies[$taxonomy->getSlug()] = $taxonomy;
        return $this;
    }

    public function getMenuIcon(): string
    {
        return $this->menuIcon;
    }

    public function setMenuIcon(string $menuIcon): self
    {
        $this->menuIcon = $menuIcon;
        return $this;
    }

    public function getLabels(): array
    {
        $this->labels += [
            'name'               => $this->namePlural,
            'singular_name'      => $this->name,
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New '.$this->name,
            'edit_item'          => 'Edit '.$this->name,
            'new_item'           => 'New '.$this->name,
            'view_item'          => 'View '.$this->name,
            'search_items'       => 'Search '.$this->namePlural,
            'not_found'          => 'No '.$this->namePlural.' found',
            'not_found_in_trash' => 'No '.$this->namePlural.' found in Trash',
            'parent_item_colon'  => 'Parent '.$this->name.':',
            'menu_name'          => $this->namePlural,
        ];
        return $this->labels;
    }

    public function setLabel(string $key, string $label): self
    {
        $this->labels[$key] = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArgs()
    {
        $this->args += [
            'labels'              => $this->getLabels(),
            'hierarchical'        => true,
            'description'         => $this->namePlural.' filterable by category',
            'supports'            => $this->supports,
            'taxonomies'          => array_keys($this->taxonomies),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => $this->menuIcon,
            'show_in_nav_menus'   => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'has_archive'         => true,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => true,
            'capability_type'     => 'post',
        ];
        return $this->args;
    }

    /**
     * @param mixed $args
     * @return PostType
     */
    public function setArgs($args)
    {
        $this->args = $args;
        return $this;
    }
}

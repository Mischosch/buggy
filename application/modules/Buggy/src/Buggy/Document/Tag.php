<?php

namespace Buggy\Document;

/**
 * @EmbeddedDocument
 */
class Tag
{
    /**
     * @var string
     */
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
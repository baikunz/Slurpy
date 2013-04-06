<?php

namespace Shuble\Slurpy\Operation;

class DumpDataOperation extends BaseOperation
{
    /**
     * Whether to dump fields data or not
     *
     * @var boolean
     */
    protected $fields;

    /**
     * Whether to encode output as UTF8 or not
     *
     * @var boolean
     */
    protected $utf8;

    /**
     * Constructor
     *
     * @param boolean $fields Whether to dump fields data or not
     * @param boolean $utf8   Whether to encode output as UTF8 or not
     */
    public function __construct($fields = false, $utf8 = false)
    {
        $this->fields = $fields;
        $this->utf8 = $utf8;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getName()
     */
    public function getName()
    {
        $name = 'dump_data';

        if (true === $this->fields) {
            $name = sprintf('%s_fields', $name);
        }

        if (true === $this->utf8) {
            $name = sprintf('%s_utf8', $name);
        }

        return $name;
    }

    /**
     * Sets whether this operation should dump data for fields or not
     *
     * @param boolean $fields
     *
     * @return DumpDataOperation
     */
    public function setFields($fields)
    {
        $this->fields = (boolean) $fields;

        return $this;
    }

    /**
     * Gets whether this operation should dump data for fields or not
     *
     * @return boolean
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Sets whether this operation should dump data as utf8 or not
     *
     * @param boolean $utf8
     *
     * @return DumpDataOperation
     */
    public function setUtf8($utf8)
    {
        $this->utf8 = (boolean) $utf8;

        return $this;
    }

    /**
     * Gets whether this operation should dump data as utf8 or not
     *
     * @return boolean
     */
    public function getUtf8()
    {
        return $this->utf8;
    }
}

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
}

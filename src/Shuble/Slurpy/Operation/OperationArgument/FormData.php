<?php

/**
 * This file is part of the Slurpy package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Shuble\Slurpy\Operation\OperationArgument;

/**
 * FormData
 *
 * @author dorian ALKOUM <baikunz@gmail.com>
 */
class FormData
{
    /**
     * An array of fields indexed by field names
     *
     * @var array
     */
    protected $fields = array();

    /**
     * Constructor
     *
     * @param array $fields An array of fields indexed by field names
     */
    public function __construct($fields = array())
    {
        $this->addFields($fields);
    }

    /**
     * Add fields
     *
     * @param array $fields An array of fields indexed by field names
     *
     * @return FormData
     */
    public function addFields($fields)
    {
        foreach ($fields as $fieldName => $value) {
            $this->addField($fieldName, $value);
        }

        return $this;
    }

    /**
     * Set fields
     *
     * @param array $fields An array of fields indexed by field names
     *
     * @return FormData
     */
    public function setFields($fields)
    {
        foreach ($fields as $fieldName => $value) {
            $this->setField($fieldName, $value);
        }

        return $this;
    }

    /**
     * Get fields
     *
     * @return array An array of fields indexed by field names
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Check whether a field is defined or not
     *
     * @param string $fieldName
     */
    public function hasField($fieldName)
    {
        return isset($this->fields[$fieldName]);
    }

    /**
     * Adds a field
     *
     * @param string $fieldName Name of the field
     * @param string $value     Value of the field
     *
     * @throws \InvalidArgumentException If the field is already defined
     *
     * @return FormData
     */
    public function addField($fieldName, $value)
    {
        if ($this->hasField($fieldName)) {
            throw new \InvalidArgumentException(sprintf('Field "%s" is already defined', $fieldName));
        }

        $this->fields[$fieldName] = $value;

        return $this;
    }

    /**
     * Sets a field name and value
     *
     * @param string $fieldName Name of the field
     * @param string $value     Value of the field
     *
     * @throws \InvalidArgumentException If the field is not defined
     *
     * @return FormData
     */
    public function setField($fieldName, $value)
    {
        if (!$this->hasField($fieldName)) {
            throw new \InvalidArgumentException(sprintf('Field "%s" is not defined', $fieldName));
        }

        $this->fields[$fieldName] = $value;

        return $this;
    }

    /**
     * Gets a field value from it's name
     *
     * @param string fieldName Name of the field
     *
     * @throws \InvalidArgumentException If the field is not defined
     *
     * @return string Value of the field
     */
    public function getField($fieldName)
    {
        if (!$this->hasField($fieldName)) {
            throw new \InvalidArgumentException(sprintf('Field "%s" is not defined', $fieldName));
        }

        return $this->fields[$fieldName];
    }

    /**
     * Get the FormData under XFDF format
     *
     * @param string $file     filepath or url of the pdf
     * @param string $encoding Encoding (Default: UTF-8)
     *
     * @return string XFDF resulting file
     */
    public function getXfdf($file, $modified = null, $encoding = 'UTF-8')
    {
        $fields = array();
        foreach ($this->fields as $fieldName => $value) {
            $fields[] = sprintf('<field name="%s"><value>%s</value></field>',
                htmlspecialchars($fieldName),
                htmlspecialchars($value)
            );
        }
        $fields = implode("", $fields);
        $original = md5($file);
        $modified = null === $modified ? time() : $modified;
        $href = $file;

        $xfdf =
<<<XML
<?xml version="1.0" encoding="{$encoding}" ?>
<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">
    <fields>
        {$fields}
    </fields>
    <ids original="{$original}" modified="{$modified}" />
    <f href="{$href}" />
</xfdf>
XML;

        return $xfdf;
    }
}

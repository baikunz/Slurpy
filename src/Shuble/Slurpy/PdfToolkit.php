<?php

namespace Shuble\Slurpy;

use Shuble\Slurpy\Operation\OperationInterface;

/**
 * Wrapper for the pdftk library
 *
 * @package Slurpy
 *
 * @author  ALKOUM Dorian <dorian.alkoum@gmail.com>
 */
class PdfToolkit
{
    /**
     * Path to pdftk binary
     *
     * @var string
     */
    protected $binary;

    /**
     * Options to use when calling pdftk
     *
     * @var array
     */
    protected $options = array();

    /**
     * An array of file paths
     *
     * @var InputFile[]
     */
    protected $inputs = array();

    /**
     * Output file path
     *
     * @var string
     */
    protected $output;

    /**
     * Operation to execute
     *
     * @var OperationInterface
     */
    protected $operation;

    /**
     * Constructor
     *
     * @param string $binary
     * @param array  $options
     */
    public function __construct($binary, array $options = array(), array $inputs = array(), $output = null, $operation = null)
    {
        $this->configure();

        $this->setBinary($binary);
        $this->setOptions($options);
        $this->setInputs($inputs);
        $this->setOutput($output);
        $this->setOperation($operation);
    }

    /**
     * Sets pdftk binary path
     *
     * @param string $binary
     *
     * @return PdfTk
     */
    public function setBinary($binary)
    {
        $this->binary = $binary;

        return $this;
    }

    /**
     * Get pdftk binary path
     *
     * @return string
     */
    public function getBinary()
    {
        return $this->binary;
    }

    /**
     * Sets an array of options
     *
     * @param array $options An associative array of options as name/value
     *
     * @throws \InvalidArgumentException If an option does not exist
     *
     * @return PdfTk
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }

        return $this;
    }

    /**
     * Sets and option. Be aware that option values are NOT validated
     *
     * @param string $name  Name of the option to set
     * @param mixed  $value Value of the option (null to unset)
     *
     * @throws \InvalidArgumentException If the option does not exist
     *
     * @return PdfTk
     */
    public function setOption($name, $value)
    {
        if (!array_key_exists($name, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The option \'%s\' does not exist.', $name));
        }

        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Adds options
     *
     * @param array $options
     *
     * @throws \InvalidArgumentException If an options is already set
     *
     * @return PdfTk
     */
    public function addOptions(array $options)
    {
        foreach ($options as $name => $default) {
            $this->addOption($name, $default);
        }

        return $this;
    }

    /**
     * Adds an option
     *
     * @param string $name    Name of the option
     * @param mixed  $default an optional default value
     *
     * @throws \InvalidArgumentException
     *
     * @return PdfTk
     */
    public function addOption($name, $default = null)
    {
        if (array_key_exists($name, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The option \'%s\' already exists.', $name));
        }

        $this->options[$name] = $default;

        return $this;
    }

    /**
     * Return the options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets all options
     */
    protected function configure()
    {
        $this->addOptions(array(
            'input_pw'       => null,
            'encrypt_40bit'  => null,
            'encrypt_128bit' => null,
            'allow'          => null,
            'owner_pw'       => null,
            'user_pw'        => null,
            'flatten'        => null,
            'compress'       => null,
            'uncompress'     => null,
            'keep_first_id'  => null,
            'keep_final_id'  => null,
            'drop_xfa'       => null,
        ));
    }

    /**
     * Returns the command for the given input and output files
     *
     * TODO: take inputs / operation into account
     *
     * @param  string $input   The input file
     * @param  string $output  The ouput file
     * @param  array  $options An optional array of options that will be used
     *                         only for this command
     *
     * @return string
     */
    public function getCommand($input, $output, array $options = array())
    {
        $options = $this->mergeOptions($options);

        return $this->buildCommand($input, $output, $options);
    }

    /**
     * Builds the command string
     *
     * @param  string $input    Url or file location of the page to process
     * @param  string $output   File location to the image-to-be
     * @param  array  $options  An array of options
     *
     * TODO: Take operation / inputs... into account
     *
     * @return string
     */
    protected function buildCommand($input, $output, array $options = array())
    {
        $command = $this->binary;

        // Input files
        $inputPasswords = array();
        foreach ($this->inputs as $input) {
            $command .= sprintf(' %s=%s', $input->getHandle(), $input->getFilePath());

            if (null !== $input->getPassword()) {
                $inputPasswords[] = sprintf('%s=%s', $input->getHandle(), $input->getPassword());
            }
        }

        if (!empty($inputPasswords)) {
            $command .= sprintf(' input_pw %s', implode(' ', $inputPasswords));
        }

        // Operation
        if (null !== $this->operation) {
            $command .= $this->operation->getName();

            if (!empty($this->operation->getArguments())) {
                $args = $this->operation->getArguments();
                array_map(escapeshellarg, $args);
                $args = implode(' ', $args);

                $command .= ' '. $args;
            }
        }

        // Output
        $command .= sprintf(' output %s', $this->output);

        // Global options
        foreach ($options as $key => $option) {
            if (null === $option || false === $option) {
                continue;
            }

            if (true === $option) {
                $command .= $key;
            } else {
                if (is_array($option)) {
                    array_map(escapeshellarg, $option);
                    $option = implode(' ', $option);
                } else {
                    $option = escapeshellarg($option);
                }

                $command .= sprintf(' %s %s', $key, $option);
            }
        }

        return $command;
    }

    /**
     * Merges the given array of options to the instance options and returns
     * the result options array. It does NOT change the instance options.
     *
     * @param  array $options
     *
     * @return array
     */
    protected function mergeOptions(array $options)
    {
        $mergedOptions = $this->options;

        foreach ($options as $name => $value) {
            if (!array_key_exists($name, $mergedOptions)) {
                throw new \InvalidArgumentException(sprintf('The option \'%s\' does not exist.', $name));
            }

            $mergedOptions[$name] = $value;
        }

        return $mergedOptions;
    }
}
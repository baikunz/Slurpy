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
class Slurpy
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
    public function __construct($binary, array $options = array(), array $inputs = array(), $output = null, OperationInterface $operation = null)
    {
        $this->configure();

        $this->setOptions($options);

        $this->binary = $binary;
        $this->inputs = $inputs;
        $this->output = $output;
        $this->operation = $operation;
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

    public function setInputs(array $inputs)
    {
        foreach ($inputs as $input) {
            $this->addInput($input);
        }

        return $this;
    }

    public function addInput(InputFile $input)
    {
        $this->inputs[] = $input;

        return $this;
    }

    public function getInputs()
    {
        return $this->inputs;
    }

    public function setOperation(OperationInterface $operation)
    {
        $this->operation = $operation;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    public function getOutput()
    {
        return $this->output;
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
     * Return the options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Returns the command for the given input and output files
     *
     * @codeCoverageIgnore
     *
     * @param string $input   The input file
     * @param string $output  The ouput file
     * @param array  $options An optional array of options that will be used
     *                         only for this command
     *
     * @return string
     */
    public function getCommand(array $options = array())
    {
        $options = $this->mergeOptions($options);

        return $this->buildCommand($options);
    }

    /**
     * Sets all options
     */
    protected function configure()
    {
        $this->addOptions(array(
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
     * Builds the command string
     *
     * @param string $input   Url or file location of the page to process
     * @param string $output  File location to the image-to-be
     * @param array  $options An array of options
     *
     * @throws \InvalidArgumentException if an option value is an array
     *
     * @return string
     */
    protected function buildCommand(array $options = array())
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
        if (null !== $this->operation && null !== $this->operation->getName()) {
            $command .= ' '. $this->operation->getName();

            $args = $this->operation->getArguments();
            if (!empty($args)) {
                $args = implode(' ', array_map('escapeshellarg', $args));
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
                $command .= ' '. $key;

                continue;
            }

            $option = is_array($option)
                ? implode(' ', array_map('escapeshellarg', $option))
                : escapeshellarg($option)
            ;

            $command .= sprintf(' %s %s', $key, $option);
        }

        return $command;
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
    protected function addOptions(array $options)
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
    protected function addOption($name, $default = null)
    {
        if (array_key_exists($name, $this->options)) {
            throw new \InvalidArgumentException(sprintf('The option \'%s\' already exists.', $name));
        }

        $this->options[$name] = $default;

        return $this;
    }

    /**
     * Merges the given array of options to the instance options and returns
     * the result options array. It does NOT change the instance options.
     *
     * @param array $options
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

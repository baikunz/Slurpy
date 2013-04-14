<?php

namespace Shuble\Slurpy\Operation;

interface OperationInterface
{
    /**
     * Get the operation's name
     *
     * @return string
     */
    public function getName();

    /**
     * Get the operation's arguments as an array of string
     *
     * @return array
     */
    public function getArguments();

    /**
     * Get the operation's stdin
     *
     * @return null|string
     */
    public function getStdin();
}

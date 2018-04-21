<?php


class ApiKeyGenerationException extends \Exception
{
    protected $code = 1001;

    protected $message = "Random generator tried really, really hard to find a new API key, but failed. We are now aware of the issue, maybe try again later?";

}
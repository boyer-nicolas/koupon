<?php

namespace Koupon\Api;

use Exception;

/**
 * Class Log
 */
final class Log
{

    private string $green;
    private string $red;
    private string $yellow;
    private string $purple;
    private string $white;
    private string $cyan;
    private string $reset;
    private string $blue;
    private \Whoops\Run $whoops;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->red = "\033[31m";
        $this->green = "\033[32m";
        $this->yellow = "\033[33m";
        $this->blue = "\033[34m";
        $this->purple = "\033[35m";
        $this->cyan = "\033[36m";
        $this->white = "";
        $this->reset = "\033[0m";
    }

    /**
     * @return bool
     */
    public static function testConsole(): bool
    {
        try
        {
            $self = new self();
            $self::console("Test", "success");
            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }

    /**
     * @param string $message
     * @param string $type
     * @param object|null $e
     *
     * @return void
     * @throws Exception
     */
    public static function console(string $message, string $type = "error", object $e = null): void
    {
        try
        {
            $self = new self();
            $date = date('d/m/Y H:i:s') . ".000";

            $messageColor = match ($type)
            {
                "success" => $self->green,
                "warning" => $self->yellow,
                "info" => $self->blue,
                "classic" => $self->white,
                default => $self->red,
            };

            $statusCodeMessage = "";

            if ($e !== null)
            {
                $line = $e->getLine();
                $file = $e->getFile();
                // Add the file and line number to the message
                $trace = " in file $self->cyan" . $file;
                $trace .= "$self->reset on line $self->cyan" . $line . "$self->reset" . PHP_EOL . PHP_EOL;
                if ($type === "error")
                {
                    $message .= $trace;
                }

                $statusCodeMessage = $e->getCode();
            }
            $message = "$self->purple[$date]$self->reset $messageColor$message$self->reset $statusCodeMessage";


            // Write the message to the log file
            file_put_contents("php://stdout", $message . PHP_EOL);
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }
}

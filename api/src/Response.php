<?php

namespace Koupon\Api;

use Exception;

/**
 * Class Response
 */
final class Response
{

    /**
     * @param string $html
     * @return never
     */
    public static function html(string $html): never
    {
        header('Content-Type: text/html');
        die($html);
    }

    /**
     * @param string $text
     * @return never
     */
    public static function text(string $text): never
    {
        header('Content-Type: text/plain');
        die($text);
    }

    /**
     * @param string $xml
     * @return never
     */
    public static function xml(string $xml): never
    {
        header('Content-Type: text/xml');
        die($xml);
    }

    /**
     * @param string $csv
     * @return never
     */
    public static function csv(string $csv): never
    {
        header('Content-Type: text/csv');
        die($csv);
    }

    /**
     * @param string $pdf
     * @return never
     */
    public static function pdf(string $pdf): never
    {
        header('Content-Type: application/pdf');
        die($pdf);
    }


    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public static function json(array $data): void
    {
        try
        {
            die(json_encode($data));
        }
        catch (Exception $e)
        {
            die(http_response_code(400));
        }
    }
}

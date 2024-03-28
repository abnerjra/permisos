<?php

namespace App\Helpers;

class CustomResponse
{
    /** 
     * Retorno de respuesta con formato
     * @param array $message    ->  content['message', 'severity', 'code']
     * @param array $data       ->  content query response
     * @param array $paginado   ->  Content paginate query response
     * 
     * @return json ->  Response in format json
     */
    public static function success($message, $data = [], $paginado = [])
    {
        $respuesta = [
            'message' => $message['message'],
            'severity' => $message['severity'],
            'results'  => $data
        ];

        if (!empty($paginado)) {
            $respuesta = [
                'message' => $message['message'],
                'severity' => $message['severity'],
                'results'  => $data,
                'totalRecords' => $paginado['total_records'],
                'totalPerPage' => $paginado['total_per_page']
            ];
        }
        return self::generateResponse($respuesta, $message['code']);
    }

    /** 
     * Retorno de respuesta erronea
     * @param array $message    ->  content['message', 'severity', 'code']
     * @param array $data       ->  content response with errors
     * 
     * @return json ->  Response in format json
     */
    public static function error($message, $data = [])
    {
        return self::generateResponse([
            'message' => $message['message'],
            'severity' => $message['severity'],
            'results'  => $data,
        ], $message['code']);
    }

    private static function generateResponse($array, $status)
    {
        return Response($array)->setStatusCode($status);
    }
}

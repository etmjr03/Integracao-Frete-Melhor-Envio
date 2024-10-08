<?php

namespace App\Http\Controllers\Integracao;

use App\Http\Controllers\Controller;
use App\Models\IntegracaoMelhorEnvio;
use Illuminate\Http\Request;

class IntegracaoMelhorEnvioController extends Controller
{
    /**
     * @method responsável por retornar as informações da integração
     * @return array de informações da integração
     */
    public static function getInformacoesIntegracao(){
        $obInformacoesMelhorEnvio = IntegracaoMelhorEnvio::getInformacoesMelhorEnvio();

        return $obInformacoesMelhorEnvio;
    }

    /**
     * @method responsável por fazer uma requisição para a api da Melhor Envio
     * @param string $metodo método da requisição GET, POST, PUT, DELETE
     * @param string $uri uri final do endpoint /cart /balance
     * @param mixed $body body da requisição
     * @return object|array response da requisição
     */
    public static function executarRequisicao($metodo, $uri = null, $body = null){
        $obInformacoesMelhorEnvio = self::getInformacoesIntegracao();
        $header = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Bearer ".$obInformacoesMelhorEnvio['token'],
            "User-Agent: Aplicação ".$obInformacoesMelhorEnvio['email']
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => $obInformacoesMelhorEnvio['endpoint'].''.$uri,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_CUSTOMREQUEST  => $metodo,
        CURLOPT_HTTPHEADER     => $header,
        CURLOPT_POSTFIELDS     => json_encode($body),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        empty($error) ? $retorno = $response : $retorno = $error;

        return json_decode($retorno);
    }
}

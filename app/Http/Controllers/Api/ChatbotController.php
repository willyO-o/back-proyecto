<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    //

    public function index(Request $request)
    {

        $mensajes = $request->input('mensajes');
        $campos = json_encode($request->input('campos'));

        $apiKey = env('GROQ_API_KEY');


        $prompt = 'Eres un asistente que llena formularios.
                    DEBES responder SIEMPRE en formato JSON: {"mensaje": "...", "campos": {' . $campos . '}.
                    Mantén el hilo de la conversación. y no respondas nada que no sea el JSON. tampoco respondas con texto plano.
                    Si es usuario te pregunta algo no relacionado con el sitio web establecimientos, servicios, categorías, o algo
                    que no tenga que ver con el sitio web, responde con un mensaje de no puedo ayudarte con la informacion que no sea realacionada
                    al sitio web en el campo  "mensaje" y deja "campos" vacío, solo agrega los datos de los campos si te lo solicitan por ejemplo si utilizan
                    palabras como , ayudame a crea, o quiero redaccion, o ayudame a completar. tambien procura mantener el formato de los campos para la respuesta

                    a continuacion te envio las consultas del usuario: ';

        // $prompt='';
        $mensajesParaGroq = [];

        foreach ($mensajes as $msj) {
            $mensajesParaGroq[] = [
                'role' => $msj['role'],
                'content' => ($msj['role']  == 'user' ? $prompt : '') . $msj['content']
            ];
        }


        $respuesta =  Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $apiKey,
        ])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                "messages" => $mensajesParaGroq,
                "model" => "llama-3.3-70b-versatile",
                "temperature" => 1,
                "max_completion_tokens" => 8192,
                "top_p" => 1,
                "stream" => false,
                'response_format' => [
                    'type' => 'json_object',
                ]

            ]);

        if ($respuesta->successful()) {
            $resultado = $respuesta->json();
            return response()->json([
                'respuesta' => $resultado['choices'][0]['message']['content']
            ]);
        }




        return response()->json([
            'error' => 'Error al comunicarse con el modelo de lenguaje',
            'details' => $respuesta->body()
        ], 500);
    }
}

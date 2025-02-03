<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;

class OpenAIController extends Controller
{
    //
    public function index()
    {
        return view('chat');
    }

    //
    public function ai(Request $request) {
        $res = "error";
        if (isset($request->message)) {
            $res2 = json_decode($this->sendRequest($request['message']), true);
            $res = $res2['choices'][0]['message']['content'];
        }
        return ["respuesta" => $res];
    }

    // private function sendRequest2($text)
    // {
    //     // https://github.com/openai-php/client
    //     $yourApiKey = getenv('OPEN_API_KEY');
    //     $client = OpenAI::client($yourApiKey);
    //     $res = $client->chat()->create([
    //         'model' => 'gpt-4o-mini',
    //         'messages' => [
    //             ['role' => 'user', 'content' => $text],
    //         ],
    //     ]);
    //     return $res;
    // }
    private function sendRequest($message)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . getenv('OPEN_API_KEY')
        ]);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            "{ \"model\": \"gpt-4o-mini\", \"messages\": [{\"role\": \"system\", \"content\": \"Te llamas Amancio Ortega y eres un especialista en frutas y verduras!\"}, {\"role\": \"user\", \"content\": \"$message\"}]}");

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
}

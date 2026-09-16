<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Customer;
use Illuminate\Http\Request;

class RepairAssistantController extends Controller
{
    public function index(Request $request)
    {
        $messages = $request->session()->get('repair_assistant.messages', [[
            'from' => 'assistant',
            'text' => 'Hola. Puedo registrar una reparación o consultar el estado de un pedido. Escribe "registrar" o "estado" para comenzar.',
        ]]);
        $state = $request->session()->get('repair_assistant.state', ['step' => 'start', 'data' => []]);

        return view('repairs.assistant', compact('messages', 'state'));
    }

    public function message(Request $request)
    {
        $data = $request->validate(['message' => 'required|string|max:500']);
        $message = trim($data['message']);
        $session = $request->session();
        $state = $session->get('repair_assistant.state', ['step' => 'start', 'data' => []]);
        $messages = $session->get('repair_assistant.messages', []);
        $messages[] = ['from' => 'user', 'text' => $message];
        $lowerMessage = mb_strtolower($message);

        if (in_array($lowerMessage, ['cancelar', 'cancel', 'salir'], true)) {
            $session->forget('repair_assistant.state');
            $messages[] = ['from' => 'assistant', 'text' => 'Registro cancelado. Escribe "registrar" o "estado" cuando quieras comenzar.'];
            $session->put('repair_assistant.messages', $messages);
            return back();
        }

        if ($state['step'] === 'start') {
            if (str_contains($lowerMessage, 'estado') || str_contains($lowerMessage, 'consulta')) {
                $state['step'] = 'query_phone';
                $reply = 'Claro. ¿Cuál es el número de teléfono del cliente?';
            } else {
                $state['step'] = 'phone';
                $reply = 'Vamos a registrar una reparación. ¿Cuál es el número de teléfono del cliente?';
            }
        } elseif (in_array($state['step'], ['phone', 'query_phone'], true)) {
            $phone = preg_replace('/[^0-9+]/', '', $message);
            if (!preg_match('/^\+?[0-9]{7,15}$/', $phone)) {
                $reply = 'El teléfono debe tener entre 7 y 15 dígitos. Escríbelo nuevamente o escribe "cancelar".';
            } elseif ($state['step'] === 'query_phone') {
                $repairs = Repair::where('customer_phone', $phone)->latest()->take(5)->get();
                $reply = $repairs->isEmpty()
                    ? 'No encontré reparaciones con ese teléfono.'
                    : $repairs->map(fn (Repair $repair) => "{$repair->product_description}: {$repair->status}")->implode("\n");
                $state = ['step' => 'start', 'data' => []];
            } else {
                $state['data']['customer_phone'] = $phone;
                $customer = Customer::where('phone', $phone)->first();
                if ($customer) {
                    $state['data']['customer_id'] = $customer->id;
                    $state['data']['customer_name'] = $customer->name;
                    $state['step'] = 'shoe';
                    $reply = "Encontré al cliente {$customer->name}. ¿Qué zapato dejó?";
                } else {
                    $state['step'] = 'name';
                    $reply = 'No encontré un cliente con ese teléfono. ¿Cuál es su nombre? Lo crearé al guardar la reparación.';
                }
            }
        } elseif ($state['step'] === 'name') {
            $state['data']['customer_name'] = $message;
            $state['step'] = 'shoe';
            $reply = '¿Qué zapato dejó el cliente?';
        } elseif ($state['step'] === 'shoe') {
            $state['data']['product_description'] = $message;
            $state['step'] = 'repair_type';
            $reply = '¿Qué tipo de reparación necesita? Por ejemplo: cambio de suela, costura o limpieza.';
        } elseif ($state['step'] === 'repair_type') {
            $state['data']['description'] = $message;
            $state['step'] = 'price';
            $reply = '¿Cuál es el precio estimado? Escribe sólo el valor o "sin precio".';
        } elseif ($state['step'] === 'price') {
            $price = preg_replace('/[^0-9.,]/', '', $message);
            $price = str_replace(',', '.', $price);
            if (! in_array($lowerMessage, ['sin precio', 'no sé', 'no se', 'por definir'], true) && (! is_numeric($price) || (float) $price < 0)) {
                $reply = 'Escribe un precio válido, por ejemplo 35000, o responde "sin precio".';
            } else {
                $state['data']['price'] = in_array($lowerMessage, ['sin precio', 'no sé', 'no se', 'por definir'], true) ? null : (float) $price;
                $state['step'] = 'confirm';
                $summary = $state['data'];
                $displayPrice = $summary['price'] === null ? 'Por definir' : '$' . number_format($summary['price'], 2, ',', '.');
                $reply = "Resumen de la reparación:\nCliente: {$summary['customer_name']}\nTeléfono: {$summary['customer_phone']}\nZapato: {$summary['product_description']}\nReparación: {$summary['description']}\nPrecio estimado: {$displayPrice}\n\n¿Deseas guardar esta reparación? Responde sí o no.";
            }
        } elseif ($state['step'] === 'confirm') {
            if (in_array($lowerMessage, ['sí', 'si', 's', 'confirmar', 'guardar'], true)) {
                $repairData = $state['data'];
                if (empty($repairData['customer_id'])) {
                    $customer = Customer::create([
                        'name' => $repairData['customer_name'],
                        'phone' => $repairData['customer_phone'],
                    ]);
                    $repairData['customer_id'] = $customer->id;
                }
                Repair::create($repairData + ['received_at' => now(), 'status' => 'Recibido']);
                $state = ['step' => 'start', 'data' => []];
                $reply = 'Reparación guardada correctamente. Escribe "registrar" o "estado" para otra consulta.';
            } elseif (in_array($lowerMessage, ['no', 'n'], true)) {
                $state = ['step' => 'start', 'data' => []];
                $reply = 'No guardé la reparación. Escribe "registrar" para comenzar de nuevo.';
            } else {
                $reply = 'Responde sí para guardar, no para descartar o cancelar para salir.';
            }
        } else {
            $state = ['step' => 'start', 'data' => []];
            $reply = 'No entendí el paso actual. Escribe "registrar" o "estado" para comenzar.';
        }

        $messages[] = ['from' => 'assistant', 'text' => $reply];
        $session->put('repair_assistant.state', $state);
        $session->put('repair_assistant.messages', $messages);

        return back();
    }
}
<?php

namespace App\Http\Controllers\Sales\Pos;

use App\Http\Controllers\Controller;
use App\Models\Sales\Pos\PosTerminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class PosAccessController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'terminal_id' => 'required|exists:pos_terminals,id',
            'pin' => 'required|numeric|digits:4'
        ]);

        $terminal = PosTerminal::findOrFail($request->terminal_id);

        if (!$terminal->verifyPin($request->pin)) {
            return response()->json([
                'message' => 'PIN de terminal incorrecto.'
            ], 422);
        }

        // Si es correcto, guardamos el acceso en la sesión con timestamp
        // Usamos una estructura de array para soportar múltiples terminales si fuera necesario
        session()->put("terminal_verified.{$terminal->id}", now()->timestamp);

        return response()->json([
            'message' => 'Acceso concedido.',
            'status' => 'success'
        ]);
    }

    public function lock(PosTerminal $pos_terminal) // Debe llamarse igual que en la ruta {pos_terminal}
    {
        return view('sales.pos.lock', ['terminal' => $pos_terminal]);
    }
}
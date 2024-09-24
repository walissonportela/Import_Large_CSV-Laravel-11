<?php

namespace App\Http\Controllers;

use App\Jobs\ImportCsvJob;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function index()
    {
        return view('users.index');
    }

    public function import(Request $request)
    {
        // Validar o arquivo 
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:8192' // 8 Mb
        ], [
            'file.required' => 'O campo arquivo é obrigatório!',
            'file.mimes' => 'Arquivo inválido, necessário enviar arquivo CSV.',
            'file.max' => 'Tamanho do arquivo excede :max Mb.',
        ]);

        // Gerar um nome arquivo baseado na data e hora atual 
        $timestamp = now()->format('Y-m-d-H-i-s');
        $filename = "import-{$timestamp}.csv";

        // Receber um arquivo e movê-lo para um lugar temporário
        $path = $request->file('file')->storeAs('uploads', $filename);

        // Despachar o Job para processar o Csv
        ImportCsvJob::dispatch($path);

        // Redirecionar o usuário para a página anterior e enviar a mensagem de sucesso
        return back()->with('success', 'Dados estão sendo importados!');
    }
}

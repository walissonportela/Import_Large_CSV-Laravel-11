<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Str;

class ImportCsvJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        //
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Ler o conteúdo do arquivo com o método createFromPath sem abrir o arquivo 
        $csv = Reader::createFromPath(storage_path('app/' . $this->filePath), 'r');

        // Definir o delimitador com ponto e vírgula 
        $csv->setDelimiter(';');

        // Definir a primeira linha como cabeçalho
        $csv->setHeaderOffset(0);

        // Inicializar o offset para começar do início do arquivo
        $offset = 0;
        $limit = 100;

        // Continuar processando até que todos os arquivos sejam lidos
        while (true){

            // Definir o início e o fim das linhas que devem ser lidas
            $stmt = (new Statement())->offset($offset)->limit($limit);

            // Retorna uma coleção de arrays associativos, cada Array representa uma linha do arquivo CSV
            // Com base no offset e limit definidos 
            $records = $stmt->process($csv);

            // Se não houver mais registros, sair do loop
            if(count($records) === 0){
                break;
            }

            // Percorrer as linhas do arquivo
            foreach($records as $record){

                // Criar array de informações de novo registro
                $userData = [
                    'name' => $record['name'],
                    'email' => $record['email'],
                    'password' => Hash::make(Str::random(7), ['rounds' => 12]),
                ];

                // Verifica se o email já está cadastrado 
                if(User::where('email', $userData['email'])->exists()){
                    // Salvar o log indicando que o e-mail já está cadastrado
                    continue;
                }

                // Inserir os dados no banco de dados
                User::create($userData);

                // Salvar o log indicando o email cadastrado com sucesso
            }

            // Atualizar o offset para a próxima iteração 
            $offset += $limit;

        }

    }
}
 
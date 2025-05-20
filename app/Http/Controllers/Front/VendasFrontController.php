<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Banco;
use App\Models\User;
use DB;
use Hash;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

class VendasFrontController extends Controller {

    public function __construct() {
        //$this->middleware('auth:web');
    }

    public function store(Request $request) {
        if (!empty($request->input('id'))) {
            $id = $request->input('id');
            $venda = Venda::findOrFail($id);
        } else {
            $venda = new Venda();
        }
        $data = $request->only($venda->getFillable());
        if (!empty($id)) {
            unset($data['id']);
        }
        $venda->fill($data)->save();
        return redirect()->route('customer_vendadeveiculos')->with('success', SUCCESS_DATA_ADD);
    }

    public function destroy($id) {
        $venda = Venda::findOrFail($id);
        $venda->delete();
        return redirect()->back()->with('success', SUCCESS_DATA_DELETE);
    }

    public function import(Request $request) {

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:20480'
                ], [
            'file.required' => 'O envio do arquivo é obrigatório',
            'file.mimes' => 'O arquivo deve ser .xlsx ou .xls',
            'file.max' => 'Tamanho máximo do arquivo 10MB'
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Validação do cabeçalho
            $header = ['COMO CONHECEU A EMPRESA?', 'NOME DO CLIENTE', 'CPF DO CLIENTE', 'DATA DA VENDA',
                'DATA PAGAMENTO FINANCIAMENTO', 'VEÍCULO', 'PLACA', 'VALOR TOTAL FINANCIAMENTO', 'FINANCEIRA'];

            if ($rows[0] !== $header) {
                return back()->with('error', 'O arquivo não segue o formato esperado!');
            }

            unset($rows[0]); // Remove a linha do cabeçalho

            foreach ($rows as $row) {
                if (count($row) < 9)
                    continue; // Garante que a linha tenha todos os campos necessários

                if (!empty($row[1]) && !empty($row[2]) && !empty($row[5]) && !empty($row[6]) && !empty($row[8])) {
                    $banco = Banco::where('banco', 'LIKE', "%{$row[8]}%")->first();
                    $arr_banco = array(
                        'banco'=>$row[8],
                        'totalFinanciado'=>0,
                        'porcentagem'=>0,
                        'totalBruto'=>0,
                        'totalLiquido'=>0,
                        'desconto'=>0,
                    );
                    if($banco){
                        $banco_id = $banco->id;
                    }else{
                        $banco_id = Banco::create($arr_banco);
                    }
                    $placa = $this->PT_limpaCPF_CNPJ($row[6]);
                    $placa_formatada = $this->mask($placa, '###-####');

                    $cpf_limpo = $this->PT_limpaCPF_CNPJ($row[2]);
                    $cpf_formatado = $this->mask($cpf_limpo, '###.###.###-##');

                    $dataVenda = $this->formatarData($row[3]);
                    $dataPagamentoFinanciamento = $this->formatarData($row[4]);

                    
                    $valorSemSimboloMonetario = $this->removeSimboloMonetario($row[7]);
                    if (strpos($valorSemSimboloMonetario, ',') !== false) {
                        $result = explode(',', $valorSemSimboloMonetario);
                        $real = $result[0];
                        $centavos = $result[1];
                        $valorvalorTotalFinanciamento = $this->PT_limpaCPF_CNPJ($real).'.' . $centavos;
                    } else {
                        $valorvalorTotalFinanciamento = number_format($valorSemSimboloMonetario, 2, '.', '');
                    }

                    
                    $array_data = [
                        'user_id' => Auth::user()->id,
                        'como_nos_conheceu' => $row[0],
                        'nomeCliente' => $row[1],
                        'cpfCliente' => $cpf_formatado,
                        'dataVenda' => $dataVenda,
                        'dataPagamentoFinanciamento' => $dataPagamentoFinanciamento,
                        'veiculo' => $row[5],
                        'placa' => $placa_formatada,
                        'valorTotalFinanciamento' => $valorvalorTotalFinanciamento,
                        'financeira' => $banco_id
                    ];
                    $existingRecord = Venda::where('cpfCliente', $cpf_formatado)  // Aqui, verificamos o CPF do cliente como exemplo, você pode usar outro campo
                            ->where('veiculo', $row[5]) // Também podemos verificar a placa
                            ->where('placa', $placa_formatada) // Também podemos verificar a placa
                            ->where('status', 'pendente') // Também podemos verificar a placa
                            ->first();

                    if ($existingRecord) {
                        $existingRecord->update($array_data);
                    } else {
                        $array_data['status'] = 'pendente';
                        Venda::create($array_data);
                    }
                }
            }

            return back()->with('success', 'Arquivo importado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao processar o arquivo: ' . $e->getMessage());
        }
    }

    private function limparCNPJ($cnpj) {
        return preg_replace('/[^0-9]/', '', $cnpj);
    }

    private function removeSimboloMonetario($valor) {
        return preg_replace('/^R\$\s?/', '', $valor);
    }

    private function PT_limpaCPF_CNPJ($valor) {
        $valor = trim($valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        $valor = str_replace("(", "", $valor);
        $valor = str_replace(")", "", $valor);
        $valor = str_replace("%", "", $valor);
        $valor = str_replace("*", "", $valor);
        $valor = str_replace("&", "", $valor);
        $valor = str_replace("¨", "", $valor);
        $valor = str_replace("$", "", $valor);
        $valor = str_replace("#", "", $valor);
        $valor = str_replace("@", "", $valor);
        $valor = str_replace("!", "", $valor);
        $valor = str_replace(" ", "", $valor);
        $valor = str_replace(" ", "", $valor);
        return $valor;
    }

    private function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    private function formatarData($data) {
        try {
            // Remove espaços extras
            $data = trim($data);

            // Tenta detectar e converter diferentes formatos
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{2,4}$/', $data)) {
                // Separa os valores da data
                $partes = explode('/', $data);
                $dia = (int) $partes[0];
                $mes = (int) $partes[1];
                $ano = (int) $partes[2];

                // Se o ano for de dois dígitos, assume que está no século 2000+
                if ($ano < 100) {
                    $ano += 2000;
                }

                // Retorna a data formatada corretamente
                return Carbon::createFromFormat('Y-m-d', "$ano-$mes-$dia")->format('Y-m-d');
            }
        } catch (Exception $e) {
            return null; // Retorna null se houver erro (evita falhas)
        }

        return null;
    }
}

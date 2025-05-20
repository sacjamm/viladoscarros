<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venda;
use App\Models\Banco;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
use Auth;
use Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Mollie\Laravel\Facades\Mollie;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class VendasController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function index(Request $request) {
        $display_style = 'display: none;';
        $format = $request->export;
        $limite = 20;

        $query = Venda::with(['venda', 'user']);

        if ($request->has('como_nos_conheceu') && $request->como_nos_conheceu != '') {
            $query->where('como_nos_conheceu', 'like', '%' . $request->como_nos_conheceu . '%');
            $display_style = 'display: block;';
        }
        if ($request->has('nomeCliente') && $request->nomeCliente != '') {
            $query->where('nomeCliente', 'like', '%' . $request->nomeCliente . '%');
            $display_style = 'display: block;';
        }
        if ($request->has('cpfCliente') && $request->cpfCliente != '') {
            $query->where('cpfCliente', 'like', '%' . $request->cpfCliente . '%');
            $display_style = 'display: block;';
        }
        if ($request->has('veiculo') && $request->veiculo != '') {
            $query->where('veiculo', 'like', '%' . $request->veiculo . '%');
            $display_style = 'display: block;';
        }
        if ($request->has('placa') && $request->placa != '') {
            $query->where('placa', 'like', '%' . $request->placa . '%');
            $display_style = 'display: block;';
        }
        /* if ($request->has('dataPagamentoFinanciamento') && $request->dataPagamentoFinanciamento != '') {
          $query->whereDate('dataPagamentoFinanciamento', $request->dataPagamentoFinanciamento);
          $display_style = 'display: block;';
          } */
        if ($request->has('dataPagamentoFinanciamento') && $request->dataPagamentoFinanciamento != '') {
            $data = Carbon::parse($request->dataPagamentoFinanciamento);

            $query->whereMonth('dataPagamentoFinanciamento', $data->month)
                    ->whereYear('dataPagamentoFinanciamento', $data->year);

            $display_style = 'display: block;';
            /* echo '<pre>';
              var_dump($request->dataPagamentoFinanciamento,$data->month,$data->year,$data);
              echo '</pre>';die; */
        }
        if ($request->has('financeira') && $request->financeira != '') {
            $query->where('financeira', $request->financeira);
            $display_style = 'display: block;';
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
            $display_style = 'display: block;';
        }
        if ($request->has('limite') && $request->limite != '') {
            $limite = $request->limite;
            $display_style = 'display: block;';
        }
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
            $display_style = 'display: block;';
        }

        $loja_id = $_GET['loja_id'] ?? 0;
        //$vendas = Venda::with('venda')->with('user')->get();
        $bancos = Banco::get();
        $lojas = User::where('status', 'Active')->get();

        if ($format === 'xls' || $format === 'xlsx') {
            $vendas = $query->take($limite)->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Nome Cliente')
                    ->setCellValue('B1', 'CPF Cliente')
                    ->setCellValue('C1', 'Veículo')
                    ->setCellValue('D1', 'Placa')
                    ->setCellValue('E1', 'Valor Total Financiamento')
                    ->setCellValue('F1', 'Previsão de Recebimento de Plus')
                    ->setCellValue('G1', 'Data Do Pagamento Do Financiamento')
                    ->setCellValue('H1', 'Financeira')
                    ->setCellValue('I1', 'Como Nos Conheceu?')
                    ->setCellValue('J1', 'Status')
                    ->setCellValue('K1', 'LOJISTA');
            //#D9E1F2
            $headerStyle = $sheet->getStyle('A1:K1');
            $headerStyle->getFont()
                    ->setBold(true); // Negrito

            $headerStyle->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('D9E1F2'); // Cor amarela

            $headerStyle->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER) // Alinhamento vertical ao centro
                    ->setWrapText(false);

            foreach (range('A', 'K') as $col) {
                $sheet->getColumnDimension($col)
                        ->setAutoSize(true);
            }

            $row = 2;

            $i = 0;
            $total_liquido = 0;
            $total_liquido_geral = 0;
            $total_liquido_aprovado = 0;
            $total_liquido_pendente = 0;
            foreach ($vendas as $venda) {
                $porcentagem = ($venda->venda->porcentagem ?? 0) / 100;
                $desconto = ($venda->venda->desconto ?? 0) / 100;
                $total_liquido = ($venda->valorTotalFinanciamento * $porcentagem) * (1 - $desconto);

                if ($venda->status === 'aprovado') {
                    $total_liquido_aprovado += $total_liquido;
                } elseif ($venda->status === 'pendente') {
                    $total_liquido_pendente += $total_liquido;
                }

                $total_liquido_geral += $total_liquido;

                $sheet->setCellValue('A' . $row, $venda->nomeCliente);
                $sheet->setCellValue('B' . $row, $venda->cpfCliente);
                $sheet->setCellValue('C' . $row, $venda->veiculo);
                $sheet->setCellValue('D' . $row, $venda->placa);
                $sheet->setCellValue('E' . $row, number_format($venda->valorTotalFinanciamento, 2, ',', '.'));
                $sheet->setCellValue('F' . $row, number_format($total_liquido, 2, ',', '.'));
                $sheet->setCellValue('G' . $row, Carbon::parse($venda->dataPagamentoFinanciamento)->format('d/m/Y'));
                $sheet->setCellValue('H' . $row, $venda->venda->banco ?? 'N/A');
                $sheet->setCellValue('I' . $row, $venda->como_nos_conheceu);
                $sheet->setCellValue('J' . $row, $venda->status);
                $sheet->setCellValue('K' . $row, $venda->user->name);
                $row++;
            }

            $fileName = 'admin_vendas_veiculos.' . $format;
            $writer = ($format === 'xlsx') ? new Xlsx($spreadsheet) : new Xls($spreadsheet);

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        } else {
            $vendas = $query->paginate($limite);
            return view('admin.admin_customer_cadastrovendas', compact('vendas', 'lojas', 'loja_id', 'bancos', 'display_style'));
        }
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
        return redirect()->route('admin_customer_cadastro_vendas')->with('success', SUCCESS_DATA_ADD);
    }

    public function ajax($id) {
        $venda = Venda::find($id);
        if ($venda->status == 'pendente') {         
            $venda->status = 'aprovado';
            $message = SUCCESS_ACTION;
            $venda->save();          
        }else {
            $venda->status = 'pendente';
            $message = SUCCESS_ACTION;
            $venda->save();
        }
        return redirect()->route('admin_customer_cadastro_vendas')->with('success', $message);
    }

    public function destroy($id) {
        $venda = Venda::findOrFail($id);
        $venda->delete();
        return redirect()->back()->with('success', SUCCESS_DATA_DELETE);
    }

    public function import(Request $request) {

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:51200'
                ], [
            'file.required' => 'O envio do arquivo é obrigatório',
            'file.mimes' => 'O arquivo deve ser .xlsx ou .xls',
            'file.max' => 'Tamanho máximo do arquivo 50MB'
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Validação do cabeçalho
            $header = ['COMO CONHECEU A EMPRESA?', 'NOME DO CLIENTE', 'CPF DO CLIENTE', 'DATA DA VENDA',
                'DATA PAGAMENTO FINANCIAMENTO', 'VEÍCULO', 'PLACA', 'VALOR TOTAL FINANCIAMENTO', 'FINANCEIRA', 'LOJISTA'];

            if ($rows[0] !== $header) {
                return back()->with('error', 'O arquivo não segue o formato esperado!');
            }

            unset($rows[0]); // Remove a linha do cabeçalho

            foreach ($rows as $row) {
                if (count($row) <= 10)
                    continue; // Garante que a linha tenha todos os campos necessários

                if (!empty($row[1]) && !empty($row[2]) && !empty($row[5]) && !empty($row[6]) && !empty($row[8])) {
                    $banco = Banco::where('banco', 'LIKE', "%{$row[8]}%")->first();
                    $arr_banco = array(
                        'banco' => $row[8],
                        'totalFinanciado' => 0,
                        'porcentagem' => 0,
                        'totalBruto' => 0,
                        'totalLiquido' => 0,
                        'desconto' => 0,
                    );
                    if ($banco) {
                        $banco_id = $banco->id;
                    } else {
                        $banco_id = Banco::create($arr_banco);
                    }

                    if (isset($row[9])) {
                        $usr = User::where('name', 'LIKE', "%{$row[9]}%")->first();
                        if ($usr) {
                            $user_id = $usr->id;
                        } else {
                            $user_id = 0;
                        }
                    } else {
                        $user_id = 0;
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
                        $valorvalorTotalFinanciamento = $this->PT_limpaCPF_CNPJ($real) . '.' . $centavos;
                    } else {
                        $valorvalorTotalFinanciamento = number_format($valorSemSimboloMonetario, 2, '.', '');
                    }

                    $array_data = [
                        'user_id' => $user_id,
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

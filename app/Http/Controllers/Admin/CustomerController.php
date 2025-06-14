<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\PackagePurchase;
use App\Models\Review;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
Use Auth;
use Hash;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function indexOriginal() {
        $customers = User::get();
        return view('admin.customer_view', compact('customers'));
    }

    public function index(Request $request) {
        if ($request->ajax()) {
            // Query para pegar os dados dos usuários e a contagem dos veículos
            $query = DB::table('users')
                    ->leftJoin('listings', 'users.id', '=', 'listings.user_id')
                    ->where('users.tipo_user','lojista')
                    ->select('users.id as UserID', 'users.photo', 'users.status', 'users.name', 'users.email',
                            DB::raw('COUNT(listings.id) as total_veiculos'))
                    ->groupBy('users.id', 'users.photo', 'users.status', 'users.name', 'users.email');

            // 🟢 Aplicar a busca do DataTables
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function ($q) use ($searchValue) {
                    $q->where('users.name', 'LIKE', "%{$searchValue}%")
                            ->orWhere('users.email', 'LIKE', "%{$searchValue}%");
                });
            }

            // 🟢 Aplicar a ordenação
            $columns = ['UserID', 'photo', 'name', 'email', 'total_veiculos', 'status'];
            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'total_veiculos';
                $orderDirection = $request->order[0]['dir'] ?? 'desc';
                $query->orderBy($orderColumn, $orderDirection);
            } else {
                $query->orderBy('total_veiculos', 'DESC'); // Padrão
            }

            // 🟢 Paginando os resultados
            $perPage = $request->length ?? 10;
            $page = $request->start / $perPage + 1;
            $customers = $query->paginate($perPage, ['*'], 'page', $page);

            // Adicionando as informações de foto e ações
            $data = $customers->items();  // Acessando os itens da coleção de paginação

            $data = array_map(function ($customer) {
                if (!file_exists(public_path('uploads/user_photos/' . $customer->photo))) {
                    $imageSrc = asset('uploads/user_photos/default_photo.jpg');
                } else if (!empty($customer->photo) && $customer->photo != '') {
                    if (!file_exists(public_path('uploads/user_photos/' . $customer->photo))) {
                        $imageSrc = asset('uploads/user_photos/default_photo.jpg');
                    } else {
                        $imageSrc = asset('uploads/user_photos/' . $customer->photo);
                    }
                } else {
                    $imageSrc = asset('uploads/user_photos/default_photo.jpg');
                }
                return [
                    'UserID' => $customer->UserID,
                    'photo' => $imageSrc, // Verifica se há foto
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'total_veiculos' => $customer->total_veiculos,
                    'status' => '<a href="#" onclick="customerStatus(' . $customer->UserID . ')">
                    <input type="checkbox" ' . ($customer->status == 'Active' ? 'checked' : '') . ' 
                        data-toggle="toggle" 
                        data-on="Active" 
                        data-off="Pending" 
                        data-onstyle="success" 
                        data-offstyle="danger">
                </a>',
                    'action' => '<div class="btn-group">
                    <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                        Ações
                    </button>
                    <div class="dropdown-menu">
                        <a href="' . route('admin_customer_acessar', ['id' => $customer->UserID]) . '" target="_blank" class="dropdown-item text-warning">
                            <i class="fa fa-external-link-alt"></i> Área do lojista
                        </a>
                        <a href="' . route('admin_cadastro_vendas') . '?loja_id=' . $customer->UserID . '" target="_blank" class="dropdown-item text-dark">
                            <i class="fa fa-cart-plus"></i> Cadastro de vendas
                        </a>
                        <a href="' . route('admin_customer_editar', ['id' => $customer->UserID]) . '" class="dropdown-item text-info">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                        <a href="' . route('admin_customer_detail', $customer->UserID) . '" class="dropdown-item text-primary">
                            <i class="fa fa-info-circle"></i> Detalhes
                        </a>
                        <a href="' . route('admin_customer_package', $customer->UserID) . '" class="dropdown-item text-danger">
                            <i class="fa fa-th"></i> Pacote
                        </a>
                        <a href="' . route('admin_customer_delete', $customer->UserID) . '" class="dropdown-item text-danger" onClick="return confirm(\'Tem certeza?\');">
                            <i class="fa fa-trash"></i> Deletar
                        </a>
                    </div>
                </div>'
                ];
            }, $data);

            return response()->json([
                        'draw' => intval($request->draw),
                        'recordsTotal' => $customers->total(),
                        'recordsFiltered' => $customers->total(),
                        'data' => $data
            ]);
        } else {
            // Se não for uma requisição Ajax, retorna a página com os resultados
            $customers = DB::table('users')
                    ->leftJoin('listings', 'users.id', '=', 'listings.user_id')
                    ->select('users.id as UserID', 'users.photo', 'users.status', 'users.name', 'users.email',
                            DB::raw('COUNT(listings.id) as total_veiculos'))
                    ->groupBy('users.id', 'users.photo', 'users.status', 'users.name', 'users.email')
                    ->get();

            return view('admin.customer_view', compact('customers'));
        }
    }
    
    public function package($id){
       $package = new \App\Http\Controllers\Front\CustomerController();
       $package->free_enroll_auto(1,$id);
       return redirect()->route('admin_customer_view')->with('success', SUCCESS_PACKAGE_ENROLL);
    }

    public function indexSemPaginacao(Request $request) {
        if ($request->ajax()) {
            // Query para pegar os dados dos usuários e a contagem dos veículos
            $query = DB::table('users')
                    ->leftJoin('listings', 'users.id', '=', 'listings.user_id')
                    ->select('users.id as UserID', 'users.photo', 'users.status', 'users.name', 'users.email',
                            DB::raw('COUNT(listings.id) as total_veiculos'))
                    ->groupBy('users.id', 'users.photo', 'users.status', 'users.name', 'users.email');

            // 🟢 Aplicar a ordenação enviada pelo DataTables
            $columns = ['UserID', 'photo', 'name', 'email', 'total_veiculos', 'status'];
            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'total_veiculos';
                $orderDirection = $request->order[0]['dir'] ?? 'desc';
                $query->orderBy($orderColumn, $orderDirection);
            } else {
                $query->orderBy('total_veiculos', 'DESC'); // Padrão
            }

            // 🟢 Aplicar a busca do DataTables
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function ($q) use ($searchValue) {
                    $q->where('users.name', 'LIKE', "%{$searchValue}%")
                            ->orWhere('users.email', 'LIKE', "%{$searchValue}%");
                });
            }

            // 🟢 Paginação
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $customers = $query->skip($start)->take($length)->get();

            //$customers = $query->paginate($length, ['*'], 'page', ($start / $length) + 1);

            return DataTables::of($customers)
                            ->addColumn('photo', function ($row) {
                                $imageSrc = (!empty($row->photo) && $row->photo != '') ? asset('uploads/user_photos/' . $row->photo) : asset('uploads/user_photos/default_photo.jpg');
                                return '<img src="' . $imageSrc . '" class="w_100">';
                            })
                            ->addColumn('status', function ($row) {
                                return '<a href="#" onclick="customerStatus(' . $row->UserID . ')">
                    <input type="checkbox" ' . ($row->status == 'Active' ? 'checked' : '') . ' 
                        data-toggle="toggle" 
                        data-on="Active" 
                        data-off="Pending" 
                        data-onstyle="success" 
                        data-offstyle="danger">
                </a>';
                            })
                            ->addColumn('action', function ($row) {
                                return '<div class="btn-group">
                    <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                        Ações
                    </button>
                    <div class="dropdown-menu">
                        <a href="' . route('admin_customer_acessar', ['id' => $row->UserID]) . '" target="_blank" class="dropdown-item text-warning">
                            <i class="fa fa-external-link-alt"></i> Área do lojista
                        </a>
                        <a href="' . route('admin_cadastro_vendas') . '?loja_id=' . $row->UserID . '" target="_blank" class="dropdown-item text-dark">
                            <i class="fa fa-cart-plus"></i> Cadastro de vendas
                        </a>
                        <a href="' . route('admin_customer_editar', ['id' => $row->UserID]) . '" class="dropdown-item text-info">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                        <a href="' . route('admin_customer_detail', $row->UserID) . '" class="dropdown-item text-primary">
                            <i class="fa fa-info-circle"></i> Detalhes
                        </a>
                        <a href="' . route('admin_customer_delete', $row->UserID) . '" class="dropdown-item text-danger" onClick="return confirm(\'Tem certeza?\');">
                            <i class="fa fa-trash"></i> Deletar
                        </a>
                    </div>
                </div>';
                            })
                            ->rawColumns(['photo', 'status', 'action'])
                            ->make(true);
        } else {
            // Se não for uma requisição Ajax, retorna a página com os resultados
            $customers = DB::table('users')
                    ->leftJoin('listings', 'users.id', '=', 'listings.user_id')
                    ->select('users.id as UserID', 'users.photo', 'users.status', 'users.name', 'users.email',
                            DB::raw('COUNT(listings.id) as total_veiculos'))
                    ->groupBy('users.id', 'users.photo', 'users.status', 'users.name', 'users.email')
                    ->get();

            return view('admin.customer_view', compact('customers'));
        }
    }

    public function detail($id) {
        $customer_detail = User::where('id', $id)->first();
        return view('admin.customer_detail', compact('customer_detail'));
    }

    public function acessar($id) {

        Auth::guard('admin')->logout();
        session()->flush();

        if (Auth::guard('web')->loginUsingId($id)) {
            return redirect()->route('customer_dashboard')->with('success', SUCCESS_LOGIN);
        }

        // Caso o login falhe
        return redirect()->back()->with('error', 'Não foi possível acessar a conta do cliente.');
    }

    public function editar($id) {
        $customer_detail = User::where('id', $id)->first();
        return view('admin.customer_editar', compact('customer_detail'));
    }

    public function update(Request $request, $id) {
        $customer = User::find($id);
        $obj = User::findOrFail($id);
        $data = $request->only($obj->getFillable());

        $slug = Str::slug($customer->name);
        
        if (isset($data['photo'])) {
            /* $request->validate([
              'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
              ], [
              'photo.image' => ERR_PHOTO_IMAGE,
              'photo.mimes' => ERR_PHOTO_JPG_PNG_GIF,
              'photo.max' => ERR_PHOTO_MAX
              ]); */
            if ($customer->photo != '') {
                if (file_exists(public_path('uploads/user_photos/' . $customer->photo))) {
                    unlink(public_path('uploads/user_photos/' . $customer->photo));
                }
            }
            $ext = $request->file('photo')->extension();

            $rand_value = md5(mt_rand(11111111, 99999999));
            $final_name = $rand_value . '.' . $ext;
            $request->file('photo')->move(public_path('uploads/user_photos/'), $final_name);
            $data['photo'] = $final_name;
        }
        if (isset($data['banner'])) {
            /* $request->validate([
              'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
              ], [
              'banner.image' => ERR_PHOTO_IMAGE,
              'banner.mimes' => ERR_PHOTO_JPG_PNG_GIF,
              'banner.max' => ERR_PHOTO_MAX
              ]); */
            if ($customer->banner != '') {
                if (file_exists(public_path('uploads/user_photos/' . $customer->banner))) {
                    unlink(public_path('uploads/user_photos/' . $customer->banner));
                }
            }
            $extB = $request->file('banner')->extension();
            $rand_valueB = md5(mt_rand(11111111, 99999999));
            $final_nameB = $rand_valueB . '.' . $extB;
            $request->file('banner')->move(public_path('uploads/user_photos/'), $final_nameB);
            $data['banner'] = $final_nameB;
        }



        if (!empty($request->cnpj)) {
            $cnpj = $this->PT_limpaCPF_CNPJ($request->cnpj);
            $data['cnpj'] = $this->mask($cnpj, '##.###.###/####-##');
        }
        if (!empty($request->cnpj_credere)) {
            $cnpj_credere = $this->PT_limpaCPF_CNPJ($request->cnpj_credere);
            $data['cnpj_credere'] = $this->mask($cnpj_credere, '##.###.###/####-##');
        }
        if (!empty($request->phone)) {
            $phone = (int) $this->PT_limpaCPF_CNPJ($request->phone);
            if (strlen($phone) === 11) {
                $data['phone'] = $this->mask($phone, '(##) #####-####');
            }
            if (strlen($phone) === 10) {
                $data['phone'] = $this->mask($phone, '(##) ####-####');
            }
        }
        if (!empty($request->country)) {
            $data['country'] = ucfirst($request->country);
        }
        $data['slug_user']=$slug;
        $obj->fill($data)->save();
        return redirect()->route('admin_customer_view')->with('success', SUCCESS_ACTION);
    }

    public function destroy($id) {

        /* if(env('PROJECT_MODE') == 0) {
          return redirect()->back()->with('error', env('PROJECT_NOTIFICATION'));
          } */

        // Before deleting, check this customer is used in another table
        $cnt = Listing::where('admin_id', 0)->where('user_id', $id)->count();
        if ($cnt > 0) {
            Listing::where('user_id', $id)->delete();
            //return redirect()->back()->with('error', ERR_ITEM_DELETE);
        }

        $cnt1 = PackagePurchase::where('user_id', $id)->count();
        if ($cnt1 > 0) {
            PackagePurchase::where('user_id', $id)->delete();
            //return redirect()->back()->with('error', ERR_ITEM_DELETE);
        }

        $cnt2 = Review::where('agent_id', $id)->where('agent_type', 'Customer')->count();
        if ($cnt2 > 0) {
            Review::where('agent_id', $id)->delete();
            //return redirect()->back()->with('error', ERR_ITEM_DELETE);
        }
        $usr = Follower::where('user_id', $id)->count();
        if ($usr > 0) {
            Follower::where('user_id', $id)->delete();
            //return redirect()->back()->with('error', ERR_ITEM_DELETE);
        }
        $flw = Follower::where('follower_id', $id)->count();
        if ($flw > 0) {
            Follower::where('follower_id', $id)->delete();
            //return redirect()->back()->with('error', ERR_ITEM_DELETE);
        }

        User::where('id', $id)->delete();
        return redirect()->route('admin_customer_view')->with('success', SUCCESS_ACTION);
    }

    public function change_status($id) {
        $customer = User::find($id);
        if ($customer->status == 'Active') {
            $customer->status = 'Pending';
            $message = SUCCESS_ACTION;
            $customer->save();
        } else {
            $customer->status = 'Active';
            $message = SUCCESS_ACTION;
            $customer->save();
        }
        return response()->json($message);
    }

    public function PT_limpaCPF_CNPJ($valor) {
        // Remove espaços em branco e caracteres indesejados usando uma expressão regular
        return preg_replace('/[^\d]/', '', trim($valor));
    }

    function mask($val, $mask) {
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
}

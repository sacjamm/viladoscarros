<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class BancosController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin:admin');
    }

    public function index() {
        $bancos = Banco::get();
        return view('admin.customer_configvendas', compact('bancos'));
    }

    public function store(Request $request) {
        if (!empty($request->input('id'))) {
            $id = $request->input('id');
            $banco = Banco::findOrFail($id);
        } else {
            $banco = new Banco();
        }
        $data = $request->only($banco->getFillable());
        if (!empty($id)) {
            unset($data['id']);
        }
        $data['porcentagem'] = floatval(str_replace(",", ".", $data['porcentagem']));
        $banco->fill($data)->save();

        return redirect()->route('admin_config_vendas')->with('success', SUCCESS_DATA_ADD);
    }

    public function destroy($id) {
        $banco = Banco::findOrFail($id);
        $banco->delete();
        return redirect()->back()->with('success', SUCCESS_DATA_DELETE);
    }
}

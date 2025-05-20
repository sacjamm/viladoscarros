<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\PackagePurchase;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
Use Auth;
use Hash;
use Yajra\DataTables\DataTables;

class UserbancoController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin:admin');
    }
    
    public function index(){
        $usuarios = User::where('tipo_user','banco')->get();

            return view('admin.userbanco_view', compact('usuarios'));
    }
    
}
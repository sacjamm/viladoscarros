@extends('admin.app_admin')
@section('admin_content')
    <h1 class="h3 mb-3 text-gray-800">Editar Cliente</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
                    <div class="float-right d-inline">
                        <a href="{{ route('admin_customer_view') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ BACK_TO_PREVIOUS }} </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('customer_update',['id'=>$customer_detail->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <table class="table table-bordered">
                            <tr>
                                <td>{{ PHOTO }}</td>
                                <td>
                                     @if($customer_detail->photo == '')
                                        <img src="{{ asset('uploads/user_photos/default_photo.jpg') }}" class="w_100">
                                    @else
                                        <img src="{{ asset('uploads/user_photos/'.$customer_detail->photo) }}" class="w_100">
                                    @endif
                                    <input type="file" name="photo" class="form-control photo" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ BANNER }}</td>
                                <td>
                                     @if($customer_detail->banner == '')
                                        <img src="{{ asset('uploads/user_photos/default_banner.jpg') }}" class="w_200">
                                    @else
                                        <img src="{{ asset('uploads/user_photos/'.$customer_detail->banner) }}" class="w_100">
                                    @endif
                                    <input type="file" name="banner" class="form-control banner" />
                                </td>
                            </tr>
                            <tr> 
                                <td>{{ NAME }}</td>
                                <td>
                                    <input type="text" name="name" class="form-control name" value="{{ $customer_detail->name }}" />
                                </td>
                            </tr> 
                            <tr>
                                <td>{{ EMAIL }}</td>
                                <td>
                                    <input type="email" name="email" class="form-control email" value="{{ $customer_detail->email }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>CNPJ</td>
                                <td>
                                    <input type="tel" name="cnpj" class="form-control cnpj" value="{{ preg_replace('/[^\d]/', '', trim($customer_detail->cnpj)) }}" maxlength="14" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ PHONE }}</td>
                                <td>
                                    <input type="tel" name="phone" class="form-control phone" value="{{ preg_replace('/[^\d]/', '', trim($customer_detail->phone)) }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ COUNTRY }}</td>
                                <td>
                                    <input type="text" name="country" class="form-control country" value="{{ $customer_detail->country }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ ADDRESS }}</td>
                                <td>
                                <input type="text" name="address" class="form-control address" value="{{ $customer_detail->address }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ STATE }}</td>
                                <td>
                                <input type="text" name="state" class="form-control state" value="{{ $customer_detail->state }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ CITY }}</td>
                                <td>
                                <input type="text" name="city" class="form-control city" value="{{ $customer_detail->city }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ ZIP }}</td>
                                <td>
                                <input type="tel" name="zip" class="form-control zip" value="{{ $customer_detail->zip }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ WEBSITE }}</td>
                                <td>
                                <input type="text" name="website" class="form-control website" value="{{ $customer_detail->website }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ FACEBOOK }}</td>
                                <td>
                                <input type="text" name="facebook" class="form-control facebook" value="{{ $customer_detail->facebook }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ TWITTER }}</td>
                                <td>
                                <input type="text" name="twitter" class="form-control twitter" value="{{ $customer_detail->twitter }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ LINKEDIN }}</td>
                                <td>
                                <input type="text" name="linkedin" class="form-control linkedin" value="{{ $customer_detail->linkedin }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ INSTAGRAM }}</td>
                                <td>
                                <input type="text" name="instagram" class="form-control instagram" value="{{ $customer_detail->instagram }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ PINTEREST }}</td>
                                <td>
                                <input type="text" name="pinterest" class="form-control pinterest" value="{{ $customer_detail->pinterest }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ YOUTUBE }}</td>
                                <td>
                                <input type="text" name="youtube" class="form-control youtube" value="{{ $customer_detail->youtube }}" />
                                </td>
                            </tr>
                            <tr>
                                <td>{{ STATUS }}</td>
                                <td>
                                <select name="status" class="form-control status" style="width:100%;">
                                    <option value="Active" {{ (($customer_detail->status == 'Active') ? ' selected ' : '') }}>Ativo</option>
                                    <option value="Pending" {{ (($customer_detail->status == 'Pending') ? ' selected ' : '') }}>Inativo</option>
                                </select>
                                </td>
                            </tr>
                        </table>
                            <button type="submit" class="btn btn-success btn-md btn-block">Atualizar</button>
                                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
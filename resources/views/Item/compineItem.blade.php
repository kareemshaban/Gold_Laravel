<div class="modal fade" id="compineModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="modelTitle"> {{__('main.compine')}}</label>
                <button type="button" class="close modal-close-btn close-create" data-bs-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="paymentBody">
                <form method="POST" action="{{ route('compineItem') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ __('main.compine_item') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span></label>
                                <input type="text" id="parent_name" name="parent_name" required
                                       class="form-control"  readonly value="{{Config::get('app.locale') == 'ar' ?  $item -> name_ar : $item -> name_en}}"/>
                                <input type="hidden" id="parent_id" name="parent_id" value="{{  $item -> id }}">

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ __('main.karat') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="text" id="karat" name="karat"
                                       class="form-control"  readonly value="{{Config::get('app.locale') == 'ar' ? $item -> karat -> name_ar : $item -> karat -> name_en}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('main.items') }} <span
                                        style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <select class="form-control" id="item_id" name="item_id" required>
                                    @foreach($items as $item)
                                        <option
                                            value="{{$item -> id}}">{{Config::get('app.locale') == 'ar' ? $item -> name_ar : $item -> name_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-6" style="display: block; margin: 20px auto; text-align: center;">
                            <button type="submit" class="btn btn-labeled btn-primary">
                                {{__('main.save_btn')}}</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">
                                #
                            </th>
                            <th class="text-uppercase text-secondary text-md-center font-weight-bolder opacity-7 ps-2">{{__('main.code')}}</th>
                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.name_ar')}}</th>
                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.name_en')}} </th>
                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.category')}} </th>
                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.karat')}} </th>
                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.weight')}} </th>
                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.no_metal')}} </th>
                            <th class="text-center text-uppercase text-secondary text-md-center font-weight-bolder opacity-7"> {{__('main.price')}} </th>
                            <th class="text-end text-uppercase text-secondary text-md-center font-weight-bolder opacity-7">{{__('main.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td class="text-center">{{$loop -> index + 1}}</td>
                                <td class="text-center">{{$item -> code}}</td>
                                <td class="text-center">{{$item -> name_ar}}</td>
                                <td class="text-center">{{$item -> name_en}}</td>
                                <td class="text-center">{{Config::get('app.locale') == 'ar' ? $item -> category_name_ar : $item -> category_name_en }}</td>
                                <td class="text-center">{{Config::get('app.locale') == 'ar' ? $item -> karat_name_ar : $item -> karat_name_en}}</td>
                                <td class="text-center">{{$item -> weight}}</td>
                                <td class="text-center">{{$item -> no_metal}}</td>
                                <td class="text-center">{{$item -> price == 0 ? '--' : $item -> price}}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-labeled btn-danger deleteCombineBtn "
                                            value="{{$item -> id}}">
                                                    <span class="btn-label" style="margin-right: 10px;"><i
                                                            class="fa fa-trash"></i></span>{{__('main.delete')}}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

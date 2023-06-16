<div class="modal fade" id="paymentsModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true"
     style="width: 100%;">
    <div class="modal-dialog modal-lg" role="document" style="min-width: 500px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"  data-dismiss="modal"  aria-label="Close" style="color: red; font-size: 20px; font-weight: bold;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label>{{__('main.money_exit_create')}}</label>
            </div>
            <div class="modal-body" id="smallBody">
                <form   method="POST" action="{{ route('storeMoneyExit') }}"
                        enctype="multipart/form-data" >
                    @csrf

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>{{ __('main.date') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input type="date"  id="date" name="date"
                                       class="form-control" required
                                       placeholder="{{ __('main.date') }}"  />

                            </div>
                        </div>
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.bill_no') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input required type="text"   id="doc_number" name="doc_number"
                                       class="form-control"  placeholder="{{ __('main.bill_no') }}"  readonly value="{{$bill_no}}"/>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.payment_type') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <select name="type" class="form-control" id="type">
                                    <option value="" selected>select..</option>
                                    <option value="0" >{{__('main.payment_type0')}}</option>
                                    <option value="2" >{{__('main.payment_type2')}}</option>
                                    <option value="1" >{{__('main.payment_type1')}}</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.supplier') .'/'. __('main.client')}} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <select name="supplier_id" class="form-control" id="supplier_id">.
                                    <option value="" selected>select..</option>

                                </select>
                            </div>
                        </div>



                    </div>

                    <div class="row payment_type1">
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.balance_gold') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input required type="text"   id="balance_gold" name="balance_gold"
                                       class="form-control"  placeholder="{{ __('main.balance_gold') }}"  readonly/>

                            </div>
                        </div>
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.price_gram') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input required type="text"   id="price_gram" name="price_gram"
                                       class="form-control"  placeholder="{{ __('main.balance_gold') }}"  readonly value="{{$pricing -> price_21}}"/>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-4 " >
                            <div class="form-group">
                                <label>{{ __('main.total_balance') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input required type="text"   id="balance" name="balance"
                                       class="form-control"  placeholder="{{ __('main.total_balance') }}"  readonly/>

                            </div>
                        </div>
                        <div class="col-4 " >
                            <div class="form-group">
                                <label>{{ __('main.based_on') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <select name="based_on" class="form-control" id="based_on">
                                    <option value="">select...</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-4 " >
                            <div class="form-group">
                                <label>{{ __('main.document_balance') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input required type="text"   id="document_balance" name="document_balance"
                                       class="form-control"  placeholder="{{ __('main.document_balance') }}"  readonly/>

                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.paid_money') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input required type="number"   id="amount" name="amount" min="0" step="any"
                                       class="form-control"  placeholder="{{ __('main.paid_money') }}"  />

                            </div>
                        </div>

                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.payment_method') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <select class="form-control" name="payment_method" id="payment_method">
                                    <option value="0"> {{__('main.cash')}} </option>
                                    <option value="1"> {{__('main.visa')}} </option>

                                </select>

                            </div>
                        </div>


                    </div>
                    <div class="row payment_type1">

                        <div class="col-6 " >
                            <div class="form-group">
                                <label>{{ __('main.paid_weight21') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <input required type="number"   id="paid_weight21" name="paid_weight21"  step="any"
                                       class="form-control"  placeholder="0" readonly />

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('main.notes') }} <span style="color:red; font-size:20px; font-weight:bold;">*</span> </label>
                                <textarea name="notes" id="notes" rows="3" placeholder="{{ __('main.notes') }}" class="form-control-lg" style="width: 100%"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12" style="display: block; margin: 20px auto; text-align: center;">
                            <button type="submit" class="btn btn-labeled btn-primary"  >
                                {{__('main.save_btn')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.payment_type1').slideUp();
        document.getElementById('date').valueAsDate = new Date();
        document.getElementById('supplier_id').value = '' ;

        $('#supplier_id').change(function (){
            var type = document.getElementById('type').value ;

            $.ajax({
                type: 'get',
                url: '/getCompany' + '/' + this.value,
                dataType: 'json',

                success: function (response) {

                    if (response) {
                        if(type == 0){
                            document.getElementById('balance').value = Number(response.deposit_amount) - Number(response.credit_amount) ;
                        } else {
                            var weight = Number(response.deposit_gold).toFixed(2) - Number(response.credit_gold).toFixed(2)
                            document.getElementById('balance_gold').value =weight.toFixed(2) ;
                            var price = document.getElementById('price_gram').value ;
                            var amount = Number(price) * Number(response.deposit_gold) - Number(response.credit_gold);
                            document.getElementById('balance').value = amount.toFixed(2) ;
                        }

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: '/getClientSupplierWorks' + '/' + this.value + '/' + type,
                dataType: 'json',

                success: function (response) {
                    console.log(response);
                    if (response) {
                        $('#based_on')
                            .empty();
                        $('#based_on').append('<option value="0">select ..</option>');
                        for (let i = 0; i < response.length; i++){
                            $('#based_on').append('<option value="'+response[i].id+'">'+response[i].bill_number + '</option>');
                        }

                    }
                }
            });
        })

        $('#based_on').change(function(){

            const type = document.getElementById('type').value ;
            $.ajax({
                type: 'get',
                url: '/getClientDocumentdata' + '/' + this.value + '/' + type,
                dataType: 'json',

                success: function (response) {
                    console.log(response);
                    if (response) {
                        if(type == 0){
                            document.getElementById('document_balance').value = response.remain_money ;
                        } else if(type == 2 || type == 1) {
                            document.getElementById('document_balance').value = response.remain_gold ;
                        }

                    } else {
                        document.getElementById('document_balance').value =0 ;
                    }
                }
            });

        });

        $('#type').change(function (){
           const val = this.value ;
           if(val == 0){
               $('.payment_type1').slideUp();
           } else {
               $('.payment_type1').slideDown();
           }
            $('#based_on')
                .empty();
            $('#supplier_id')
                .empty();
            $('#based_on').append('<option value="0"> select...</option>');
            $('#supplier_id').append('<option value="0">select...</option>');

            console.log(val);
            $.ajax({
                type: 'get',
                url: '/getClientSupplier' + '/' + val,
                dataType: 'json',

                success: function (response) {
                    console.log(response);
                    if (response) {
                        for (let i = 0; i < response.length; i++){
                            $('#supplier_id').append('<option value="'+response[i].id+'">'+response[i].name + '</option>');
                        }
                    }
                }
            });


        });

        $(document).on('change', '#amount', function () {
            var type = document.getElementById('type').value;
            var price = document.getElementById('price_gram').value;
            var amount =this.value ;
            var weight = amount / price ;
            if(type == 2 || type == 1){
                document.getElementById('paid_weight21').value = weight.toFixed(2);
            } else {
                document.getElementById('paid_weight21').value = 0;
            }



        });
        $(document).on('keyup', '#amount', function () {
            var type = document.getElementById('type').value;
            var price = document.getElementById('price_gram').value;
            var amount =this.value ;
            var weight = amount / price ;
            if(type == 2 || type == 1){
                document.getElementById('paid_weight21').value = weight.toFixed(2);
            } else {
                document.getElementById('paid_weight21').value = 0;
            }



        });

    })
</script>

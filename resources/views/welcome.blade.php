<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    </head>
    <body>
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('calc')}}" method="GET" class="mt-4 mb-4" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-4">Период проживания</div>
                    <div class="col-8">
                        <div class="form-group">                            
                            с <input type="date" name="dateBegin" min="<?= date('Y-m-d')?>" value="{{request('dateBegin')}}" required />
                            по <input type="date" name="dateEnd" min="<?= date('Y-m-d')?>" value="{{request('dateEnd')}}" required/>
                        </div>                        
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">Количество проживающих</div>
                    <div class="col-8">
                        <input type="number" name="people" min="1" value="{{request('people')}}" required/>
                    </div>
                </div> 
                <div class="row mb-3">
                    <div class="col-12"><b>Настройки</b></div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">Базовая цена за сутки</div>
                    <div class="col-8">
                        <input type="number" name="price" value="{{request('price')}}" required/>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">Сезонная цена за сутки</div>
                    <div class="col-8">
                        <input type="number" name="seasonPrice" value="{{request('seasonPrice')}}"/>
                        с <input type="date" name="seasonDateBegin" value="{{request('seasonDateBegin')}}" />
                        по <input type="date" name="seasonDateEnd" value="{{request('seasonDateEnd')}}" />                        
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">Сезонная доплата за превышение проживающих</div>
                    <div class="col-8">
                        <p>Максимальное количество проживающих <input type="number" name="maxPeople" min="1" value="{{request('maxPeople')}}"/></p>
                        <p>Ставка за превышение <input type="number" name="pricePeople" value="{{request('pricePeople')}}"/></p>
                    </div>
                </div> 
                <div class="row mb-3">
                    <div class="col-4">Скидки</div>
                    <div class="col-8">
                        <input type="text" name="discount" value="{{request('discount')}}" pattern="\d+(\.\d{2})?(%)?" placeholder="500 или 20%"/>
                        от скольки дней <input type="number" name="discountDays" value="{{request('discountDays')}}"/>  
                    </div>
                </div>  
                <div class="row">
                    @if(isset($total))
                        <p>Итог: {{$total}}, скидка: {{$discount}}</p>
                    @endif
                    <div class="col-12">
                        <label for="isSave">Сохранить расчет?</label>
                        <input type="checkbox" name="isSave" value="1"/>
                        <input type="submit" value="Рассчитать"> 
                    </div>
                </div>
            </form>
            @if (isset($settings) && $settings->total() > 0)
            <div class="settings">
                <table>
                    <thead>
                        <tr>
                            <td>Дата заезда</td>
                            <td>Дата выезда</td>
                            <td>Количество проживающих</td>
                            <td>Базовая цена</td>
                            <td>Сезонная цена</td>
                            <td>Старт сезона</td>
                            <td>Окончание сезона</td>
                            <td>Максимальное число проживающих</td>
                            <td>Цена за дополнительного проживающего</td>
                            <td>Скидка</td>
                            <td>Скидка от числа дней</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($settings as $item)
                        <tr>
                            <td>{{ $item->dateBegin->format('Y-m-d') }}</td>
                            <td>{{ $item->dateEnd->format('Y-m-d') }}</td>
                            <td>{{ $item->people }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->seasonPrice }}</td>
                            <td>{{ $item->seasonDateBegin->format('Y-m-d') }}</td>
                            <td>{{ $item->seasonDateEnd->format('Y-m-d') }}</td>
                            <td>{{ $item->maxPeople }}</td>
                            <td>{{ $item->pricePeople }}</td>
                            <td>{{ $item->discount }}</td>
                            <td>{{ $item->discountDays }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                <table>
            </div>
            @endif
        </div>
        <script>
            var currentDate = new Date();

            $('[name="date_begin"]').on('change', function(){
                var val = $(this).val();
                var chooseDate = new Date(val);
                $('[name="date_end"]').attr('min', formatDate(chooseDate));
            });

            var currentDate = new Date();
            $('.daterange').daterangepicker({
                'minDate': currentDate,
                'locale': {
                    'format': 'DD.MM.YYYY'
                }
            }, function(start, end, label) {
            
            });

            function formatDate(date){
                var month = (date.getMonth() + 1);
                if(month < 10){
                    month = '0' + month; 
                }
                var day = date.getDate();
                var year = date.getFullYear();
                return year + '-' + month + '-' + day;
            }
        </script>
    </body>
</html>

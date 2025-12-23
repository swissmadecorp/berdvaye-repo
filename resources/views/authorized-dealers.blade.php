@extends('layouts.default1')

@section ('content')

<section class=" alternate" style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="text-align:center;margin-top: 15px">
                <h4>AUTHORIZED DEALERS</h4>
            </div>

            <div class="col-md-12">
                <div class="sub-text-line m_bottom_25"><img src="/public/images/title.png"></div>
                
                    <div class="container">
                        <div class="row text-center">
                            @inject('countries','App\Libs\Countries')

                            @foreach ($dealers as $dealer)
                                @if ($dealer->logo)
                                <article class="dealers col-sm-12 col-lg-4 col-md-6 custom-item-width">
                                    <a target="_blank" href="{{$dealer->website}}">
                                        <div class="dealer-item">
                                            <div class="dealer-logo">
                                            <img src="/public/images/logo/{{$dealer->logo}}" />
                                            </div>
                                            <hr>
                                            <p><?= nl2br($dealer->address).'<br>'.$dealer->website ?> 
                                        </div>
                                    </a>
                                </article>
                                @endif
                            @endforeach
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>


@endsection



@extends ("layouts.default1")

@section ('styles')
    <style>
        .verticalmenu.navigation.side-verticalmenu {width: 210px}
    </style>
@endsection

@section ('content')

    <div class="row" style="padding-top: 10px">
        <div class="col-sm-5">
            <div class="well">
                <h3 ><i class="fa fa-home fa-1x"></i> Address:</h3>    
                <p>Swiss Made Inc.<br>15 W 47th Street<br>Ste # 503<br>New York, NY 10036</p>

                <h3 ><i class="fa fa-envelope fa-1x"></i> E-Mail Address:</h3>
                <p style="">info@swissmade.com</p>
            </div>
        </div>
        <div class="col-sm-7">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.2171298816847!2d-73.98165538422266!3d40.75724887932698!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c258fef868bda1%3A0xb7ebc4fcc65c5822!2s15+W+47th+St%2C+New+York%2C+NY+10036!5e0!3m2!1sen!2sus!4v1500582532892" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
    </div>

       

@endsection
<html>
<head></head>
  <body>
    @if ($comments)
    {!! str_replace("\n","<br>",$comments) !!}
    @else
    <p>Dear {{ $company }},</p>
    <p>We're glad you found what you were looking for! Details for your {{$orderStatus }}
    #{{$order_id}} is attached to this email in PDF format.</p>
    <p>If you are having troubles viewing your {{$orderStatus }}, please notify us and we will send
      you a different format.</p>

    <p>Thank you,</p>

    <img src="https://berdvaye.com/images/berdvaye-logo-pdf.jpg" alt="" style="width: 140px" /><br>
    610 5th Ave PO Box 2222<br>
    New York, NY 10185<br>
    Tel: 833-BERDVAYE (237-3829)<br>
    info@berdvaye.com<br>
    www.berdvaye.com
  @endif
  </body>
</html>
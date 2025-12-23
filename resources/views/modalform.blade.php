<!-- MODAL -->
<div class="modal" id="pricingModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- <form role="form" id="pricingForm" method="post" action="upload/php/price.php"> -->
      <form role="form" id="contactForm" action="{{ route('ajax.inquiry') }}">
        <div class="modal-header text-center">
          <h4 class="modal-title">Request Pricing</h4>
        </div>
        <div class="modal-body">
          <div class="messages"></div>
          <ul class="defaultForm-list pl-0">
            <li>
              <div class="form-group">
                <input type="text" class="form-control trimSpace" id="exampleInputFullName" name="fullname" placeholder="Name" pattern="^[a-zA-Z0-9\-\/!@#$%^&*(),.?:{}_|<>+][\sa-zA-Z0-9\-\/!@#$%^&*(),.?:{}_|<>+]*" data-pattern-error="Use only letters or numbers as a first character" required>
                <div class="help-block with-errors"></div>
              </div>
            </li>
            <li>
              <div class="form-group">
                <input type="email" class="form-control" id="exampleInputEmail" name="email" placeholder="Email" required>
                <div class="help-block with-errors"></div>
              </div>
            </li>
            <li>
              <div class="form-group">
                <input type="number" class="form-control" id="exampleInputMobile" name="mobile" placeholder="Phone Number" required>
                <div class="help-block with-errors"></div>
              </div>
            </li>
            <li>
              <div class="form-group mb-0">
                <textarea class="form-control" id="exampleInputComment" name="message" placeholder="Your Message" required></textarea>
                <div class="help-block with-errors"></div>
              </div>
            </li>
            <li class="hideForm">
              <input type="text" id="inputProduct" name="product" />
              <input type="text" id="inputProductsize" name="productsize" />
            </li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-link" data-dismiss="modal">Close</button>
          <button type="submit" class="btn" >Send</button>
        </div>
        </form>
    </div>
  </div>
</div>
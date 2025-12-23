var billExt = '{{$billExt}}';
var shipExt = '{{$shipExt}}';

$('#'+billExt+'country').change( function() {
    _this = $(this);
    $.get("{{ route('get.state.from.country')}}",{id: $(_this).val()})
    .done (function (data) {
        $('#'+billExt+'state-input').html(data);
        if ($('#h_state').length) {
            $('#'+billExt+"state-input option[value='"+ $('#h_state').val() +"']").prop('selected','selected')
            $('#'+shipExt+"state-input option[value='"+ $('#h_state').val() +"']").prop('selected','selected')
        }
    })
})

if ( $('#'+shipExt+'country').length > 0 ) {
  $('#'+shipExt+'country').change( function() {
        _this = $(this);
        $.get("{{ route('get.state.from.country')}}",{id: $(_this).val()})
        .done (function (data) {
            $('#'+shipExt+'state-input').html(data);
        })
    })  
}
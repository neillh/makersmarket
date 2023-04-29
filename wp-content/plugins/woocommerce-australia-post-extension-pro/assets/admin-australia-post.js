jQuery( function ( $ ) {

  var australia_post_tracking_url = 'https://auspost.com.au/mypost/track/#/details/{tracking_id}';

  $('.action-group').show();

  $('#btn-generate-label').click(function (e) {
    e.preventDefault();
    $('#wpruby-spinner').addClass('is-active');
    var settings = {
      "url": ajaxurl,
      "method": "POST",
      "data": {
        "action": "generate_label",
        "order_id": order_id
      }
    };

    $.ajax(settings).done(function (response) {
      $('.action-group').hide();
      $('.action-group' + response.active_flow + ':first').show();
      $('#wpruby-spinner').removeClass('is-active');
    });
  });




function showErrors(errors) {
  var errors_html = '<ul>';
  $.each(errors, function (i, e) {
    errors_html += '<li>'+ e +'</li>';
  });
  $('#labels-errors p').html(errors_html + '</ul>');
  $('#labels-errors').show();
}


function get_shipment_items() {
  return {
    weight: get_item('weight'),
    length: get_item('length'),
    width: get_item('width'),
    height: get_item('height'),
    product_id: get_item('product-id')
  };
}

function get_item(attr){
  var items = [];
  $('.item-' + attr).each(function (e) {
    items.push($(this).val());
  });
  return items;
}
  $(document).on('click', '#btn-create-shipment',function (e) {
    e.preventDefault();
    $('#labels-errors').hide();
    $('#wpruby-spinner').addClass('is-active');
    var settings = {
      url: ajaxurl,
      method: "POST",
      dataType: 'json',
      data: {
        "action": "create_shipment",
        "order_id": order_id,
        "items": get_shipment_items()
      }
    };

    $.ajax(settings).done(function (response) {

      $('#wpruby-spinner').removeClass('is-active');
      if(response.status === 'ok'){

        $('#btn-print-label').attr('href', response.url);
        $('#btn-print-label').show();

        if(response.tracking_ids){
          $.each(response.tracking_ids, function (index, tracking_id) {
            var tracking_url = australia_post_tracking_url;
            $('#shipment-created-items').append(
              '<tr>' +
              '<td>'+(index + 1)+'</td>' +
              '<td><a target="_blank" href="'+ tracking_url.replace('{tracking_id}', tracking_id) +'">'+ tracking_id +'</a></td>' +
              '</tr>'
            );
          });
        }
        $(response.active_flow).show();
        $('.has_contracted_price').hide();
      }else{
        showErrors(response.errors);
      }


    });
  });






});
$(".commander").on('click', function (e) {
    var quantite = $('.number-spinner').find('input').val();
    var id_product = $("input[name='id_product']").val();
    $.ajax({
        url: '/panier/addpanier',
        type: "POST",
        dataType: "json",
        data: {
            'quantite': quantite, 'id_product': id_product, "format": "json"
        }
    }

    ).done(function (data) {
      
    });

});
//--------------Upload d'image
// Code goes here

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
$(".btn-default").on("click", function (e) {
    var type = $(this).attr("type");
    if (type === "panier") {
        var signe = $(this).attr("data-dir");
        var id = $(this).attr("id_product");
        var total = $('#total').attr("price");
        if (signe == "up") {
            $.ajax({
                url: '/panier/ajoutquantite',
                type: "POST",
                dataType: "json",
                data: {
                    'id_product': id, 'total': total, "format": "json"
                }
            }

            ).done(function (data) {
                var prix = data[id]['prix'];
                var newprix = data[id]['prixTTC'];
                var qte = data[id]['quantite'];
                var total = data[id]['total'];
                $("#ttc-" + id).text(newprix + " €");
                $('#total').text(total + "€");
                $('#total').attr("price", total);
                $("#qte-panier-" + id).text("Quantite : " + qte);
            });
        }
        if (signe == "dwn") {
            $.ajax({
                url: '/panier/retirequantite',
                type: "POST",
                dataType: "json",
                data: {
                    'id_product': id, 'total': total, "format": "json"
                }
            }

            ).done(function (data) {
                var qte = data[id]['quantite'];
                if (qte == 0) {
                    $("#tr-" + id).remove();
                    var total = data[id]['total'];
                    $('#total').text(total + "€");
                    $('#total').attr("price", total);
                    $('#item-' + id).remove();
                    var qtePanier = $("#panier").attr("nb");
                    qtePanier --;
                    if(qtePanier > 1){
                         $("#panier").text(qtePanier + " produits");
                    }else{
                         $("#panier").text(qtePanier + " produit");
                    }
                     
                    if (total == 0) {
                        $(".table-hover").remove();
                        $(".paniervide").show();
                        $("#panier").text("panier");
                        $("#voir-panier").hide();
                        $("#panier-vide").show();
                    }
                } else {
                    var prix = data[id]['prix'];
                    var newprix = data[id]['prixTTC'];
                    $("#ttc-" + id).text(newprix + " €");
                    var total = data[id]['total'];
                    $('#total').text(total + "€");
                    $('#total').attr("price", total);
                    $("#qte-panier-" + id).text("Quantite : " + qte);
                }
            });
        }

    }
});
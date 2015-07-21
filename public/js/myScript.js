$(".commander").on('click', function (e) {
    if ($('.bg-danger').is(':visible')) {
        return false;
        e.preventdefault();
    }
    var quantite = $('.number-spinner').find('input').val();
    var id_product = $("input[name='id_product']").val();
    var image = $("img").attr('src');
    var title = $(".product-title").text();
    var prix = $(".product-price").text();
    $.ajax({
        url: '/panier/addpanier',
        type: "POST",
        dataType: "json",
        data: {
            'quantite': quantite, 'id_product': id_product, "format": "json"
        }
    }

    ).done(function (data) {

        if ($('.item-info').text() != "") {
            $('.item-info').hide();
        } 
        var panier = $(".dropdown-cart").children().first();
        var text = "";
        text += "<li>";
        text += '<span class="item" id="item-' + id_product + '">';
        text += '<span class="item-left">';
        text += '<img style="max-height: 50px; max-width: 50px;" src="' + image + '" />';
        text += '<span class="item-info">';
        text += '<span><center><a href="/article/product/fiche/' + id_product + '">' + title + '</a></center></span>';
        text += '<span>Prix : ' + prix + ' </span>';
        text += '<span id="qte-panier-' + id_product + '">Quantité :' + quantite + '</span>';
        text += ' </span>';
        text += ' </span>';
        text += ' </span>';
        text += ' </li>';
        panier.append(text);
          $(".divider").show();
          $('.div-panier').show();
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
                    qtePanier--;
                    if (qtePanier > 1) {
                        $("#panier").text(qtePanier + " produits");
                    } else {
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
//----------------STEP Création de produit

$(document).ready(function () {

    var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');
});
//-------------Upload d'image
$(document).on('change', '.btn-file :file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        
        if( input.length ) {
           
        } else {
            if( log ) alert(log);
        }
        
    });
});
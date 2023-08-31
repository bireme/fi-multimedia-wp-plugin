var $j = jQuery;

$j(window).load(function(){
	regExp();
	reportarErro();
	sugerirTag();
});

$j(function(){
    $j('#similares').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 3000,
        infinite: true,
        dots: false
    });
});

function regExp(){
	if($j(".cat-item").length){
		$j("#categories-3 .cat-item").each(function(e){
			i = e+1;
			element = $(this);

			var cat_text = element.html();
			var cat_link = element.children("a").attr("href");
			var cat_nome = element.children("a").text();
			var cat_new  = "<a href='"+cat_link+"' title='Ver todos os posts arquivados em "+cat_nome+"'>"+cat_nome+"</a>";

			var regex    = /(.*)(\()(.*)(\))/;
			var result   = cat_text.replace(regex, "<span class='cat-item-count'>$3</span>");

			element.text("").append(cat_new+result);
		});
	}
}

function reportarErro(){
	$j(".reportar-erro-open").on("click", function(){
    $j(".reportar-erro").hide();
		$j(".compartilhar").hide();
		$j(".sugerir-tag").hide();
    $j(".error-report-result").hide();
		$j(this).siblings(".reportar-erro").show();
    $j(".erro-form").show();
	});

	$j(".reportar-erro-close").on("click", function(){
		$j(".reportar-erro").hide();
	});

  // Attach a submit handler to the form
  $j( "#reportErrorForm" ).submit(function( event ) {
    // Stop form from submitting normally
    event.preventDefault();

    // Get some values from elements on the page:
    var $form = $j( this ),
        url = $form.attr( "action" );

    // Send the data using post
    var posting = $j.post( url, $form.serialize() );

    // Put the results in a div
    posting.done(function( data ) {
      $j(".erro-form").hide();

      if (data == 'True'){
        $j(".error-report-result").find('#result-problem').hide();
      }else{
        $j(".error-report-result").find('#result-ok').hide();
      }

      $j(".error-report-result").show();
    });
  });
}

function sugerirTag(){
	$j(".sugerir-tag-open").on("click", function(){
		$j(".reportar-erro").hide();
		$j(".compartilhar").hide();
		$j(".sugerir-tag").hide();
    $j(".sugerir-tag-result").hide();
		$j(this).siblings(".sugerir-tag").show();
    $j(".sugerir-form").show();
	});

	$j(".sugerir-tag-close").on("click", function(){
		$j(".sugerir-tag").hide();
	});

  // Attach a submit handler to the form
  $j( "#tagForm" ).submit(function( event ) {
    // Stop form from submitting normally
    event.preventDefault();

    // Get some values from elements on the page:
    var $form = $j( this ),
        resource_id = $form.find( "input[name='resource_id']" ).val(),
        tags = $form.find( "input[name='txtTag']" ).val(),
        url = $form.attr( "action" );

    // Send the data using post
    var posting = $j.post( url, { resource_id: resource_id, tags: tags } );

    // Put the results in a div
    posting.done(function( data ) {
      $j(".sugerir-form").hide();

      if (data == 'True'){
        $j(".sugerir-tag-result").find('#result-problem').hide();
      }else{
        $j(".sugerir-tag-result").find('#result-ok').hide();
      }

      $j(".sugerir-tag-result").show();
    });
  });
}

function show_similar(url){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("ajax").innerHTML = this.responseText;
        }else {
            document.getElementById("ajax").innerHTML = '<li class="cat-item"><div class="loader"></div></li>';
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function show_related(url){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var loader = document.getElementById("loader");
            loader.parentNode.removeChild(loader);
            document.getElementById("async").innerHTML = this.responseText;

            $j('#async').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: false,
                autoplaySpeed: 3000,
                infinite: true,
                dots: false
            });
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function change_sort(obj){
    var sort = obj.options[obj.selectedIndex].value;
    var form = document.searchForm;

    form.sort.value = sort;
    $j("#searchForm").submit();
}
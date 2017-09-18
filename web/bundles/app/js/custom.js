

    $(document).ready(function () {


    /* accordion for story text when viewing in mobile */
    $('.panel-body.storyText a').click( function () {
        $('.panel-body.storyText p').toggle(600);
        $('.fa-chevron-up').toggle(0);
        $('.fa-chevron-down').toggle(0);
    }

    )


    if (typeof photosArr !== 'undefined') {
        console.log ("avem "+ photosArr.length +" poze");
        for (var i = 0; i < photosArr.length; i++) {
            console.log(photosArr[i].file + ": " + photosArr[i].note);
        }
    }

    /* filter select - if y0 is selected, disable y1 values that are smaller */
    var y0Selector = $('#y0');
    var y1Selector = $('#y1');
    //console.log("y0"+y0Selector);
    var v0 = 0;
    y0Selector.on('change', function() {
        v0 = parseInt(this.value);
        $('option[disabled]').prop("disabled",false);
        $('#y1 > option').each( function () {
            if ((parseInt(this.value) < v0) && (parseInt(this.value) > 0)) {
                    $( this ).prop('disabled', true);
                    if ($( this ).prop("selected") ) {
                        $( this ).prop("selected",false);
                        $('#y1 > option').first().prop("selected",true);

                    }
            }
        })
    });


    //navigation menu

    $('.responsive-nav-icon').click(function() {
		$('nav').addClass('slide-in');
		$('html').css("overflow", "hidden");
		$('#overlay').show();
		return false;
	});

	// Navigation Slide Out
	$('#overlay, .responsive-nav-close').click(function() {
		$('nav').removeClass('slide-in');
		$('html').css("overflow", "auto");
		$('#overlay').hide();
        $('#filter-area').hide();
        $('nav li a').removeClass('active');
		return false;
    });



    $('#filter').click(function () {
        $(this).toggleClass('active');
        $('#filter-area').slideToggle( 400 );
    });





  });

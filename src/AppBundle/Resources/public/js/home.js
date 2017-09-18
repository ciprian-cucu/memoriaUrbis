

    $(document).ready(function () {
    //initialize swiper when document ready

    var mySwiper;
    var paginator;

    function initMySwiper() {

        mySwiper = new Swiper('.s1', {
            speed: 400,
            hashnav: true,
            autoHeight: false,
            slidesPerView: 4,
            spaceBetween: 20,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            breakpoints: {
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    autoHeight: false,
                },
                640: {
                   slidesPerView: 1,
                   spaceBetween: 100,
                   autoHeight: true
               }
           }

        });

    }



    //slidesPerView trebuie sa fie mai mic decat nr. of slides :)
        function initPager () {

            //console.log("init pager");

            var numberOfStories = $('div.swiper-slide.pager-box').length; //number of stories actually loaded - this depends on filters
            var shownStories = mySwiper.params.slidesPerView; //defined above, this lets us know how many stories are shown in the viewport (depending on resolution)
            //console.log(shownStories);

            if (numberOfStories > 4) {
                var numberOfSlides = (numberOfStories < 40) ? numberOfStories : 40;
                var numberOfSlides1024 = (numberOfStories < 30) ? numberOfStories : 30;
                var numberOfSlides640 = (numberOfStories < 10) ? numberOfStories : 10;


                paginator = new Swiper('.s2', {
                    speed: 400,
                    slidesPerView: numberOfSlides,
                    spaceBetween: 3,
                    hashnav: false,
                    nextButton: '.s2-button-next',
                    prevButton: '.s2-button-prev',
                    breakpoints: {
                        1024: {
                            slidesPerView: numberOfSlides1024,
                            spaceBetween: 3,
                        },
                        640: {
                           slidesPerView: numberOfSlides640,
                           spaceBetween: 3,
                       }
                   }

                });

                var pi = $('.pager-box.active').offset().left; //pozitia initiala (unde incepe sa se incarce paginatorul)
                var width = $('.s2').width(); //latimea paginatorului


                function updateActivePage () {
                    $('.pager-box.active').removeClass('active');
                    var currentIndex = mySwiper.activeIndex;
                    for (var i = 1; i <= shownStories; i++) {
                        $('.pager-box:eq('+currentIndex+')').addClass('active');
                        currentIndex++;
                    }

                    var left = $('.pager-box:eq('+mySwiper.activeIndex+')').offset().left;
                    if ((pi + width < left) || (left < pi)) {
                        paginator.slideTo(mySwiper.activeIndex);
                    }
                }


                $('.pager-box').on('click', function(){
                        mySwiper.slideTo($(this).index());
                        updateActivePage ();

                        //$('.pager-box.active').removeClass('active');
                        //$(this).addClass('active');

                        /*
                        var el=$(this);
                        for (var i = 1; i < shownStories; i++) {
                            var el = el.next();
                            //console.log(el);
                            el.addClass('active');
                        }
                        */
                        //console.log($(this));
                });

                mySwiper.on('onSlideChangeEnd', function () {
                   //get new slider and set the correct active
                   updateActivePage ();
                });

                updateActivePage();
            } else {
                $('#pager').hide();
            }

        }



    if ($(window).width() < 960) {
        
        if (!sessionStorage.overlay1) {
            sessionStorage.overlay1 = 1;
            $('#overlay1').fadeIn(400);
            $('#closeOverlay').click(function () {
                $('#overlay1').fadeOut(700);
            });

            $(function() {
                // setTimeout() function will be fired after page is loaded
                // it will wait for 5 sec. and then will fire
                // $("#successMessage").hide() function
                setTimeout(function() {
                    $("#overlay1").fadeOut(1000)
                }, 4500);
            });
        };
    }

    initMySwiper();
    initPager();


    $(window).resize(function(){
        //console.log("resize");
        if (mySwiper) {
            mySwiper.destroy(true, true);
            initMySwiper();
        }
        if (paginator) {
            paginator.destroy(true, true);
            initPager();
        }

    });



  });

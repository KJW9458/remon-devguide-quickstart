$('.arrBtnWrap').click(function () {
    $(this).children('button').toggleClass('btnOff')
})

$('#expYear').ready(function () {
    var today = new Date(),
        yyyy = today.getFullYear(),
        inpYear = $('#expYear'),
        html = '';

    html = html + '<option>해당연도</option>'

    for (var i = 0; i < 30; i++, yyyy--) {
        html = html + '<option>' + yyyy + '</option>';
    };

    inpYear.html(html);

})
$(document).ready(function () {
    $('#wrap_header.main-page-header .tgnb a').css('color', '#fff')
    $('.main-page-header .tgnb-wrap').mouseenter(function () {
        $('.ti-wrap').stop().animate({
            height: '299px !important'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'block')
        $('.ti-wrap').stop().css('display', 'block')
        $('.tgnb-item-bg').stop().animate({
            height: '300px'
        }, 300)
        $('.main-page-header').stop().css('background', 'rgba(0,0,0,0.7)')
        $('#wrap_header .tgnb a').css('color', '#fff')
    })
    $('.main-page-header .tgnb-wrap').mouseleave(function () {
        $('.ti-wrap').stop().animate({
            height: '0'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'none')
        $('.ti-wrap').stop().css('display', 'none')
        $('.tgnb-item-bg').stop().animate({
            height: '0'
        }, 300)
        $('.main-page-header').stop().css('background', 'rgba(0,0,0,0.3)')
        $('#wrap_header .tgnb a').css('color', '#fff')
    })
    $('.main-page-header .tgnb-item-bg').mouseenter(function () {
        $('.ti-wrap').stop().animate({
            height: '299px !important'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'block')
        $('.ti-wrap').stop().css('display', 'block')
        $('.tgnb-item-bg').stop().animate({
            height: '300px'
        }, 300)
        $('.main-page-header').stop().css('background', 'rgba(0,0,0,0.7)')
        $('#wrap_header .tgnb a').css('color', '#fff')
    })
    $('.main-page-header .tgnb-item-bg').mouseleave(function () {
        $('.ti-wrap').stop().animate({
            height: '0'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'none')
        $('.ti-wrap').stop().css('display', 'none')
        $('.tgnb-item-bg').stop().animate({
            height: '0'
        }, 300)
        $('.main-page-header').stop().css('background', 'rgba(0,0,0,0.3)')
        $('#wrap_header .tgnb a').css('color', '#fff')
    })
    $('.main-page-header .ti-wrap').mouseenter(function () {
        $('.ti-wrap').stop().animate({
            height: '299px !important'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'block')
        $('.ti-wrap').stop().css('display', 'block')
        $('.tgnb-item-bg').stop().animate({
            height: '300px'
        }, 300)
        $('.main-page-header').stop().css('background', 'rgba(0,0,0,0.7)')
        $('#wrap_header .tgnb a').css('color', '#fff')
    })
    $('.main-page-header .ti-wrap').mouseleave(function () {
        $('.ti-wrap').stop().animate({
            height: '0'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'none')
        $('.ti-wrap').stop().css('display', 'none')
        $('.tgnb-item-bg').stop().animate({
            height: '0'
        }, 300)
        $('.main-page-header').stop().css('background', 'rgba(0,0,0,0.3)')
        $('#wrap_header .tgnb a').css('color', '#fff')
    })
})



$(document).ready(function () {
    $('.sub-page-header .tgnb-wrap').mouseenter(function () {
        $('.ti-wrap').stop().animate({
            height: '302px !important'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'block')
        $('.ti-wrap').stop().css('display', 'block')
        $('.tgnb-item-bg').stop().animate({
            height: '300px'
        }, 300)
    })
    $('.sub-page-header .tgnb-wrap').mouseleave(function () {
        $('.ti-wrap').stop().animate({
            height: '0'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'none')
        $('.ti-wrap').stop().css('display', 'none')
        $('.tgnb-item-bg').stop().animate({
            height: '0'
        }, 300)
    })
    $('.sub-page-header .tgnb-item-bg').mouseenter(function () {
        $('.ti-wrap').stop().animate({
            height: '302px !important'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'block')
        $('.ti-wrap').stop().css('display', 'block')
        $('.tgnb-item-bg').stop().animate({
            height: '300px'
        }, 300)
    })
    $('.sub-page-header .tgnb-item-bg').mouseleave(function () {
        $('.ti-wrap').stop().animate({
            height: '0'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'none')
        $('.ti-wrap').stop().css('display', 'none')
        $('.tgnb-item-bg').stop().animate({
            height: '0'
        }, 300)
    })
    $('.sub-page-header .ti-wrap').mouseenter(function () {
        $('.ti-wrap').stop().animate({
            height: '302px !important'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'block')
        $('.ti-wrap').stop().css('display', 'block')
        $('.tgnb-item-bg').stop().animate({
            height: '300px'
        }, 300)
    })
    $('.sub-page-header .ti-wrap').mouseleave(function () {
        $('.ti-wrap').stop().animate({
            height: '0'
        }, 300)
        $('.tgnb-item-wrap').stop().css('display', 'none')
        $('.ti-wrap').stop().css('display', 'none')
        $('.tgnb-item-bg').stop().animate({
            height: '0'
        }, 300)
    })
})

$(document).ready(function () {
    $('#menuToggle input').removeClass('mmOn')
    $('.mobile-menu-bg').css('display', 'none');
    $('#menuToggle input').click(function () {
        var windowWidth = $(window).width()
        if (windowWidth >= 760) {
            $(this).toggleClass('mmOn');
            if ($('#menuToggle input').hasClass('mmOn')) {
                $('.mobile-menu-bg').fadeIn();
                /**
                $('#menuToggle').css('top', '25px')
                $('#menuToggle').css('left', '20px')
                $('#menuToggle').css('position', 'fixed')
                */
                $('#menu').animate({
                    left: '15px'
                }, 300)
            } else {
                $('.mobile-menu-bg').fadeOut();
                /**
                $('#menuToggle').css('top', '0px');
                $('#menuToggle').css('left', '0px');
                $('#menuToggle').css('position', 'relative')
                **/
                $('#menu').animate({
                    left: '-248px'
                }, 300)
            }
        } else if (windowWidth < 760) {
            $(this).toggleClass('mmOn');
            if ($('#menuToggle input').hasClass('mmOn')) {
                $('.mobile-menu-bg').fadeIn();
                /**
                $('#menuToggle').css('top', '14px')
                $('#menuToggle').css('left', '20px')
                $('#menuToggle').css('position', 'fixed')
                **/
                $('#menu').animate({
                    left: '15px'
                }, 300)
            } else {
                $('.mobile-menu-bg').fadeOut();
                /**
                $('#menuToggle').css('top', '0px');
                $('#menuToggle').css('left', '0px');
                $('#menuToggle').css('position', 'relative')
                **/
                $('#menu').animate({
                    left: '-248px'
                }, 300)
            }
        }


    })
})

$(document).ready(function () {
    $('.mmi-cat1').click(function () {
        if ($(this).hasClass('red-dot') == true) {
            $(this).siblings('.mmi-cat2-wrap').slideUp(200);
            $(this).removeClass('red-dot')
        } else if ($(this).hasClass('red-dot') == false) {
            $(this).parents('li').siblings('li').children('.mmi-cat2-wrap').slideUp(200);
            $(this).parents('li').siblings('li').children('.mmi-cat1').removeClass('red-dot')
            $(this).stop().siblings('.mmi-cat2-wrap').slideDown(200)
            $(this).addClass('red-dot')
        }
    })
})





$(document).ready(function () {
    $('html').click(function (e) {
        var $tgPoint = $(e.target);
        var $listCall = $tgPoint.hasClass('scwlcs-btn')
        var $listArea = $tgPoint.hasClass('scwlcs')
        if (!$listCall && !$listArea) {
            $('.scwlcs-list').slideUp(300)
            $('.scwlcs-btn').removeClass('slideDown')
        }
    })
    $('.scwlcs-btn').click(function () {
        $(this).parents('.scwlcs').siblings('.scwlcs').children('.scwlcs-list').slideUp(300)
        if ($(this).hasClass('slideDown') == false) {
            $(this).addClass('slideDown')
            $(this).siblings('.scwlcs-list').slideDown(300)

        } else if ($(this).hasClass('slideDown') == true) {
            $(this).removeClass('slideDown')
            $(this).siblings('.scwlcs-list').slideUp(300)
        }
    })
    $('.scwlcs-list-item').click(function () {
        var selectedItem = $(this).children('label').text()
        $(this).parents('.scwlcs-list').siblings('.scwlcs-btn').text(selectedItem)
    })

})

$(document).ready(function () {
    var historyH = $('.schc-txt-wrap').outerHeight()
    $('.schc-line').css({
        height: historyH
    })
})


$('.scgc-item img').on('click', function () {
    var imgURL = $(this).attr('src')
    console.log(imgURL)
    $('.gallery-window').css('display', 'block')
    $('.gallery-window-bg').css('display', 'block')
    $('.gw-img img').attr('src', imgURL)
    var imgwidth = $('.gw-img img').outerWidth()
    var imgHeight = $('.gw-img img').outerHeight()
    var marginleft = -imgwidth / 2
    var margintop = -imgHeight / 2
    $('.gw-img img').css('margin-left', marginleft)
    $('.gw-img img').css('margin-top', margintop)
})

$('.gallery-window-bg').click(function () {
    $('.gallery-window').css('display', 'none')
    $('.gallery-window-bg').css('display', 'none')
})


$('.scmc-item img').on('click', function () {
    var videoURL = $(this).siblings('video').attr('src')
    console.log(videoURL)
    $('.movie-window').css('display', 'block')
    $('.movie-window-bg').css('display', 'block')
    if (videoURL.match('/movie/')) {
        $('.mw-youtube-wrap').css('display', 'none')
        $('.mw-movie-wrap video').css('display', 'block')
        $('.mw-movie-wrap video').attr('src', videoURL)
        $('.scmc-txt-wrap').css('top', '10px')

        $('.movie-close-btn').css('top', '-50px')
    } else {
        var youtubeLink = $('.youtube-link').html()
        $('.mw-movie-wrap video').css('display', 'none')
        $('.mw-youtube-wrap').css('display', 'block')
        $('.mw-youtube .embed-youtube').html(youtubeLink)

        var youtubeWidth = $('.mw-youtube-wrap').outerWidth()
        var youtubeHW = -youtubeWidth / 2
        $('.mw-youtube-wrap').css('margin-left', youtubeHW)
        $('.scmc-txt-wrap').css('top', '310px')
        $('.movie-close-btn').css('top', '240px')
    }
})

$('.movie-window-bg').on('click', function () {
    $('.movie-window').css('display', 'none')
    $('.movie-window-bg').css('display', 'none')
})
$('.movie-close-btn').on('click', function () {
    $('.movie-window').css('display', 'none')
    $('.movie-window-bg').css('display', 'none')
})



$(document).ready(function () {
    var scatHeight = $('.scat').outerHeight()
    var marginscat = -scatHeight / 2
    $('.scat').css('margin-top', marginscat)
})


$(function () {
    $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        zIndex: 2048,
        language: 'ko-KR'
    });
});
$(document).ready(function () {
    $('.scai-wine').click(function () {
        var $more = $(this).siblings('.scai-more')
        var $winename = $(this)
        $more.stop().slideToggle(300)
        $winename.stop().toggleClass('more-open')
    })
})


if ($('.scwpi-link li').length > 0) {
    $('.scwpi-note').css('display', 'block')
    $('.scwpi-notyet').css('display', 'none')
} else {
    $('.scwpi-note').css('display', 'none')
    $('.scwpi-notyet').css('display', 'block')
}


$(window).resize(function () {
    var changedWidth = $(window).outerWidth()
    if (changedWidth > 780) {
        $('.admin-mobile-menu-btn').removeClass('ammbOpen')
        $('.LgnbWrap').css('left', '0')
        $('.adminPageWrap').css('left', '122px')
    } else if (changedWidth < 780) {
        $('body').css('overflow-x', 'hidden')

    }
})

$(document).ready(function () {
    var windowWidth = $(window).outerWidth()
    $('.admin-mobile-menu-btn').addClass('ammbOpen')
    if (windowWidth < 780) {
        $('.admin-mobile-menu-btn').click(function () {
            if ($(this).hasClass('ammbOpen') == true) {
                $('.LgnbWrap').animate({
                    left: '0px'
                }, 300)
                $('.adminPageWrap').animate({
                    left: '122px'
                }, 300)
                $(this).removeClass('ammbOpen')
            } else if ($(this).hasClass('ammbOpen') == false) {
                $('.LgnbWrap').animate({
                    left: '-120px'
                }, 100)
                $('.adminPageWrap').animate({
                    left: '0'
                }, 100)
                $(this).addClass('ammbOpen')
            }
        })

    } else if (windowWidth > 780) {
        $('LgnbWrap').css('left', '0')
        $('.admin-mobile-menu-btn').click(function () {
            if ($(this).hasClass('ammbOpen') == true) {
                $('.LgnbWrap').animate({
                    left: '0px'
                }, 300)
                $('.adminPageWrap').animate({
                    left: '122px'
                }, 300)
                $(this).removeClass('ammbOpen')
            } else if ($(this).hasClass('ammbOpen') == false) {
                $('.LgnbWrap').animate({
                    left: '-120px'
                }, 300)
                $('.adminPageWrap').animate({
                    left: '0'
                }, 300)
                $(this).addClass('ammbOpen')
            }
        })
    }

})




$(document).ready(function () {
    $('.admin-couponEvent tr').click(function () {
        $('.ac-infoWindow-wrap').css('display', 'block')
        var infoName = $(this).children('td:nth-child(1)').text()
        var infoMail = $(this).children('td:nth-child(2)').text()
        var infoAddress = $(this).children('td:nth-child(3)').text()
        var infoPhone = $(this).children('td:nth-child(4)').text()
        var infoCode = $(this).children('td:nth-child(5)').text()

        $('.aciWi-name').text(infoName)
        $('.aciWi-mail').text(infoMail)
        $('.aciWi-address').text(infoAddress)
        $('.aciWi-phone').text(infoPhone)
        $('.aciWi-code').text(infoCode)

    })
    $('.ac-infoWindow-close').click(function () {
        $('.ac-infoWindow-wrap').css('display', 'none')
    })
})

$(document).ready(function () {
     var w = $('.adminPageTit').children('p').not('.txtLine').text()
    $('title').text(w)
})

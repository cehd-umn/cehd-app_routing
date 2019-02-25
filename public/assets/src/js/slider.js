var slides = document.querySelectorAll('#slides .slide');
var navs = document.querySelectorAll('.navthumbs a');
var currentSlide = 0;
var currentNav = 0;
var slideInterval = setInterval(nextSlide,5000);

function nextSlide(){
    goToSlide(currentSlide+1)
    showNav(currentSlide+1);
}

function previousSlide(){
    goToSlide(currentSlide-1);
    showNav(currentSlide+1);
}

function goToSlide(n){
    slides[currentSlide].className = 'slide';
    currentSlide = (n+slides.length)%slides.length;
    slides[currentSlide].className = 'slide showing';
}

function showSlide(id){
    var thisSlide = id;
    slides[currentSlide].className = 'slide';
    currentSlide = thisSlide;
    slides[currentSlide].className = 'slide showing';
}

function showNav(n){
    console.log(n)
    $('.nav-thumbs a.bullet-active').removeClass('bullet-active');
    var currentBullet = document.getElementsByClassName(n);
    console.log(n)
    $(currentBullet).addClass("bullet-active");
}


sliderInt = 0; //slider default on load
sliderNext = 1; //next image in order

$('.nav-thumbs a').click(function(){
    pauseSlideshow();
    $('.nav-thumbs a').removeClass('bullet-active');
    $(this).addClass('bullet-active');
    var id = $(this).attr('data-slide');
    showSlide(id);
    playSlideshow();
});

var playing = true;

function pauseSlideshow(){
    playing = false;
    clearInterval(slideInterval);
}

function playSlideshow(){
    playing = true;
    slideInterval = setInterval(nextSlide,5000);
}

// pauseButton.onclick = function(){
//     if(playing){ pauseSlideshow(); }
//     else{ playSlideshow(); }
// };

var next = document.getElementById('next');
var previous = document.getElementById('previous');

next.onclick = function(){
    pauseSlideshow();
    nextSlide();
    playSlideshow();
};
previous.onclick = function(){
    pauseSlideshow();
    previousSlide();
    playSlideshow();
};

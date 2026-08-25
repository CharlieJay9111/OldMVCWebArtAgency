

var ScrollHelper = {
    init : function(navSelector, padding){

        hash = location.hash;

        if(hash){
            nav = document.querySelector(navSelector);
            height = nav.clientHeight;

            element = document.querySelector(hash);
            y = element.offsetTop;

            scrollTo(0, y - height - padding);
        }
        
    }
}

window.addEventListener("load", function(){

    ScrollHelper.init("nav", 20);
} );


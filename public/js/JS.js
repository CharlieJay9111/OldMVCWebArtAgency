

var JS = {
    toggle : function(selector, css = "hide"){
        element = document.querySelector(selector);

        if(element.classList.contains(css)){
            element.classList.remove(css)
        }
        else
        {
            element.classList.add(css);
        }
    }
}
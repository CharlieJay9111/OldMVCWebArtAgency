
var Editor = {
    
    element : null,
    input : null,
    actions : {
        bold : "STRONG",

    },
    

    init : function()
    {
        element = document.querySelector("#editor");
        this.element = element.children[0];
        this.input = element.children[1];
        
        this.input.classList.add("hide");
        this.element.innerHTML = this.input.innerText;
        this.element.setAttribute("contenteditable", true);
        this.element.setAttribute("spellcheck", true);

        actions = document.querySelectorAll("#editor-nav > span")

        this.element.onkeydown = function(e){

            Editor.keyup(e);

            if(e.keyCode == 13)
            {
                e.preventDefault();
                Editor.enter();
            }

            if(e.keyCode == 8)
            {
                if(Editor.element.innerHTML.match(/^[\u200B]+$/))
                {
                    Editor.element.innerHTML = "";
                }
            }
        }

        this.element.onkeyup = function(e){

            Editor.keyup(e);
        }

        this.element.onmouseup = function(e){

            Editor.keyup(e);

        }

        this.element.onfocus = function(e){

        }

        this.element.onblur = function(e)
        {
            if(Editor.element.innerHTML.match(/^\u200B+$/) || Editor.element.innerHTML == "<br>")
            {
                Editor.element.innerHTML = "";
            }

            
        }

        actions.forEach(element => {
            element.onclick = function(){

                var command = element.dataset['action'];
                
                switch(command)
                {
                    case "bold" : Editor.add("strong"); break;
                    case "italic" : Editor.add("em"); break;
                    case "underline" : Editor.add("u"); break;
                    case "link" : Editor.add("a", { src : prompt("Odkaz:", "https://") }); break;
                }
            }
        });
    },

    //
    keyup : function(e)
    {
        console.log(e);

        var selection = window.getSelection();
        var anchorNode = selection.anchorNode;
        var parents = this.getParents(anchorNode);

        var range = selection.getRangeAt(0);
        var clone = range.cloneContents();
        
        this.toggle("STRONG", "bold", parents);
        this.toggle("EM", "italic", parents);
        this.toggle("U", "underline", parents);
        this.toggle("A", "link", parents);

        console.log(clone)

    },


    toggle : function(name, action, array)
    {
        var element = document.querySelector(`[data-action=${action}]`);

        if(array.find(value => value == name))
        {
            element.classList.add("active");
        }
        else
        {
            element.classList.remove("active");
        }
    },

    getParents(element)
    {
        var parents = [];

        while(element)
        {
            element = element.parentElement;
            if(element)
            parents.push(element.tagName);

            if(element == this.element)
            {
                element = null;
            }
        }

        return parents;
    },

    //
    add: function(tag, attributes = null)
    {
        selection = window.getSelection();
        range = selection.getRangeAt(0);



        clone = range.cloneContents();

        html = this.getSelectionHtml();
        if(html)
        {
            start = new RegExp("<" + tag + ">");
            end = new RegExp("</" + tag + ">");

            console.log("html", html);

            if(html.match(start) && html.match(end))
            {
                html = html.replace(start, "").replace(end, "");
                
                console.log("html replace", html);

                container = document.createElement("div");
                container.innerHTML = html;

                var fragment = document.createDocumentFragment(), node, lastNode;
                while ( (node = container.firstChild) ) {
                    lastNode = fragment.appendChild(node);
                }

                content = range.extractContents();
                range.insertNode(fragment)
            }
            else
            {
                element = this.createElement(tag, attributes);
                range.surroundContents(element);
            }
        }
        else
        {
            element = this.createElement(tag, attributes);
            range.surroundContents(element);
        }
        
        this.element.focus(selection);

        return element;
    },

    submit : function(e)
    {
        e.preventDefault();
        form = e.target;
        content = this.element.innerHTML.replaceAll("\u200B", "");
        this.input.innerHTML = content;
        form.submit();
    },

    createElement: function(tag, attributes = null)
    {
        element = document.createElement(tag);

        if(attributes)
        {
            keys = Object.keys(attributes);
            values = Object.values(attributes);

            values.forEach((value, key) => {
                key = keys[key];
                element.setAttribute(key, value);
            });
        }

        return element;
    },

    readImage(element) {

        file = element.files[0];
        img = element.parentElement.querySelector("img");

        if (file.type && !file.type.startsWith('image/')) {
            console.log('File is not an image.', file.type, file);
            return;
        }

        const reader = new FileReader();
        reader.addEventListener('load', (event) => {
            img.src = event.target.result;
        });

        reader.readAsDataURL(file);
    },

    enter: function()
    {
        selection = window.getSelection();
        range = selection.getRangeAt(0);

        element = document.createElement("br");
        range.deleteContents();
        range.insertNode(element);

        range.setStartAfter(element)
        range.setEndAfter(element)

        ze = document.createTextNode("\u200B");
        range.insertNode(ze);
        range.setStartBefore(ze);
        range.setEndBefore(ze);
    
        selection.removeAllRanges();
        selection.addRange(range);
    },

    end: function()
    {
        range = new Range();
        range.setStart(Editor.element.lastChild, 0);
        range.setEnd(Editor.element.lastChild, 0);
        return range;
    },

    getSelectionHtml: function() {
        var html = "";
        if (typeof window.getSelection != "undefined") {
            var sel = window.getSelection();
            if (sel.rangeCount) {
                var container = document.createElement("div");
                for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                    container.appendChild(sel.getRangeAt(i).cloneContents());
                }
                html = container.innerHTML;
            }
        } else if (typeof document.selection != "undefined") {
            if (document.selection.type == "Text") {
                html = document.selection.createRange().htmlText;
            }
        }
        return html;
    }
}

Editor.init();
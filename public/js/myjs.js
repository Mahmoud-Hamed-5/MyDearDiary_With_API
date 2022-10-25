function isliked(post_id) {
    var xhr = new XMLHttpRequest();

    var url = '/post/isliked/' + post_id;
    xhr.open("GET", url, false);
    var isliked = 0;
    xhr.onload = function() {
        isliked = this.responseText;
    }

    xhr.send();
    return isliked;
}

function like(post_id, counter_id, b) {
    // Creating XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    var liked = isliked(post_id);

    if (liked == 1) {
        var url = '/post/dislike/' + post_id;
        classNamew = "fa-regular fa-2x fa-thumbs-up";
        //document.getElementById("pa").innerHTML = "not liked";
    } else {
        var url = '/post/like/' + post_id;
        classNamew = "fa-regular fa-2x fa-thumbs-down";
        //document.getElementById("pa").innerHTML = "liked";
    }

    // Making connection
    xhr.open("POST", url, true);

    // function execute after request is successful
    xhr.onload = function() {
            document.getElementById(counter_id).innerHTML = " " + this.responseText;

            document.getElementById("like_button" + b).className = classNamew;
        }
        // Sending request
    xhr.send();
}

function show(id, n) {
    // Creating XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    var url = '/post/likenames/' + id;

    // Making connection
    xhr.open("GET", url, true);
    // function execute after request is successful
    xhr.onload = function() {

            document.getElementById("likes_names" + n).innerHTML = this.response;
        }
        // Sending request
    xhr.send();
}

function show2(id, n) {

    var req = new XMLHttpRequest();
    var url = '/post/likenames/' + id;
    req.responseType = 'json';
    req.open('GET', url, true);
    req.onload = function() {
        var json = req.response;
        // do something with jsonResponse
        json.forEach(name => {
            document.getElementById("likes_names" + n).innerHTML += "<br>" + name;
        });

    };
    req.send(null);

}

function hide(n) {
    document.getElementById("likes_names" + n).innerHTML = "";
}

function showLikes(post_id) {
    var req = new XMLHttpRequest();
    let url = '/post/likenames/' + post_id;
    req.responseType = 'json';
    req.open('GET', url, true);
    req.onload = function() {
        // do something with jsonResponse
        var json = req.response;
        var count = Object.keys(json).length;
        if (count != 0) {
            document.getElementById("smallBody").innerHTML = "People who likes this: <br>";
            json.forEach(name => {
                document.getElementById("smallBody").innerHTML += name + "<br>";
            });
        } else {
            document.getElementById("smallBody").innerHTML = "No likes yet!";
        }
    }
    req.send(null);
}

function showHideReplies(replies_id, span_id, replies_count) {
    replies_class = document.getElementById(replies_id).className;
    span_title = document.getElementById(span_id).innerHTML;


    new_replies_class = (replies_class == "d-none") ? "d-block" : "d-none";

    show_title = "Show " + replies_count + " replies";
    hide_title = "Hide " + replies_count + " replies";
    new_span_title = (span_title == show_title) ? hide_title : show_title;

    document.getElementById(replies_id).className = new_replies_class;
    document.getElementById(span_id).innerHTML = new_span_title;
}
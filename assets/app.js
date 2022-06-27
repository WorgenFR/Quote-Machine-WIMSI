/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
//import './bootstrap';

require('@fortawesome/fontawesome-free/js/all.js');

import $ from 'jquery';
var parseInt = require('parse-int');

// $('body').on('click', function () {
//     alert('Test');
// });

//Changement de l'icone 'like' selon l'etat
$('.like-btn').on('click', function(){
    if($(this).hasClass('fas')){
        $(this).children().removeClass('fas');
        $(this).children().addClass('far')
    }
    else{
        $(this).next().html('');
        $(this).children().removeClass('far');
        $(this).children().addClass('fas');
    }

    let nbLike = $('span').html();
    $(this).siblings().html(parseInt(nbLike) + 1);
})






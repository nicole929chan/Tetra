
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});

$(document).ready(function(){

  $("#sidebar-arrow").click(function(){
    $("#sidebar-open").css('display', 'block');
    $("#sidebar").stop(true).animate({width:0, opacity: '0'}, 'slow', function(){
      $("#sidebar").css('display', 'none');

    });
  });

  $("#tools_on").click(function(){
    $("#sidebar-open").css('display', 'none');
    $("#sidebar").css('display', 'block');
    $("#sidebar").stop(true).animate({width: '100%', opacity: '1'}, 'slow', function(){


    });
  });

  $("#menu_off").click(function(){
    $("#menu-open").css('display', 'block');
    var mapid_height = $("#mapid").height() + 45;
    $("#mapid").css('height', mapid_height);
    $("#menu").stop(true).animate({height:0, opacity: '0'}, 'slow', function(){
      $("#menu").css('display', 'none');

    });
  });

  $("#menu_on").click(function(){
    $("#menu-open").css('display', 'none');
    $("#menu").css('display', 'block');
    var mapid_height = $("#mapid").height() - 45;
    $("#mapid").css('height', mapid_height);
    $("#menu").stop(true).animate({height: '45px', opacity: '1'}, 'slow', function(){

    });
  });
});

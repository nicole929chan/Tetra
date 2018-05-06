/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 189);
/******/ })
/************************************************************************/
/******/ ({

/***/ 189:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(190);


/***/ }),

/***/ 190:
/***/ (function(module, exports) {

$(document).ready(function () {
  var selection_height = 0;
  //關閉底部
  $("#view_select_off").click(function (event) {
    event.preventDefault();
    selection_height = $("#view_select").height();
    $("#view_select-open").css('display', 'block');
    $("#view_select").stop(true).animate({ height: 0, opacity: '0' }, 'slow', function () {
      $("#view_select").css('display', 'none');
    });
  });

  //展開底部
  $("#view_select_on").click(function (event) {
    event.preventDefault();
    $("#view_select-open").css('display', 'none');
    $("#view_select").css('display', 'block');
    $("#view_select").stop(true).animate({ height: selection_height, opacity: '1' }, 'slow', function () {});
  });

  $('.selections').click(function () {
    $("#main_img").attr('rel', $(this).attr('rel'));
    $("#main_img").attr('src', $(this).children('img').attr('src'));
    $("#download").attr('href', $(this).children('img').attr('src'));
  });

  $('#feedback').click(function () {
    var main_img = $("#main_img").attr('rel');
    var url = "/rooms/selections/";

    $.ajax({
      data: main_img,
      dataType: 'json',
      type: "POST",
      url: url + main_img,
      success: function success(data) {
        console.log(data);
        alert(data.message);
      },
      error: function error(data) {
        console.log('Error:', data);
      }
    });
  });
});

/***/ })

/******/ });
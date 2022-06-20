/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ (function(module) {

module.exports = window["jQuery"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["data"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!*********************!*\
  !*** ./src/main.js ***!
  \*********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);


/*import { useBlockProps } from '@wordpress/block-editor';*/

/*
const allPosts = useSelect((select) => {
	return wp.data.select('core/block-editor').getBlockAttributes('create-block/agutenblog').attributes;
});

console.log(allPosts);
debugger;*/

jquery__WEBPACK_IMPORTED_MODULE_0___default()(function ($) {
  /* $('body').on('click', 'a.page-numbers', function (e){
         e.preventDefault();
         console.log(myAjax.ajaxurl);
         $.ajax({
             type: "post",
             dataType: "json",
             url: '/getPosts/v1/getHTML',
             data: {
                 action: "load_ajax_archive_list",
                 /!* current_category_id: current_category_id,
                  feature_post_ids: feature_post_ids,
                  per_page: per_page,
                  pageNumber: pageNumber*!/
             },
             beforeSend: function() {
                 /!*loadMoreButton.addClass('loading').html('+ Load More...');
                 targetSection.addClass('loading-section');*!/
             },
             success: function(response) {
                 /!*loadMoreButton.attr('data-pageNumber', (pageNumber + 1));
                 loadMoreButton.closest('div').before(response);
                  if (response === '') {
                     loadMoreButton.hide();
                 } else {
                     loadMoreButton.show();
                 }
     *!/
                 $('.list-items').append(response.data.data);
                 console.log(response);
             },
             complete: function() {
                 /!* loadMoreButton.removeClass('loading').html('+ Load More');
                  targetSection.removeClass('loading-section');*!/
             },
             error: function(xhr) {
                 // if error occured
                 alert("Error occured. Please try again");
                 console.log(xhr.statusText + xhr.responseText);
             },
         });
     });*/
});

function getResults() {
  jquery__WEBPACK_IMPORTED_MODULE_0___default().when(jquery__WEBPACK_IMPORTED_MODULE_0___default().getJSON('http://localhost/wordpress/gutenberg/wordpress/wp-json/getPosts/v1/getHTML?id=63')).then(posts => {
    //	$('.list-items').append(posts.data.posts);
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.list-items').append(posts.posts);
    console.log(posts);
  }, () => {
    console.log(`<p>Unexpected error; Please try again.</p>`);
  });
  /* this.resultsDiv.html("Imagine real search results here...");
                     this.isSpinnerVisible = false;*/
}

jquery__WEBPACK_IMPORTED_MODULE_0___default()('body').on('click', 'a.page-numbers', function (e) {
  e.preventDefault();
  getResults();
});
}();
/******/ })()
;
//# sourceMappingURL=main.js.map
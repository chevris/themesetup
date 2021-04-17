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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/editor-preview.js":
/*!****************************************!*\
  !*** ./resources/js/editor-preview.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\n * Philip Walton implementation of \"container queries\"\n *\n * Use this javascript code to add classes to the .block-editor__typewriter block just following\n * the .editor-styles-wrapper block. Those classes are here to mimick front-end breakpoints.\n * As .editor-styles-wrapper container replaces front-end body and because of the 2 sidebars\n * (WordPress left menu and Gutenberg right settings sidebar), traditional media queries could\n * be very complicated to use. By using those classes for breakpoints it is much more easier\n * to simulate front-end sidebars in the back-end editor.\n *\n * Classes and breakpoints :\n *     .small-container class added beyond 1px width\n *     .medium-container class added beyond 992px width\n *     .large-container class added beyond 1170px width\n *\n * @see https://philipwalton.com/articles/responsive-components-a-solution-to-the-container-queries-problem/\n */\n\n/*\nwindow.addEventListener( 'DOMContentLoaded', e => {\n\n\tvar ro;\n\n\tif ( 'ResizeObserver' in self ) {\n\t\tro = new ResizeObserver( function( entries ) {\n\t\t\tentries.forEach( function( entry ) {\n\n\t\t\t\t// Those breakpoints are theme dependent\n\t\t\t\tvar breakpoints = {\n\t\t\t\t\t'small-container': 1,\n\t\t\t\t\t'medium-container': 992,\n\t\t\t\t\t'large-container': 1170\n\t\t\t\t};\n\t\t\t\tObject.keys( breakpoints ).forEach( function( breakpoint ) {\n\t\t\t\t\tvar minWidth = breakpoints[breakpoint];\n\t\t\t\t\tentry.contentRect.width >= minWidth ?\n\t\t\t\t\t\tentry.target.classList.add( breakpoint ) :\n\t\t\t\t\t\tentry.target.classList.remove( breakpoint );\n\t\t\t\t});\n\t\t\t});\n\t\t});\n\t\tro.observe( document.querySelector( '.editor-styles-wrapper' ) );\n\t}\n});\n*///# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvZWRpdG9yLXByZXZpZXcuanM/YmE0YiJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsImZpbGUiOiIuL3Jlc291cmNlcy9qcy9lZGl0b3ItcHJldmlldy5qcy5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogUGhpbGlwIFdhbHRvbiBpbXBsZW1lbnRhdGlvbiBvZiBcImNvbnRhaW5lciBxdWVyaWVzXCJcbiAqXG4gKiBVc2UgdGhpcyBqYXZhc2NyaXB0IGNvZGUgdG8gYWRkIGNsYXNzZXMgdG8gdGhlIC5ibG9jay1lZGl0b3JfX3R5cGV3cml0ZXIgYmxvY2sganVzdCBmb2xsb3dpbmdcbiAqIHRoZSAuZWRpdG9yLXN0eWxlcy13cmFwcGVyIGJsb2NrLiBUaG9zZSBjbGFzc2VzIGFyZSBoZXJlIHRvIG1pbWljayBmcm9udC1lbmQgYnJlYWtwb2ludHMuXG4gKiBBcyAuZWRpdG9yLXN0eWxlcy13cmFwcGVyIGNvbnRhaW5lciByZXBsYWNlcyBmcm9udC1lbmQgYm9keSBhbmQgYmVjYXVzZSBvZiB0aGUgMiBzaWRlYmFyc1xuICogKFdvcmRQcmVzcyBsZWZ0IG1lbnUgYW5kIEd1dGVuYmVyZyByaWdodCBzZXR0aW5ncyBzaWRlYmFyKSwgdHJhZGl0aW9uYWwgbWVkaWEgcXVlcmllcyBjb3VsZFxuICogYmUgdmVyeSBjb21wbGljYXRlZCB0byB1c2UuIEJ5IHVzaW5nIHRob3NlIGNsYXNzZXMgZm9yIGJyZWFrcG9pbnRzIGl0IGlzIG11Y2ggbW9yZSBlYXNpZXJcbiAqIHRvIHNpbXVsYXRlIGZyb250LWVuZCBzaWRlYmFycyBpbiB0aGUgYmFjay1lbmQgZWRpdG9yLlxuICpcbiAqIENsYXNzZXMgYW5kIGJyZWFrcG9pbnRzIDpcbiAqICAgICAuc21hbGwtY29udGFpbmVyIGNsYXNzIGFkZGVkIGJleW9uZCAxcHggd2lkdGhcbiAqICAgICAubWVkaXVtLWNvbnRhaW5lciBjbGFzcyBhZGRlZCBiZXlvbmQgOTkycHggd2lkdGhcbiAqICAgICAubGFyZ2UtY29udGFpbmVyIGNsYXNzIGFkZGVkIGJleW9uZCAxMTcwcHggd2lkdGhcbiAqXG4gKiBAc2VlIGh0dHBzOi8vcGhpbGlwd2FsdG9uLmNvbS9hcnRpY2xlcy9yZXNwb25zaXZlLWNvbXBvbmVudHMtYS1zb2x1dGlvbi10by10aGUtY29udGFpbmVyLXF1ZXJpZXMtcHJvYmxlbS9cbiAqL1xuXG4vKlxud2luZG93LmFkZEV2ZW50TGlzdGVuZXIoICdET01Db250ZW50TG9hZGVkJywgZSA9PiB7XG5cblx0dmFyIHJvO1xuXG5cdGlmICggJ1Jlc2l6ZU9ic2VydmVyJyBpbiBzZWxmICkge1xuXHRcdHJvID0gbmV3IFJlc2l6ZU9ic2VydmVyKCBmdW5jdGlvbiggZW50cmllcyApIHtcblx0XHRcdGVudHJpZXMuZm9yRWFjaCggZnVuY3Rpb24oIGVudHJ5ICkge1xuXG5cdFx0XHRcdC8vIFRob3NlIGJyZWFrcG9pbnRzIGFyZSB0aGVtZSBkZXBlbmRlbnRcblx0XHRcdFx0dmFyIGJyZWFrcG9pbnRzID0ge1xuXHRcdFx0XHRcdCdzbWFsbC1jb250YWluZXInOiAxLFxuXHRcdFx0XHRcdCdtZWRpdW0tY29udGFpbmVyJzogOTkyLFxuXHRcdFx0XHRcdCdsYXJnZS1jb250YWluZXInOiAxMTcwXG5cdFx0XHRcdH07XG5cdFx0XHRcdE9iamVjdC5rZXlzKCBicmVha3BvaW50cyApLmZvckVhY2goIGZ1bmN0aW9uKCBicmVha3BvaW50ICkge1xuXHRcdFx0XHRcdHZhciBtaW5XaWR0aCA9IGJyZWFrcG9pbnRzW2JyZWFrcG9pbnRdO1xuXHRcdFx0XHRcdGVudHJ5LmNvbnRlbnRSZWN0LndpZHRoID49IG1pbldpZHRoID9cblx0XHRcdFx0XHRcdGVudHJ5LnRhcmdldC5jbGFzc0xpc3QuYWRkKCBicmVha3BvaW50ICkgOlxuXHRcdFx0XHRcdFx0ZW50cnkudGFyZ2V0LmNsYXNzTGlzdC5yZW1vdmUoIGJyZWFrcG9pbnQgKTtcblx0XHRcdFx0fSk7XG5cdFx0XHR9KTtcblx0XHR9KTtcblx0XHRyby5vYnNlcnZlKCBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCAnLmVkaXRvci1zdHlsZXMtd3JhcHBlcicgKSApO1xuXHR9XG59KTtcbiovXG4iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/editor-preview.js\n");

/***/ }),

/***/ 3:
/*!**********************************************!*\
  !*** multi ./resources/js/editor-preview.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/teva/Local Sites/devsite/app/public/wp-content/themes/themesetup/resources/js/editor-preview.js */"./resources/js/editor-preview.js");


/***/ })

/******/ });
const { series, src, dest, watch } = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const autoprefixer = require("gulp-autoprefixer");
const cleanCSS = require("gulp-clean-css");
const concat = require("gulp-concat");
// const imagemin = require("gulp-imagemin");
const babel = require("gulp-babel");
var uglify = require("gulp-uglify");

function compilePagesSCSS(cb) {
	cb();
	return src("src/scss/*.scss")
		.pipe(sass().on("error", sass.logError))
		.pipe(autoprefixer())
		.pipe(cleanCSS())
		.pipe(dest("dist/css/"));
} //compilePagesSCSS

function compileBlocksSCSS(cb) {
	cb();
	return src("blocks/**/*.scss")
		.pipe(sass())
		.pipe(autoprefixer())
		.pipe(cleanCSS())
		.pipe(dest("dist/css/blocks/"));
} //compileBlocksSCSS

function compileJS(cb) {
	return (
		src("node_modules/jquery/dist/jquery.min.js")
			//.pipe(babel())
			//.pipe(concat("jquery.js"))
			.pipe(dest("dist/js/"))
	);
} //runJS

// function compileBootstrapJS(cb) {
// 	return (
// 		src("node_modules/bootstrap/dist/js/bootstrap.min.js")
// 			//.pipe(babel())
// 			//.pipe(concat("jquery.js"))
// 			.pipe(dest("assets/js/"))
// 	);
// } //runJS

// /* Swiper slider */
// function compileSwiperCss(cb) {
//   cb();
//   return src("node_modules/swiper/swiper.scss")
//     .pipe(sass().on("error", sass.logError))
//     .pipe(autoprefixer())
//     .pipe(cleanCSS())
//     .pipe(dest("dist/css/swiper/"));
// } //runSwiperCss
// function compileSwiperJs(cb) {
//   cb();
//   return src("node_modules/swiper/swiper-bundle.js")
//     .pipe(babel())
//     .pipe(uglify())
//     .pipe(dest("dist/js/swiper/"));
// } //runSwiperJs

exports.default = series(
	compilePagesSCSS,
	compileBlocksSCSS,
	compileJS,
	// compileBootstrapJS,
	// compileSwiperCss,
	// compileSwiperJs
);
exports.watcher = () => {
	watch(["src/scss/*.scss"], compilePagesSCSS);
	watch("blocks/**/*.scss", compileBlocksSCSS);
};
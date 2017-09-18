const browserify = require("browserify");
const buffer = require("vinyl-buffer");
const gulp = require("gulp");
const gutil = require("gulp-util");
const rename = require("gulp-rename");
const runSequence = require("run-sequence");
const sass = require("gulp-sass");
const source = require("vinyl-source-stream");
const uglify = require("gulp-uglify");
const webpack = require("webpack-stream");

// CEHD
gulp.task("default", function() {
    runSequence(
        "cehd-sass",
        "cehd-inline-sass",
        "cehd-forms-sass",
        "cehd-svg-sass",
        "cehd-javascript"
    );
});

gulp.task("cehd-sass", function() {
    return gulp.src("./public/assets/src/sass/cehd.scss")
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(gulp.dest("./public/assets/dist/css/minified/"));
});

gulp.task("cehd-inline-sass", function() {
    return gulp.src("./public/assets/src/sass/inline/inline.scss")
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(gulp.dest("./public/assets/dist/css/minified/"));
});

gulp.task("cehd-forms-sass", function() {
    return gulp.src("./public/assets/src/sass/forms/forms.scss")
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(gulp.dest("./public/assets/dist/css/minified/"));
});

gulp.task("cehd-svg-sass", function() {
    return gulp.src("./public/assets/src/sass/svg/svg.scss")
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(gulp.dest("./public/assets/dist/css/minified/"));
});

// JavaScript is now compiled using webpack
gulp.task("cehd-javascript", function () {
    return webpack(require("./webpack.config.js"));
});

// CEHD watch task
gulp.task("watch", function() {
    gulp.watch(
        "./public/assets/src/sass/**/*.scss",
        [
            "cehd-sass",
            "cehd-inline-sass",
            "cehd-forms-sass",
            "cehd-svg-sass",
        ]
    );

    gulp.watch(
        "./public/assets/src/js/**/*.js",
        ["cehd-javascript"]
    );
});

// Ed Psych
gulp.task("edpsych", function() {
    runSequence(
        "edpsych-javascript",
        "edpsych-sass"
    );
});

gulp.task("edpsych-javascript", function () {
  // set up the browserify instance on a task basis
  var b = browserify({
    entries: [
        "./public/edpsych/assets/src/js/edpsych.js",
        "./public/edpsych/assets/src/js/http_client.js",
        "./public/edpsych/assets/src/js/renderer.js",
        "./public/edpsych/assets/src/js/helpers.js",
        "./public/edpsych/assets/src/js/search_client.js",
        "./public/assets/src/js/ga.js",
    ],
    debug: true
  });

  return b.bundle()
    .pipe(source("./public/edpsych/assets/src/js/edpsych.js"))
    .pipe(buffer())
    .pipe(rename("js/edpsych.min.js"))
    .pipe(gulp.dest("./public/edpsych/assets/dist"));
});

gulp.task("edpsych-sass", function() {
    return gulp.src("./public/edpsych/assets/src/sass/edpsych.scss")
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(gulp.dest("./public/edpsych/assets/dist/css/minified/"));
});

// Ed Psych watch task
gulp.task("watch-edpsych", function() {
    gulp.watch(
        "./public/edpsych/assets/src/sass/**/*.scss",
        [
            "edpsych-sass",
        ]
    );

    gulp.watch(
        "./public/edpsych/assets/src/js/**/*.js",
        ["javascript"]
    );
});

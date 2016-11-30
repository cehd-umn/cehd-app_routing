var browserify = require("browserify");
var buffer = require("vinyl-buffer");
var gulp = require("gulp");
var gutil = require("gulp-util");
var rename = require("gulp-rename");
var runSequence = require("run-sequence");
var sass = require("gulp-sass");
var source = require("vinyl-source-stream");
var sourcemaps = require("gulp-sourcemaps");
var uglify = require("gulp-uglify");

gulp.task("default", function() {
    runSequence(
        "javascript",
        "sass-minified-task",
        "sass-expanded-task",
        "copy-views"
    );
});

gulp.task("javascript", function () {
  // set up the browserify instance on a task basis
  var b = browserify({
    entries: [
        "./public/edpsych/assets/src/js/edpsych.js",
        "./public/edpsych/assets/src/js/http_client.js",
        "./public/edpsych/assets/src/js/renderer.js",
    ],
    debug: true
  });

  return b.bundle()
    .pipe(source("./public/edpsych/assets/src/js/edpsych.js"))
    .pipe(buffer())
    .pipe(sourcemaps.init({loadMaps: true}))
        // Add transformation tasks to the pipeline here.
        .pipe(uglify())
        .on("error", gutil.log)
    .pipe(rename("js/edpsych.min.js"))
    .pipe(sourcemaps.write("./"))
    .pipe(gulp.dest("./public/edpsych/assets/dist"));
});

gulp.task("copy-views", function() {
    return gulp.src("./public/edpsych/assets/src/views/**/*.html")
    .pipe(gulp.dest("./public/edpsych/assets/dist/views"));
});

gulp.task("sass-minified-task", function() {
    return gulp.src("./public/edpsych/assets/src/sass/edpsych.scss")
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(sourcemaps.write("./maps"))
    .pipe(gulp.dest("./public/edpsych/assets/dist/css/minified/"));
});

gulp.task("sass-expanded-task", function() {
    return gulp.src("./public/edpsych/assets/src/sass/edpsych.scss")
    .pipe(sass({outputStyle: "expanded"}).on("error", sass.logError))
    .pipe(gulp.dest("./public/edpsych/assets/dist/css/unminified/"));
});

// Watch task
gulp.task("watch", function() {
    gulp.watch(
        "./public/edpsych/assets/src/sass/**/*.scss",
        [
            "sass-minified-task",
            "sass-expanded-task"
        ]
    );

    gulp.watch(
        "./public/edpsych/assets/src/js/**/*.js",
        ["javascript"]
    );

    gulp.watch(
        "./public/edpsych/assets/src/views/**/*.html",
        ["copy-views"]
    );
});

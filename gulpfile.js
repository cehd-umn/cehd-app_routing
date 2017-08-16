const browserify = require("browserify");
const babel = require("gulp-babel");
const buffer = require("vinyl-buffer");
// const concat = require("gulp-concat");
const gulp = require("gulp");
const gutil = require("gulp-util");
const rename = require("gulp-rename");
const runSequence = require("run-sequence");
const sass = require("gulp-sass");
const source = require("vinyl-source-stream");
const sourcemaps = require("gulp-sourcemaps");
const uglify = require("gulp-uglify");

// CEHD
gulp.task("default", function() {
    runSequence(
        "cehd-sass-task",
        "cehd-inline-sass-task",
        "cehd-forms-sass-task",
        "cehd-svg-sass-task",
        "cehd-javascript"
    );
});

gulp.task("cehd-sass-task", function() {
    return gulp.src("./public/assets/src/sass/cehd.scss")
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(sourcemaps.write("./maps"))
    .pipe(gulp.dest("./public/assets/dist/css/minified/"));
});

gulp.task("cehd-inline-sass-task", function() {
    return gulp.src("./public/assets/src/sass/inline/inline.scss")
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(sourcemaps.write("assets/maps"))
    .pipe(gulp.dest("./public/assets/dist/css/minified/"));
});

gulp.task("cehd-forms-sass-task", function() {
    return gulp.src("./public/assets/src/sass/forms/forms.scss")
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(sourcemaps.write("assets/maps"))
    .pipe(gulp.dest("./public/assets/dist/css/minified/"));
});

gulp.task("cehd-svg-sass-task", function() {
    return gulp.src("./public/assets/src/sass/svg/svg.scss")
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(sourcemaps.write("assets/maps"))
    .pipe(gulp.dest("./public/assets/dist/css/minified/"));
});

gulp.task("cehd-javascript", function () {
    /**
     * Babelify es6 first then bundle it?
     */
    const entries = {
        entries: [
            "./public/assets/src/js/cehd.js",
            "./public/assets/src/js/ga.js",
            "./public/assets/src/js/helpers.js",
            "./public/assets/src/js/http_client.js",
            "./public/assets/src/js/renderer.js",
            "./public/assets/src/js/search_client.js",
        ]
    };
    const bundler = browserify(entries);

    return bundler.bundle()
        .pipe(source("cehd.min.js"))
        .pipe(buffer())
        .pipe(babel({
            presets: ["env"]
        }))
        .pipe(uglify().on("error", function(err) {
            gutil.log(gutil.colors.red("[Error]"), err.toString());
            this.emit("end");
        }))
        .pipe(gulp.dest("./public/assets/dist/js/"));
});

// CEHD watch task
gulp.task("watch", function() {
    gulp.watch(
        "./public/assets/src/sass/**/*.scss",
        [
            "cehd-sass-task",
            "cehd-inline-sass-task",
            "cehd-forms-sass-task",
            "cehd-svg-sass-task",
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
        "edpsych-sass-minified-task"
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
    .pipe(sourcemaps.init({loadMaps: true}))
        // Add transformation tasks to the pipeline here.
        .pipe(uglify())
        .on("error", gutil.log)
    .pipe(rename("js/edpsych.min.js"))
    .pipe(sourcemaps.write("./"))
    .pipe(gulp.dest("./public/edpsych/assets/dist"));
});

gulp.task("edpsych-sass-minified-task", function() {
    return gulp.src("./public/edpsych/assets/src/sass/edpsych.scss")
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
    .pipe(sourcemaps.write("./maps"))
    .pipe(gulp.dest("./public/edpsych/assets/dist/css/minified/"));
});

// Ed Psych watch task
gulp.task("watch-edpsych", function() {
    gulp.watch(
        "./public/edpsych/assets/src/sass/**/*.scss",
        [
            "edpsych-sass-minified-task",
        ]
    );

    gulp.watch(
        "./public/edpsych/assets/src/js/**/*.js",
        ["javascript"]
    );
});

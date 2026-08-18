'use strict';

// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var gulpIf = require('gulp-if');
var minifyCss = require('gulp-minify-css');
var uglify = require('gulp-uglify-es').default;
var concat = require('gulp-concat');

// Variables & options
var sassOptions = {
  errLogToConsole: true,
  outputStyle: 'expanded'
};

gulp.task('js-page', function() {
  return gulp.src('./resources/assets/js/*.js')
    .pipe(sourcemaps.init())
    .pipe(sourcemaps.write({includeContent: false}))
    .pipe(sourcemaps.init({loadMaps: true}))
    .pipe(sourcemaps.write('./'))
    .pipe(gulpIf('*.js', uglify()))
    .pipe(gulp.dest('./public/js/'))
});

gulp.task('js-main', function() {
  return gulp.src(['./resources/assets/js/plugins/jquery.js','./resources/assets/js/plugins/bootstrap.js','./resources/assets/js/plugins/summernote.js','./resources/assets/js/plugins/sweetalert.js','./resources/assets/js/app.js'])
    .pipe(concat('app.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./public/js/'))
});

gulp.task('sass', function() {
    gulp.src('./resources/assets/sass/**/*')
        .pipe(sass(sassOptions).on('error', sass.logError))
        .pipe(sourcemaps.init())
        .pipe(sourcemaps.write({includeContent: false}))
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(autoprefixer())
        .pipe(sourcemaps.write('./'))
        .pipe(gulpIf('*.css', minifyCss()))
        .pipe(gulp.dest('./public/css/'));
});

gulp.task('automate', function() {
    gulp.watch('./resources/assets/sass/**/*', ['sass']);
    gulp.watch('./resources/assets/js/*.js', ['js-page']);
    // gulp.watch('./resources/assets/js/app.js', ['js-main']);
});

gulp.task('default', ['sass','js-page']);
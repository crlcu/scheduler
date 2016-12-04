var gulp = require('gulp');

// include plug-ins
var browserify = require('browserify');
var buffer = require('vinyl-buffer');
var concat = require('gulp-concat');
var less = require('gulp-less');
var minifyCSS = require('gulp-minify-css');
var rename = require('gulp-rename');
var source = require('vinyl-source-stream');
var stripDebug = require('gulp-strip-debug');
var uglify = require('gulp-uglify');

// Compile less
gulp.task('compile-less', function() {
    gulp.src(['public/css/less/style.less'])
        .pipe(less())
        .pipe(minifyCSS())
        .pipe(rename('style.min.css'))
        .pipe(gulp.dest('public/css/'));
});

// JS concat, strip debugging and minify
gulp.task('scripts', function() {
    gulp.src(['public/js/app.js'])
        .pipe(concat('app.min.js'))
        .pipe(stripDebug())
        .pipe(uglify())
        .pipe(gulp.dest('public/js/'));
});

gulp.task('browserify', function() {
    browserify('public/jsx/tasks/search.js')
        .transform('babelify', {presets: ['es2015', 'react']})
        .bundle()
        .pipe(source('public/js/tasks/search.js'))
        .pipe(buffer())
        .pipe(concat('search.min.js'))
        //.pipe(stripDebug())
        .pipe(uglify())
        .pipe(gulp.dest('public/js/tasks/'));
});

gulp.task('styles', ['compile-less']); 
gulp.task('default', ['styles', 'scripts', 'browserify']);

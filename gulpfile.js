var gulp = require('gulp'),
  sass = require('gulp-ruby-sass'),
  autoprefixer = require('gulp-autoprefixer'),
  svgmin = require('gulp-svgmin'),
  jshint = require('gulp-jshint'),
  uglify = require('gulp-uglify'),
  imagemin = require('gulp-imagemin'),
  rename = require('gulp-rename'),
  clean = require('gulp-clean'),
  concat = require('gulp-concat'),
  notify = require('gulp-notify'),
  cache = require('gulp-cache'),
  livereload = require('gulp-livereload'),
  lr = require('tiny-lr'),
  server = lr();

gulp.task('styles', function() {
  return gulp.src('scss/style.scss')
    .pipe(sass({compass: true}))
    .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(gulp.dest('./'))
    .pipe(livereload(server))
    .pipe(notify({ message: 'Styles task complete' }));
});

gulp.task('scripts', function() {
  return gulp.src([
      'bower_components/foundation/js/foundation/foundation.js',
      'bower_components/foundation/js/foundation/foundation.abide.js',
      'bower_components/foundation/js/foundation/foundation.accordion.js',
      'bower_components/foundation/js/foundation/foundation.clearing.js',
      'bower_components/foundation/js/foundation/foundation.dropdown.js',
      'bower_components/foundation/js/foundation/foundation.equalizer.js',
      'bower_components/foundation/js/foundation/foundation.interchange.js',
      'bower_components/foundation/js/foundation/foundation.joyride.js',
      'bower_components/foundation/js/foundation/foundation.magellan.js',
      'bower_components/foundation/js/foundation/foundation.offcanvas.js',
      'bower_components/foundation/js/foundation/foundation.orbit.js',
      'bower_components/foundation/js/foundation/foundation.reveal.js',
      'bower_components/foundation/js/foundation/foundation.tab.js',
      'bower_components/foundation/js/foundation/foundation.tooltip.js',
      'bower_components/foundation/js/foundation/foundation.topbar.js',
      'js/app.js'])
    .pipe(concat('fv.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('js'))
    .pipe(livereload(server))
    .pipe(notify({ message: 'Scripts task complete' }));
});

gulp.task('images', function() {
  /*gulp.src('images/*.svg')
    .pipe(svgmin())
    .pipe(gulp.dest('images'));*/

  return gulp.src('images/**/*.{png,jpg,jpeg}')
    .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
    .pipe(gulp.dest('images'))
    .pipe(livereload(server))
    .pipe(notify({ message: 'Images task complete' }));
});

gulp.task('default', function() {
  gulp.run('styles', 'scripts', 'images');
});
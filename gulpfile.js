const gulp = require('gulp');
const watch = require('gulp-watch');
const imagemin = require('gulp-imagemin');
const sass = require('gulp-sass');
const clean = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');
const minify = require('gulp-minify-css');
const concat = require('gulp-concat');
const babel = require('gulp-babel');

gulp.task('default', () => {
  gulp.src('./assets/css/*')
      .pipe(sourcemaps.init())
      .pipe(sass())
      .pipe(clean())
      .pipe(sourcemaps.write())
      .pipe(minify())
      .pipe(gulp.dest('./database-backups/assets/css/'));

  gulp.src(['./assets/js/*.js', '!./assets/js/vendor/**/*.js'])
      .pipe(sourcemaps.init())
      .pipe(babel({
        presets: ['env'],
      }))
      .pipe(concat('core.js'))
      .pipe(uglify())
      .pipe(sourcemaps.write())
      .pipe(gulp.dest('./database-backups/assets/js'));

  gulp.src('./assets/image/**/*')
      .pipe(imagemin())
      .pipe(gulp.dest('./database-backups/assets/image'));
});

gulp.task('watch',function(){
    gulp.watch(['./assets/js/*.js', '!./assets/js/vendor/**/*.js'], ['default']);
});
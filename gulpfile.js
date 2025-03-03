const { src, dest, series, watch, task } = require('gulp');
const dartSass = require('sass');
const gulpDartSass = require('gulp-dart-sass');
const concat = require('gulp-concat');
const cleanCSS = require('gulp-clean-css');

/* Combined CSS job */
const cssCombined = () => {
  return src(['assets/css/*.scss']) // Using 'src' directly from destructured gulp
    .pipe(gulpDartSass(dartSass).on('error', gulpDartSass.logError))
    .pipe(concat('metabox.min.css'))
    .pipe(cleanCSS())
    .pipe(dest('assets/css'));
};

/* Watch Task */
const watchFiles = () => {
  watch('assets/css/**/*.scss', cssCombined); // Watches all SCSS files in the directory and subdirectories
};

// Default task: Run cssCombined first, then watch for changes
task('default', series(cssCombined, watchFiles));

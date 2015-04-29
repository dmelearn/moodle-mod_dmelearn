// Setup gulp packages
var gulp = require('gulp');
var rename = require('gulp-rename');

// Directories
var targetCSSDir = 'css';
var targetFontDir = 'font';
var targetFontsDir = 'fonts';

// All Tasks

// - move files from bower_components to correct location
gulp.task('fontawesome-fonts', function () {
    return gulp.src('bower_components/fontawesome/fonts/*.*')
            .pipe(gulp.dest(targetFontsDir));
});

gulp.task('fontawesome-3-fonts', function () {
    return gulp.src('bower_components/fontawesome-3/font/*.*')
            .pipe(gulp.dest(targetFontDir));
});

gulp.task('fontawesome-css', function () {
    return gulp.src('bower_components/fontawesome/css/*.min.css')
            .pipe(gulp.dest(targetCSSDir));
});

gulp.task('fontawesome-3-css', function () {
    return gulp.src('bower_components/fontawesome-3/css/*.min.css')
            .pipe(rename(function (path) {
                path.basename = path.basename.replace('.min', '-3.min');
            }))
            .pipe(gulp.dest(targetCSSDir));
});

// Default: (this runs when you just run gulp on the command line)
gulp.task('default', ['fontawesome-fonts', 'fontawesome-3-fonts',
    'fontawesome-css', 'fontawesome-3-css'
]);
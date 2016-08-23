// Setup gulp packages.
var gulp = require('gulp');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var del = require('del');

// Directories.
var targetCSSDir = 'css';
var targetJSDir = 'js';
var targetFontDir = 'font';
var targetFontsDir = 'fonts';
var targetTemplateJSDir = 'template/views/js';

// All Tasks.

// Move files from bower_components to correct location.
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

gulp.task('bootstrap3-css', function () {
    return gulp.src('bower_components/bootstrap/dist/css/bootstrap.min.css')
        .pipe(gulp.dest(targetCSSDir));
});

gulp.task('fontawesome-3-css', function () {
    return gulp.src('bower_components/fontawesome-3/css/*.min.css')
        .pipe(rename(function (path) {
        path.basename = path.basename.replace('.min', '-3.min');
        }))
        .pipe(gulp.dest(targetCSSDir));
});

gulp.task('bootstrap3-js', function () {
    return gulp.src('bower_components/bootstrap/dist/js/bootstrap.min.js')
        .pipe(gulp.dest(targetJSDir));
});

gulp.task('respond-js', function () {
    return gulp.src('bower_components/respond/dest/respond.min.js')
        .pipe(gulp.dest(targetJSDir+'/respond'));
});

gulp.task('html5shiv-js', function () {
    return gulp.src('bower_components/html5shiv/dist/html5shiv.min.js')
        .pipe(gulp.dest(targetJSDir+'/html5shiv'));
});

// Compress JS.
gulp.task('compress-js', function () {
    return gulp.src(['js/*.js', '!js/*.min.js'])
        .pipe(gulp.dest(targetJSDir))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest(targetJSDir))
});

// Template JS.
gulp.task('compress-template-js', function () {
    return gulp.src([targetTemplateJSDir+'/*.js', targetTemplateJSDir+'!js/*.min.js'])
        .pipe(gulp.dest(targetTemplateJSDir))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest(targetTemplateJSDir))
});

// Cleanup the Composer vendor folder to prepare for packaging up plugin.
gulp.task('clean:vendor', function () {
    return del([
        // Remove any git files.
        'vendor/**/.git',
        'vendor/**/.gitignore',
        // Remove testing files.
        'vendor/**/.travis.yml',
        'vendor/**/phpunit.xml',
        'vendor/**/phpunit.xml.dist',
        // Remove IDE files.
        'vendor/**/.editorconfig',
        // Remove documentation and test files not needed.
        'vendor/guzzlehttp/guzzle/docs',
        'vendor/guzzlehttp/guzzle/tests',
        'vendor/guzzlehttp/ringphp/docs',
        'vendor/guzzlehttp/ringphp/tests',
        'vendor/guzzlehttp/streams/tests',
        'vendor/react/promise/tests'
    ]);
});
// Remove bower_components folder.
gulp.task('del:bower', function () {
    return del([
        'bower_components/'
    ]);
});

// Remove node_modules folder.
gulp.task('del:nm', function () {
    return del([
        'node_modules/'
    ]);
});

// Default: (This task runs when you run 'gulp' on the command line).
gulp.task('default', ['fontawesome-fonts', 'fontawesome-3-fonts',
    'fontawesome-css', 'fontawesome-3-css', 'bootstrap3-css', 'bootstrap3-js',
    'respond-js', 'html5shiv-js', 'compress-js'
]);

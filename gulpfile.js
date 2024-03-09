const gulp        = require('gulp');
const sass        = require('gulp-dart-sass');
const livereload  = require('gulp-livereload');
const minify      = require('gulp-minify');
const jshint      = require('gulp-jshint');
const browserSync = require('browser-sync');

const paths = {
    scripts: './frontend/dist/scripts/**/*.js',
    styles: './frontend/scss/**/*.scss',
}

// Dev tasks
gulp.task('dev-styles', () => {
    return gulp.src(paths.styles)
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./frontend/dist/styles'));
        //.pipe(livereload());
});

gulp.task('dev-scripts', () => {
    return gulp.src(paths.scripts)
        .pipe(jshint())
        .pipe(jshint.reporter('jshint-stylish'))
        .pipe(gulp.dest('./frontend/dist/scripts'));
        //.pipe(livereload());
});

gulp.task('watch', () => {
    livereload.listen();
    gulp.watch(paths.styles, gulp.series('dev-styles'));
    gulp.watch(paths.scripts, gulp.series('dev-scripts'));
});

// Production tasks
gulp.task('min-styles', () => {
    return gulp.src(paths.styles)
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(gulp.dest('./frontend/dist/styles'));
});

gulp.task('min-scripts', () => {
    return gulp.src(paths.scripts)
        .pipe(minify({
            noSource: true,
            ext: {
                src: '.js',
                min: '.js'
            }
        }))
        .pipe(gulp.dest('./frontend/dist/scripts'))
});

// Default task - run `gulp` from cli
gulp.task('default', gulp.series('dev-styles', 'dev-scripts'));
// Dev task - run `gulp dev` from cli
gulp.task('dev', gulp.series('default', 'watch'));
// Prodcution task - run `gulp prod` from cli
gulp.task('prod', gulp.series('min-styles', 'min-scripts'));
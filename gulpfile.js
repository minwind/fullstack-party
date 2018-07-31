var gulp = require('gulp'),
    concat = require('gulp-concat'),
    sass = require('gulp-sass'),
    sassGlob = require('gulp-sass-glob'),
    prefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    plumber = require('gulp-plumber');
    rigger = require('gulp-rigger');

var config = {
    assetsDir: 'src/AppBundle/Resources/assets/',
    sassPattern: 'sass/main.scss',
    jsPattern: 'js/main.js',
    assetsOutputDir: 'web/assets',
};

gulp.task('styles', function() {
    gulp.src(config.assetsDir + config.sassPattern)
        .pipe(plumber())
        .pipe(sassGlob())
        .pipe(sass({}))
        .pipe(sass({outputStyle:'compressed'}))
        .pipe(prefixer('last 2 versions'))
        .pipe(concat(config.assetsOutputDir +'/css/main.min.css'))
        .pipe(gulp.dest(''));
});

gulp.task('scripts', function() {
    gulp.src(config.assetsDir + config.jsPattern)
        .pipe(plumber())
        .pipe(rigger())
        .pipe(concat(config.assetsOutputDir +'/js/main.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(''));
});

gulp.task('watch', function(){
    gulp.watch('src/AppBundle/Resources/assets/sass/**/*.scss', ['styles']);
    gulp.watch('src/AppBundle/Resources/assets/js/**/*.js', ['scripts']);
});

gulp.task('default', [
    'styles',
    'scripts',
    'watch'
]);
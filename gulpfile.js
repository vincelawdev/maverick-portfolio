'use strict';

//REQUIRED MODULES
var gulp = require('gulp'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    changed = require('gulp-changed'),
    less = require('gulp-less'),
    minifyCSS = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    pngquant = require('imagemin-pngquant');

//LESS COMPILER WITHOUT CSS MINIFY
gulp.task('less', function()
{
    return gulp.src('src/less/main.less')
        .pipe(less())
        .pipe(gulp.dest('src/css'));
});

//LESS COMPILER WITH CSS MINIFY
gulp.task('less-minify', function()
{
    return gulp.src('src/less/main.less')
        .pipe(less())
        .pipe(minifyCSS())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('build/css'));
});

//UGLIFY MODULE JS
gulp.task('js-uglify', function()
{
    return gulp.src('src/js/modules/*.js')
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('build/js/modules'));
});

//COMPRESS IMAGES
gulp.task('images', function()
{
    var images_build = 'build/images';

    return gulp.src('src/images/*')
        .pipe(changed(images_build))
        .pipe(imagemin(
        {
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest(images_build));
});

//BUILD TASK TO RUN TASKS ABOVE
gulp.task('build', ['less-minify', 'js-uglify', 'images']);

//WATCH TASK TO RUN TASK WHEN FILES ARE CHANGED
gulp.task('watch', ['less-minify', 'js-uglify', 'images'], function()
{
    //WATCH FOR LESS CHANGES
    gulp.watch('src/less/*.less', ['less-minify']);

    //WATCH FOR JS CHANGES
    gulp.watch('src/js/modules/*.js', ['js-uglify']);

    //WATCH FOR IMAGE CHANGES
    gulp.watch('src/images/*', ['images']);
});
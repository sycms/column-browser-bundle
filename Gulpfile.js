var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var livereload = require('gulp-livereload');
var paths = {
    js: [
        './bower_components/jquery/dist/jquery.min.js',
        './bower_components/jquery-ui/jquery-ui.min.js',
        './bower_components/semantic/dist/semantic.min.js',
        './src/Resources/private/js/column_browser.js'
    ],
    css: [
        './bower_components/semantic/dist/semantic.min.css',
        './src/Resources/private/**/*.css'
    ]
};

gulp.task('build', function () {
    gulp.src(paths.css)
        .pipe(concat('main.css'))
        .pipe(gulp.dest('./example/web/assets'));

    gulp.src('./bower_components/semantic/dist/themes/**/*')
        .pipe(gulp.dest('./example/web/assets/themes'));

    gulp.src(paths.js)
        .pipe(concat('main.js'))
        .pipe(gulp.dest('./example/web/assets'));
});


gulp.task('watch', function() {
    livereload.listen();

    gulp.watch(paths.js, ['build']);
    gulp.watch(paths.css, ['build']);
});

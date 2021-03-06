'use strict';

var gulp = require('gulp'),
    watch = require('gulp-watch'),
    prefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    sass = require('gulp-sass'),
    // sourcemaps = require('gulp-sourcemaps'),
    rigger = require('gulp-rigger'),
    cssmin = require('gulp-minify-css'),
    // imagemin = require('gulp-imagemin'),
    // pngquant = require('imagemin-pngquant'),
    rimraf = require('rimraf'),
    // browser-sync not important may be delete
    browserSync = require("browser-sync"),
    reload = browserSync.reload;

var path = {
    build: { //Тут мы укажем куда складывать готовые после сборки файлы
        // html: 'build/',
        // js: 'build/js/',
        js: 'public/assets/js/',
        // css: 'build/css/'
        css: 'public/assets/css/',
        // img: 'build/img/',
        fonts: 'public/assets/fonts/'
    },
    src: { //Пути откуда брать исходники
        // html: 'src/*.html', //Синтаксис src/*.html говорит gulp что мы хотим взять все файлы с расширением .html
        js: 'src/js/main.js',//В стилях и скриптах нам понадобятся только main файлы
        style: 'src/style/main.scss',
        // img: 'src/img/**/*.*', //Синтаксис img/**/*.* означает - взять все файлы всех расширений из папки и из вложенных каталогов
        awesomeFonts: 'bower_components/font-awesome/fonts/**/*.*'
    },
    watch: { //Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        // html: 'src/**/*.html',
        js: 'src/js/**/*.js',
        style: 'src/style/**/*.scss',
        // img: 'src/img/**/*.*',
        awesomeFonts: 'bower_components/font-awesome/fonts/**/*.*'
    },
    clean: './build'
};

var config = {
    server: {
        baseDir: "./build"
    },
    tunnel: true,
    host: 'localhost',
    port: 9000,
    logPrefix: "Frontend_Devil"
};

gulp.task('js:build', function () {
    gulp.src(path.src.js) //Найдем наш main файл
        .pipe(rigger()) //Прогоним через rigger
        // .pipe(sourcemaps.init()) //Инициализируем sourcemap
        .pipe(uglify()) //Сожмем наш js
        // .pipe(sourcemaps.write()) //Пропишем карты
        .pipe(gulp.dest(path.build.js)) //Выплюнем готовый файл в build
        .pipe(reload({stream: true})); //И перезагрузим сервер
});

gulp.task('style:build', function () {
    gulp.src(path.src.style) //Выберем наш main.scss
        // .pipe(sourcemaps.init()) //То же самое что и с js
        .pipe(sass()) //Скомпилируем
        .pipe(prefixer()) //Добавим вендорные префиксы
        .pipe(cssmin()) //Сожмем
        // .pipe(sourcemaps.write())
        .pipe(gulp.dest(path.build.css)) //И в build
        .pipe(reload({stream: true}));
});

gulp.task('awesomeFonts:build', function() {
    gulp.src(path.src.awesomeFonts)
        .pipe(gulp.dest(path.build.fonts))
});

gulp.task('build', [
    // 'html:build',
    'js:build',
    'style:build',
    'awesomeFonts:build',
    // 'image:build'
]);

gulp.task('watch', function(){
    // watch([path.watch.html], function(event, cb) {
    //     gulp.start('html:build');
    // });
    watch([path.watch.style], function(event, cb) {
        gulp.start('style:build');
    });
    watch([path.watch.js], function(event, cb) {
        gulp.start('js:build');
    });
    // watch([path.watch.img], function(event, cb) {
    //     gulp.start('image:build');
    // });
    watch([path.watch.awesomeFonts], function(event, cb) {
        gulp.start('awesomeFonts:build');
    });
});

// gulp.task('webserver', function () {
//     browserSync(config);
// });

gulp.task('default', ['build', 'watch']);
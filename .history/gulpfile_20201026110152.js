// プラグインの読み込み
var gulp = require('gulp');
// css圧縮
var cleancss = require('gulp-clean-css');
// sassコンパイル
var sass = require('gulp-sass');

// 画像圧縮
var imagemin = require('gulp-imagemin');
var changed = require('gulp-changed');
var imageminJpg = require('imagemin-jpeg-recompress');
var imageminPng = require('imagemin-pngquant');
// ベンダープレフィックス付与
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
// ブラウザ自動更新
var browserSync = require('browser-sync').create();
// JSをビルドするため+gulpでそれをwatchするため
var browserify = require('browserify');
var source = require('vinyl-source-stream');
// reset.cssを読み込む
var packageImporter = require('node-sass-package-importer');

gulp.task('default',['watch']);

gulp.task('sass', function(){
    gulp.src('./src/scss/*.scss')
    /* sassの引数でオプションを設定できる。そうすると実行後の
    ファイル内で改行されたりなど分かりやすくなる。*/
    .pipe(sass({
        // cssを読み込めるようにする
        importer:packageImporter({
            extensions: ['.scss', '.css']
        })
    }))
    .pipe(gulp.dest('./css'));
});

// CSS圧縮
gulp.task('clean-css', function(){
    // 圧縮したい元のファイルのパスを記述
    return gulp.src("./css/*.css")
    // 何を実行するか。pipeメソッドで連結
    .pipe(cleancss())
    // 圧縮したCSSファイルをどこに保存するか
    .pipe(gulp.dest('./css/'));
});

// browserifyでのJSビルド
gulp.task('browserify', function(){
    return browserify('./src/js/app.js')
    .bundle()
    // .pipe(source('build.js'))
    .pipe(gulp.dest('dist/'));
})

//  browserify('./js/app.js')
//     .bundle()
//     .pipe(gulp.dest('js/'));

// 第一引数は監視したいディレクトリ配下
// 第二引数に変更があった場合に実行するタスクを配列型式で指定
gulp.task('watch', function(){
    gulp.watch('src/scss' + '/app.scss', ['sass']);
    // gulp.watch('./css' + '/style.css', ['autoprefixer']);
    // gulp.watch('./css' + '/style.css', ['clean-css']);
    gulp.watch('src/js' + '/app.js', ['browserify']);
});
// ブラウザ自動更新
gulp.task('browser-sync', function(){
    browserSync.init({
        server: {
            baseDir: "./", // 対象ディレクトリ先
            index: "index.html" //indexファイル名
        }
    });
});
gulp.task('bs-reload', function(){
    browserSync.reload();
});

// 画像圧縮
// 圧縮前と圧縮後のディレクトリ
var paths = {
    srcDir : 'src',
    dstDir : 'dist'
};
// jpg,png,gif画像の圧縮タスク
gulp.task('imagemin', function() {
    // **で配下のディレクトリ全てが対象。その中にある.--というファイル。
    var srcGlob = paths.srcDir + '/**/*.+(jpg|jpeg|png|gif)';
    var dstGlob = paths.dstDir;
    gulp.src(srcGlob)
    /* changedライブラリで差分を確認して、
    変更されていないもののみ変更できる。*/
    .pipe(changed(dstGlob))
    // 圧縮する
    .pipe(imagemin([
        imageminPng(),
        imageminJpg({
            interlaced: false,
            optimizationLevel: 3,
            colors:180
        })
        // imagemin.gifsicle({interlaced: true}),
        // imagemin.jpegtran({progressive: true}),
        // imagemin.optipng({optimizationLevel: 5})
    ]
    ))
    .pipe(gulp.dest(dstGlob));
});

// ベンダープレフィックス
gulp.task('autoprefixer', function(){
    gulp.src('./css/app.css')
      .pipe(postcss([
          autoprefixer({
              browsers: ['last 2 versions', 'ie >= 10', 'Android >= 4'],
              cascade: false
          })
      ]))
      .pipe(gulp.dest('./css'));
});

var gulp = require('gulp');
var sass = require('gulp-sass');
var gulpFont = require('gulp-font');

gulp.task('fonts', function() {
	return gulp.src('assets/scss/fonts/**/*.{eot,svg,ttf,woff,woff2}')
		// .pipe(gulpFont({
			// ext: '.css',
            // fontface: 'src/assets/fonts',
            // relative: '/assets/fonts',
            // dest: 'dist/assets/fonts',
            // embed: ['woff'],
            // collate: false			
		// })
		.pipe(gulp.dest('public/css/fonts'))
});

gulp.task('css', function() {
	console.log('Recompiling css...');
	return gulp.src('assets/scss/custom.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('public/css'))
});

gulp.task('css:watch', function () {
  gulp.watch('assets/scss/*.scss', ['css']);
});

gulp.task('default', ['css']);
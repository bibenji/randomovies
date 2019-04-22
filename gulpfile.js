var gulp = require('gulp');
var sass = require('gulp-sass');
var gulpFont = require('gulp-font');
var assetManifest = require('gulp-asset-manifest-symfony');
var rev = require('gulp-rev');
var del = require('del');
var glob = require('glob');

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
	console.log('Delete old files...');
	glob('public/css/*', function(err, files) {
		del(files.filter(function(file) {
			return !!file.match(/custom-.{10}\.css/g);
		}));
	});

	console.log('Recompiling css...');
	return gulp.src('assets/scss/custom.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(rev())
		.pipe(assetManifest({
			bundleName: 'css/custom.css',
			pathPrepend: 'css/',
			manifestFile: 'public/css/asset_manifest.json',
			log: true,
		}))
		.pipe(gulp.dest('public/css'))
});

gulp.task('css:watch', function () {
  gulp.watch('assets/scss/**/*.scss', ['css']);
});

gulp.task('back:css', function() {
	console.log('Recompiling back css...');
	return gulp.src('assets/scss/back/back_custom.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('public/css'))
});

gulp.task('default', ['css', 'back:css']);
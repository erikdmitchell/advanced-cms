// get packages
var gulp = require('gulp'),
		gutil = require('gulp-util'),
		zip = require('gulp-zip'),
		runSequence = require('gulp-run-sequence'),
		del = require('del');

var project='mdw-cms';
var build='./build/';
var buildInclude 	= [
	// include common file types
	'**/*.php',
	'**/*.html',
	'**/*.css',
	'**/*.js',
	'**/*.svg',
	'**/*.ttf',
	'**/*.otf',
	'**/*.eot',
	'**/*.woff',
	'**/*.woff2',

	// include specific files and folders
	'readme.txt',

	// exclude files and folders
	'!build/*',
	'!node_modules/**/*',
	'!gulpfile.js'
];

// errors //
var onError = function (err) {
  console.log('An error occurred:', gutil.colors.magenta(err.message));
  gutil.beep();
  this.emit('end');
};

// moves all files to a folder for zipping //
gulp.task('buildFiles', function() {
	return gulp.src(buildInclude)
		.pipe(gulp.dest(build));
});

// builds a zip file from our build folder //
gulp.task('buildZip', function() {
	return 	gulp.src(build+'/**/')
		.pipe(zip(project+'.zip'))
		.pipe(gulp.dest('../'));
});

// removes build folder //
gulp.task('cleanBuild', function() {
	return del([build]);
});

// overall build task //
gulp.task('build', function(cb) {
	runSequence('buildFiles', 'buildZip', 'cleanBuild');
});

// create a default task
gulp.task('default', function() {
	gutil.log('We has gulp. Drink it down.');
});
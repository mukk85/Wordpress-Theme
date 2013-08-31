module.exports = function(grunt) {

	grunt.initConfig({

		// Watches for changes and runs tasks
		regarde : {
			sass : {
				files : ['scss/**/*.scss'],
				tasks : ['compass:dev', 'livereload']
			},
			js : {
				files : ['js/**/*.js'],
				tasks : ['jshint', 'livereload']
			},
			php : {
				files : ['**/*.php'],
				tasks : ['livereload']
			}
		},

		// JsHint your javascript
		jshint : {
			all : ['javascripts/*.js', '!javascripts/html5.js', '!javascripts/*.min.js', '!javascripts/vendor/**/*.js'],
			options : {
				browser: true,
				curly: false,
				eqeqeq: false,
				eqnull: true,
				expr: true,
				immed: true,
				newcap: true,
				noarg: true,
				smarttabs: true,
				sub: true,
				undef: false
			}
		},

		// Dev and production build for sass
		compass : {
			production : {
				options: {              // Target options
					sassDir: 'scss',
					cssDir: 'css',
					environment: 'production',
					outputStyle: 'compressed',
					require: 'zurb-foundation'
				}
			},
			dev : {
				options: {
					sassDir: 'sass',
					cssDir: 'css',
					outputStyle: 'expanded',
					require: 'zurb-foundation'
				}
			}
		},

		// minimize the javascript
		uglify: {
			production: {
				files: {
					'scripts/optimized.min.js': ['javascripts/foundation/foundation.js', 'javascripts/app.js']
				}
			}
		},

		// Image min
		imagemin : {
			production : {
				files : [
					{
						expand: true,
						cwd: 'images',
						src: '**/*.{png,jpg,jpeg}',
						dest: 'images'
					}
				]
			}
		},

		// SVG min
		svgmin: {
			production : {
				files: [
					{
						expand: true,
						cwd: 'images',
						src: '**/*.svg',
						dest: 'images'
					}
				]
			}
		}
	});

	// Default task
	grunt.registerTask('default', ['livereload-start', 'regarde']);

	// Build task
	grunt.registerTask('build', ['jshint', 'compass:production', 'imagemin:production', 'svgmin:production', 'uglify:production']);

	// Template Setup Task
	grunt.registerTask('setup', ['compass:dev', 'bower-install']);

	// Load up tasks
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-livereload');
	grunt.loadNpmTasks('grunt-regarde');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-svgmin');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-uglify');

	// Run bower install
	grunt.registerTask('bower-install', function() {
		var done = this.async();
		var bower = require('bower').commands;
		bower.install().on('end', function(data) {
			done();
		}).on('data', function(data) {
				console.log(data);
			}).on('error', function(err) {
				console.error(err);
				done();
			});
	});

};

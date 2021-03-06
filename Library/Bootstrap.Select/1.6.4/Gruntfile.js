module.exports = function( grunt )
{

    // From TWBS
    RegExp.quote = function( string )
    {
        return string.replace( /[-\\^$*+?.()|[\]{}]/g, '\\$&' );
    };

    // Project configuration.
    grunt.initConfig( {

        // Metadata.
        pkg: grunt.file.readJSON( 'package.json' ),
        banner: '/*!\n' +
        ' * Bootstrap-select v<%= pkg.version %> (<%= pkg.homepage %>)\n' +
        ' *\n' +
        ' * Copyright 2013-<%= grunt.template.today(\'yyyy\') %> bootstrap-select\n' +
        ' * Licensed under <%= pkg.license.type %> (<%= pkg.license.url %>)\n' +
        ' */\n',

        // Task configuration.

        clean: {
            css: 'dist/css',
            js: 'dist/js'
        },

        jshint: {
            options: {
                jshintrc: 'js/.jshintrc'
            },
            gruntfile: {
                options: {
                    'node': true
                },
                src: 'Gruntfile.js'
            },
            main: {
                src: 'js/*.js'
            },
            i18n: {
                src: 'js/i18n/*.js'
            }
        },

        concat: {
            options: {
                banner: '<%= banner %>',
                stripBanners: true
            },
            main: {
                src: '<%= jshint.main.src %>',
                dest: 'dist/js/<%= pkg.name %>.js'
            },
            i18n: {
                expand: true,
                src: '<%= jshint.i18n.src %>',
                dest: 'dist/'
            }
        },

        uglify: {
            options: {
                preserveComments: 'some'
            },
            main: {
                src: '<%= concat.main.dest %>',
                dest: 'dist/js/<%= pkg.name %>.min.js',
                options: {
                    sourceMap: true,
                    sourceMapName: 'dist/js/<%= pkg.name %>.js.map'
                }
            },
            i18n: {
                expand: true,
                src: 'dist/<%= jshint.i18n.src %>',
                ext: '.min.js'
            }
        },

        less: {
            options: {
                strictMath: true,
                sourceMap: true,
                outputSourceFiles: true,
                sourceMapURL: '<%= pkg.name %>.css.map',
                sourceMapFilename: '<%= less.css.dest %>.map'
            },
            css: {
                src: 'less/bootstrap-select.less',
                dest: 'dist/css/<%= pkg.name %>.css'
            }
        },

        usebanner: {
            css: {
                options: {
                    banner: '<%= banner %>'
                },
                src: '<%= less.css.dest %>'
            }
        },

        cssmin: {
            options: {
                compatibility: 'ie8',
                keepSpecialComments: '*',
                advanced: false
            },
            css: {
                src: '<%= less.css.dest %>',
                dest: 'dist/css/<%= pkg.name %>.min.css'
            }
        },

        csslint: {
            options: {
                'adjoining-classes': false,
                'box-sizing': false,
                'box-model': false,
                'compatible-vendor-prefixes': false,
                'floats': false,
                'font-sizes': false,
                'gradients': false,
                'important': false,
                'known-properties': false,
                'outline-none': false,
                'qualified-headings': false,
                'regex-selectors': false,
                'shorthand': false,
                'text-indent': false,
                'unique-headings': false,
                'universal-selector': false,
                'unqualified-attributes': false,
                'overqualified-elements': false
            },
            css: {
                src: '<%= less.css.dest %>'
            }
        },

        sed: {
            versionNumber: {
                path: [
                    'js/<%= pkg.name %>.js',
                    'bower.json',
                    'composer.json',
                    'package.json'
                ],
                pattern: (function()
                {
                    var old = grunt.option( 'old' );
                    return old ? RegExp.quote( old ) : old;
                })(),
                replacement: grunt.option( 'new' ),
                recursive: true
            }
        },

        autoprefixer: {
            options: {
                browsers: [
                    'Android 2.3',
                    'Android >= 4',
                    'Chrome >= 20',
                    'Firefox >= 24', // Firefox 24 is the latest ESR
                    'Explorer >= 8',
                    'iOS >= 6',
                    'Opera >= 12',
                    'Safari >= 6'
                ]
            },
            css: {
                options: {
                    map: true
                },
                src: '<%= less.css.dest %>'
            }
        },

        compress: {
            zip: {
                options: {
                    archive: 'bootstrap-select-<%= pkg.version %>.zip',
                    mode: 'zip'
                },
                files: [
                    {
                        expand: true,
                        cwd: 'dist/',
                        src: '**',
                        dest: 'bootstrap-select-<%= pkg.version %>/'
                    }, {
                        src: ['bower.json', 'composer.json', 'package.json'],
                        dest: 'bootstrap-select-<%= pkg.version %>/'
                    }
                ]
            }
        },

        watch: {
            gruntfile: {
                files: '<%= jshint.gruntfile.src %>',
                tasks: 'jshint:gruntfile'
            },
            js: {
                files: ['<%= jshint.main.src %>', '<%= jshint.i18n.src %>'],
                tasks: 'build-js'
            },
            less: {
                files: 'less/*.less',
                tasks: 'build-css'
            }
        }
    } );

    // These plugins provide necessary tasks.
    require( 'load-grunt-tasks' )( grunt, {
        scope: 'devDependencies'
    } );

    // Version numbering task.
    // grunt change-version-number --old=A.B.C --new=X.Y.Z
    // This can be overzealous, so its changes should always be manually reviewed!
    grunt.registerTask( 'change-version-number', 'sed' );

    // CSS distribution
    grunt.registerTask( 'build-css', ['clean:css', 'less', 'autoprefixer', 'usebanner', 'cssmin'] );

    // JS distribution
    grunt.registerTask( 'build-js', ['clean:js', 'concat', 'uglify'] );

    // Development watch
    grunt.registerTask( 'dev-watch', ['build-css', 'build-js', 'watch'] );

    // Full distribution
    grunt.registerTask( 'dist', ['build-css', 'build-js', 'compress'] );

    // Default task.
    grunt.registerTask( 'default', ['build-css', 'build-js'] );

};

module.exports = function( grunt ) {
    'use strict';

    // Load all grunt tasks matching the `grunt-*` pattern
    require( 'load-grunt-tasks' )( grunt );

    // Show elapsed time
    require( 'time-grunt' )( grunt );

    // Project configuration
    grunt.initConfig({
        package: grunt.file.readJSON( 'package.json' ),
        dirs: {
            lang: 'src/languages',
            code: 'src'
        },
        makepot: {
            framework: {
                options: {
                    cwd: '<%= dirs.code %>',
                    domainPath: 'languages',
                    exclude: [],
                    potFilename: 'dws-wp-framework-utilities.pot',
                    mainFile: 'bootstrap.php',
                    potHeaders: {
                        'report-msgid-bugs-to': 'https://github.com/deep-web-solutions/wordpress-framework-utilities/issues',
                        'project-id-version': '<%= package.title %> <%= package.version %>'
                    },
                    processPot: function( pot ) {
                        delete pot.headers['x-generator'];

                        // include the default value of the constant DWS_WP_FRAMEWORK_UTILITIES_NAME
                        pot.translations['']['DWS_WP_FRAMEWORK_UTILITIES_NAME'] = {
                            msgid: 'Deep Web Solutions: Framework Utilities',
                            comments: { reference: 'bootstrap.php:35' },
                            msgstr: [ '' ]
                        };

                        return pot;
                    },
                    type: 'wp-plugin',
                    updateTimestamp: false,
                    updatePoFiles: true
                }
            }
        },
        potomo: {
            framework: {
                options: {
                    poDel: false
                },
                files: [{
                    expand: true,
                    cwd: '<%= dirs.lang %>',
                    src: ['*.po'],
                    dest: '<%= dirs.lang %>',
                    ext: '.mo',
                    nonull: true
                }]
            }
        }
    });

    grunt.registerTask( 'default', ['makepot', 'potomo'] );
}

module.exports = function(grunt) {

   
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-preprocess');
    grunt.loadNpmTasks('grunt-symfony2');
    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks('grunt-contrib-htmlmin');

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        meta: {
            banner: '// ZONADELIVERY, v<%= pkg.version %>\n' +
                '// Copyright (c)<%= grunt.template.today("yyyy") %> ZONADELIVERY.\n'
        },
        clean: {
            target: ['target/'],
            directories: [  "target/src/*",
                            "target/bin/*",
                            "target/app/*",
                            "target/vendor/*",
                            "target/web/*",
                            ]
        },
        'sf2-console': {
            options: {
                bin: 'app/console'
            },
            cache_clear: {
                cmd: 'cache:clear'
            },
            assetic_dump:{
                cmd: "assetic:dump"
            }
            
        },
        'sf2-doctrine-schema-update': {
            dev: {
                args: { force: true }
            }
        },
        copy: {
            create_structure: {
                            files: [
                                {expand: true, src: ['app/**'], dest: 'target/'},
                                {expand: true, src: ['bin/**'], dest: 'target/'},
                                {expand: true, src: ['src/**'], dest: 'target/'},
                                {expand: true, src: ['web/**'], dest: 'target/'},
                                {expand: true, src: ['vendor/**'], dest: 'target/'}
                            ]
            }
        },
        preprocess : {
            options: {
                context : {VERSION: "<!-- ZONADELIVERY, v<%= pkg.version %> -->" }
            },
            delivery_local : {
                options : {
                    context : {
                        ENV : 'local',
                        DB: 'delivery',
                        USER: 'root',
                        PASS: 'barabani'
                    }
                },
                files: [
                    {src: 'target/app/config/parameters.yml', dest: 'target/app/config/parameters.yml'}
                    
                ]
            },
            delivery_prod : {
                options : {
                    context : { //TODO
                        ENV : 'prod',
                        DB: 'delivery',
                        USER: 'root',
                        PASS: 'barabani'
                        
                },
                files: [
                    {src: 'target/app/config/parameters.yml', dest: 'target/app/config/parameters.yml'}
                    
                ]
            },
        }
    },
    shell: {
        permissions: {
            command: 'chmod 777 -R target/app/cache target/app/logs'
        },
        phpmin:{
            command: 'php minifyphp.php'
        }
    }
    
    
    });

    grunt.registerTask("build-app", "Builds webapp", function() {
        var environment = grunt.option('env');
        if(environment == undefined) {
            environment = "local"; //Default local
        }
        
        grunt.task.run("clean:target");
        grunt.task.run("sf2-console:cache_clear");
        grunt.task.run("sf2-doctrine-schema-update");
        //minificar js 
        grunt.task.run("sf2-console:assetic_dump"); 
        grunt.task.run("copy:create_structure");
        grunt.task.run("preprocess:delivery_" + environment);
        grunt.task.run("shell:permissions");
        if (environment != "local"){
          grunt.task.run("shell:phpmin");
        }
    });

   

};

var gulp = require('gulp'),    fs = require('fs'),    wrapper = require('./wrapper'),    assemblyInfo = require('gulp-dotnet-assembly-info'),    msbuild = require('gulp-msbuild'),    nunit = require('gulp-nunit-runner');gulp.task('ci', ['test']);gulp.task('deploy', function() {    console.log('Running deploy yay!');});gulp.task('wrapper', function(cb) {    wrapper('../spec.json',         './Wrapper/Reachmail/Reachmail.Template.cs',        './Wrapper/Reachmail/Reachmail.cs');    cb();});gulp.task('assemblyInfo', ['wrapper'], function() {    return gulp        .src('**/AssemblyInfo.cs')        .pipe(assemblyInfo({            company: 'Reachmail Inc.',            product: 'Reachmail API Wrapper',            title: 'Reachmail API Wrapper',            description: 'Wrapper for the Reachmail API.',            copyright: 'Copyright (c) ' + new Date().getFullYear() + ' Reachmail Inc.',            version: process.env.BUILD_NUMBER,            fileVersion: process.env.BUILD_NUMBER        }))        .pipe(gulp.dest('.'));});gulp.task('build', ['assemblyInfo'], function() {    return gulp        .src('**/*.sln')        .pipe(msbuild({            toolsVersion: 4.0,            targets: ['Clean', 'Build'],            stdout: true,            errorOnFail: true        }));});gulp.task('test', ['build'], function () {    return gulp        .src(['**/*Tests.dll'], { read: false })        .pipe(nunit({            executable: 'C:\\Program Files (x86)\\NUnit 2.6.3\\bin\\nunit-console.exe', // <-- This can go with the next version            teamcity: true        }));});
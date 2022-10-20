/*
    Important Information regarding this Jenkinsfile.
    - Make sure the WEBSERVICE Variable is exactly the same as the project name on Gitlab.
    - When the webservice needs to be deployed on the pub-staging swarm or public swarm, make sure you use the correct certificates
    -
*/
def PROJECT_BUILD_VERSION = ""
def DEV_NETWORK = ""
def WEBSERVICE = "[Webservice name (same as project name on Gitlab)]]"
def DOCKER_PROJECT_URL = "[ssh:xel-webservices/application-name]"
pipeline {
    agent any
    options {
        // This is required if you want to clean before build
        skipDefaultCheckout(true)
    }
    stages {
        stage('Check for other blocking TA jobs'){
            steps{
                // Block build if certain jobs are running
                script {
                    try {
                        jobName = "xel/block_pipeline_job"
                        println("Preparing to build the ${jobName}...")
                        build job:"${jobName}", propagate:false, wait:true
                        } catch (NullPointerException e) {
                        println("Not building the job ${jobName} as it doesn't exist")
                    }
                }
            }
        }
        stage('Checkout') {
            steps {
                slackSend(
                    channel: "#jenkinsbuilds",
                    color: '#4287f5',
                    message: """*Started:* - Job ${env.JOB_NAME} build ${env.BUILD_NUMBER} \n More info at: <${env.BUILD_URL} | *Here* >"""
                )
                cleanWs()
                checkout scm
            }
        }
        stage('Build') {
            steps {
                echo 'Building..'
                sh 'phing clean'
                sh 'phing install'
                sh 'phing report'
            }
            post {
                always {
                    sendSlack("**/build/php-unit/junit.xml")
                }
                success {
                    script {
                        if (env.BRANCH_NAME == 'master' || env.BRANCH_NAME == 'dev') {
                            echo "Set new version to: " + env.BUILD_NUMBER
                            def pipeline
                            def build = env.BUILD_NUMBER
                            pipeline = load '/var/lib/jenkins/jenkins_project_version_docker.groovy'
                            def composer_version = pipeline.get_version(build)
                            def projectbuild = composer_version
                            //PROJECT_BUILD_VERSION needs to be a global variable
                            PROJECT_BUILD_VERSION = projectbuild

                            buildName "${PROJECT_BUILD_VERSION}"
                            sh """sed -i -e 's/${WEBSERVICE}_service=master/${WEBSERVICE}_service='${PROJECT_BUILD_VERSION}rc'/g' settings.env """
                            sh "git add settings.env composer.json && git commit -m \"jenkins updated version to ${PROJECT_BUILD_VERSION}\""
                            sh "git tag ${PROJECT_BUILD_VERSION}rc"
                            sh "git push origin ${PROJECT_BUILD_VERSION}rc --no-verify"
                        } else {
                            sh """sed -i -e 's/${WEBSERVICE}_service=master/${WEBSERVICE}_service='${env.BRANCH_NAME}'/g' settings.env """
                        }
                    }
                    publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'build/php-loc/html', reportFiles: 'phploc.txt', reportName: 'PHP-LOC Report', reportTitles: ''])
                    publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'build/php-depend/html', reportFiles: 'index.html', reportName: 'PHP-Depend Report', reportTitles: ''])
                    publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'build/php-cs/html', reportFiles: 'index.html', reportName: 'PHP-CS Report', reportTitles: ''])
                    publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'build/php-md/html', reportFiles: 'index.html', reportName: 'PHP-MD Report', reportTitles: ''])
                    publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'build/php-cpd/html', reportFiles: 'index.html', reportName: 'PHP-CPD Report', reportTitles: ''])
                }
            }
        }
        stage('DEV Deploy: TA') {
            when { not { branch 'master' } }
            steps {
                script {
                    // OPTION TO INCLUDE GIT PULL ON DOCKER_IMAGES PROJECT
                    sshagent(credentials: ['1234-abcdefg-hasdasd']) {
                        def output = sh returnStdout: true, script: 'ssh -o StrictHostKeyChecking=no ubuntu@albert.xel.nl sudo /home/ubuntu/check_network.sh'
                        DEV_NETWORK = output.trim()
                        echo "NETWORK =" + DEV_NETWORK
                        lock("${DEV_NETWORK}") {
                            //  PULL DOCKER-IMAGES PROJECT
                            dir('DOCKER-PROJECT') {
                                checkout([$class: 'GitSCM', branches: [[name: '*/master']], doGenerateSubmoduleConfigurations: false, extensions: [[$class: 'SubmoduleOption', disableSubmodules: false, parentCredentials: false, recursiveSubmodules: false, reference: '', trackingSubmodules: false]], submoduleCfg: [], userRemoteConfigs: [[credentialsId: "jenkins", url: "${DOCKER_PROJECT_URL}"]]])
                                docker.withServer('tcp://manager-staging.xel-webservices.nl:2376', 'staging-manager-xel-webservices-client-certs') {
                                    // Make build script executable
                                    sh "chmod +x environments/dev/build-dev.sh"
                                    sh """environments/dev/build-dev.sh -runlevel=ta -location=private -webservice=all -network=${DEV_NETWORK}"""
                                }
                            }
                        }
                    }
                }
            }
            post {
                failure {
                    slackSend(
                        channel: "#jenkinsbuilds",
                        color: '#FF0000',
                        message: """*${currentBuild.currentResult}:* - *Job* ${env.JOB_NAME} build ${env.BUILD_NUMBER} \n *Duration*: ${currentBuild.durationString.minus(' and counting')}  \n
                \n More info at: <${env.BUILD_URL} | *Here* >"""
                    )
                }
            }
        }
        stage('DEV Testing: TA') {
            when { not { branch 'master' } }
            steps {
                dir('DOCKER-PROJECT') {
                    script {
                        sleep 180
                        hook = registerWebhook()
                        echo "Waiting for POST to ${hook.getURL()}"
                        docker.withServer('tcp://manager-staging.xel-webservices.nl:2376', 'staging-manager-xel-webservices-client-certs') {
                            sh """environments/ta/build.sh -xel-run-level=ta -webservice=${WEBSERVICE} -network=${DEV_NETWORK} -url=${hook.getURL()} """
                            data = waitForWebhook hook
                            echo "Webhook called with data: ${data}"
                            sshagent(credentials: ['1234-abcdefg-hasdasd']) {
                                sh """ssh -o StrictHostKeyChecking=no ubuntu@albert.xel.nl sudo cp -r /var/lib/docker/volumes/${DEV_NETWORK}/_data /home/ubuntu/logs/${DEV_NETWORK}/_data """
                                sh """scp -o StrictHostKeyChecking=no -r ubuntu@albert.xel.nl:/home/ubuntu/logs/${DEV_NETWORK}/_data/ ./${DEV_NETWORK} """
                                sh """docker stack rm ta-${DEV_NETWORK}-${WEBSERVICE} rabbitmq-${DEV_NETWORK} ${WEBSERVICE}-${DEV_NETWORK}"""
                            }
                        }
                    }
                }
            }
            post {
                always {
                    sendSlack("**/*/report.xml")
                }
            }
        }
        stage('DEV Deploy: DEV') {
            when { branch 'master' }
            steps {
                sshagent(credentials: ['1234-abcdefg-hasdasd']) {
                    script {
                        //  PULL DOCKER-IMAGES PROJECT
                        dir('DOCKER-PROJECT') {
                            checkout([$class: 'GitSCM', branches: [[name: '*/master']], doGenerateSubmoduleConfigurations: false, extensions: [[$class: 'SubmoduleOption', disableSubmodules: false, parentCredentials: false, recursiveSubmodules: false, reference: '', trackingSubmodules: false]], submoduleCfg: [], userRemoteConfigs: [[credentialsId: "jenkins", url: "${DOCKER_PROJECT_URL}"]]])
                            docker.withServer('tcp://manager-staging.xel-webservices.nl:2376', 'staging-manager-xel-webservices-client-certs') {
                                sh """environments/dev/build-dev.sh -runlevel=dev -location=private -webservice=${WEBSERVICE} -network=DEV_NETWORK"""
                            }
                        }
                    }
                }
            }
            post {
                failure {
                    slackSend(
                        channel: "#jenkinsbuilds",
                        color: '#FF0000',
                        message: """*${currentBuild.currentResult}:* - *Job* ${env.JOB_NAME} build ${env.BUILD_NUMBER} \n *Duration*: ${currentBuild.durationString.minus(' and counting')}  \n
            \n More info at: <${env.BUILD_URL} | *Here* >"""
                    )
                }
            }
        }
        stage('Version check'){
            when { branch 'master' }
            steps {
                script {
                    timeout(time: 15, unit: 'MINUTES') {
                        waitUntil {
                            try {
                                sleep 10
                                def response = httpRequest "https://${WEBSERVICE}-staging.xel-webservices.nl/"
                                return response.content.contains("${PROJECT_BUILD_VERSION}")
                            } catch (Exception e) {
                                return false
                            }
                        }
                    }
                }
            }
            post {
                failure {
                    slackSend(
                        channel: "#jenkinsbuilds",
                        color: '#FF0000',
                        message: """*${currentBuild.currentResult}:* - *Job* ${env.JOB_NAME} build ${env.BUILD_NUMBER} \n *Duration*: ${currentBuild.durationString.minus(' and counting')}  \n
            \n More info at: <${env.BUILD_URL} | *Here* >"""
                    )
                }
            }
        }
        stage('DEV Testing: DEV') {
            when { branch 'master' }
            steps {
                dir('DOCKER-PROJECT') {
                    lock("DEV_NETWORK") {
                        script {
                            hook = registerWebhook()
                            echo "Waiting for POST to ${hook.getURL()}"
                            docker.withServer('tcp://manager-staging.xel-webservices.nl:2376', 'staging-manager-xel-webservices-client-certs') {
                                sh """environments/ta/build.sh -xel-run-level=ta -webservice=${WEBSERVICE} -network=DEV_NETWORK -url=${hook.getURL()} """
                                data = waitForWebhook hook
                                echo "Webhook called with data: ${data}"
                                sshagent(credentials: ['1234-abcdefg-hasdasd']) {
                                    sh """ssh -o StrictHostKeyChecking=no ubuntu@albert.xel.nl sudo cp -r /var/lib/docker/volumes/DEV_NETWORK/_data /home/ubuntu/logs/DEV_NETWORK/_data """
                                    sh """scp -o StrictHostKeyChecking=no -r ubuntu@albert.xel.nl:/home/ubuntu/logs/DEV_NETWORK/_data/ ./DEV_NETWORK"""
                                    sh """docker stack rm ta-DEV_NETWORK-${WEBSERVICE}"""
                                }
                            }
                        }
                    }
                }
            }
            post {
                always {
                    sendSlack("**/*/report.xml")
                }
            }
        }
        stage('Prepare release ') {
            when { branch 'master' }
            steps {
                script {
                    sh "git tag ${PROJECT_BUILD_VERSION} ${PROJECT_BUILD_VERSION}rc"
                    sh "git push origin ${PROJECT_BUILD_VERSION} --no-verify"
                }
            }
        }
    }
    post {
        success {
            script {
                if (env.BRANCH_NAME != 'master') {
                    slackSend(
                        channel: "#jenkinsbuilds",
                        color: '#2aad72',
                        message: """*${currentBuild.currentResult}:* - *Job* ${env.JOB_NAME} build ${env.BUILD_NUMBER} \n *Duration*: ${currentBuild.durationString.minus(' and counting')}  \n *Test Summary* - ${summary.totalCount}, Failures: ${summary.failCount}, Skipped: ${summary.skipCount}, Passed: ${summary.passCount}
                \n More info at: <${env.BUILD_URL} | *Here* >"""
                    )
                } else {
                    slackSend(
                        channel: "#jenkinsbuilds",
                        color: '#2aad72',
                        message: """*${currentBuild.currentResult}:* - *Job* ${env.JOB_NAME} build ${env.BUILD_NUMBER} \n *Duration*: ${currentBuild.durationString.minus(' and counting')} \n Dev build finished and ready for deployment \n Tag: *${PROJECT_BUILD_VERSION}* \n *Test Summary* - ${summary.totalCount}, Failures: ${summary.failCount}, Skipped: ${summary.skipCount}, Passed: ${summary.passCount}
                \n More info at: <${env.BUILD_URL} | *Here* >
                 \n <https://jenkins.xel.nl/job/XEL/job/Deploy%20Docker%20Xel%20Webservices/job/master/build | *Trigger Prod deploy job* >  """
                    )
                }
            }
        }
    }
}

def sendSlack(file){
    summary = junit "${file}"
    if (currentBuild.result == 'UNSTABLE'){
        currentBuild.result = 'FAILURE'
        slackSend (
            channel: "#jenkinsbuilds",
            color: '#FF0000',
            message: """*${currentBuild.currentResult}:* - *Job* ${env.JOB_NAME} build ${env.BUILD_NUMBER} \n *Duration*: ${currentBuild.durationString.minus(' and counting')}  \n *Test Summary* - ${summary.totalCount}, Failures: ${summary.failCount}, Skipped: ${summary.skipCount}, Passed: ${summary.passCount}
           \n More info at: <${env.BUILD_URL} | *Here* >"""
        )
        error('See test results...')
    }
}

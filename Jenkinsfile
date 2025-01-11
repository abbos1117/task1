pipeline {
    agent any

    environment {
        DOCKER_REGISTRY = 'docker.io'
        IMAGE_NAME = 'shodlik/task1'
        DOCKER_CREDENTIALS = 'dockerhub_id' // Update with your Jenkins credentials ID for Docker Hub
    }

    stages {
        stage('Checkout') {
            steps {
                // Git repo checkout
                checkout scm
            }
        }

        stage('Build Docker Image') {
            steps {
                // Build Docker image from the Dockerfile
                script {
                    sh 'docker build -t task1 .'
                }
            }
        }

        stage('Push to Docker Hub') {
            steps {
                script {
                    // Login to Docker Hub securely
                    withCredentials([usernamePassword(credentialsId: DOCKER_CREDENTIALS, 
                                                      usernameVariable: 'DOCKER_USERNAME', 
                                                      passwordVariable: 'DOCKER_PASSWORD')]) {
                        sh '''
                            docker login -u $DOCKER_USERNAME -p $DOCKER_PASSWORD
                            docker tag task1 $DOCKER_USERNAME/task1:latest
                            docker push $DOCKER_USERNAME/task1:latest
                            docker logout
                        '''
                    }
                }
            }
        }
    }

    post {
        always {
            // Always run this block
            echo 'Build Finished'
        }
        success {
            // Block runs if the build is successful
            echo 'Build Succeeded!'
        }
        failure {
            // Block runs if the build fails
            echo 'Build Failed!'
        }
    }
}

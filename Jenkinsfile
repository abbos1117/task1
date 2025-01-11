pipeline {
    agent any

    environment {
        DOCKER_CREDENTIALS = 'docker_credentials'  // Docker credentials stored in Jenkins
        DOCKER_REGISTRY = 'docker.io'  // Docker registry (Docker Hub)
        IMAGE_NAME_FREEZETILE = 'shodlik/freeztile'
        IMAGE_NAME_JENKINS_APP = 'shodlik/jenkins.app'
        IMAGE_NAME_TASK1 = 'shodlik/task1'
    }

    stages {
        stage('Checkout') {
            steps {
                // Checkout the code from the repository
                checkout scm
            }
        }

        stage('Docker Login') {
            steps {
                script {
                    // Login to Docker registry using credentials
                    docker.withCredentials([usernamePassword(credentialsId: "${DOCKER_CREDENTIALS}", usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                        sh "echo ${DOCKER_PASS} | docker login -u ${DOCKER_USER} --password-stdin ${DOCKER_REGISTRY}"
                    }
                }
            }
        }

        stage('Build Docker Image Freeztile') {
            steps {
                script {
                    // Build the Docker image for Freeztile
                    def image = docker.build("${IMAGE_NAME_FREEZETILE}:${BUILD_NUMBER}")
                }
            }
        }

        stage('Build Docker Image Jenkins App') {
            steps {
                script {
                    // Build the Docker image for Jenkins App
                    def image = docker.build("${IMAGE_NAME_JENKINS_APP}:${BUILD_NUMBER}")
                }
            }
        }

        stage('Build Docker Image Task1') {
            steps {
                script {
                    // Build the Docker image for Task1
                    def image = docker.build("${IMAGE_NAME_TASK1}:${BUILD_NUMBER}")
                }
            }
        }

        stage('Push Docker Images') {
            steps {
                script {
                    // Push each Docker image to Docker registry
                    docker.withCredentials([usernamePassword(credentialsId: "${DOCKER_CREDENTIALS}", usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                        sh "docker push ${IMAGE_NAME_FREEZETILE}:${BUILD_NUMBER}"
                        sh "docker push ${IMAGE_NAME_JENKINS_APP}:${BUILD_NUMBER}"
                        sh "docker push ${IMAGE_NAME_TASK1}:${BUILD_NUMBER}"
                    }
                }
            }
        }
    }

    post {
        always {
            // Clean up any Docker resources after the job finishes
            sh "docker logout"
        }

        success {
            echo 'Build and Push Succeeded!'
        }

        failure {
            echo 'Build or Push Failed!'
        }
    }
}

pipeline {
    agent any

    environment {
        DOCKER_IMAGE = "shodlik/task1"
        DOCKER_REGISTRY_CREDENTIALS = "dockerhub_id" // Jenkinsda yaratilgan credential ID
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build and Push Docker Image') {
            steps {
                script {
                    docker.withRegistry('https://registry.hub.docker.com', "${DOCKER_REGISTRY_CREDENTIALS}") {
                        def customImage = docker.build("${DOCKER_IMAGE}:${env.BUILD_NUMBER}")
                        customImage.push()
                        customImage.push("latest")
                    }
                }
            }
        }
    }

    post {
        success {
            echo "Docker image successfully built and pushed!"
        }
        failure {
            echo "Build failed. Check logs for more details."
        }
    }
}

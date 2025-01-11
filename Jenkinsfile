pipeline {
    environment {
        gitRepo = 'https://github.com/abbos1117/task1' // GitHub repository URL
        branchName = 'main' // Git branch
        dockerImage = '' // Docker image placeholder
    }

    agent any

    stages {
        stage('Git - Checkout') {
            steps {
                echo "Cloning repository..."
                checkout([$class: 'GitSCM', branches: [[name: branchName]], userRemoteConfigs: [[url: gitRepo]]])
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    echo "Building Docker image..."
                    dockerImage = docker.build("${env.DOCKER_USERNAME}/task1:${env.BUILD_NUMBER}")
                    dockerImage.tag("latest")
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                script {
                    withDockerRegistry([credentialsId: 'dockerhub_id', url: 'https://index.docker.io/v1/']) {
                        echo "Pushing Docker image to Docker Hub..."
                        dockerImage.push("${env.BUILD_NUMBER}")
                        dockerImage.push("latest")
                    }
                }
            }
        }

        stage('Pull and Run Docker Image') {
            steps {
                script {
                    echo "Pulling and running the Docker container on port 8000:8000..."

                    // Stop and remove any existing container with the same name
                    sh "docker rm -f task1-container || true"

                    // Pull the latest image from Docker Hub
                    sh "docker pull ${env.DOCKER_USERNAME}/task1:latest"

                    // Run the container with port mapping
                    sh "docker run -d --name task1-container -p 8000:8000 ${env.DOCKER_USERNAME}/task1:latest"
                }
            }
        }
    }

    post {
        success {
            echo "Pipeline executed successfully: Build, push, pull, and run!"
        }
        failure {
            echo "Pipeline failed. Check the logs for details."
        }
    }
}

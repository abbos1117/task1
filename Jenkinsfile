pipeline {
    environment {
        gitRepo = 'https://github.com/abbos1117/task1' // GitHub repository URL
        branchName = 'main' // Git branch
        dockerImageName = 'task1' // Docker image nomi
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
                    def dockerImage = docker.build("${env.DOCKER_USERNAME}/${dockerImageName}:${env.BUILD_NUMBER}")
                    dockerImage.tag("latest")
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                script {
                    echo "Authenticating Docker Hub..."
                    sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin'
                    echo "Pushing Docker image to Docker Hub..."
                    sh "docker push ${env.DOCKER_USERNAME}/${dockerImageName}:${env.BUILD_NUMBER}"
                    sh "docker push ${env.DOCKER_USERNAME}/${dockerImageName}:latest"
                }
            }
        }

        stage('Pull and Run Docker Image') {
            steps {
                script {
                    echo "Pulling and running the Docker container on port 8000:8000..."
                    sh """
                        docker rm -f task1-container || true
                        docker pull ${env.DOCKER_USERNAME}/${dockerImageName}:latest
                        docker run -d --name task1-container -p 8000:8000 --restart unless-stopped ${env.DOCKER_USERNAME}/${dockerImageName}:latest
                    """
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

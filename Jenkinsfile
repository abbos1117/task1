pipeline {
    environment {
        gitRepo = 'https://github.com/abbos1117/task1' // GitHub repository URL
        branchName = 'shodlik' // Git branch nomi
        dockerImage = '' // Docker image o'zgaruvchisi
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
                    dockerImage = docker.build("${env.DOCKER_USERNAME}/pipeline:${env.BUILD_NUMBER}") // Build number bilan Docker image yaratish
                    dockerImage.tag("latest") // 'latest' teg qo‘shish
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                script {
                    echo "Authenticating Docker Hub with global credentials..."
                    withCredentials([usernamePassword(credentialsId: 'dockerhub_id', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                        sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin' // Docker Hub login
                    }

                    echo "Pushing Docker image to Docker Hub..."
                    dockerImage.push("${env.BUILD_NUMBER}") // Build number bilan image push
                    dockerImage.push("latest") // 'latest' teg bilan image push
                }
            }
        }

        stage('Run Docker Image') {
            steps {
                script {
                    echo "Running Docker image..."
                    // Run the Docker image to verify it works correctly
                    sh "docker run -d -p 8000:8000 --name test-container ${env.DOCKER_USERNAME}/pipeline:${env.BUILD_NUMBER}"
                    // You can replace the `-d` flag with additional flags or commands as needed.
                    echo "Docker image is running in container: test-container"
                }
            }
        }

        stage('Clean Up') {
            steps {
                script {
                    echo "Cleaning up Docker images..."
                    sh "docker rmi ${env.DOCKER_USERNAME}/pipeline:${env.BUILD_NUMBER} || true" // Build image ni o'chirish
                    sh "docker rmi ${env.DOCKER_USERNAME}/pipeline:latest || true" // 'latest' image ni o'chirish
                    sh "docker stop test-container || true" // Stop the test container
                    sh "docker rm test-container || true" // Remove the test container
                }
            }
        }
    }

    post {
        success {
            echo "Build and push successful!"
        }
        failure {
            echo "Build failed!"
        }
        always {
            echo "Cleaning workspace..."
            cleanWs() // Workspace tozalash
        }
    }
}

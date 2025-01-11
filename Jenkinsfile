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
                    dockerImage = docker.build("${env.DOCKER_USERNAME}/task1:${env.BUILD_NUMBER}") // Build number bilan Docker image yaratish
                    dockerImage.tag("latest") // 'latest' teg qo‘shish
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                script {
                    echo "Authenticating Docker Hub with credentials..."
                    withCredentials([usernamePassword(credentialsId: 'dockerhub_id', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                        sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin' // Docker Hub login
                    }

                    echo "Pushing Docker image to Docker Hub..."
                    dockerImage.push("${env.BUILD_NUMBER}") // Build number bilan image push
                    dockerImage.push("latest") // 'latest' teg bilan image push
                }
            }
        }

        stage('Clean Up') {
            steps {
                script {
                    echo "Cleaning up Docker images..."
                    sh "docker rmi ${env.DOCKER_USERNAME}/task1:${env.BUILD_NUMBER} || true" // Build image ni o'chirish
                    sh "docker rmi ${env.DOCKER_USERNAME}/task1:latest || true" // 'latest' image ni o'chirish
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
            node {
                echo "Cleaning workspace..."
                cleanWs() // Workspace tozalash
            }
        }
    }
}

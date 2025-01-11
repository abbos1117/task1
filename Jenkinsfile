pipeline {
    environment {
        gitRepo = 'https://github.com/abbos1117/task1' // GitHub repository URL
        branchName = 'shodlik' // Git branch nomi
        dockerImage = '' // Docker image o'zgaruvchisi
        DOCKER_USERNAME = credentials('dockerhub_id') // Docker Hub username, Jenkins credentials'dan olish
        DOCKER_PASSWORD = credentials('dockerhub_password') // Docker Hub password, Jenkins credentials'dan olish
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
                    dockerImage = docker.build("${DOCKER_USERNAME}/task1:${env.BUILD_NUMBER}") // Task1 image ni yaratish
                    dockerImage.tag("latest") // 'latest' teg qo‘shish
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                script {
                    echo "Authenticating Docker Hub with credentials..."
                    sh "echo $DOCKER_PASSWORD | docker login -u $DOCKER_USERNAME --password-stdin" // Docker Hub login qilish
                    echo "Pushing Docker image to Docker Hub..."
                    dockerImage.push("${env.BUILD_NUMBER}") // Build number bilan image'ni push qilish
                    dockerImage.push("latest") // 'latest' teg bilan image'ni push qilish
                }
            }
        }

        stage('Clean Up') {
            steps {
                script {
                    echo "Cleaning up Docker images..."
                    sh "docker rmi ${DOCKER_USERNAME}/task1:${env.BUILD_NUMBER} || true" // Build image ni o'chirish
                    sh "docker rmi ${DOCKER_USERNAME}/task1:latest || true" // 'latest' image'ni o'chirish
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

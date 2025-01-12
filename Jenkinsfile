pipeline {
    environment {
        gitRepo = 'https://github.com/abbos1117/task1' // GitHub repository URL
        branchName = 'shodlik' // Git branch nomi
        dockerImage = '' // Docker image o'zgaruvchisi
    }

    agent any

    stages {
        stage('Development') {
            steps {
                script {
                    echo "Development bosqichi..."
                    
                    echo "Cloning repository..."
                    checkout([$class: 'GitSCM', branches: [[name: branchName]], userRemoteConfigs: [[url: gitRepo]]])

                    echo "Building Docker image for development..."
                    dockerImage = docker.build("${env.DOCKER_USERNAME}/pipeline-dev:${env.BUILD_NUMBER}")
                    dockerImage.tag("latest-dev")
                    
                    echo "Authenticating and pushing to Docker Hub..."
                    withCredentials([usernamePassword(credentialsId: 'dockerhub_id', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                        sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin'
                        dockerImage.push("${env.BUILD_NUMBER}")
                        dockerImage.push("latest-dev")
                    }

                    echo "Running Docker container for development..."
                    sh "docker stop dev-container || true"
                    sh "docker rm dev-container || true"
                    sh "docker run -d -p 8001:8000 --name dev-container ${env.DOCKER_USERNAME}/pipeline-dev:${env.BUILD_NUMBER}"
                }
            }
        }

        stage('UAT') {
            steps {
                script {
                    echo "UAT bosqichi..."

                    echo "Pulling Docker image for UAT..."
                    withCredentials([usernamePassword(credentialsId: 'dockerhub_id', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                        sh 'docker pull ${env.DOCKER_USERNAME}/pipeline-dev:latest-dev'
                        dockerImage = docker.image("${env.DOCKER_USERNAME}/pipeline-dev:latest-dev")
                    }

                    echo "Running Docker container for UAT..."
                    sh "docker stop uat-container || true"
                    sh "docker rm uat-container || true"
                    sh "docker run -d -p 8002:8000 --name uat-container ${env.DOCKER_USERNAME}/pipeline-dev:latest-dev"
                }
            }
        }

        stage('PROD') {
            steps {
                script {
                    echo "PROD bosqichi..."

                    echo "Tagging Docker image for production..."
                    dockerImage.tag("prod-${env.BUILD_NUMBER}")
                    dockerImage.push("prod-${env.BUILD_NUMBER}")
                    
                    echo "Running Docker container for production..."
                    sh "docker stop prod-container || true"
                    sh "docker rm prod-container || true"
                    sh "docker run -d -p 8003:8000 --name prod-container ${env.DOCKER_USERNAME}/pipeline-dev:prod-${env.BUILD_NUMBER}"
                }
            }
        }
    }

    post {
        success {
            echo "Pipeline successful for all stages!"
        }
        failure {
            echo "Pipeline failed at some stage!"
        }
        always {
            echo "Cleaning workspace..."
            cleanWs() // Workspace tozalash
        }
    }
}

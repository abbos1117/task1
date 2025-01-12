pipeline {
    environment {
        gitRepo = 'https://github.com/abbos1117/task1'
        branchName = 'shodlik'
        dockerImage = ''
    }

    agent any

    stages {
        stage('Git - Checkout') {
            steps {
                echo "Cloning repository..."
                checkout([$class: 'GitSCM', branches: [[name: branchName]], userRemoteConfigs: [[url: gitRepo]]])
            }
        }

        stage('Lint Code') {
            steps {
                script {
                    echo "Running PHP lint checks..."
                    sh 'php -l $(find . -type f -name "*.php")'
                    sh 'vendor/bin/phpcs --standard=PSR12 src/'
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    echo "Running unit tests..."
                    sh 'vendor/bin/phpunit --testdox'
                }
            }
        }

        stage('Build Docker Image') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                script {
                    echo "Building Docker image for Development..."
                    def devImage = docker.build("${env.DOCKER_USERNAME}/pipeline:dev-${env.BUILD_NUMBER}", "--target=dev .")
                    devImage.push("dev-${env.BUILD_NUMBER}")

                    echo "Building Docker image for Production..."
                    dockerImage = docker.build("${env.DOCKER_USERNAME}/pipeline:prod-${env.BUILD_NUMBER}", "--target=prod .")
                    dockerImage.tag("latest")
                }
            }
        }

        stage('Run Docker Image - UAT') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                script {
                    echo "Running Docker image for UAT..."
                    sh "docker stop uat-container || true"
                    sh "docker rm uat-container || true"
                    sh "docker run -d -p 8001:8000 --name uat-container ${env.DOCKER_USERNAME}/pipeline:dev-${env.BUILD_NUMBER}"
                    echo "Docker image is running in UAT container: uat-container"
                }
            }
        }

        stage('Push Docker Image - PROD') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                script {
                    echo "Authenticating Docker Hub with global credentials..."
                    withCredentials([usernamePassword(credentialsId: 'dockerhub_id', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                        sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin'
                    }

                    echo "Pushing Docker image to Docker Hub..."
                    dockerImage.push("${env.BUILD_NUMBER}")
                    dockerImage.push("latest")
                }
            }
        }

        stage('Cleanup') {
            steps {
                script {
                    echo "Cleaning up unused Docker images and containers..."
                    sh "docker system prune -f"
                }
            }
        }
    }

    post {
        success {
            echo "Pipeline executed successfully!"
        }
        failure {
            echo "Pipeline failed!"
        }
        always {
            echo "Cleaning workspace..."
            cleanWs()
        }pipeline {
    environment {
        gitRepo = 'https://github.com/abbos1117/task1'
        branchName = 'shodlik'
        dockerImage = ''
    }

    agent any

    stages {
        stage('Git - Checkout') {
            steps {
                echo "Cloning repository..."
                checkout([$class: 'GitSCM', branches: [[name: branchName]], userRemoteConfigs: [[url: gitRepo]]])
            }
        }

        stage('Lint Code') {
            steps {
                script {
                    echo "Running PHP lint checks..."
                    sh 'php -l $(find . -type f -name "*.php")'
                    sh 'vendor/bin/phpcs --standard=PSR12 src/'
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    echo "Running unit tests..."
                    sh 'vendor/bin/phpunit --testdox'
                }
            }
        }

        stage('Build Docker Image') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                script {
                    echo "Building Docker image..."
                    def devImage = docker.build("${env.DOCKER_USERNAME}/pipeline:dev-${env.BUILD_NUMBER}", "--target=dev .")
                    devImage.push("dev-${env.BUILD_NUMBER}")

                    dockerImage = docker.build("${env.DOCKER_USERNAME}/pipeline:prod-${env.BUILD_NUMBER}", "--target=prod .")
                    dockerImage.tag("latest")
                }
            }
        }

        stage('Run Docker Image') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                script {
                    echo "Running Docker image..."
                    sh "docker stop test-container || true"
                    sh "docker rm test-container || true"
                    sh "docker run -d -p 8002:8000 --name test-container ${env.DOCKER_USERNAME}/pipeline:${env.BUILD_NUMBER}"
                    echo "Docker image is running in container: test-container"
                }
            }
        }

        stage('Push Docker Image') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                script {
                    echo "Authenticating Docker Hub with global credentials..."
                    withCredentials([usernamePassword(credentialsId: 'dockerhub_id', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                        sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin'
                    }

                    echo "Pushing Docker image to Docker Hub..."
                    dockerImage.push("${env.BUILD_NUMBER}")
                    dockerImage.push("latest")
                }
            }
        }

        stage('Cleanup') {
            steps {
                script {
                    echo "Cleaning up unused Docker images and containers..."
                    sh "docker system prune -f"
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
            // Optional: Send failure notification here (e.g., Slack or Email)
        }
        always {
            echo "Cleaning workspace..."
            cleanWs()
        }
    }
}

    }
}

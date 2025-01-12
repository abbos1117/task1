pipeline {
    environment {
        gitRepo = 'https://github.com/abbos1117/task1'
        branchName = 'shodlik'
        dockerImage = ''
        stageName = '' // Muvaffaqiyatli ishlash uchun environment orqali o'zgartiriladi
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
            when {
                expression { env.stageName == 'development' }
            }
            steps {
                script {
                    echo "Running PHP lint checks in development..."
                    sh 'php -l $(find . -type f -name "*.php")'
                    sh 'vendor/bin/phpcs --standard=PSR12 src/'
                }
            }
        }

        stage('Run Tests') {
            when {
                expression { env.stageName == 'UAT' }
            }
            steps {
                script {
                    echo "Running unit tests in UAT..."
                    sh 'vendor/bin/phpunit --testdox'
                }
            }
        }

        stage('Build Docker Image') {
            when {
                expression { env.stageName == 'PROD' }
            }
            steps {
                script {
                    echo "Building Docker image in production..."
                    def devImage = docker.build("${env.DOCKER_USERNAME}/pipeline:dev-${env.BUILD_NUMBER}", "--target=dev .")
                    devImage.push("dev-${env.BUILD_NUMBER}")

                    dockerImage = docker.build("${env.DOCKER_USERNAME}/pipeline:prod-${env.BUILD_NUMBER}", "--target=prod .")
                    dockerImage.tag("latest")
                }
            }
        }

        stage('Push Docker Image') {
            when {
                expression { env.stageName == 'PROD' }
            }
            steps {
                script {
                    echo "Authenticating Docker Hub with global credentials..."
                    withCredentials([usernamePassword(credentialsId: 'dockerhub_id', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                        sh 'echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin'
                    }

                    echo "Pushing Docker image to Docker Hub in production..."
                    dockerImage.push("${env.BUILD_NUMBER}")
                    dockerImage.push("latest")
                }
            }
        }

        stage('Cleanup') {
            when {
                expression { env.stageName == 'PROD' }
            }
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
            echo "Pipeline execution successful for ${env.stageName}!"
        }
        failure {
            echo "Pipeline failed in ${env.stageName}!"
        }
        always {
            echo "Cleaning workspace..."
            cleanWs()
        }
    }
}
